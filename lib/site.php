<?

session_start();

//error_reporting(0);

require_once _LIBPATH . "common.php";
require_once _LIBPATH . "xml.php";
require_once _LIBPATH . "template.php";
require_once _LIBPATH . "config.php";
require_once _LIBPATH . "html.php";
require_once _LIBPATH . "database.php";
require_once _LIBPATH . "vars.php";
require_once _LIBPATH . "menu.php";
require_once _LIBPATH . "library.php";
require_once _LIBPATH . "sqladmin.php";
require_once _LIBPATH . "forms.php";
require_once _LIBPATH . "mail.php";

class CBase {
	/*
	* description
	*
	* @var type
	*
	* @access type
	*/
	var $html;
	
}
class CSite {

	/**
	* description
	*
	* @var type
	*
	* @access type
	*/
	var $admin;
	/**
	* description
	*
	* @var type
	*
	* @access type
	*/
	var $html;
	

	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function CSite($xml , $admin = false) {
		global $_CONF , $base;

		$this->admin = $admin;

		//loading the config
		$tmp_config = new CConfig($xml);

		$_CONF = $tmp_config->vars["config"];

		//loading the templates
		if ($this->admin) {
			if (is_array($_CONF["templates"]["admin"])) {
				foreach ($_CONF["templates"]["admin"] as $key => $val) {
					if ($key != "path")
						$this->templates[$key] = new CTemplate($_CONF["templates"]["admin"]["path"] . $_CONF["templates"]["admin"][$key]);
				}			
			}			
		} else {

			if (is_array($_CONF["templates"])) {
				foreach ($_CONF["templates"] as $key => $val) {
					if (($key != "path" ) && ($key != "admin"))
						$this->templates[$key] = new CTemplate($_CONF["templates"]["path"] . $_CONF["templates"][$key]);
				}				
			}
		}
		

		$base = new CBase();
		$base->html = new CHtml();
		$this->html = &$base->html;

		//make a connection to db
		if (is_array($_CONF["database"])) {
			$this->db = new CDatabase($_CONF["database"]);

			//vars only if needed
			if ($_CONF["tables"]["vars"]) {
				$this->vars = new CVars($this->db , $_CONF["tables"]["vars"]);
				$base->vars = &$this->vars;
			}

			$this->tables = &$_CONF["tables"];
		}				
		
	}

	function TableFiller($item) {
		if (file_exists("pb_tf.php")) {
			include("pb_tf.php");
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
	function Run() {
		global $_TSM;

		if (file_exists("pb_events.php")) {
			include("pb_events.php");
			
			$_TSM["PB_EVENTS"] = @DoEvents(&$this);
		}

		if (is_object($this->templates["layout"])) {
			echo $this->templates["layout"]->Replace($_TSM);
		}		
	}
}


?>