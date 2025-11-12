<?php
namespace Downloads;

// spl_autoload_register(function($clase) 
// {
//     $carpetas = ["Controllers", "Helpers", "Models", "Repositorys", "Views/Forms", "Public", "templates", "api"];


//     $base = realpath(__DIR__ . "/../"); 

//     foreach ($carpetas as $carpeta) {
//         $fichero = $base . '/' . $carpeta . '/' . $clase . '.php';
//         if (file_exists($fichero)) {
//             require_once $fichero;
//             break;
//         }
//     }
// });



spl_autoload_register(function ($clase) {

    $clase = str_replace('\\', '/', $clase);

    $fichero = __DIR__ . '/../' . $clase . '.php';

    if (file_exists($fichero)) {
        require_once $fichero;
    } 
});
