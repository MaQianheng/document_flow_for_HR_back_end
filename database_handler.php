<?php
// mysql --host=35.240.173.241 --user=root --password
$conn = mysqli_connect('35.240.173.241','root','00000000','SP2');
if (!$conn) {
	die("Connect db Fail: ".mysqli_connect_error());
}
$conn->query("set names'utf8'");
$conn->set_charset('utf8_general_ci');
?>