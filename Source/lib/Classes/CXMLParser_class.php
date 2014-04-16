<?php
/**
 * File description: Class file
 * Class: CXMLParser
 * Modified by Kris Sherrerd
 * Last updated: 4/9/2014
 * Changes Copyright 2014 by Kris Sherrerd
 */

if(!defined('PMC_INIT')){
     die('Your not suppose to be in here! - Ibid');
 }

/**
 * Class CXMLParser
 * Description: XML parser
 * Vars:$parser, $namespace, $attr_namespace, $tag, $attr
  */
class CXMLParser {
	/**
	* actual expat parser
	* @var resource
	* @access protected
	*/
	var $parser;

	/**
	* current namespace
	* @var string
	* @access preotected
	*/
	var $namespace;

	/**
	* specifies whether the current tag attrs have namespaces or not [defaults to FALSE]
	* @var bool
	* @access protected
	*/
	var $attr_namespace = FALSE;

	/**
	* current tag
	* @var string
	* @access protected
	*/
	var $tag;

	/**
	* current tag attributes
	* @var string
	* @access protected
	*/
	var $attr;


    /**
     * needed for config
     * current depth in xml tree
     * @var int
     * @access private
     */
    var $depth = 0;

    /**
     * needed for config
     * config tree
     * @var array
     * @access public
     */
    var $vars = array();

    /**
     * depth tags parser helper
     * @var array
     * @access private
     */
    var $tagsc = array();

    /**
     * @description: basic constructor
     * @return void
     * @access public
     */
    public function __constructor($type = NULL) {
        //Basic constructor, nothing is created.
        $this->InitParser($type);
	}

	/**
	* @description Initilizes Parser
	* @return void;
	* @access private
	*/
	private function InitParser($type = NULL) {
		$this->parser = xml_parser_create();
        if(!isset($type)){
            xml_set_object($this->parser,$this);
            xml_set_element_handler($this->parser,"HNDTagOpen","HNDTagClose");
            xml_set_character_data_handler($this->parser,"HNDCData");
        }
        else{
            xml_set_object($this->parser,$this);
            xml_set_element_handler($this->parser,"HNDTagOpenconfig","HNDTagCloseconfig");
            xml_set_character_data_handler($this->parser,"HNDCDataconfig");
        }
	}

	/**
	* @description open tag handler (remember to call from child to be able to handle cdata properly)
	* @param resource $parser	expat xml parser
	* @param string $tag		current tag
	* @param string $attr		current tag attributes
	* @return void
	* @access public
	*/
	public function HNDTagOpen($parser,$tag,$attr) {
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
			}
            else{
				$_attr["$key"] = $val;
            }
		}

		// update the attr if we have namespaces
		if ($attr_namespace == TRUE) {
			$attr = $_attr;
			$this->attr_namespace = TRUE;
		}
        else{
			$this->attr_namespace = FALSE;
        }

		// update our current tag its attributes
		$this->tag = $tag;
		$this->attr = $attr;
    }

    /**
     * @description xml parser open tag handler
     * @param object $parser	actual expat parser
     * @param string $tag	current xml tag
     * @param array $attr	current tag attributes
     * @return void
     * @access public
     */
    public function HNDTagOpenconfig($parser,$tag,$attr) {//echo "<pre>";
        // call parent to save tag and attr info for cdata handler
        $this->HNDTagOpen($parser,$tag,$attr);

        // expand helper tag array
        $this->tagsc[$this->depth] = $tag;
        $this->depth++;

        // prepare dynamic code for attr handling
        foreach ($this->tagsc as $key => $val)
            $code[] = "\"" . strtolower($val) . "\"";

        // build code
        $node = implode("][",$code);
        $code = "foreach (\$attr as \$key => \$val) if (\$key != \"NAME\") \$this->vars[$node][strtolower(\$key)] = \"\$val\"; else \$this->vars[\$attr[\"NAME\"]][strtolower(\"\$key\")] = \"\$val\";";

        // and finally execute
        eval($code);


    }



    /**
     * @desctiption
     * @param $parser
     * @param $tag
     * @return void
     * @access public
     */
    public function HNDTagClose($parser,$tag) {
		$this->tag = "";
		$this->attr = "";
	}

    /**
     * @description close tag handler
     * @param object $parser	actual expat parser
     * @param string $tag	current xml tag
     * @return void
     * @access public
     */
    public function HNDTagCloseconfig($parser,$tag) {
        // compress helper tag array
        unset($this->tagsc[$this->depth]);
        $this->depth--;
    }

    /**
     * @desctiption
     * @param $parser
     * @param $cdata
     * @return void
     * @access public
     */
	public function HNDCData($parser,$cdata) {
		echo "<br>" .	$cdata;
	}

    /**
     * character data handler
     * @param object $parser	actual expat parser
     * @param string $cdata	current tag character data
     * @return void
     * @access public
     */
    public function HNDCDataconfig($parser,$cdata) {
        $cdata = str_replace("[amp]","&",$cdata);
        //echo "<br>" . $cdata;
        // create the proper tree node if NAME attribute is set
        if (isset($this->attr["NAME"]) && $this->attr["NAME"] != ""){
            $this->tagsc[count($this->tagsc) - 1] = $this->attr["NAME"];
        }
        // cleanup cdata
        $cdata = trim($cdata);
        //$cdata = preg_replace("/(\015\012)|(\015)|(\012)/","",$cdata);

        // only parse if cdata not void
        if ($cdata != "") {
            //print_r($this->attr);
            //echo "<br>" . $cdata;

            // prepare dynamic code
            foreach ($this->tagsc as $key => $val){
                $code[] = "\"" . strtolower($val) . "\"";
            }

            // build code
            $code = "\$this->vars[" . implode("][",$code) . "] = \"" . $cdata . "\";";
  		    // and finally execute
            eval($code);
        }
    }

    /**
     * @desctiption
     * @param $data
     * @return void
     * @access public
     */
	public function Parse($data, $return_type = false, $type = NULL) {
		$this->namespace = "";
		$this->attr_namespace = FALSE;
		$this->tag = "";
		$this->attr = array();
        if(!isset($this->parser)){
            $this->InitParser($type);
        }
		xml_parse($this->parser,$data);
        if($return_type == true){
            return $this->vars;
        }
	}

    public function freeParser(){
        xml_parser_free($this->parser);
    }











}
?>