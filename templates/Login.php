<?php $this->layout('layout_simple', ['titulo' => 'Iniciar sesión']); ?>
<?php $this->start('css') ?>
<link rel="stylesheet" href="../Public/css/Sesion.css">
<link rel="stylesheet" href="../Public/css/Modal.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>

   <div class="login-container">
    <h2>Iniciar sesión</h2>
    <form action="../Public/Index.php" method="post">
        <label>Usuario</label>
        <input type="text" name="username" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <input type="submit" value="Login" name="accion">
 

    <p>¿No tienes cuenta?</p>
    <button id="btnAbrirRegistro">Registrarse como Alumno</button>
    <a href="../Public/Index.php?menu=RegistroEmpresa" >Registrarse como Empresa</a>

       </form>
</div>


<div id="modalAlumno"></div>
<div id="modalCamara"></div>



<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="../Public/js/modal.js"></script>
<script src="../Public/js/logicaRegistro.js"></script>
<?php $this->stop() ?>