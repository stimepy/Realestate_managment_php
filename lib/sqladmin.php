<?php

class CSQLAdmin extends CLibrary {

	/**
	* description
	*
	* @var type
	*
	* @access type
	*/
	var $form;

	/**
	* description
	*
	* @var type
	*
	* @access type
	*/
	var $functions;
	

	/**
	* description functions list which will be executed in variouse points of sqladmin
	*
	* @var type
	*
	* @access type
	*/
	var $functions;
	

	function CSQLAdmin($section , $templates , $db , $tables , $extra = "") {
		global $_CONF;

		if (!$_GET["page"])
			$_GET["page"] = 1;		


		parent::CLibrary("SQLAdmin");
		
		//checking if the templates are orblects or path to a template file
		if (!is_array($templates))					
			//if path the load the tempmate form that file
			$this->templates = array("generic_form" => new CTemplate($templates));
		else
			$this->templates = $templates;
		
		$this->db = $db;
		$this->tables = $tables;
		//extra variables to be passed to cform
		$this->extra = $extra;

		//loading the forms , changed the varialbes locations, but still keeping the compatibility
		$path = ($_CONF["forms"]["adminpath"] ? $_CONF["forms"]["adminpath"] : $_CONF["formspath"] );
		if (dirname($section)) {

			$path .= dirname($section) . "/" ;
			$section = basename($section);
		}
		
		//debuging part 
		if (defined("PB_DEBUG") && (PB_DEBUG == "1"))
			echo "<br>FILE:SQLADMIN:MAIN:{$path}{$section}.xml";

		$conf = new CConfig( $path . $section . ".xml");

		$this->forms = $conf->vars["form"];
		
		//loading the edit/add forms
		if (is_array($this->forms["forms"])) {
			foreach ($this->forms["forms"] as $key => $val) {	
				unset($conf);

				//debuging part 
//				if (defined("PB_DEBUG") && PB_DEBUG == "1")		
//					echo "<br>FILE:SQLADMIN:SECTION:{$path}{$section}.xml";

				$conf = new CConfig($path . $val );
				$this->forms["forms"][$key] = $conf->vars["form"];

				//adding the tables
				$this->forms["forms"][$key]["table"] = $this->forms["table"];
				$this->forms["forms"][$key]["table_uid"] = $this->forms["table_uid"];
				$this->forms["forms"][$key]["xmlfile"] = $path . $val ;
			}			
		}

		$this->form = new CForm($this->templates["generic_form"], &$db , &$tables);
	}

	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function FormList($items = "") {
		global $base;

		//checking if hte values weren't inputed ion the main object
		if (is_array($this->items)) {
			$items = $this->items;
		}		

		//crap, preexecute a function, which is suposed in some times to preload the items too

		if (is_array($this->functions["list"]["pre"]))
			call_user_func($this->functions["list"]["pre"], &$items , &$items_count);

		//if i got no elements from preloader functions, then i load it manualy
		if (!is_array($items)) {

			//cheking if is a normal browse or a search method
			if (isset($this->forms["uridata"]["search"]) && ($_GET[$this->forms["uridata"]["action"]] == $this->forms["uridata"]["search"])) {

				$items = $this->db->QuerySelectLimit($this->tables[$this->forms["forms"]["list"]["table"]],"*", "`" . $_GET["what"] . "` " . ( $_GET["type"] == "int" ? "='" . $_GET["search"] . "'" : "LIKE '%" . $_GET["search"] . "%'"),(int) $_GET["page"],$this->forms["forms"]["list"]["items"]);
				$count = $this->db->RowCount($this->tables[$this->forms["forms"]["list"]["table"]] , " WHERE `" . $_GET["what"] . "` " . ( $_GET["type"] == "int" ? "='" . $_GET["search"] . "'" : "LIKE '%" . $_GET["search"] . "%'"));

			} else {
			
				$items = $this->db->QuerySelectLimit($this->tables[$this->forms["forms"]["list"]["table"]],"*","",(int) $_GET["page"],$this->forms["forms"]["list"]["items"]);
				$count = $this->db->RowCount($this->tables[$this->forms["forms"]["list"]["table"]]);
			}
		}

		$_GET["page"] = $_GET["page"] ? $_GET["page"] : 1;
		//auto index the element
		$start = $this->forms["forms"]["list"]["items"] * ($_GET["page"] - 1 );

		if (is_array($items)) {
			foreach ($items as $key => $val) {
				$items[$key]["_count"] = ++$start;
			}			
		}		

		//$data = new CForm($this->templates["generic_form"], &$this->db , &$this->tables);
		return $this->form->SimpleList($this->forms["forms"]["list"] , $items , $count , $this->extra["list"]);
	}


	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function SetFunction( $form , $event , $function) {
		$this->functions[$form][$event] = $function;
	}
	

	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function ListProcess($pre = "" , $after = "" ) {

		$this->functions["list"]["pre"] = $pre;
		$this->functions["list"]["after"] = $after;
	}

	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function StoreRecord($redirect = true) {
		global $base, $_CONF;

		//validating the input data
		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			//doing a autodetect for storing type , edit or add
			//if $_GET["type"]	is set is simple, else detecting after the id form
			if (!isset($_GET["type"])) {
				if ($_POST[$this->forms["table_uid"]])
					$_GET["type"] = "edit";
				else
					$_GET["type"] = "add";
			}	

			//if validation succeeds then i move the files from /tmp to their directory, else i will proceed to add
			//precheck for uploaded files, like temporary images, etc.
			$form = $this->forms["forms"][$_GET["type"]];

			if (is_array($form["fields"])) {
				foreach ($form["fields"] as $key => $val) {

					switch ($val["type"]) {

						case "date":
							$_POST[$key] = mktime ( $_POST[$key . "_hour"] , $_POST[$key . "_minute"] , $_POST[$key . "_second"] , $_POST[$key. "_month"] , $_POST[$key. "_day"] , $_POST[$key. "_year"]);
						break;

						case "droplist":
							if ($val["subtype"] == "multiple") {

								//detect the fields which should be available for this field
								if (is_array($_POST)) {
									
									foreach ($_POST as $k => $v) {
										if (strstr($k , $key . "_option_")) {
											$option[] = $v;
										}										
									}						
									//ok, now build the result
									if (is_array($option)) {
										$_POST[$key] = implode($val["tree"]["db_separator"],$option);
									} else {
										$_POST[$key] = "";
									}
								} else {
									
								}
							}
							
						break;

						case "upload":
							$file = true;
						case "image":
							unset($_POST[$key]);

							//checking how choosed the client to set the image
							switch ($_POST[$key . "_radio_type"]) {
								case 0:
									//checking if the client specified any image type
									if (is_array($_FILES[$key . "_upload_client"]) && is_uploaded_file($_FILES[$key . "_upload_client"]["tmp_name"])) {									
										$img = &$_FILES[$key . "_upload_client"];
										//temporary upload the file in images/upload/tmp/
										$name = $_POST[$key . "_temp"] != "" ? $_POST[$key . "_temp"] : $val["file"]["default"] . time() . $val["file"]["ext"];	
										
										@move_uploaded_file($img["tmp_name"] , $_CONF["path"] . $_CONF["upload"] . "tmp/" . $name );

										// generate the tn image
										if ($val["tn"]["generate"] == "true") {
											$base->image->Resize(
																	$_CONF["path"] . $_CONF["upload"] . "tmp/" . $name ,
																	$_CONF["path"] . $_CONF["upload"] . "tmp/" . $val["tn"]["preffix"] . $name ,
																	$val["tn"]["width"]
																);
											$_POST["tn_" . $key] = "1";
										}
										
										//setting read/delete/save permission for all users, usefull if the httpd is working as normal user ( most cases )
										chmod ($_CONF["path"] . $_CONF["upload"] . "tmp/" . $name , 0777);
//										die;
										//setting the temp variable
										$_fields["values"][$key . "_temp"] = $name;
										$_POST[$key . "_temp"] = $name;
										$_POST[$key . "_file"] = $_FILES[$key . "_upload_client"]["name"];
										$_POST[$key] = "1";

									}								
								break;

								case "1":
									//, the guy wants to download a ing image

									if ($_POST[$key . "_upload_web"] != "http://") {										
										//i have to be very carefully here, if the image is not a valid link, then 
										//everithing get messed.
										$image = @GetFileContents($_POST[$key . "_upload_web"]);
										
										$name = $_POST[$key . "_temp"] != "" ? $_POST[$key . "_temp"] : $val["file"]["default"] . time() . $val["file"]["ext"];

										SaveFileContents( $_CONF["path"] . $_CONF["upload"] . "tmp/" . $name , $image);
										chmod ($_CONF["path"] . $_CONF["upload"] . "tmp/" . $name , 0777);

										// generate the tn image
										if ($val["tn"]["generate"] == "true") {
											@$base->image->Resize(
																	$_CONF["path"] . $_CONF["upload"] . "tmp/" . $name ,
																	$_CONF["path"] . $_CONF["upload"] . "tmp/" . $val["tn"]["preffix"] . $name ,
																	$val["tn"]["width"]
																);

											$_POST["tn_" . $key] = "1";
										}

										//setting the temp variable
										$_fields["values"][$key . "_temp"] = $name;
										$_POST[$key . "_temp"] = $name;
										$_POST[$key . "_file"] = basename($_POST[$key . "_upload_web"]);
										$_POST[$key] = "1";
									}

								break;

								case "-1":
//									echo "<pre style=\"background-color:white\">";
//									print_r($_POST);
//									die;
									//trying to remove the tmp image is exists
									if (file_exists($_CONF["path"] . $_CONF["upload"] . "tmp/" . $_POST[$key . "_temp"]) && is_file($_CONF["path"] . $_CONF["upload"] . "tmp/" . $_POST[$key . "_temp"]))
										@unlink($_CONF["path"] . $_CONF["upload"] . "tmp/" . $_POST[$key . "_temp"]);										
									//removing the original image too if exists
									else
										@unlink($_CONF["path"] . $_CONF["upload"] . $val["path"] . $val["file"]["default"] . $_POST[$val["file"]["field"]] . $val["file"]["ext"]);

									$_fields["values"][$key . "_radio_type"] = 0;

									$_POST[$key] = 0;
									$_fields["values"][$key . "_temp"] = "";
									$_POST[$key . "_temp"] = "";
									$_POST[$key . "_file"] = "";
								break;

							}
							//hm ... checking if that IS A REAL IMAGE
							if ($_POST[$key . "_temp"] && !$file) {
								
								$img = @GetImageSize($_CONF["path"] . $_CONF["upload"] . "tmp/" . $_POST[$key . "_temp"]);

								if (!is_array($img)) {

									//removing the image, maybe in future return the er a proper answer
									//echo "MOHHHHH";
									@unlink($_CONF["path"] . $_CONF["upload"] . "tmp/" . $_POST[$key . "_temp"]);
									$_POST[$key . "_temp"] = "";
									$_POST[$key] = 0;
								}									
							}
																
						break;
					}							
				}						
			}

			//force for no validation sometimes
			if ($_GET["FORMvalidate"] == "false")
				$fields = "";
			else
				$fields = $this->form->Validate($this->forms["forms"][$_GET["type"]] , $_POST);
			
			if (!is_array($fields)) {
				//adding to database
				
				if (!$_POST[$this->forms["forms"]["add"]["table_uid"]]) {

					$id = $this->db->QueryInsert($this->tables[$this->forms["forms"]["add"]["table"]] , $_POST);
					$_POST[$this->forms["forms"]["add"]["table_uid"]] = $id;
				
				} else {
					$this->db->QueryUpdate($this->tables[$this->forms["forms"]["edit"]["table"]] , $_POST , "`" . $this->forms["forms"]["edit"]["table_uid"] . "`='" . $_POST[$this->forms["forms"]["edit"]["table_uid"]] . "'" );

					$id = $_POST[$this->forms["forms"]["edit"]["table_uid"]];
				}

				//data stored, taking care of uploade files/images, etc
				if (is_array($form["fields"])) {
					foreach ($form["fields"] as $key => $val) {

						switch ($val["type"]) {
							case "upload":
							case "image":

							//checking if is really e file, else if no tmp is set then it can be the folder where are stored the values
								if (is_file($_CONF["path"] . $_CONF["upload"] . "tmp/" . $_POST[$key . "_temp"])) {

									//moving the image stored in temp variable
									//check if the file already exists
									if (is_file($_CONF["path"] . $_CONF["upload"] . $val["path"] . $val["file"]["default"] . $_POST[$val["file"]["field"]] . $val["file"]["ext"])) {
										@unlink($_CONF["path"] . $_CONF["upload"] . $val["path"] . $val["file"]["default"] . $_POST[$val["file"]["field"]] . $val["file"]["ext"]);
									}
									
									@rename(
										$_CONF["path"] . $_CONF["upload"] . "tmp/" . $_POST[$key . "_temp"] ,
										$_CONF["path"] . $_CONF["upload"] . $val["path"] . $val["file"]["default"] . $_POST[$val["file"]["field"]] . $val["file"]["ext"]
										);	

										// generate the tn image
										if ($val["tn"]["generate"] == "true") {
											@rename(
												$_CONF["path"] . $_CONF["upload"] . "tmp/" . $val["tn"]["preffix"] . $_POST[$key . "_temp"] ,
												$_CONF["path"] . $_CONF["upload"] . $val["path"] . $val["tn"]["preffix"] . $val["file"]["default"] . $_POST[$val["file"]["field"]] . $val["file"]["ext"]
												);	

										}

									//setting the image as true
									$_POST[$key] = 1;
									//updateing the database
									$this->db->QueryUpdate($this->tables[$this->forms["forms"]["edit"]["table"]] , $_POST , "`" . $this->forms["forms"]["edit"]["table_uid"] . "`='" . $_POST[$this->forms["forms"]["edit"]["table_uid"]] . "'" );
								} 
							break;

							default:
								if (is_array($val["file"]))
									SaveFileContents($_CONF["path"] . $_CONF["upload"] . $val["file"]["path"] . $val["file"]["default"] . $_POST[$val["file"]["field"]] . $val["file"]["ext"] , $_POST[$key] );
							break;

						}
					}
				}

				if (!$_GET["type"]) {
					$_GET["type"] = $_POST[$this->forms["forms"]["table_uid"]] ? "edit" : "add";
				}
				

				$this->templates["generic_form"]->blocks["Temp"]->input = $this->forms["forms"][$_GET["type"]]["redirect"];
				//replacing the values
				//die($this->templates["generic_form"]->blocks["Temp"]->Replace($_POST));

				if ($_GET["returnURL"]) {
					header("Location:" . urldecode($_GET["returnURL"]));
					exit;
				}
				
				if ($redirect == true) {
					header("Location: " . CryptLink($this->templates["generic_form"]->blocks["Temp"]->Replace(array_merge($_GET,$_POST))));
					exit;
				} else {
					return true;
				}
			}
								
		} else {
			die("ARGH!!!");
			//redirecting to list page
			header("Location:" . str_replace("&action=store" , "" , $_SERVER["REQUEST_URI"]));
			exit;
		}				


