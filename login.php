<?php
session_start();
require_once 'database.php';
require_once 'send_mail.php';

$errore = "";

if(!empty($_POST)){
    $pdo = Database::getInstance()->getConnection();
    $stmt = $pdo->prepare("SELECT * FROM utenti WHERE username = :username");
    $stmt->execute(["username" => $_POST['username']]);
    $utente = $stmt->fetch();

    if($utente && password_verify($_POST['password'], $utente['password'])){
        $_SESSION['username'] = $utente['username'];
        sendLoginMail($utente['username'] . "@gmaill.com", $utente['username']);
        header("Location: index.php");
        exit;
    } else{
        $errore = "Credenziali errate";
    }

}
$title = "Login";
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
<form action="login.php" method="post" class ="login">
    <div>
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
    </div>
    <input type="submit" value="Login">
    <?php if(!empty($errore)): ?>
        <p class="errore"><?= $errore?></p>
    <?php endif; ?>
</form>

</body>
</html>
