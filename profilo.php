<?php
require_once 'database.php';
session_set_cookie_params([
    "lifetime" => 0,
    "domain" => "localhost",
    "secure" => true,
    "httponly" => true,
    "samesite" => "Strict"
]);
session_start();

$title = "Profilo";

$pdo = Database::getInstance()->getConnection();


if(!isset($_SESSION['username'])){
    header("Location: index.php");
    exit;
}

if(!empty($_POST)){

    if(isset($_POST['logout']) && $_POST['logout'] == 1){
        session_destroy();
        header("Location: index.php");
        exit;
    }



    $stmt = $pdo->prepare("DELETE FROM prenotazioni WHERE id_campo = :id_campo AND data_prenotazione = :data_prenotazione");
    $stmt->execute([
        ':id_campo' => $_POST['id_campo'],
        ':data_prenotazione' => $_POST['data_prenotazione']
    ]);
}



$stmt = $pdo->prepare("SELECT prenotazioni.*, utenti.username FROM prenotazioni
                            INNER JOIN utenti ON prenotazioni.id_utente = utenti.id 
                            ORDER BY utenti.username");
$stmt->execute();
$prenotazioni = $stmt->fetchAll();







?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title><?= $title?></title>
</head>
<body>
<?php require_once 'navigation.php' ?>
<h1><?= $title?></h1>
<div class="profilo">
    <p>Benvenuto, <?= $_SESSION['username'] ?>!</p>

    <form action="" method="post">
        <input type="hidden" name="logout" value="1">
        <button type="submit">Logout</button>
    </form>


    <div class="profilo">
        <h4>Prenotazioni effettuate:</h4>
        <ul>
            <?php foreach ($prenotazioni as $prenotazione): ?>
                <li class="singola-prenotazione">
                    <a href="campi.php?id_campo=<?= $prenotazione['id_campo'] ?>" class="prenotazione-link">
                        <?= $prenotazione['id_campo'] ?> - <?= $prenotazione['username'] ?>
                        - <?= date('d M Y', strtotime($prenotazione['data_prenotazione'])) ?>
                    </a>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="id_campo" value="<?= $prenotazione['id_campo'] ?>">
                        <input type="hidden" name="data_prenotazione" value="<?= $prenotazione['data_prenotazione'] ?>">
                        <button type="submit">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</div>
</body>
</html>
