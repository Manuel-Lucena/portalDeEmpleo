<?php $this->layout('layout_simple', ['titulo' => 'Detalles de la Empresa']) ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="../Public/css/Forms.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<div class="contenedor-detalles">
  <div class="caja-detalles">
    <?php if (!empty($empresa)): ?>
      <?php 
          $logo = $empresa->getLogo();
          if ($logo && file_exists("../fotos/empresa/" . $logo)) {
              $rutaLogo = "../fotos/empresa/" . $logo;
          } else {
              $rutaLogo = "../fotos/empresa/predeterminada.png";
          }
      ?>

      <h2>Detalles de la Empresa</h2>

      <div class="detalles-entidad">
        <div class="contenedor-logo">
          <img src="<?= $rutaLogo ?>" alt="Logo de la empresa">
        </div>

        <div class="informacion-detalles">
          <p><b>Nombre:</b> <?= $empresa->getNombreEmpresa() ?></p>
          <p><b>Email:</b> <?= $empresa->getEmail() ?></p>
          <p><b>Teléfono:</b> <?= $empresa->getTelefono() ?></p>
          <p><b>Dirección:</b> <?= $empresa->getDireccion() ?></p>
          <p><b>Persona de contacto:</b> <?= $empresa->getPersonaContacto() ?></p>
        </div>
      </div>

    <?php else: ?>
      <p>No se ha encontrado la empresa.</p>
    <?php endif; ?>

    <p><a href="../public/index.php?menu=PanelAdmin" class="btn-volver">Volver al Panel</a></p>
  </div>
</div>

<?php $this->stop() ?>
