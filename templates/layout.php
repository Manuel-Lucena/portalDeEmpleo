<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->e($titulo ?? 'Portal de Empleo') ?></title>

    <!-- CSS global -->
    <link rel="stylesheet" href="../Public/css/base.css">

    <?= $this->section('css') ?>
</head>

<body>

    <!-- Header global -->
    <header>
        <a href="../Public/Index.php?menu=Inicio"><img src="../Public/images/Logo.png" class="logo"></a>
        <div class="menu-container">
            <nav>
                <?php
                require_once __DIR__ . '/../Helpers/Login.php';

                use Helpers\Login;

                $rol = Login::getRol();
                switch ($rol) {
                    case '1':
                        echo $this->insert('partial/nav/_nav_admin');
                        break;
                    case '2':
                        echo $this->insert('partial/nav/_nav_empresa');
                        break;
                    case '3':
                        echo $this->insert('partial/nav/_nav_alumno');
                        break;
                    default:
                        echo "<ol><li><a href='../Public/Index.php?menu=Inicio'>Inicio</a></li></ol>";
                        break;
                }
                ?>
            </nav>
        </div>
        <?php if (Login::estaLogeado()) { ?>
            <a href="Index.php?menu=Logout" class="btn-login">Cerrar sesión</a>
        <?php } else { ?>
            <a href="login.php" class="btn-login">Login</a>
        <?php } ?>


    </header>

    <!-- Contenido principal -->
    <main>
        <?= $this->section('contenido') ?>
    </main>

    <!-- Footer global -->
    <footer>
        <div class="footer-contenido">
            <div class="footer-izquierda">
                <img src="../Public/images/Logo.png" class="footer-logo">
                <div class="footer-redes">
                    <a href="">Facebook</a>
                    <a href="">Twitter</a>
                    <a href="">LinkedIn</a>
                </div>
            </div>
            <div class="footer-derecha">
                <ul>
                    <li><a href="">Mapa del sitio</a></li>
                    <li><a href="">Política de privacidad</a></li>
                    <li><a href="">Política de cookies</a></li>
                </ul>
            </div>
        </div>
    </footer>
    <!-- <script src="../Public/js/Token.js"></script> -->

    <?= $this->section('js') ?>
</body>

</html>