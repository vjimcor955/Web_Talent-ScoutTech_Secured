<?php
require_once dirname(__FILE__) . '/private/conf.php';
require dirname(__FILE__) . '/private/auth.php';

if (!isset($_SESSION['userId'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    # Just in from POST => save to database
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validación de entradas
    $username = filter_var($username, FILTER_SANITIZE_STRING);

    // Hashing de contraseñas
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Uso de consultas preparadas para evitar inyección SQL
    $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);

    $stmt->execute() or die("Invalid query");
    header("Location: list_players.php");
}

# Show form

?>
<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="css/style.css">
        <title>Práctica RA3 - Players list</title>
    </head>
    <body>
        <header>
            <h1>Register</h1>
        </header>
        <main class="player">
            <form action="#" method="post">
                <input type="hidden" name="id" value="<?=$id?>">
                <label>Username:</label>
                <input type="text" name="username">
                <label>Password:</label>
                <input type="password" name="password">
                <input type="submit" value="Send">
            </form>
                <form action="#" method="post" class="menu-form">
                <a href="list_players.php">Back to list</a>
                <input type="submit" name="Logout" value="Logout" class="logout">
            </form>
        </main>
        <footer class="listado">
            <img src="images/logo-iesra-cadiz-color-blanco.png">
            <h4>Puesta en producción segura</h4>
            < Please <a href="http://www.donate.co?amount=100&amp;destination=ACMEScouting/"> donate</a> >
        </footer>
    </body>
</html>

