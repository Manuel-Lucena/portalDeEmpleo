<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->e($titulo) ?></title>
    <link rel="icon" type="image/x-icon" href="/public/images/Logo.png">
    <?= $this->section('css') ?>
</head>

<body>
    <?= $this->section('contenido') ?>

    <?= $this->section('js') ?>

</body>

</html>