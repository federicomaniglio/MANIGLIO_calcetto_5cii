<?php
session_start();
require_once 'database.php';
$title = $_GET['id_campo'];

$pdo = Database::getInstance()->getConnection();
$stmt = $pdo->prepare("SELECT * FROM campi WHERE nome_campo = :nome_campo");
$stmt->execute(["nome_campo" => $_GET['id_campo']]);
$campo = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM utenti");
$stmt->execute();
$utenti = $stmt->fetchAll();


//$stmt = $pdo->prepare("SELECT * FROM prenotazioni WHERE id_campo = :id_campo");
$stmt = $pdo->prepare("SELECT prenotazioni.*, utenti.username FROM prenotazioni
                            INNER JOIN utenti ON prenotazioni.id_utente = utenti.id
                            WHERE prenotazioni.id_campo = :id_campo 
                            ORDER BY prenotazioni.data_prenotazione");
$stmt->execute(["id_campo" => $_GET['id_campo']]);
$prenotazioni = $stmt->fetchAll();

$errorePrenotazione = false;
if (!empty($_POST)) {
    try {
        $stmt = $pdo->prepare("INSERT INTO prenotazioni (id_campo, id_utente, data_prenotazione) VALUES (:id_campo, :id_utente, :data)");
        $stmt->execute([
                ':id_campo' => $_POST['id_campo'],
                ':id_utente' => $_POST['id_utente'],
                ':data' => $_POST['data']
        ]);
        header("Location: index.php");
    } catch (Exception $e) {
        $errorePrenotazione = true;
    }
}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require_once 'navigation.php' ?>
<h1><?= $title ?></h1>


<hr>

<div class="campo">
    <h3> <?= $campo['capienza'] ?> </h3>
    <div class="box-immagine">
        <img src="<?= $campo['foto_url'] ?>">
    </div>
    <p> <?= $campo['capienza'] ?> persone </p>


    <div>
        <?php
        foreach ($prenotazioni as $prenotazione) {
            ?>
            <p> <?= $prenotazione['username'] ?> - <?= $prenotazione['data_prenotazione'] ?> </p>
            <hr>
            <?php
        }
        ?>
    </div>


    <div class="form">
        <form action="" method="post">
            <input type="hidden" name="id_campo" value="<?= $campo['nome_campo'] ?>">
            <?php
            foreach ($utenti as $utente) {
                ?>
                <input type="radio" name="id_utente" value="<?= $utente['id'] ?>" required> <?= $utente['nome'] ?>
                <br>
                <?php
            }
            ?>
            <input type="hidden" name="id_campo" value="<?= $campo['nome_campo'] ?>">
            <label>
                <input type="date" name="data" required>
            </label>
            <input type="submit" value="Prenota">
        </form>
        <?php if($errorePrenotazione) : ?>
            <p class="error">Errore durante la prenotazione</p>
        <?php endif; ?>
    </div>

</div>


</body>
</html>
