<?php $this->layout('layout', ['titulo' => $titulo]) ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="../Public/css/Paginas.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<section class="oferta-section">
    <h1><?= $titulo ?></h1>
    <p><?= $mensaje ?></p>

    <?php
    use Helpers\Login;
    $rol = Login::getRol();
    $idUser = Login::getId();
    ?>

    <?php if ($rol == 2 || $rol == 1): ?>
        <div class="contenedor-crear-oferta">
            <a href="index.php?menu=GestionOferta&idEmpresa=<?= $idUser ?>" class="btn-crear-oferta">Crear Oferta</a>
        </div>
    <?php endif; ?>

    <div class="oferta-container">
        <?php foreach ($ofertas as $oferta): ?>
            <?php if ($rol != 2 || $oferta->getIdEmpresa() == $idUser): ?>
                <div class="oferta-card">
                    <h2><?= $oferta->getTitulo() ?></h2>
                    <p class="descripcion"><b>Descripción:</b> <?= $oferta->getDescripcion() ?></p>
                    <p class="ubicacion"><b>Fecha inicio:</b> <?= $oferta->getFechaInicio() ?></p>
                    <p class="ubicacion"><b>Fecha fin:</b> <?= $oferta->getFechaFin() ?></p>

                    <div class="botones-oferta">
                        <?php if ($rol == 1 || $rol == 3): ?>
                            <input type="hidden" name="id" value="<?= $oferta->getId() ?>">
                            <button class="btn-solicitar">Solicitar</button>
                        <?php endif; ?>

                        <?php if ($rol == 2 || $rol == 1): ?>
                            <form action="index.php?menu=GestionOferta" method="POST">
                                <input type="hidden" name="id" value="<?= $oferta->getId() ?>">
                                <button type="submit" name="accion" value="Eliminar" class="btn-eliminar">Eliminar</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>
<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="../Public/js/logicaSolicitud.js"></script>
<?php $this->stop() ?>
