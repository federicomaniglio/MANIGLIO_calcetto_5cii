<?php
require_once 'database.php';
$title = "Calcetto";

$pdo = Database::getInstance()->getConnection();
//
//$stmt = $pdo->query("SELECT * FROM campi ORDER BY  capienza DESC");
//$stmt->execute();
//$result = $stmt->fetchAll();


$result = $pdo->query("SELECT * FROM campi ORDER BY  capienza DESC");

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?= $title ?>
    </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1><?= $title ?></h1>
<?php
foreach ($result as $row){
?>
<hr>
<div class="campo">
    <h3> <?= $row['nome_campo'] ?> </h3>
    <div class="box-immagine">
        <a href="campi.php?id_campo=<?=$row['nome_campo'] ?>"> <img src="<?= $row['foto_url']?>"> </a>
    </div>
    <p> <?= $row['capienza'] ?> persone </p>
</div>
<?php
}
?>
</body>
</html>
