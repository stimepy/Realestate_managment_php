<?php

class CXMLParser {
	/**
	* actual expat parser
	*
	* @var resource
	*
	* @access protected
	*/
	var $parser;

	/**
	* current namespace
	*
	* @var string
	*
	* @access preotected
	*/
	var $namespace;

	/**
	* specifies whether the current tag attrs have namespaces or not [defaults to FALSE]
	*
	* @var bool
	*
	* @access protected
	*/
	var $attr_namespace = FALSE;

	/**
	* current tag
	*
	* @var string
	*
	* @access protected
	*/
	var $tag;

	/**
	* current tag attributes
	*
	* @var string
	*
	* @access protected
	*/
	var $attr;

	function CXMLParser() {
		// ??
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
	function InitParser() {		
		$this->parser = xml_parser_create();

		xml_set_object($this->parser,&$this);
		xml_set_element_handler($this->parser,"HNDTagOpen","HNDTagClose");
		xml_set_character_data_handler($this->parser,"HNDCData");
	}

	/**
	* open tag handler (remember to call from child to be able to handle cdata properly)
	*
	* @param resource $parser	expat xml parser
	* @param string $tag		current tag
	* @param string $attr		current tag attributes
	*
	* @return void
	*
	* @acces public
	*/
	function HNDTagOpen($parser,$tag,$attr) {
		// pass the tag as a reference so it gets updated when we have a namespace
		if (strpos($tag,":")) {
			list($namespace,$tag) = explode(":",$tag);
			$this->namespace = $namespace;
		}

		// now check and handle the attr namespaces
		$attr_namespace = FALSE;


		foreach ($attr as $key => $val) {
			if (strpos($key,":")) {
				$attr_namespace = TRUE;

				list($namespace,$name) = explode(":",$key);
				$_attr["$namespace"]["$name"] = $val;
			} else
				$_attr["$key"] = $val;
		}

		// update the attr if we have namespaces
		if ($attr_namespace == TRUE) {
			$attr = $_attr;
			$this->attr_namespace = TRUE;
		} else
			$this->attr_namespace = FALSE;

		// update our current tag its attributes
		$this->tag = $tag;
		$this->attr = $attr;
	}

	function HNDTagClose($parser,$tag) {
		$this->tag = "";
		$this->attr = "";
	}

	function HNDCData($parser,$cdata) {
		echo "<br>" .	$cdata;
	}

	function Parse($data) {
		$this->namespace = "";
		$this->attr_namespace = FALSE;
		$this->tag = "";
		$this->attr = array();

		$this->InitParser();

		xml_parse($this->parser,$data);
		xml_parser_free($this->parser);
	}
}
?>