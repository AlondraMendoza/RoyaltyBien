<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inicio extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("modelousuario");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        }
        $id = $this->session->userdata('id');
        if (!$this->modelousuario->TienePerfil($id, 3)) {
            redirect('usuario/logueado');
        }
    }

    public function index() {
        $datos["nombre"] = "Cadena ejemplo";
        $datos["apellido"] = "Cadena ejemplo 2";
        $this->load->view('capturista/index', $datos);
    }

    //Checar metodo
    /* public static function Verificar() {
      $usuario = ($_POST["usuario"]);
      $contra = ($_POST["contra"]);
      $fila = \Models\Usuarios::NoUsuariosLogin($usuario, $contra);
      $_SESSION["usuario"] = $fila["Nombre"];
      if ($fila["cuantos"] > 0) {
      header("Location: " . URL . "usuario");
      } else {
      return "error";
      }
      } */
}
