<?php
namespace Services;

use Dompdf\Dompdf;
use Dompdf\Options;
use Repositorys\RepoAlumno;

class PDFServices
{
    public static function generarListadoAlumnos($nombreArchivo = 'Alumnos.pdf')
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->setChroot(__DIR__ . '/..');
        $dompdf = new Dompdf($options);

        $alumnos = RepoAlumno::findAll();

        // HTML con tabla
        $html = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Listado de Alumnos</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 30px;
                }

                h1 {
                    text-align: center;
                    color: #2e6c80;
                    margin-bottom: 25px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 12px;
                }

                table thead tr {
                    background-color: #f2f2f2;
                }

                table th, table td {
                    border: 1px solid #ccc;
                    padding: 8px;
                    text-align: left;
                }

                table th {
                    font-weight: bold;
                }

                table tr:nth-child(even) {
                    background-color: #fafafa;
                }
            </style>
        </head>
        <body>
            <h1>Listado de Alumnos</h1>

            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Fecha nacimiento</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($alumnos as $alumno) {
            $html .= "
                    <tr>
                        <td>{$alumno->getNombre()}</td>
                        <td>{$alumno->getEmail()}</td>
                        <td>{$alumno->getFechaNacimiento()}</td>
                        <td>{$alumno->getDireccion()}</td>
                        <td>{$alumno->getTelefono()}</td>
                    </tr>";
        }

        $html .= '
                </tbody>
            </table>
        </body>
        </html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($nombreArchivo, ['Attachment' => true]);
    }
}
