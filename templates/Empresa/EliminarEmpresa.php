<?php $this->layout('layout_simple', ['titulo' => 'Confirmar Eliminación']) ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="../Public/css/Forms.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<main>
    <div class="contenedor-eliminar">
        <div class="caja-eliminar">
            <h2 class="titulo-eliminar">¿Seguro que deseas eliminar esta empresa?</h2>

            <form method="post" action="../public/index.php?menu=EliminarEmpresa" class="form-eliminar">
                <input type="hidden" name="idEmpresa" value="<?= $idEmpresa ?>">

                <div class="botones-eliminar">
                    <button type="submit" name="accion" value="confirmarEliminar" class="btn btn-eliminar">
                        Sí, eliminar
                    </button>

                    <a href="../public/index.php?menu=PanelAdmin" class="btn btn-cancelar">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>


<?php $this->stop() ?>