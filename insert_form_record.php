<?php

$username = $_POST["username"];
$password = $_POST["password"];

if (empty($_GET["fk_form_type_id"]) || empty($_GET["fk_staff_info_id"])) {
	echo json_encode(array('id' => -1, 'message' => 'Required parameters are empty'));
	exit();
}

if (!($username=="admin" && md5($password)==md5('00000000'))) {
	echo json_encode(array('id' => -2, 'message' => 'Authentication failed'));
	exit();
}

require "database_handler.php";
require "functions.php";

// sperate tables
/*
switch ($_GET["type"]) {
	case "authorized_leave_form":
		$arr_insert_values = [$_GET["id"], $_GET["submission_time"], $_GET["authorized_leave_taken_days"], $_GET["sick_leave_taken_days"], $_GET["annual_leave_taken_days"], $_GET["reason"], $_GET["date_from"], $_GET["date_to"], $_GET["total_days"], $_GET["requested_by"], $_GET["staff_info_id"], $_GET["status"]];

		$sql = "INSERT INTO authorized_leave_form_record (id, submission_time, authorized_leave_taken_days, sick_leave_taken_days, annual_leave_taken_days, reason, date_from, date_to, total_days, requested_by, staff_info_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$insert_res = sql_executor($conn, $sql, 'isiiisssisis', $arr_insert_values);
		break;
	case "sick_leave_form":
		$arr_insert_values = [$_GET["id"], $_GET["submission_time"], $_GET["authorized_leave_taken_days"], $_GET["sick_leave_taken_days"], $_GET["annual_leave_taken_days"], $_GET["nature_of_illness"], $_GET["date_from"], $_GET["date_to"], $_GET["total_days"], $_GET["doctor_certification_is_attached"], $_GET["doctor_certification_link"], $_GET["requested_by"], $_GET["staff_info_id"], $_GET["status"]];

		$sql = "INSERT INTO sick_leave_form_record (id, submission_time, authorized_leave_taken_days, sick_leave_taken_days, annual_leave_taken_days, nature_of_illness, date_from, date_to, total_days, doctor_certification_is_attached, doctor_certification_link, requested_by, staff_info_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$insert_res = sql_executor($conn, $sql, 'isiiisssisssis', $arr_insert_values);
		break;
	case "special_leave_form":
		$arr_insert_values = [$_GET["id"], $_GET["submission_time"], $_GET["reason"], $_GET["date_from"], $_GET["date_to"], $_GET["total_days"], $_GET["requested_by"], $_GET["staff_info_id"], $_GET["status"]];

		$sql = "INSERT INTO special_leave_form_record (id, submission_time, reason, date_from, date_to, total_days, requested_by, staff_info_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$insert_res = sql_executor($conn, $sql, 'issssisis', $arr_insert_values);
		break;
	case "vacation_leave_form":
		$arr_insert_values = [$_GET["id"], $_GET["submission_time"], $_GET["annual_vacation_leave_taken_days"], $_GET["annual_vacation_leave_taken_times"], $_GET["annual_vacation_leave_days"], $_GET["date_from"], $_GET["date_to"], $_GET["total_days"], $_GET["requested_by"], $_GET["staff_info_id"], $_GET["status"], $_GET["phone_no"], $_GET["emergency_address"]];

		$sql = "INSERT INTO vacation_leave_form_record (id, submission_time, annual_vacation_leave_taken_days, annual_vacation_leave_taken_times, annual_vacation_leave_days, date_from, date_to, total_days, requested_by, staff_info_id, status, phone_no, emergency_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$insert_res = sql_executor($conn, $sql, 'isiiissisisis', $arr_insert_values);
		break;
	case "vacation_leave_form_for_tmp_staffs":
		$arr_insert_values = [$_GET["id"], $_GET["submission_time"], $_GET["annual_vacation_leave_taken_days"], $_GET["annual_vacation_leave_taken_times"], $_GET["annual_vacation_leave_days"], $_GET["date_from"], $_GET["date_to"], $_GET["total_days"], $_GET["requested_by"], $_GET["staff_info_id"], $_GET["status"], $_GET["phone_no"], $_GET["emergency_address"]];

		$sql = "INSERT INTO vacation_leave_form_for_tmp_staffs_record (id, submission_time, annual_vacation_leave_taken_days, annual_vacation_leave_taken_times, annual_vacation_leave_days, date_from, date_to, total_days, requested_by, staff_info_id, status, phone_no, emergency_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$insert_res = sql_executor($conn, $sql, 'isiiissisisis', $arr_insert_values);
		break;
	default:
		echo json_encode(array('id' => -3, 'message' => 'No such type'));
		exit();
}
*/

// combined table
$arr_insert_values = [$_GET["submission_time"], $_GET["reason"], $_GET["date_from"], $_GET["date_to"], $_GET["total_days"], $_GET["requested_by"], $_GET["status"], $_GET["doctor_certification_is_attached"], $_GET["doctor_certification_URL"], $_GET["fk_staff_info_id"], $_GET["fk_form_type_id"]];

$sql = "INSERT INTO form_record (submission_time, reason, date_from, date_to, total_days, requested_by, status, doctor_certification_is_attached, doctor_certification_URL, fk_staff_info_id, fk_form_type_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$insert_res = sql_executor($conn, $sql, 'ssssssssii', $arr_insert_values);


if ($insert_res) {
	echo json_encode(array('id' => mysqli_insert_id($conn), 'message' => ''));
} else {
	echo json_encode(array('id' => -3, 'message' => 'Insert fail'));
}