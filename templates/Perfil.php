<?php $this->layout('layout_simple', ['titulo' => 'Perfil']); ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="../Public/css/perfil.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>

<section class="perfil">
    <h2>Mi Perfil</h2>

    <div class="perfil-info">
        <p>Nombre de usuario:<?= $usuario['username'] ?></p>
        <p>Correo electrónico:<?= $usuario['email'] ?></p>
        <p><strong>Rol:</strong> <?= $usuario['rol'] ?></p>
    </div>

    <div class="acciones">
        <a href="Index.php?menu=EditarPerfil" class="btn">Editar Perfil</a>
        <a href="Index.php?menu=Logout" class="btn btn-rojo">Cerrar sesión</a>
    </div>
</section>
