<?php 

// username: admin
// password: 00000000

$username = $_POST["username"];
$password = $_POST["password"];
$name = $_GET['name'];
// echo $name;

if (empty($name)) {
	// echo $username;
	// echo $password;
	// echo $name;
	echo json_encode(array(
		'id' => -1,
		'name' => '',
		'code_no' => '',
		'department' => '',
		'position' => '',
		'email' => '',
		'emergency_address' => '',
		'phone_no' => '',
		'authorized_leave_taken_days' => 0,
		'sick_leave_taken_days' => 0,
		'annual_vacation_leave_times' => 0,
		'annual_vacation_leave_days' => 0,
		'err_message' => 'Required parameters are empty'));
	exit();
}

if (!($username=="admin" && md5($password)==md5('00000000'))) {
	echo json_encode(array(
		'id' => -2,
		'name' => '',
		'code_no' => '',
		'department' => '',
		'position' => '',
		'email' => '',
		'emergency_address' => '',
		'phone_no' => '',
		'authorized_leave_taken_days' => 0,
		'sick_leave_taken_days' => 0,
		'annual_vacation_leave_times' => 0,
		'annual_vacation_leave_days' => 0,
		'err_message' => 'Authentication failed'));
	exit();
}

require "database_handler.php";

// $info_sql = "SELECT * FROM staffs_info WHERE name = ?";
$info_sql = "SELECT staffs_info.id, staffs_info.name, staffs_info.code_no, department.department, staffs_info.position, staffs_info.email, staffs_info.emergency_address, staffs_info.phone_no FROM staffs_info INNER JOIN department ON staffs_info.fk_department = department.id AND staffs_info.name=?";
$stmt = $conn->prepare($info_sql);
$stmt->bind_param("s", $name);
$stmt->execute();
$stmt->store_result();

$stmt->bind_result($id, $name, $code_no, $department, $position, $email, $emergency_address, $phone_no);
$stmt->fetch();

if (empty($id)) {
	echo json_encode(array(
		'id' => -3,
		'name' => '',
		'code_no' => '',
		'department' => '',
		'position' => '',
		'email' => '',
		'emergency_address' => '',
		'phone_no' => '',
		'authorized_leave_taken_days' => 0,
		'sick_leave_taken_days' => 0,
		'annual_vacation_leave_times' => 0,
		'annual_vacation_leave_days' => 0,
		'err_message' => 'No such record'));

		$stmt->free_result();
		$stmt->close();
		exit();
}

// authorized_leave_taken_days,
// sick_leave_taken_days,
// annual_vacation_leave_times,
// annual_vacation_leave_days

$year = date('Y')."%";
// SELECT total_days, fk_form_type_id FROM form_record WHERE `status`='approved' AND fk_staff_info_id=211 AND submission_time LIKE '2020%'

$leave_days_sql = "SELECT total_days, fk_form_type_id FROM form_record WHERE `status` = 'approved' AND fk_staff_info_id = ? AND submission_time LIKE ?";
$stmt = $conn->prepare($leave_days_sql);
$stmt->bind_param("is", $id, $year);
$stmt->execute();
$stmt->store_result();

// 1 authorized
// 2 sick
// 3 special
// 4 vacation
// 5 vacation for tmp staffs

$authorized_leave_taken_days = 0;
$sick_leave_taken_days = 0;
$annual_vacation_leave_times = 0;
$annual_vacation_leave_days = 0;

if ($stmt->num_rows > 0) {
    $stmt->bind_result($total_days, $form_type);
    while($row = $stmt->fetch()) {
    	switch ($form_type) {
    		case 1:
    			$authorized_leave_taken_days = $authorized_leave_taken_days + $total_days;
    			break;
    		case 2:
    			$sick_leave_taken_days = $sick_leave_taken_days + $total_days;
    			break;
    		case 3:
    			// code...
    			break;
    		default:
    			$annual_vacation_leave_times = $annual_vacation_leave_times + 1;
    			$annual_vacation_leave_days = $annual_vacation_leave_days + $total_days;
    			break;
    	}
    }
} else {
	echo json_encode(array(
		'id' => $id,
		'name' => $name,
		'code_no' => $code_no,
		'department' => $department,
		'position' => $position,
		'email' => $email,
		'emergency_address' => $emergency_address,
		'phone_no' => $phone_no,
		'authorized_leave_taken_days' => 0,
		'sick_leave_taken_days' => 0,
		'annual_vacation_leave_times' => 0,
		'annual_vacation_leave_days' => 0,
		'err_message' => ''));

	$stmt->free_result();
	$stmt->close();
	exit();
}

echo json_encode(array(
		'id' => $id,
		'name' => $name,
		'code_no' => $code_no,
		'department' => $department,
		'position' => $position,
		'email' => $email,
		'emergency_address' => $emergency_address,
		'phone_no' => $phone_no,
		'authorized_leave_taken_days' => $authorized_leave_taken_days,
		'sick_leave_taken_days' => $sick_leave_taken_days,
		'annual_vacation_leave_times' => $annual_vacation_leave_times,
		'annual_vacation_leave_days' => $annual_vacation_leave_days,
		'err_message' => '')
);

$stmt->free_result();
$stmt->close();

// div(sub(ticks(outputs('Get_response_details')?['body/rce55ada8ae374a29bade1d500535cb3f']),ticks(outputs('Get_response_details')?['body/r4fb8eb5705f1424da77886a795a3dfd8'])),864000000000)

// add(1, div(sub(ticks(body('Get_response_details')?['re5f7588550014503b60362f3f59254a2']),ticks(body('Get_response_details')?['r685f1af9e98f48508ab50d16642dc238'])),864000000000))