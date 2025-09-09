<?php

namespace Controllers;

use Classes\Email;
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

            //Revisar que alertas este vacio
            if (empty($alertas)) {
                //Verificar que el usuario no este registrado
                $resultado =  $usuario->existeUsuario();
                if ($resultado && $resultado->num_rows > 0) {
                    $alertas = Usuario::getAlertas();
                } else {
                    //Hashear el password
                    $usuario->hashPassword();
                    //Generar un token unico
                    $usuario->crearToken();
                    //Enviar el email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);

                    $email->enviarConfirmacion();

                    //Crear el usuario
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header('Location: /mensaje');
                    }



                }

            }

        }

        $router->render('auth/crear-cuenta', [
           'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }
}
