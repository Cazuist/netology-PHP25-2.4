<?php

    include_once('functions.php');

    if (!isAuthorised()) {
        http_response_code(403);
        /*echo 'Вы не авторизованный пользователь!';*/
        echo '<p><a href="index.php">Перейти к авторизации</a></p>';
        exit;
    }

    $upploadDir = 'uploaded_tests';

    if (isset($_FILES) && isset($_FILES['testfile'])) {
        
        $fileName = strtolower($_FILES['testfile']['name']);
        $filePathTmp = $_FILES['testfile']['tmp_name'];
        
        if ($fileName) {
            $decode = file_get_contents($filePathTmp);
            $valid = isValidJSON($decode);       

            if (strpos($fileName, 'json')) {            
                if ($valid) {
                    move_uploaded_file($filePathTmp, $upploadDir.'/'.$fileName);

                    $message = 'Поздравляем! Ваш тест успешно загружен.';
                    header('Location: list.php');

                } else {                
                    $message = 'Структура файла не JSON!';
                }

            } else {
                $message = 'Загрузите файл с расширением JSON!';
            }

        } else {
            $message = 'Вы должны выбрать файл!';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Загрузка тестов</title>
    <link rel="stylesheet" href="css/styles.css">  
</head>
<body>

    <form enctype = "multipart/form-data" action="admin.php" method="POST">
        <input type = "file" name="testfile" style="display: block; margin-bottom: 10px">
        <input type = "submit" value="Загрузить">
    </form>

    <p><pre><?= $message ?></pre></p><br>

    <a href="list.php">Перейти к списку тестов</a><br>
    <a href="logout.php">Выйти</a>
    
</body>
</html>