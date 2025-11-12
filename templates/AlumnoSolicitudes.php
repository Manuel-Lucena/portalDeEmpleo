<?php $this->layout('layout', ['titulo' => 'Mis Solicitudes']) ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="../Public/css/Paginas.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<section class="solicitudes">
    <h1>Mis Solicitudes</h1>
    <p>Aquí puedes consultar el estado de todas tus solicitudes enviadas.</p>

    <div class="lista-solicitudes">
        <?php foreach($solicitudes as $solicitud): ?>
            <div class="solicitud">
                <h2><?= $solicitud->getId() ?></h2>
                <p>Oferta: <?= $solicitud->getIdOferta() ?></p>
                <p>Alumno: <?= $solicitud->getIdAlumno() ?></p>
                <p>Fecha solicitud: <?= $solicitud->getFechaSolicitud() ?></p>
                <p>Estado: <?= $solicitud->getEstado() ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php $this->stop() ?>
