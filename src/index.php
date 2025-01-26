<?php
session_start();

# Generar un token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

# On logout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    if (isset($_POST['Logout'])) {
        # Regenerar ID de sesión para evitar ataques de fijación de sesión
        session_regenerate_id(true);

        # Delete cookies
        setcookie('user', '', time() - 3600, '/', '', true, true);
        setcookie('password', '', time() - 3600, '/', '', true, true);
        setcookie('userId', '', time() - 3600, '/', '', true, true);

        unset($_COOKIE['user']);
        unset($_COOKIE['password']);
        unset($_COOKIE['userId']);

        session_destroy();

        header("Location: index.php");
        exit();
    }
}

# Configurar cookies de sesión seguras
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Práctica RA3</title>
</head>
<body>
    <header>
        <h1>Developers Awards</h1>
    </header>
    <main>
        <h2><a href="insert_player.php"> Add a new player</a></h2>
        <h2><a href="list_players.php"> List of players</a></h2>
        <h2><a href="buscador.html"> Search a player</a></h2>
    </main>
    <form action="#" method="post" class="menu-form">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="submit" name="Logout" value="Logout" class="logout">
    </form>
    <footer>
        <h4>Puesta en producción segura</h4>
        < Please <a href="http://www.donate.co?amount=100&amp;destination=ACMEScouting/"> donate</a> >
    </footer>
</body>
</html>