		if (is_array($_fields["values"]))
			$fields["values"] = array_merge($fields["values"], $_fields["values"]);
		
		return $this->form->Show($this->forms["forms"][$_GET["type"]] , $fields);				
	}
	
	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function RestoreURI($section) {
		if (is_array($_GET)) {
			foreach ($_GET as $key => $val) {
				$out[$key] = $key . "=" . $val;
			}
						
			$out[$this->forms["uridata"]["action"]] = $this->forms["uridata"]["action"] . "=" . $this->forms["uridata"][$section];
			unset($out[$this->forms["table_uid"]]);

			return CryptLink($_SERVER["SCRIPT_NAME"] . "?" . implode("&" , $out));

			//return $_
		}		
	}
	

	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function DoEvents($section = ""  , $extra = "" , $values = "") {
		global $base , $_CONF;

		if (is_array($extra)) {
			$this->extra = array_merge($this->extra , $extra);
		}
		
		switch ($_GET[$this->forms["uridata"]["action"]]) {

			case $this->forms["uridata"]["delete"]:
	

				if (($_GET["rconfirm"] == "true")&&($_GET["confirmed"] != "true")) {
					return $this->templates["generic_form"]->blocks["DeleteItem"]->Replace(array(
									"title" => $_GET["title"] ? urldecode($_GET["title"]) : "Delete Item",
									"description" => $_GET["description"] ? urldecode($_GET["description"]) : "Are you sure you want to delete this record?",
									"return" => urldecode($_GET["returnURL"]),
									"cancel_location" => urldecode($_GET["returnURL"]),
									"delete_location" => $_SERVER["REQUEST_URI"] . "&confirmed=true"
								));
				}

				
				//searching for element
				$data = $this->db->QFetchArray("SELECT * FROM `" . $this->tables[$this->forms["forms"]["edit"]["table"]] . "` WHERE `" . $this->forms["forms"]["edit"]["table_uid"] . "`='" . $_GET[$this->forms["forms"]["edit"]["table_uid"]] . "'" );

				//checking if this is a valid data
				if (is_array($data)) {
					$this->db->Query("DELETE FROM `" . $this->tables[$this->forms["forms"]["edit"]["table"]] . "` WHERE `" . $this->forms["forms"]["edit"]["table_uid"] . "`='" . $_GET[$this->forms["forms"]["edit"]["table_uid"]] . "'" );
				}
			
				if ($_GET["returnURL"]) {
					header("Location: " . CryptLink(urldecode($_GET["returnURL"])));
					exit;
				} else {
					header("Location:" . $_SERVER["HTTP_REFERER"]/*$this->RestoreURI("list")*/);
					exit;
				}
				
			break;

			case $this->forms["uridata"]["store"]:
				return $this->StoreRecord();
			break;

			case $this->forms["uridata"]["add"]:
				$fields["values"] = $values;
				return $this->form->Show($this->forms["forms"]["add"] , $fields , $this->extra["add"]);
			break;

			case $this->forms["uridata"]["edit"]:
				//searching for element
				$data = $values["edit"] ? $values["edit"] : $this->db->QFetchArray("SELECT * FROM `" . $this->tables[$this->forms["forms"]["edit"]["table"]] . "` WHERE `" . $this->forms["forms"]["edit"]["table_uid"] . "`='" . $_GET[$this->forms["forms"]["edit"]["table_uid"]] . "'" );

				//checking if this is a valid data
				if (is_array($data)) {
					$fields["values"] = $data;
					return $this->form->Show($this->forms["forms"]["edit"] , $fields , $this->extra["edit"]);
				} 

				header("Location:" . $this->RestoreURI("list"));
				exit;
				
			break;

			case $this->forms["uridata"]["details"]:
				//searching for element
				$data = $this->db->QFetchArray("SELECT * FROM `" . $this->tables[$this->forms["forms"]["edit"]["table"]] . "` WHERE `" . $this->forms["forms"]["edit"]["table_uid"] . "`='" . $_GET[$this->forms["forms"]["edit"]["table_uid"]] . "'" );

				//checking if this is a valid data
				if (is_array($data)) {
					$fields["values"] = $data;
					return $this->form->Show($this->forms["forms"]["details"] , $fields, $this->extra["details"]);
				} 

				header("Location:" . $this->RestoreURI("list"));
				exit;
				
			break;

			case $this->forms["uridata"]["search"]:
			case $this->forms["uridata"]["list"]:
			default:
				
				return $this->FormList($values["list"]);
			break;

		}	
	}
}

?>