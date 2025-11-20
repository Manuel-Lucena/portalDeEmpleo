<?php
namespace Helpers;

class Validator
{
    private $errores = [];

    public function limpiar()
    {
        $this->errores = [];
    }

    public function obligatorio($campo, $valor, $mensaje = null)
    {
        $valor = $valor ?? '';
        if (trim($valor) === '') {
            $this->errores[$campo] = $mensaje ?? "El campo $campo es obligatorio.";
        }
    }

    public function email($campo, $valor, $mensaje = null)
    {
        $valor = $valor ?? '';
        if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            $this->errores[$campo] = $mensaje ?? "El campo $campo debe ser un email válido.";
        }
    }

    public function longitudMinima($campo, $valor, $longitud, $mensaje = null)
    {
        $valor = $valor ?? '';
        if (strlen($valor) < $longitud) {
            $this->errores[$campo] = $mensaje ?? "El campo $campo debe tener al menos $longitud caracteres.";
        }
    }

    public function longitudMaxima($campo, $valor, $longitud, $mensaje = null)
    {
        $valor = $valor ?? '';
        if (strlen($valor) > $longitud) {
            $this->errores[$campo] = $mensaje ?? "El campo $campo debe tener como máximo $longitud caracteres.";
        }
    }

    public function telefono($campo, $valor, $mensaje = null)
    {
        $valor = $valor ?? '';
        if (!preg_match('/^\d{9}$/', $valor)) {
            $this->errores[$campo] = $mensaje ?? "Teléfono inválido (9 dígitos).";
        }
    }

    public function fecha($campo, $valor, $mensaje = null)
    {
        $valor = $valor ?? '';
        if ($valor === '') {
            $this->errores[$campo] = $mensaje ?? "Fecha inválida para $campo.";
            return;
        }

        $d = \DateTime::createFromFormat('Y-m-d', $valor);
        if (!$d || $d->format('Y-m-d') !== $valor) {
            $this->errores[$campo] = $mensaje ?? "Fecha inválida para $campo.";
        }
    }

    public function getErrores()
    {
        return $this->errores;
    }

    public function esValido()
    {
        return empty($this->errores);
    }
}
