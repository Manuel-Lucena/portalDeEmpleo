<?php $this->layout('layout_simple', ['titulo' => 'Crear Oferta']); ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="../Public/css/Forms.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<div class="oferta-container">
    <div class="oferta-box">
        <h2>Crear Nueva Oferta</h2>

        <form method="post" action="index.php?menu=GestionOferta" class="form-oferta">
            <input type="hidden" name="idEmpresa" value="<?= $idEmpresa ?>">

            <div class="fila">
                <div class="campo">
                    <label for="titulo">Título de la Oferta:</label>
                    <input type="text" id="titulo" name="titulo" required maxlength="150">
                </div>
                <div class="campo">
                    <label for="ciclo">Ciclo requerido:</label>
                    <select name="idCiclo" id="ciclo" required>
                        <option value="">-- Selecciona un ciclo --</option>
                        <?php foreach ($ciclo as $c): ?>
                            <option value="<?= $c->getId() ?>"><?= $c->getNombre() ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

            <div class="fila">
                <div class="campo">
                    <label for="fechaFin">Fecha de Fin:</label>
                    <input type="date" id="fechaFin" name="fechaFin" required>
                </div>
                <div class="campo">
                    <label for="fechaInicio">Fecha de Inicio:</label>
                    <input type="date" id="fechaInicio" name="fechaInicio" required>
                </div>
            </div>



            <div class="campo span-dos">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="5" required></textarea>
            </div>
            <button type="submit" name="accion" value="GuardarOferta">Crear</button>
            <a href="index.php?menu=Oferta">Volver</a>
        </form>
    </div>
</div>
<?php $this->stop() ?>