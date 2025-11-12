<?php $this->layout('layout', ['titulo' => 'Inicio']); ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="../Public/css/Paginas.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>

<section class="bienvenida">
  <div class="contenido-bienvenida">
    <h1>Bienvenido</h1>
    <p>
      Encuentra las mejores oportunidades laborales y talleres para mejorar tus habilidades. 
      Nuestro portal te conecta con empresas y profesionales de tu área.
    </p>
    <a href="#" class="btn btn-bienvenida">Explora las oportunidades</a>
  </div>
</section>

<section class="destacados">
  <div class="div-destacados">
    <img src="../Public/images/Software.png">
    <p>Talleres de Desarrollo Profesional</p>
  </div>
  <div class="div-destacados">
    <img src="../Public/images/Orson.png">
    <p>Empresas que buscan talento</p>
  </div>
  <div class="div-destacados">
    <img src="../Public/images/Nter.png">
    <p>Asesoría y orientación laboral</p>
  </div>
</section>

<section class="contacto">
  <div class="contenido-contacto">
    <h2>Conéctate con nosotros</h2>
    <p>Regístrate para recibir notificaciones sobre nuevas ofertas, talleres y noticias importantes.</p>
    <a href="#" class="btn btn-contacto">Contactar</a>
  </div>
</section>

<?php $this->stop() ?>
