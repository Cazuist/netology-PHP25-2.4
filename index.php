<?php

	include_once('functions.php');
	
	$errors = [];
	$attempt = 6;
	$attemptWithCapcha = 5;
	$totalAttempts = $attempt + $attemptWithCapcha;	

	if (!isset($_SESSION['loginIncor'])) {
		$_SESSION['loginIncor'] = 0;	
	}

	//Проверка авторизации без капчи
	if ($_SESSION['loginIncor'] < $attempt) {
	    if (!empty($_POST['login']) && !empty($_POST['password'])) {		
			
			if (login($_POST['login'], $_POST['password'])) {
				$_SESSION['loginIncor'] = 0;
				$errors = [];
				redirect('admin');
			} else {
				$errors[] = 'Неверные логин и пароль';
				$_SESSION['loginIncor']++;
				$remain = $attempt - $_SESSION['loginIncor'];					
			}
		}
    }    

    //Проверка авторизации с капчей
    if ($_SESSION['loginIncor'] >= $attempt && $_SESSION['loginIncor'] < $totalAttempts) {
    	if (!empty($_POST['login']) && !empty($_POST['password'])) {		
			
			if (login($_POST['login'], $_POST['password']) && $_SESSION['code'] == $_POST['code']) {
				$errors = [];
				$_SESSION['loginIncor'] = 0;
				redirect('admin');
			} 

			if (!login($_POST['login'], $_POST['password']) || $_SESSION['code'] != $_POST['code']) {
				$errors = [];
				$errors[] = 'Неверные логин, пароль или контрольное число';
				$_SESSION['loginIncor']++;
				$remain = $totalAttempts - $_SESSION['loginIncor'];					
			}			
		}
    }

    //Блокировка
    if ($_SESSION['loginIncor'] == $totalAttempts) {
    	/*setcookie('coockie1', 'block', time() + 300);*/
    	$_SESSION['time'] = time();
    	redirect('block');
    }

	if (!empty($_POST['guest'])) {
		redirect('list');
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Авторизация</title>
  <link rel="stylesheet" href="css/styles.css">  
</head>
<body>
	
	<fieldset>
		<legend>Давайте знакомиться</legend>
		<h3>Авторизируйтесь,</h3>

		<ul>
			<? foreach ($errors as $error): ?>
				<ul><?= $error ?></ul>
				<ul>Осталось попыток: <?= $remain ?> </ul>
			<? endforeach ?>			
		</ul>	

		<form method="POST" action="">
			<label>Ваше имя: <input type="text" name="login"></label><br><br>
			<label>Введите пароль: <input type="password" name="password"></label><br><br>
			
			<? if ($_SESSION['loginIncor'] >= $attempt) : ?>
				<label>Код с картинки: <input type="text" name="code"></label><br>
				<img src="capcha.php" alt="This is a capcha!" style="margin-top: 20px;"><br>				
			<? endif ?>

			<br><input type="submit" name="toLog" value="Откройте дверь">	
	 	</form>	 	

		<h3>или войдите как гость</h3>
	 	<form method="POST">
			<label>Введите ваше имя: <input type="text" name="guest"></label><br><br>
			<input type="submit" name="toLog" value="Аккуратно постучитесь">
	 	</form>
 	</fieldset>
    
</body>
</html>