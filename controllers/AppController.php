<?php

namespace Controllers;

use MVC\Router;

class AppController {
    public static function index(Router $router){
        // Cambiar para que apunte al archivo correcto de pages
        $router->render('pages/index', [], $layout = 'layout/layout');
    }
}