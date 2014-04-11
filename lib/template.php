<?php
/*

*/
$_TSM = array();

/**
*/
class CTemplate {
	/**
	* template source data
	*
	* @var string
	*
	* @access private
	*/
	var $input;

	/**
	* template result data
	*
	* @var string
	*
	* @access public
	*/
	var $output;

	/**
	* template blocks if any
	*
	* @var array
	*
	* @access public
	*/
	var $blocks;

	/**
	* constructor which autoloads the template data
	*
	* @param string $source			source identifier; can be a filename or a string var name etc
	* @param string $source_type	source type identifier; currently file and string supported
	*
	* @return void
	*
	* @acces public
	*/
	function CTemplate($source,$source_type = "file") {
		$this->Load($source,$source_type);
	}

	/**
	* load a template from file. places the file content into input and output
	* also setup the blocks array if any found
	*
	* @param string $source			source identifier; can be a filename or a string var name etc
	* @param string $source_type	source type identifier; currently file and string supported
	*
	* @return void
	*
	* @acces public
	*/
	function Load($source,$source_type = "file") {
		switch ($source_type) {
			case "file":
				// get the data from the file
				$data = GetFileContents($source);
				//$data = str_Replace('$','\$',$data);
			break;
			
			case "rsl":
			case "string":
				$data = $source;
			break;
		}


		// blocks are in the form of <!--S:BlockName-->data<!--E:BlockName-->
		preg_match_all("'<!--S\:.*?-->.*?<!--E\:.*?-->'si",$data,$matches);

		// any blocks found?
		if (count($matches[0]) != 0)
			// iterate thru `em
			foreach ($matches[0] as $block) {
				// extract block name
				$name = substr($block,strpos($block,"S:") + 2,strpos($block,"-->") - 6);

				// cleanup block delimiters
				$block = substr($block,9 + strlen($name),strlen($block) - 18 - strlen($name) * 2);

				// insert into blocks array
				$this->blocks["$name"] = new CTemplate($block,"string");
			}

		// cleanup block delimiters and set the input/output
		$this->input = $this->output = preg_replace(array("'<!--S\:.*?-->(\r\n|\n|\n\r)'si","'<!--E\:.*?-->(\r\n|\n|\n\r)'si"),"",$data);
	}

	/**
	* replace template variables w/ actual values
	*
	* @param array $vars	array of vars to be replaced in the form of "VAR" => "val"
	* @param bool $clear	reset vars after replacement? defaults to TRUE
	*
	* @return string the template output
	*
	* @acces public
	*/
	function Replace($vars,$clear = TRUE) {
		if (is_array($vars)) {
			foreach ($vars as $key => $var) {
				if (is_array($var)) {
					unset($vars[$key]);
				}				
			}			
		}
		
		// init some temp vars
		$patterns = array();
		$replacements = array();

		// build patterns and replacements
		if (is_array($vars))
			// just a small check		
			foreach ($vars as $key => $val) {
				$patterns[] = "/\{" . strtoupper($key) . "\}/";

				//the $ bug
				$replacements[] = str_replace('$','\$',$val);
			}

		// do regex
		$result = $this->output = preg_replace($patterns,$replacements,$this->input);

		// do we clear?
		if ($clear == TRUE)
			$this->Clear();

		// return output
		return $result;
	}

	/**
	* replace a single template variable
	*
	* @param string $var	variable to be replaced
	* @param string $value	replacement
	* @param bool $perm		makes the change permanent [i.e. replaces input also]; defaults to FALSE
	*
	* @return string result of replacement
	*
	* @acces public
	*/
	function ReplaceSingle($var,$value,$perm = FALSE) {
		$var = strtoupper($var);

		if ($perm == TRUE)
			$this->input = str_replace("\{$var}",$value,$this->input);

		return $this->output = str_replace("\{$var}",$value,$this->output);
	}

	/**
	* resets all the replaced vars to their previous status
	*
	* @return void
	*
	* @acces public
	*/
	function Clear() {
		$this->output = $this->input;
	}

