
MAMANI Sofia <sofix.nai.mamani@gmail.com>
8:18 a.m. (hace 7 minutos)
para mí

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titulo Ejemplo</title>
</head>
<body>
    <header>
        <?php require_once "navbar.php"; ?>
    </header>

    <?php
        $section = (isset($section)) ? $section : 'home';
        require_once $section . '.php';
    ?>

    <footer>
        <?php require_once "footer.php"; ?>
    </footer>
</body>
</html>