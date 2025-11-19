<?php $this->layout('layout', ['titulo' => 'Mis Solicitudes']) ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="../Public/css/Paginas.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<section class="solicitudes">
    <h1>Mis Solicitudes</h1>
    <p>Aquí puedes consultar el estado de todas tus solicitudes enviadas.</p>

    <div class="lista-solicitudes">
   
    </div>

    <div id="modalCv"></div>
</section>
<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="../Public/js/Modal.js"></script>
<script src="../Public/js/logicaSolicitud.js"></script>

<?php $this->stop() ?>
