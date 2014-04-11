<?php
class CHTML {
	/**
	* generates paging from user data
	*
	* @param mixed $template	template file or object to work w/
	* @param int $ic			total number of items
	* @param int $ipp			items per page
	* @param int $cp			current page
	* @param array $vars		template vars [if any]
	* @param bool $pn			also include prev/next controls? [defaults to TRUE]
	*
	* @return string html page code
	*
	* @access public
	*/
	function Paging($template,$ic,$ipp,$cp,$vars,$pn = TRUE) {
		if ($ipp == 0) 
			return "";

		// check to see if paging required
		if ($ic > $ipp) {
			// init vars
			$result = "";

			// load template
			if (!is_object($template)) {
				$template = new CTemplate($template);
			}

			// set some helper templates
			$tpl_normal = $template->blocks["Page"];
			$tpl_active = $template->blocks["PageActive"];

			// compute page count
			$pc = round(ceil($ic / $ipp));

			// validate page
			if ($cp < 1)
				$cp = 1;
			elseif ($cp > $pc)
				$cp = $pc;

			// iterate thru all the pages
			for ($i = 0; $i < $pc; $i++) {
				// increment zerobased iterator
				$pn = $i + 1;

				// build template and make clickable if needed
				$tpl = ($pn == $cp) ? $tpl_active : $tpl_normal;

				// fill vars
				$vars["PAGE"] = $pn;
				$vars["FACE"] = $pn;

				// replace vars and add to result
				$result .= $tpl->Replace($vars);
			}

			// build prev/next
			if ($pn == TRUE) {
				// check if first page
				if ($cp > 1) {
					// fill vars
					$vars["PAGE"] = $cp - 1;
					$vars["FACE"] = $template->blocks["Prev"]->output;

					// replace vars and prepend to result
					$result = $tpl_normal->Replace($vars) . $result;
				}

				// check if last page
				if ($cp < $pc) {
					// fill vars
					$vars["PAGE"] = $cp + 1;
					$vars["FACE"] = $template->blocks["Next"]->output;

					// replace vars and append to result
					$result .= $tpl_normal->Replace($vars);
				}
			}

			// add the extra info and the pages to the result
			$return["ITEM_COUNT"] = $ic;
			$return["CURRENT_PAGE"] = $cp;
			$return["PAGE_COUNT"] = $pc;
			$return["PAGES"] = $result;

			// return the result
			return $template->blocks["Main"]->Replace($return);
		} else
			return "";
	}

	/**
	* dinamically generates a select form element w/ the provided data
	*
	* @param string $name		tag name attribute
	* @param array $vars		array of option values in the form of "VAL" => "NAME"
	* @param object $template	template object to use for generation
	* @param string $block		name of template block which contains the select body
	* @param string $selected	selected item if any [defaults to void]
	* @param array	$extra_vars	extra variables to be replaced in each option [keys must be
	*							the same of $vars to work properly]
	* @param array	$global_vars extra variables to be replaced in select
	*
	* @return string generated html code
	*
	* @access public
	*/
	function FormSelect($name,$vars,$template,$block,$selected = "",$extra_vars = array(), $global_vars = array()) {

		if (is_array($vars))
			foreach ($vars as $key => $val) {
				$replace = array(
					"VALUE" => $key,
					"NAME" => $val,
					"SELECTED" => (($key == $selected) ? " selected=\"selected\"" : "")
				);
				
				if (is_array($extra_vars))
					$replace = array_merge($replace,$extra_vars[$key]);

				$options .= $template->blocks["{$block}Option"]->Replace($replace);
			}

		if (count($global_vars) != 0)
			$select = $global_vars;
		$select["NAME"] = $name;
		$select["OPTIONS"] = $options;

		return $template->blocks["$block"]->Replace($select);
	}