	/**
	* voids every template variable
	*
	* @return void
	*
	* @acces public
	*/
	function EmptyVars() {
		global $_TSM;

		//$this->output = $this->ReplacE($_TSM["_PERM"]);
		//return$this->output = preg_replace("'{[A-Z]}'si","",$this->output);
		return $this->output = preg_replace("'{[A-Z_\-0-9]*?}'si","",$this->output);
		//return $this->output = preg_replace("'{[\/\!]*?[^{}]*?}'si","",$this->output);
	}

	/**
	* checks if the specified template block exists
	*
	* @param string	$block_name	block name to look for
	*
	* @return bool TRUE if exists or FALSE if it doesnt
	*
	* @access public
	*/
	function BlockExists($block_name) {
		return isset($this->blocks[$block_name]) ? TRUE : FALSE;
	}
}

/**
* template package class [layout]
*
*/
class CLayout extends CXMLParser {
	/**
	* raw template data
	*
	* @var string $data
	*
	* @access private
	*/
	var $data;

	/**
	* layout version; defaults to 1.0
	*
	* @var string $version
	*
	* @access private
	*/
	var $version;

	/**
	* base block name
	*
	* @var string $name
	*
	* @access private
	*/
	var $name;

	/**
	* base layout path
	*
	* @var string $base
	*
	* @access private
	*/
	var $base;

	/**
	* main layout template
	*
	* @var object $body
	*
	* @access private
	*/
	var $body;

	/**
	* layout blocks
	*
	* @var array $blocks
	*
	* @access private
	*/
	var $blocks;

	/**
	* specifies whether the layout has been loaded ok or not
	*
	* @var bool $loaded
	*
	* @access private
	*/
	var $loaded = FALSE;

	/**
	* constructor which optionally autoloads the layout
	*
	* @param string $file_name	template filename to autoload
	*
	* @return void
	*
	* @acces public
	*/
	function CLayout($file_name = "") {
		parent::CXMLParser();

		if ($file_name != "")
			$this->Load($file_name);
	}

	/**
	* xml parser open tag handler
	*
	* @param object $parser	actual expat parser
	* @param string $tag	current xml tag
	* @param array $attr	current tag attributes
	*
	* @return void
	*
	* @acces private
	*/
	function HNDTagOpen($parser,$tag,$attr) {
		global $base;

		switch ($tag) {
			// handle main tag; setup a few things like version, base, name and load body
			case "LAYOUT":
				$this->version = ($attr["VERSION"] == "") ? "1.0" : $attr["VERSION"];
				$this->base = $attr["BASE"];
				$this->name = $attr["NAME"];
				$this->body = new CTemplate($GLOBALS["_TEMPLATES_PATH"] . $this->base . $attr["SRC"]);
			break;

			// load the block into the block array
			case "BLOCK":
				$this->blocks[$attr["NAME"]] = new CTemplate($GLOBALS["_TEMPLATES_PATH"] . $this->base . $attr["SRC"]);
			break;

			// assign tag
			case "ASSIGN":
				// figure out what kinda assign we have
				switch ($attr["TYPE"]) {
					// static variable assign from layout
					case "var":
						if ($attr["BLOCK"] == $this->name)
							$this->body->ReplaceSingle($attr["VAR"],$attr["VAL"],TRUE);
						else
							$this->blocks[$attr["BLOCK"]]->ReplaceSingle($attr["VAR"],$attr["VAL"]);
					break;

					// static template assign w/ optional require
					case "tpl":
						$parse = TRUE;

						if ($attr["REQUIRES"] != "")
							if ($GLOBALS["_TSM"][$attr["REQUIRES"]] == "")
								$parse = FALSE;

						if ($parse)
							$val = GetFileContents($this->base . $attr["VAL"],TRUE);
						else
							$val = $attr["DEFAULT"];

						if ($attr["BLOCK"] == $this->name)
							$this->body->ReplaceSingle($attr["VAR"],$val,TRUE);
						else
							$this->blocks[$attr["BLOCK"]]->ReplaceSingle($attr["VAR"],$val,TRUE);

					break;

					// assign the results returned by a called function
					case "call":
						if ($attr["BLOCK"] == $this->name)
							$this->body->ReplaceSingle($attr["VAR"],call_user_func($attr["VAL"]),TRUE);
						else
							$this->blocks[$attr["BLOCK"]]->ReplaceSingle($attr["VAR"],call_user_func($attr["VAL"]));
					break;

					// assign the results returned by a called module method
					case "module":
						if (strstr($attr["PARAMS"],",")) {
							$params = "," . $attr["PARAMS"];
							$call = "\$result = call_user_func(array(&\$base->modules[\$attr[\"MODULE\"]],\$attr[\"METHOD\"])$params);";
							echo $call;
							eval($call);

							//wtf, smth is wrong
//							$temp = explode(',',$attr["PARAMS"]);
//							$result = call_user_func(array(&$base->modules[$attr["MODULE"]],$attr["METHOD"]),$temp[0],$temp[1],$temp[2],$temp[3],$temp[4]);
						} else {
							$call = "\$result = \$base->modules[$attr[MODULE]]->$attr[METHOD]($attr[PARAMS]);";
							eval($call);							
							}
							//$result = call_user_func(array(&$base->modules[$attr["MODULE"]],$attr["METHOD"]),$attr["PARAMS"]);

						if ($attr["BLOCK"] == $this->name)
							$this->body->ReplaceSingle($attr["VAR"],$result,TRUE);
						else
							$this->blocks[$attr["BLOCK"]]->ReplaceSingle($attr["VAR"],$result,TRUE);
					break;
				}
			break;
		}
	}

