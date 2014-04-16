<?php

//define ("PB_CRYPT_LINKS" , "1");

function DoEvents($this) {
	global $_CONF , $_TSM;

	$_TSM["MENU"] = "";

	//checking if user is logged in
	if (!$_SESSION["minibase"]["user"]) {

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			//autentificate
			$user = $this->db->QFetchArray("select * from {$this->tables[users]} where `user_login` = '{$_POST[user]}' AND `user_password` = '{$_POST[pass]}'");

			if (is_array($user)) {
				$_SESSION["minibase"]["user"] = 1;
				$_SESSION["minibase"]["raw"] = $user;

				//redirecing to viuw sites
				header("Location: $_CONF[default_location]");
				exit;
			} else
				return $this->templates["login"]->blocks["Login"]->output;

		} else
			return $this->templates["login"]->blocks["Login"]->output;
	}
	if ($_SESSION["minibase"]["raw"]["user_level"] == 0) {
		$_TSM["MENU"] = $this->templates["login"]->blocks["MenuAdmin"]->output;
	} else {
		$_TSM["MENU"] = $this->templates["login"]->blocks["MenuUser"]->output;
	}

	if (!$_POST["task_user"])
		$_POST["task_user"] = $_SESSION["minibase"]["user"];

	if($_SESSION["minibase"]["raw"]["user_level"] == 1) {
		$_CONF["forms"]["adminpath"] = $_CONF["forms"]["userpath"];
	}

	switch ($_GET["sub"]) {
		case "logout":
			unset($_SESSION["minibase"]["user"]);
			header("Location: index.php");

			return $this->templates["login"]->EmptyVars();
		break;

		case "properties":
		case "expenses":

			if (($_GET["sub"] == "properties") && ($_GET["action"] == "details")) {
				$task = new CSQLAdmin("expenses", $_CONF["forms"]["admintemplate"],$this->db,$this->tables , $extra);
				$extra["details"]["fields"]["button"] = $task->DoEvents();			
			}

			$data = new CSQLAdmin($_GET["sub"], $_CONF["forms"]["admintemplate"],$this->db,$this->tables,$extra);

			if (($_GET["sub"] == "properties") && ($_GET["action"] == "details")) {

				$expense = $this->db->QFetchArray("SELECT sum(expense_cost) FROM `{$this->tables[expenses]}` WHERE expense_prop ='{$_GET[prop_id]}' " .
							($_GET[date_year] ? " AND expense_date_year ={$_GET[date_year]} " : '') .
							($_GET[date_month] ? " AND expense_date_month ={$_GET[date_month]} " : ''));

				$property = $this->db->QFetchArray("SELECT * FROM {$this->tables[properties]} WHERE prop_id='{$_GET[prop_id]}'");

				$data->forms["forms"]["details"]["fields"]["expense_total"]= array(
															
															"type" => "text",
															"title" => "Expenses Total",
															"action" => "price",
															"preffix" => "$",
															"forcevalue" => $expense['sum(expense_cost)']
															);

				$data->forms["forms"]["details"]["fields"]["expense_income"]= array(
															
															"type" => "text",
															"title" => "Leased Amount",
															"action" => "price",
															"preffix" => "$",
															"forcevalue" => $property['prop_leased_amount']
															);

				if ($_GET["date_month"] && $_GET["date_year"])
					$data->forms["forms"]["details"]["fields"]["expense_income2"]= array(
																
																"type" => "text",
																"title" => "Profit",
																"action" => "price",
																"preffix" => "$",
																"forcevalue" => $property['prop_leased_amount'] - $expense['sum(expense_cost)']
																);

			}

			return $data->DoEvents("","",$_POST);
		break;

		case "users":
			
			if ((!$_GET["action"])&&($_SESSION["minibase"]["raw"]["user_level"] != 0 )) {
				$_GET["action"] = "details";				
			}

			if ($_SESSION["minibase"]["raw"]["user_level"] == 1) {
				$_GET["user_id"] = $_SESSION["minibase"]["raw"]["user_id"]; 
				$_POST["user_id"] = $_SESSION["minibase"]["raw"]["user_id"];
			}
			
			$data = new CSQLAdmin($_GET["sub"], $_CONF["forms"]["admintemplate"],$this->db,$this->tables);
			return $data->DoEvents();
		break;

		default:
			return "Properties Expenses Administration Area";
		break;
	}
}

?>