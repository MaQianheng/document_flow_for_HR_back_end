<?php

function generate_arr_sql ($table, $authorized_person) {
	$arr_update_values = [$_GET["status"], $_GET["{$authorized_person}_comments"], $_GET["{$authorized_person}_completion_time"], $_GET["id"]];

	$sql = "UPDATE {$table} SET status=?, {$authorized_person}_comments=?, {$authorized_person}_completion_time=? WHERE id=?";
	return array($arr_update_values, $sql);
}

$username = $_POST["username"];
$password = $_POST["password"];

if (!($username=="admin" && md5($password)==md5('00000000'))) {
	echo json_encode(array('id' => -1, 'message' => 'Authentication failed'));
	exit();
}

// switch ($_GET["type"]) {
// 	case "authorized_leave_form":
// 		$table = "authorized_leave_form_record";
// 		break;
// 	case "sick_leave_form":
// 		$table = "sick_leave_form_record";
// 		break;
// 	case "special_leave_form":
// 		$table = "special_leave_form_record";
// 		break;
// 	case "vacation_leave_form":
// 		$table = "vacation_leave_form_record";
// 		break;
// 	case "vacation_leave_form_for_tmp_staffs":
// 		$table = "vacation_leave_form_for_tmp_staffs_record";
// 		break;
// 	default:
// 		echo json_encode(array('id' => -2, 'message' => 'Required parameters are empty or not matched'));
// 		exit();
// }

$table = "form_record";

if (!empty($_GET["supervisor_completion_time"])) {
	$arr_tmp = generate_arr_sql($table, "supervisor");
} elseif (!empty($_GET["dean_completion_time"])) {
	$arr_tmp = generate_arr_sql($table, "dean");
} elseif (!empty($_GET["vice_president_completion_time"])) {
	$arr_tmp = generate_arr_sql($table, "vice_president");
} elseif (!empty($_GET["president_completion_time"])) {
	$arr_tmp = generate_arr_sql($table, "president");
} else {
	echo json_encode(array('id' => -1, 'message' => 'Required parameters are empty or not matched'));
	exit();
}

require "database_handler.php";
require "functions.php";

$arr_update_values = $arr_tmp[0];
$sql = $arr_tmp[1];

$update_res = sql_executor($conn, $sql, 'sssi', $arr_update_values);

if ($update_res) {
	echo $update_res;
} else {
	echo '0';
}
