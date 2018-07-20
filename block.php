<?php
	
	require_once 'functions.php';
	
	$blockTime = 600;

	if ($_SESSION['time'] + $blockTime <= time()) {
		session_destroy();
	    redirect('index');
	}
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Блокировка</title>
  <link rel="stylesheet" href="css/styles.css">  
</head>
<body>
	
	<h3>Вы заблокированы на <?= $blockTime / 60 ?> минут!</h3>
	<p>До окончания блокировки осталось минут: <?= round(($_SESSION['time'] + $blockTime - time()) /  60) ?> </p>	
	<form method="POST" action="">
		<input type="submit" value="Обновить">
	<form>

</body>
</html>