<?php $this->layout('layout_simple', ['titulo' => 'Editar Empresa']); ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="../Public/css/Sesion.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<div class="registro-container">
  <div class="registro-box">
    <h2>Editar Empresa</h2>

    <?php if ($empresa): ?>
      <form method="post" action="index.php?menu=EditarEmpresa" enctype="multipart/form-data" class="form-dos-columnas">
        <input type="hidden" name="idUser" value="<?= $empresa->getIdUser() ?>">

        <!-- Fila 1: Nombre de empresa y Usuario (o Email) -->
        <div class="fila">
          <div class="campo">
            <label for="nombreEmpresa">Nombre de la empresa:</label>
            <input type="text" id="nombreEmpresa" name="nombreEmpresa" value="<?= $empresa->getNombreEmpresa() ?>" required>
          </div>

          <div class="campo">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= $empresa->getEmail() ?>" required>
          </div>
        </div>

        <!-- Fila 2: Contraseña y Confirmación -->
        <div class="fila">
          <div class="campo">
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" placeholder="(Dejar en blanco si no se cambia)">
          </div>

          <div class="campo">
            <label for="repetirContraseña">Repetir contraseña:</label>
            <input type="password" id="repetirContraseña" name="repetirContrasena" placeholder="(Dejar en blanco si no se cambia)">
          </div>
        </div>

        <!-- Fila 3: Teléfono y Persona de contacto -->
        <div class="fila">
          <div class="campo">
            <label for="telefono">Teléfono de contacto:</label>
            <input type="text" id="telefono" name="telefono" value="<?= $empresa->getTelefono() ?>">
          </div>

          <div class="campo">
            <label for="personaContacto">Persona de contacto:</label>
            <input type="text" id="personaContacto" name="personaContacto" value="<?= $empresa->getPersonaContacto() ?>">
          </div>
        </div>

        <!-- Fila 4: Dirección -->
        <div class="fila">
          <div class="campo span-dos">
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" value="<?= $empresa->getDireccion() ?>">
          </div>
        </div>

        <!-- Fila 5: Logo de empresa -->
        <div class="fila">
          <div class="campo span-dos">
            <label for="logo">Logo de empresa:</label>
            <input type="file" id="logo" name="logo" accept="image/*">
          </div>
          <div class="campo">
            <!-- Columna vacía para mantener la alineación -->
          </div>
        </div>

        <input type="submit" value="GuardarCambios" name="accion">
      </form>
    <?php else: ?>
      <p>No se ha encontrado la empresa a editar.</p>
    <?php endif; ?>

    <p><a href="index.php?menu=PanelAdmin">Volver al panel</a></p>
  </div>
</div>
<?php $this->stop() ?>
