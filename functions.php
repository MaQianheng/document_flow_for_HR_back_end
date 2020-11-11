<?php
function refValues ($arr) {
	 //Reference is required for PHP 5.3+
	if (strnatcmp(phpversion(),'5.3') >= 0)
	{
		$refs = array();
		foreach($arr as $key => $value)
			$refs[$key] = &$arr[$key];
		return $refs;
	}
	return $arr;
}


function sql_executor ($conn, $stmt_sql, $bind_param_str, $values) {
	// echo '1';
	// var_dump($conn);
	// var_dump($stmt_sql);
	// var_dump($values);
	// $stmt = $conn->prepare($stmt_sql);
	// var_dump($stmt);
	if ($stmt = $conn->prepare($stmt_sql)) {
	    /* Bind variable for placeholder */
	    array_unshift($values, $bind_param_str);
	    call_user_func_array(array($stmt,'bind_param'), refValues($values));
	    $stmt->execute();
	    // var_dump($stmt);
	    $res = $stmt->affected_rows;
	    $stmt->close();
	    return $res;
	}
}