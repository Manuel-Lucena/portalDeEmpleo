<?php $this->layout('layout_simple', ['titulo' => 'Iniciar sesión']); ?>
<?php $this->start('css') ?>

<link rel="stylesheet" href="../Public/css/Sesion.css">
<link rel="stylesheet" href="../Public/css/Modal.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>

<div class="sesion-app"> 
    <div class="login-container">
        <h2>Iniciar sesión</h2>
        <form action="../Public/Index.php" method="post">
  
            <input type="hidden" name="accion" value="Login">

            <label>Usuario</label>
            <input type="text" name="username" required>

            <label>Contraseña</label>
            <input type="password" name="password" required>

            <input type="submit" value="Login">

            <p>¿No tienes cuenta?</p>
            <button id="btnAbrirRegistro" type="button">Registrarse como Alumno</button>
            <button type="button" onclick="location.href='../Public/Index.php?menu=RegistroEmpresa'">Registrarse como Empresa</button>
        </form>
    </div>

    <div id="modalAlumno"></div>
    <div id="modalCamara"></div>
</div>

<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="../Public/js/modal.js"></script>
<script src="../Public/js/logicaRegistro.js"></script>
<script src="../Public/js/Token2.js"></script>
<?php $this->stop() ?>