	/**
	* load the layout and parse it
	*
	* @param string $file_name	layout filename to load
	*
	* @return void
	*
	* @acces public
	*/
	function Load($file_name) {
		if (file_exists($file_name)) {
			parent::Parse($this->data = GetFileContents($file_name));
			$this->loaded = TRUE;
		} else
			$this->loaded = FALSE;
	}

	/**
	* replace the body vars
	*
	* @param array $vars	array of variables to be replaced in the form of "VAR" => "val"
	*
	* @return void
	*
	* @acces private
	*/
	function Replace($vars) {
		$this->body->Replace($vars,FALSE);
	}

	/**
	* replace the vars in a certain block [do we really need this?]
	*
	* @param string $block	block in which to make the replacements
	* @param array $vars	array of variables to be replaced in the form of "VAR" => "val"
	*
	* @return void
	*
	* @acces private
	*/
	function BlockReplace($block,$vars) {
		$this->blocks[$block]->Replace($vars,FALSE);
	}

	/**
	* checks if a block exists in the layout
	*
	* @param string $block	name of block that we ar searchin
	*
	* @return bool TRUE if found or FALSE if not found
	*
	* @access public
	*/
	function BlockExists($block) {
		if (isset($this->blocks[$block]))
			return TRUE;
		else
			return FALSE;
	}

	/**
	* build the layout and perform all the replacements using tsm
	*
	* @return void
	*
	* @acces public
	*/
	function Build() {
		global $_TSM;

		if ($this->loaded == FALSE)
			return;

		$vars = array();

		// do we have any blocks?
		if (is_array($this->blocks)) {
			// yup! iterate thru `em
			foreach ($this->blocks as $key => $block) {
				// anything in tsm that we should replace here?
				if ($_TSM[$key])
					$this->BlockReplace($key,$_TSM[$key]);
				
				// grab processed template output
				$vars[$key] = $block->output;
			}

			// do something w/ the blocks
			foreach ($_TSM as $k => $v)
				if (!$this->BlockExists($k))
					$vars[$k] = $v;

			// add the perms
			$vars = array_merge($vars,$_TSM["_PERM"]);

			// replace everything in main
			$this->body->Replace($vars,FALSE);
		} else
			$this->body->Replace($_TSM,FALSE);
	}

	/**
	* spit out the built layout
	*
	* @return void
	*
	* @acces public
	*/
	function Show() {
		print($this->body->output);
	}
}
?>