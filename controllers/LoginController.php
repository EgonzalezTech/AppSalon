<?php

namespace Controllers;

use Models\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $router->render('auth/login');
    }
    public static function logout()
    {
        echo "Desde Logout";
    }
    public static function olvide(Router $router)
    {
        $router->render('auth/olvide-password', [
            'titulo' => 'Recupera tu acceso '
        ]);
    }
    public static function recuperar()
    {
        echo "Desde Recuperar";
    }
    public static function crear(Router $router)
    {
        $usuario = new Usuario();
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Crear una nueva instancia
            //Validar que no haya campos vacios
            //Validar que el usuario no este registrado
            //Hashear su password
            //Generar un token unico
            //Enviar el email
            //Crear el usuario
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();




        }

        $router->render('auth/crear-cuenta', [
           'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
}