	/**
	* description generating a custom seeting page from a template
	*
	* @param string $rights		a string contaign all rights
	* @param object $template	template object to use for generation
	* @param array $vars		variable with data :]
	*
	* @return string generated html code
	*
	* @access public
	*/
	function SettingsPage($template,$rights,$vars) {

		if (!$rights)
			return null;		

		$_rights = explode (",",$rights);
		$section = "NONE";		
		
		foreach ($_rights as $right) {
			// building an array with all sections
			if (strstr($right , "SEPARATOR") && is_object($template->blocks[$right] ))
				$SECTIONS[] = $section = $right;
			// buildin an array with sections data (templates)
			if (is_object($template->blocks["Section_" . $right]))
				$CONTENT[$section][] = $template->blocks["Section_" . $right]->Replace($vars);							
		}
		foreach ($SECTIONS as $SECTION) {
			// showing the section header if exists
			$return .= ($SECTION != "NONE") ? $template->blocks["Separator"]->Replace(array("CONTENT"=>$template->blocks[$SECTION]->output)) : "";
			$i = 0;
			while ($i < count($CONTENT[$SECTION])) {
				// showing block 2 by 2
				$content_1 = ($CONTENT[$SECTION][$i] ? $CONTENT[$SECTION][$i] : "<img width=0 height=0>");
				$content_2 = ($CONTENT[$SECTION][$i + 1] ? $CONTENT[$SECTION][$i + 1] : "<img width=0 height=0>");

				$return .= $template->blocks["Content"]->Replace(array("SECTION_1" => $content_1 , "SECTION_2" => $content_2 ));

				$i+= 2;
			}
		}

		return $template->blocks["Main"]->Replace(array("SECTIONS_CONTENT" => $return));
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
	function Table($template,$template_block,$data,$has_paging = FALSE,$element_count = 0,$elements_per_page = 0,$page = 0,$paging_template = NULL,$paging_vars = array()) {

		if (is_array($data))
			foreach ($data as $element) {
				$element["date"] = @date("F j, Y, g:i a",$element["date"]);
				//echo "<br>" . $template_block . "Element";
				$return .= $template->blocks[$template_block . "Element"]->Replace($element);
			}
		else
			$return = $template->blocks[$template_block . "Empty"]->output;

		if ($has_paging == TRUE)
			$paging = $this->Paging($paging_template,$element_count,$elements_per_page,$page,$paging_vars);

		return $template->blocks[$template_block . "Group"]->Replace(array("DATA" => $return, "PAGING" => $paging));
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
	function TableSimple($template,$block,$items,$vars = array(),$filler_func = NULL,$paging = NULL,$page = 0,$tic = 0,$paging_vars = array()) {
		$item_count = count($items);

		if (is_array($items)) {
			foreach ($items as $item) {
				if (is_array($filler_func) && is_array($item))
					call_user_func($filler_func,&$item);
				$rows .= $template->blocks["{$block}Row"]->Replace($item);
			}
		} else
			$rows = $template->blocks["{$block}Empty"]->output;

		// setup paging
		$_paging = ($paging != NULL) ? $this->Paging($paging,$tic,$item_count,$page,$paging_vars) : "";

		// return the built layout
		return $template->blocks[$block]->Replace(array_merge(array("ROWS" => $rows, "PAGING" => $_paging),$vars));
	}

	/**
	* uses the specified data array to build a very simple table
	*
	* @param object	$template	template to use
	* @param string	$block		template block to use
	* @param array	$data		data array to be processed
	*
	* @return mixed the table or void if empty data
	*
	* @access public
	*/
	function TableLight($template,$block,$data) {
		if ($data == "")
			return "";
		else {
			foreach ($data as $item)
				$rows .= $template->blocks["{$block}Row"]->Replace($item);

			return $template->blocks[$block]->Replace(array("ROWS" => $rows));
		}
	}

	/**
	* builds and displays a multi row/col html table
	*
	* @param mixed $template	template file name or object
	* @param string $block		template block which contains the table body
	* @param array $items		array w/ the table items
	* @param int $rc			row count
	* @param int $cc			column count
	* @param array $vars		array of variables to be replaced in block [if needed]
	* @param mixed $filler_func	array of object and method to call for filling other vars in the item
	* @param mixed $paging		template filename or object used for paging [defaults to NULL]
	* @param int $page			current `page'
	* @param int $tic			total item count [used for paging]
	* @param array $paging_vars	array of vars to be replaced in the paging templates
	*
	* @return string html code of the built table
	*
	* @access public
	*/
	function TableComplex($template,$block,$items,$rc,$cc,$vars = array(),$filler_func = NULL,$paging = NULL,$page = 0,$tic = 0,$paging_vars = array()) {
		// compute item count ?
		$item_count = count($items);

		// if we have any items we proceed
		if (is_array($items)) {
			// recompute row/column count
			$row_count = ceil(count($items) / $cc);
			$column_count = ceil(count($items) / $row_count);

			// setup the column and row data
			$columns = "";
			$rows = "";

			// and the position in the data array
			$key = 0;

			// iterate thru all the rows
			for ($i = 0; $i < $row_count; $i++) {
				// iterate thru all the row`s columns
				for ($j = 0; $j < $column_count; $j++) {
					// set our current item
					$item = $items[$key];

					// then feed it to the filler func if needed
					if (is_array($filler_func) && is_array($item))
						call_user_func($filler_func,&$item);

					// populate column data + check if the cell is empty
					$columns .= (is_array($item)) ? $template->blocks["Column"]->Replace($item) : $template->blocks["ColumnEmpty"]->output;

					// increment the position in the data array
					$key++;
				}

				// populate the row data and reset the column data to prepare it for the next row
				$rows .= $template->blocks["Row"]->Replace(array("COLUMNS" => $columns));
				$columns = "";
			}
		} else
			// we dont have any items so we handle it gracefully
			$rows = $template->blocks["Empty"]->output;
		// setup paging
		$_paging = ($paging != NULL) ? $this->Paging($paging,$tic,$item_count,$page,$paging_vars) : "";

		// return the built layout
		return $template->blocks[$block]->Replace(array_merge(array("ROWS" => $rows, "PAGING" => $_paging),$vars));
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
	function error($template,$id) {
		return $template->blocks[$id]->output;
	}

}
?>
