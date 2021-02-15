<?php
	$token = $args['token'];
    $auth = $conn->query("SELECT id FROM user WHERE token = '$token'");
