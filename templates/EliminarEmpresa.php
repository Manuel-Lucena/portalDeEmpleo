<?php $this->layout('layout_simple', ['titulo' => 'Confirmar Eliminación']) ?>

<?php $this->start('contenido') ?>
<main>
    <h2>¿Seguro que deseas eliminar esta empresa?</h2>

    <form method="post" action="../public/index.php?menu=EliminarEmpresa">
        <input type="hidden" name="idEmpresa" value="<?= $idEmpresa ?>">
        <button type="submit" name="accion" value="confirmarEliminar">Sí, eliminar</button>
        <button><a href="../public/index.php?menu=PanelAdmin">Cancelar</a></button>
    </form>
</main>
<?php $this->stop() ?>
