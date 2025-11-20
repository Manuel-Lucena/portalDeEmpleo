<?php $this->layout('layout', ['titulo' => 'Inicio']); ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="../Public/css/Paginas.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>

<div class="estadisticas">

<h2>Estadísticas</h2>

    
    <div class="fila-graficos">
        <div class="grafico-container">
            <canvas id="graficoEstado"></canvas>
        </div>
        <div class="grafico-container">
            <canvas id="graficoTotales"></canvas>
        </div>
    </div>
   
    <div class="fila-graficos">
        <div id="graficoOferta-container">
            <canvas id="graficoOferta"></canvas>
        </div>
    </div>






    <?php $this->stop() ?>


    <?php $this->start('js') ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../Public/js/logicaEstadisticas.js"></script>
    <?php $this->stop() ?>