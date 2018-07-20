<?php

    include_once('functions.php');

    $testsPathList = glob('uploaded_tests/*.json');

    foreach ($testsPathList as $num => $test) {
        if (isset($_POST[$num])) {
            unlink($testsPathList[$num]);
            $testsPathList = glob('uploaded_tests/*.json');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Список тестов</title>
    <link rel="stylesheet" href="css/styles.css">  
</head>
<body>

    <h3>Список доступных тестов</h3>    
    
    <?php        
        
        foreach ($testsPathList as $num => $test) {
            $singleTest = file_get_contents($test);
            $testNum = 'Тест №'. ($num + 1).'.';
        ?>            
            <div>
                <h3><?= $testNum ?></h3>
                <a href="test.php?test=<?= $num + 1 ?>" target="_blank">Пройти тест</a><br>
                <?php if (isAuthorised()) : ?>
                    <form method="POST" action="">

                        <input type="submit" name="<?= $num ?>" value="Удалить тест">
                        
                    </form>
                    
                <?php endif ?>                    
            </div>
        <?php
        }

        if (!count($testsPathList)) {
            echo '<p>Нет доступных тестов</p>';
        }

    ?>
    
    <hr>
    <?php if (isAuthorised()) : ?>
        <a href="admin.php">Перейти к загрузке тестов</a><br>
        <a href="logout.php">Выйти</a>
    <?php endif ?>

    <?php if (!isAuthorised()) : ?>
        <a href="index.php"><br>Перейти к авторизации</a>
    <? endif ?>

</body>
</html>