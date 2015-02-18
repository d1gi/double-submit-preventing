<?php
session_start();
if (isset($_POST['input'])) {
    sleep(1);
    file_put_contents('log.txt', date('Y-m-d H:i:s').': '.$_POST['input']."\n", FILE_APPEND);
    $_SESSION['value'] = $_POST['input'];
    header('Location: index.php');
}
$value = isset($_SESSION['value']) ? $_SESSION['value'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Check Form for double click preventing</title>

    <link href="http://yastatic.net/bootstrap/2.3.2/css/bootstrap.min.css" rel="stylesheet">
		<script src="http://yastatic.net/jquery/2.1.3/jquery.min.js"></script>
<!--    <script src="http://yastatic.net/jquery/2.1.3/jquery.min.js"></script>-->
<!--    <script src="http://yastatic.net/bootstrap/2.3.2/js/bootstrap.min.js"></script>-->
</head>
<body>
<div class="container">
    <?php
    if (!empty($value)) {
        echo "<h3>Previous value: {$value}</h3>";
    }
    ?>
    <form method="post">
        <h4>Здесь можно проверить как уходит на сервер несколько запросов,
            при этом работает HTML5 валидация "required"
        </h4>

        <input type="text" name="input" placeholder="Форма 1" required autofocus>
        <br/>
        <button class="btn btn-lg btn-primary" type="submit" id="form1_button">Жмём несколько раз</button>
    </form>

    <br/>
    <br/>

    <form method="post">
        <h4>Здесь применяется обработчик onclick на кнопке,
            при этом HTML5 валидация "required" не работает,
            но нет возможности посылать несколько запросов.
        </h4>

        <input type="text" name="input" placeholder="Форма 2" required>
        <br/>
        <button onclick="this.disabled=true;this.form.submit();" class="btn btn-lg btn-primary" type="submit" id="form2_button">Жмём несколько раз (с onclick)</button>
    </form>

    <h2>Задача: как сделать так, что бы работала и HTML5 валидация и блокировка от нескольких кликов?</h2>

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
    </ul>

		<br/>
		<br/>

		<h2>Может так?</h2>
		<p>используется jquery</p>

		<form id="form3" method="post">
				<input type="text" name="input" placeholder="Форма 3" required>
				<br/>
				<button class="btn btn-lg btn-primary" type="submit" id="form3_button">Жмём несколько раз</button>
				<script type="text/javascript">
					$('#form3').submit(function(){
						$('#form3_button').attr('disabled', 'disabled');
					});
				</script>
		</form>
</div>

</body>
</html>
