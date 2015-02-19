<?php
session_start();

if (isset($_POST['input'])) {
    sleep(1);
    file_put_contents('log.txt', date('Y-m-d H:i:s').': '.$_POST['input']."\n", FILE_APPEND);
    $_SESSION['value']  = $_POST['input'];
    $_SESSION['button'] = isset($_POST['button']) ? $_POST['button'] : '$_POST["button"] not set!';
    header('Location: index.php');
}

$value  = isset($_SESSION['value'])  ? $_SESSION['value']  : '';
$button = isset($_SESSION['button']) ? $_SESSION['button'] : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Check Form for double click preventing</title>

    <link href="http://yastatic.net/bootstrap/2.3.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://yastatic.net/jquery/2.1.3/jquery.min.js"></script>
    <script src="http://yastatic.net/bootstrap/2.3.2/js/bootstrap.min.js"></script>
    <script src="main.js"></script>
</head>
<body>
<div class="container">
    <?php
    if (!empty($value)) {
        echo "<h3>Previous value: {$value}</h3>";
    }

    echo "<h3>Previous button name: {$button}</h3>";

    ?>
    <form method="post">
        <h4>Здесь можно проверить как уходит на сервер несколько запросов,
            при этом работает HTML5 валидация "required",
            а в POST данных есть значение привязанного к самой кнопке, в частности button=form1
        </h4>

        <input type="text" name="input" placeholder="Форма 1" required autofocus>
        <br/>
        <button class="btn btn-lg btn-primary" type="submit" id="form1_button" name="button" value="form1">Жмём несколько раз</button>
    </form>

    <br/>
    <br/>

    <form method="post">
        <h4>Здесь применяется обработчик onclick на кнопке,
            при этом HTML5 валидация "required" не работает,
            но нет возможности посылать несколько запросов,
            а также в POST данных нету значения привязанного к самой кнопке, в частности button=form2
        </h4>

        <input type="text" name="input" placeholder="Форма 2" required>
        <br/>
        <button onclick="this.disabled=true;this.form.submit();" class="btn btn-lg btn-primary" type="submit" id="form2_button" name="button" value="form2">Жмём несколько раз (с onclick)</button>
    </form>

    <br/>
    <br/>

    <form onsubmit="document.getElementById('form3_button').disabled=true;" id="form3" method="post">
        <h4>Здесь применяется обработчик onsubmit на форме,
            при этом HTML5 валидация "required" работает и нет возможности посылать несколько запросов.
            <br/> НО в POST данных нету значения привязанного к самой кнопке, в частности button=form3
        </h4>

        <input type="text" name="input" placeholder="Форма 3" required>
        <br/>
        <button class="btn btn-lg btn-primary" type="submit" id="form3_button" name="button" value="form3">Жмём несколько раз</button>
    </form>

    <br/>
    <br/>

    <form data-action="disable" method="post">
        <h4>Решение. Для всех таких форм добавить атрибут data-action="disable". Просто на form скрипт вешать не стоит, мало ли какие формы будут.</h4>

        <input type="text" name="input" placeholder="Форма 4" required>
        <br/>
        <button class="btn btn-lg btn-primary" type="submit" name="button" value="form4">Жмём несколько раз</button>
    </form>

    <h2>Задача: как сделать так, что бы работала и HTML5 валидация, и блокировка от нескольких кликов, и уходили POST данные самой кнопки?</h2>

    <p>Проверил на 2-х браузерах:</p>
    <ul>
        <li>Firefox 28:
            <ul>
                <li>Форма 1: браузер сам не посылает несколько запросов, и валидация работает - т.е. тут проблемм вообще нет.</li>
                <li>Форма 2: валидация работает и при пустом поле просит его заполнить, но кнопка деактивируется и больше не активируется - что есть неправильно.</li>
            </ul>
        </li>
        <li>Chrome 40:
            <ul>
                <li>Форма 1: валидация работает и при пустом поле просит его заполнить,
                    но при заполенном поле, можно накликаться и увидеть в log.txt все запросы - что есть неправильно.</li>
                <li>Форма 2: валидация <i>НЕ работает</i>, и при клике с пустым полем, на сервер улетает пустое значение - что есть неправильно.
                    А вот если данные введены, то множественный клик предотвращается - это правильно.
                </li>
            </ul>
        </li>
        <li>
            Форма 3 отлично справляется с валидацией и предотвращением множественных отправок в любых браузерах, но не посылает POST данные кнопки.
        </li>
    </ul>

    <br/>
    <br/>

</div>

</body>
</html>
