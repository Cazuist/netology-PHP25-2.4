<?php 
    include_once('functions.php');       
    
    $testNumber = $_GET['test'];
    $testsPathList = glob('uploaded_tests/*.json');

    if (!$testsPathList[$testNumber - 1]) {
        header('HTTP/1.1 404 Not Found');
        exit;
    } else {
        $currentTest = json_decode(file_get_contents($testsPathList[$testNumber - 1]) ,true);
    }    

    if (isset($_POST['certificate'])) {
        include_once('certificate.php');        
    }         
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Тест</title>
    <link rel="stylesheet" href="css/styles.css">  
</head>
<body>    

    <h2>Тест №<?= $testNumber ?></h2>
    
    <?php if (!isset($_POST['check'])) : ?>
        <form enctype = "multipart/form-data" action = "" method = "POST" >
                       
             <? foreach ($currentTest as $num => $test) : ?>
                <fieldset>
                    <legend><?= $test['question'] ?></legend>

                    <? foreach ($test['varAnswers'] as $var => $questions) : ?>
                        <label><input type="radio" name="question<?= $num + 1 ?>" value="<?= $var ?>"><?= $questions ?></label><br>
                    <? endforeach ?>

                </fieldset>
            <? endforeach ?>        

        <input type="submit" name="check" value="Проверить ответы" style="margin-top: 20px;">
        </form> 
    <? endif ?>
        
    <?php if (isset($_POST['check'])) {

        $totalQuestions = count($currentTest);
        $correctAnswers = 0;        
        $continue = true;

        foreach ($currentTest as $key => $test) {
            if (!isset($_POST['question'.($key + 1)])) {
                echo "Необходимо ответить на все вопросы"; ?>
                
                <br><a href=''>Вернуться к тесту</a>

                <? $continue = false;
                break;
            } else {
                if ($test['trueAnswer'] === $_POST['question'.($key + 1)]) {
                     $correctAnswers++;
                }
            }                 
        }

        $result = round($correctAnswers / $totalQuestions * 100);        
        $resultList = [
            'name' => $_SESSION['user']['username'],
            'total' => $totalQuestions,
            'correct' => $correctAnswers,
            'result' => $result
        ];

        if ($continue) { ?>
            
            <div>
                <h3>Результаты тестирования пользователя <?= $_SESSION['user']['username'] ?></h3>
                <p>Всего вопросов: <?= $totalQuestions ?></p>
                <p>Правильных ответов: <?= $correctAnswers ?></p>
                <p>Ваш результат: <?= $result ?>%</p>

                <form method="POST" action="">
                    <input type="hidden" name="results[name]" value="<?php echo $resultList['name'] ?>">
                    <input type="hidden" name="results[total]" value="<?php echo $resultList['total'] ?>">
                    <input type="hidden" name="results[correct]" value="<?php echo $resultList['correct'] ?>">
                    <input type="hidden" name="results[result]" value="<?php echo $resultList['result'] ?>">

                    <input type="submit" name="certificate" value="Сгенерировать сертификат">
                </form>
            </div>

        <?}          
    }            
    ?>    
    
    <br><a href="list.php">Перейти к списку тестов</a>
    <a href="index.php"><br>Перейти к авторизации</a>   

</body>
</html>

