<?php
	session_start();

	function login($login, $password) {
		$user = getUser($login);

		if ($user != null && $user['password'] === $password) {
			$_SESSION['user'] = $user;
			return true;
		}
		return false;
	}

	function getUser($login) {
		$users = getUsers();
		foreach ($users as $user) {
			if ($login === $user['login']) {
				return $user;
			}
		}
		return null;
	}

	function getUsers() {
		$userData = file_get_contents(__DIR__.'/data/users.json');
		if (!$userData) {
			return [];
		}

		$users = json_decode($userData, true);

		if(!$users) {
			return [];
		}
		return $users;
	}

	function isAuthorised() {
		return !empty($_SESSION['user']);
	}

	function redirect($page) {
		header("Location: $page.php");
		exit;
	}

	function isValidJSON ($file) {
    	return (json_decode($file)) ? true : false;
	}
?>