<?php

$name = $_POST['name'];
$birthPlace = $_POST['birthPlace'];
$gender = $_POST["gender"];

//記入時間を取得
$time = date('Y/m/d H:i:s');

//ファイルをオープン a; add
$file = fopen('data/data.txt', 'a');

$c = ",";

// ファイルに書き込み
fwrite($file, $time . $name . $birthPlace . $c . $gender . $c . "\n");

//ファイルに保存
fclose($file);

?>


<html>

<head>
    <meta charset="utf-8">
    <title>File書き込み</title>
</head>

<body>

    <h1>書き込みしました。</h1>
    <h2>./data/data.txt を確認しましょう！</h2>

    <ul>
        <li><a href="read.php">確認する</a></li>
        <li><a href="input.php">戻る</a></li>
    </ul>
</body>

</html>