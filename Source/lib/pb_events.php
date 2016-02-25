<?php

//define ("PB_CRYPT_LINKS" , "1");

function DoEvents($event) {
	global $_CONF , $_TSM;

	$_TSM["MENU"] = "";

	//checking if user is logged in
	if (!$_SESSION["minibase"]["user"]) {

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			//autentificate
            $user = $event->db->QuerySelectLimit($event->tables[users],'*',"`user_login` = '{$_POST[user]}' AND `user_password` = '{$_POST[pass]}'");
			//$user = $event->db->QFetchArray("select * from {} where );

			if (is_array($user)) {
				$_SESSION["minibase"]["user"] = 1;
				$_SESSION["minibase"]["raw"] = $user;

				//redirecing to viuw sites
				header("Location: $_CONF[default_location]");
				exit;
			}
            else{
            	return $event->templates["login"]->blocks["Login"]->output;
            }

		} else{
         //   echo is_object($event->templates);
			return $event->templates["login"]->blocks["Login"]->output;
        }
	}
	if ($_SESSION["minibase"]["raw"]["user_level"] == 0) {
		$_TSM["MENU"] = $event->templates["login"]->blocks["MenuAdmin"]->output;
	} else {
		$_TSM["MENU"] = $event->templates["login"]->blocks["MenuUser"]->output;
	}

	if (!$_POST["task_user"])
		$_POST["task_user"] = $_SESSION["minibase"]["user"];

	if($_SESSION["minibase"]["raw"]["user_level"] == 1) {
		$_CONF["oldforms"]["adminpath"] = $_CONF["oldforms"]["userpath"];
	}

	switch ($_GET["sub"]) {
		case "logout":
			unset($_SESSION["minibase"]["user"]);
			header("Location: index.php");

			return $event->templates["login"]->EmptyVars();
		break;

		case "properties":
		case "expenses":

			if (($_GET["sub"] == "properties") && ($_GET["action"] == "details")) {
				$task = new CSQLAdmin("expenses", $_CONF["oldforms"]["admintemplate"],$event->db,$event->tables , $extra);
				$extra["details"]["fields"]["button"] = $task->DoEvents();			
			}

			$data = new CSQLAdmin($_GET["sub"], $_CONF["oldforms"]["admintemplate"],$event->db,$event->tables,$extra);

			if (($_GET["sub"] == "properties") && ($_GET["action"] == "details")) {
                $expense = $event->db->QuerySelectLimit($event->tables[expenses],'sum(expense_cost)', "expense_prop ='{$_GET[prop_id]}' " .
                    ($_GET[date_year] ? " AND expense_date_year ={$_GET[date_year]} " : '') .
                    ($_GET[date_month] ? " AND expense_date_month ={$_GET[date_month]} " : ''));
				//$expense = $event->db->QFetchArray("SELECT sum(expense_cost) FROM `{$event->tables[expenses]}` WHERE expense_prop ='{$_GET[prop_id]}' " .
				//			($_GET[date_year] ? " AND expense_date_year ={$_GET[date_year]} " : '') .
				//			($_GET[date_month] ? " AND expense_date_month ={$_GET[date_month]} " : ''));
                $property = $event->db->QuerySelectLimit($event->tables[properties], '*', "prop_id='{$_GET[prop_id]}'");
				//$property = $event->db->QFetchArray("SELECT * FROM {$event->tables[properties]} WHERE prop_id='{$_GET[prop_id]}'");

				$data->forms["oldforms"]["details"]["fields"]["expense_total"]= array(
															
															"type" => "text",
															"title" => "Expenses Total",
															"action" => "price",
															"preffix" => "$",
															"forcevalue" => $expense['sum(expense_cost)']
															);

				$data->forms["oldforms"]["details"]["fields"]["expense_income"]= array(
															
															"type" => "text",
															"title" => "Leased Amount",
															"action" => "price",
															"preffix" => "$",
															"forcevalue" => $property['prop_leased_amount']
															);

				if ($_GET["date_month"] && $_GET["date_year"])
					$data->forms["oldforms"]["details"]["fields"]["expense_income2"]= array(
																
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
			
			$data = new CSQLAdmin($_GET["sub"], $_CONF["oldforms"]["admintemplate"],$event->db,$event->tables);
			return $data->DoEvents();
		break;

		default:
 			return "Properties Expenses Administration Area";
		break;
	}
}

?>