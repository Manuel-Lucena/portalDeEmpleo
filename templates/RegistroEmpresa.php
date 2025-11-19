<?php $this->layout('layout_simple', ['titulo' => 'Registro de Empresa']); ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="../Public/css/Sesion.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<div class="registro-container">
  <div class="registro-box">
    <h2>Registro de Empresa</h2>

    <form method="post" action="../Public/index.php?menu=RegistroEmpresa" enctype="multipart/form-data" class="form-dos-columnas">


      <div class="fila">
        <div class="campo">
          <label for="nombreEmpresa">Nombre de la empresa:</label>
          <input type="text" id="nombreEmpresa" name="nombreEmpresa" required>
        </div>

        <div class="campo">
          <label for="nuevoNombre">Usuario:</label>
          <input type="text" id="nuevoNombre" name="nombreUsuario" required>
        </div>
      </div>

 
      <div class="fila">
        <div class="campo">
          <label for="nuevoContraseña">Contraseña:</label>
          <input type="password" id="nuevoContraseña" name="contrasena" required>
        </div>

        <div class="campo">
          <label for="repetirContraseña">Repetir contraseña:</label>
          <input type="password" id="repetirContraseña" name="repetirContraseña" required>
        </div>
      </div>


      <div class="fila">
        <div class="campo">
          <label for="nuevoEmail">Email:</label>
          <input type="text" id="nuevoEmail" name="email" required>
        </div>

        <div class="campo">
          <label for="nuevoTelefono">Teléfono de contacto:</label>
          <input type="text" id="nuevoTelefono" name="telefono">
        </div>
      </div>


      <div class="fila">
        <div class="campo">
          <label for="personaContacto">Persona de contacto:</label>
          <input type="text" id="personaContacto" name="personaContacto">
        </div>

        <div class="campo">
          <label for="nuevoDireccion">Dirección:</label>
          <input type="text" id="nuevoDireccion" name="direccion">
        </div>
      </div>


      <div class="fila">
       <div class="campo span-dos">
          <label for="logoEmpresa">Logo de empresa:</label>
          <input type="file" id="logoEmpresa" name="logoEmpresa">
        </div>
        <div class="campo">
          
        </div>
      </div>

      <input type="submit" name="accion" value="Registrar">
    </form>

    <?php if (!empty($logueado)): ?>
        <p><a href="../Public/index.php?menu=PanelAdmin">Volver al Panel de Administración</a></p>
    <?php else: ?>
        <p>¿Ya tienes cuenta? <a href="../Public/index.php?menu=Login">Inicia sesión aquí</a></p>
    <?php endif; ?>
  </div>
</div>
<?php $this->stop() ?>
