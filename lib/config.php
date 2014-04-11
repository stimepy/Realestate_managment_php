<?php
class CConfig extends CXMLParser {
	/**
	* current depth in xml tree
	*
	* @var int
	*
	* @access private
	*/
	var $depth = 0;

	/**
	* depth tags parser helper
	*
	* @var array
	*
	* @access private
	*/
	var $tags = array();

	/**
	* config tree
	*
	* @var array
	*
	* @access public
	*/
	var $vars = array();

	/**
	* creates the xml parser and optionally loads a config file
	*
	* @param string $file_name	config file name to load
	*
	* @return void
	*
	* @access public
	*/
	function CConfig($file_name = "") {
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
	function HNDTagOpen($parser,$tag,$attr) {//echo "<pre>";
		// call parent to save tag and attr info for cdata handler
		parent::HNDTagOpen($parser,$tag,$attr);
		
		// expand helper tag array
		$this->tags[$this->depth] = $tag;
		$this->depth++;

		// prepare dynamic code for attr handling
		foreach ($this->tags as $key => $val)
			$code[] = "\"" . strtolower($val) . "\"";

		// build code
		$node = implode("][",$code);
		$code = "foreach (\$attr as \$key => \$val) if (\$key != \"NAME\") \$this->vars[$node][strtolower(\$key)] = \"\$val\"; else \$this->vars[\$attr[\"NAME\"]][strtolower(\"\$key\")] = \"\$val\";";

		// and finally execute
		eval($code);
	}

	/**
	* close tag handler
	*
	* @param object $parser	actual expat parser
	* @param string $tag	current xml tag
	*
	* @return void
	*
	* @access private
	*/
	function HNDTagClose($parser,$tag) {
		// compress helper tag array
		unset($this->tags[$this->depth]);
		$this->depth--;
	}

	/**
	* character data handler
	*
	* @param object $parser	actual expat parser
	* @param string $cdata	current tag character data
	*
	* @return void
	*
	* @access private
	*/
	function HNDCData($parser,$cdata) {
		$cdata = str_replace("[amp]","&",$cdata);
		//echo "<br>" . $cdata;
		// create the proper tree node if NAME attribute is set
		if ($this->attr["NAME"] != "")
			$this->tags[count($this->tags) - 1] = $this->attr["NAME"];

		// cleanup cdata
		$cdata = trim($cdata);
		//$cdata = preg_replace("/(\015\012)|(\015)|(\012)/","",$cdata);

		// only parse if cdata not void
		if ($cdata != "") {
			//print_r($this->attr);
			//echo "<br>" . $cdata;

			// prepare dynamic code
			foreach ($this->tags as $key => $val)
				$code[] = "\"" . strtolower($val) . "\"";

			// build code
			$code = "\$this->vars[" . implode("][",$code) . "] = \"" . $cdata . "\";";

			// and finally execute
			eval($code);
		}
	}

	/**
	* load the config file and parse it
	*
	* @param string $file_name	config filename to load
	*
	* @return void
	*
	* @acces public
	*/
	function Load($file_name) {
		parent::Parse($this->data = str_replace("&","[amp]",GetFileContents($file_name)));
		$this->vars = ArrayReplace("[amp]" , "&" , $this->vars );
	}
}
?>