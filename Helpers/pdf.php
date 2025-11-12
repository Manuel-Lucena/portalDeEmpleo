<?php
namespace Helpers;

require __DIR__ . '/../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->setChroot(dirname(__DIR__));

// instantiate and use the dompdf class
$dompdf = new Dompdf($options);

$rutaImagen = __DIR__ . '\..\fotos\alumno\foto_6.png';

$html = '
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Prueba PDF - PortalDeEmpleo</title>
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      margin: 20px;
      color: #222;
      line-height: 1.4;
      font-size: 12px;
    }
    header { text-align: center; margin-bottom: 18px; }
    header h1 { margin: 0; font-size: 22px; }
    header p { margin: 4px 0 0; font-size: 11px; color: #555; }
    .card {
      border: 1px solid #ddd;
      border-radius: 6px;
      padding: 12px;
      display: flex;
      gap: 12px;
      align-items: center;
    }
    .avatar img {
      width: 120px;
      height: 120px;
      display: block;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    .content h2 { margin: 0 0 6px 0; font-size: 16px; }
    .content p { margin: 0 0 6px 0; font-size: 12px; color: #333; }
    .meta { margin-top: 8px; font-size: 11px; color: #666; }
    footer {
      margin-top: 18px;
      text-align: center;
      font-size: 11px;
      color: #777;
      border-top: 1px dashed #e0e0e0;
      padding-top: 8px;
    }
  </style>
</head>
<body>
  <header>
    <h1>Portal de Empleo — Informe de Prueba</h1>
    <p>PDF generado con Dompdf — ejemplo con texto, imagen y CSS simple</p>
  </header>

  <section class="card">
    <div class="avatar">
      <img alt="'. $rutaImagen .'" src="'. $rutaImagen .'" />
    </div>

    <div class="content">
      <h2>Juan Pérez</h2>
      <p>Alumno de Formación Profesional con interés en desarrollo web y tecnologías backend. Este documento es un ejemplo de cómo incluir contenido HTML y CSS en un PDF generado con Dompdf.</p>

      <div class="meta">
        <strong>DNI:</strong> 12345678A &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Email:</strong> juan.perez@ejemplo.com
      </div>
    </div>
  </section>

  <footer>
    Generado el: ' . date('Y-m-d') . ' — PortalDeEmpleo
  </footer>
</body>
</html>';
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();