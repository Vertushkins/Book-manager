<?php
	function sanitize($str){
		global $pdo;
		$str = htmlentities($str);
		return $pdo->quote($str);
	}
?>
