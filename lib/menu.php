<?php

// dependencies
require_once _LIBPATH . "template.php";

class CMenu extends CXMLParser {
	var $data;
	var $src;
	var $state_base;
	var $item_base;
	var $states;
	var $items;
	var $body;

	function CMenu($file = "",$active = "") {
		parent::CXMLParser();

		if ($file != "") {
			$this->Load($file);
			$this->Build($active);
		}
	}

	function HNDTagOpen($parser,$tag,$attr) {
		parent::HNDTagOpen($parser,$tag,$attr);

		switch ($tag) {
			case "MENU":
				$this->src = $attr["SRC"];
			break;

			case "STATES":
				$this->state_base = $attr["BASE"];
			break;

			case "ITEMS":
				$this->item_base = $attr["BASE"];
			break;
		}
	}

	function HNDCData($parser,$cdata) {
		switch ($this->tag) {
			case "NORMAL":
			case "OVER":
			case "ACTIVE":
				$this->states[$this->tag] = $cdata;
			break;

			case "ITEM":
				$this->items[$cdata]["alt"] = $this->attr["ALT"];
				$this->items[$cdata]["href"] = $this->attr["HREF"];
			break;
		}
	}

	function Load($file) {
		parent::Parse($this->data = GetFileContents($file));
	}

	function Build($active) {
		$this->body = "";
		$tpl = new CTemplate($this->src);
		$tpl_normal = new CTemplate($this->state_base . $this->states["NORMAL"]);
		$tpl_active = new CTemplate($this->state_base . $this->states["ACTIVE"]);

		foreach ($this->items as $key => $item) {
			if ($key == $active)
				$tpl_item = $tpl_active;
			else
				$tpl_item = $tpl_normal;

			$vars = array (
				"HREF" => $item["href"],
				"NAME" => $key,
				"ALT" => $item["alt"]
			);

			$this->body .= $tpl_item->Replace($vars);
		}

		$this->body = $tpl->ReplaceSingle("ITEMS",$this->body);
	}
}
?>