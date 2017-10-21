<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Capturista extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $datos["nombre"] = "Cadena ejemplo";
        $datos["apellido"] = "Cadena ejemplo 2";
        $this->load->view('capturista/index', $datos);
    }

    public function capturaCarro() {
//        $p = \Models\CProductos::ListarProductos();
//        $h = \Models\Hornos::ListarHornos();
//        $c = \Models\Carros::ListarCarros();
//        $array = [
//            "listaproductos" => $p,
//            "hornos" => $h,
//            "carros" => $c,];
//        return $array;
    }

    public function ObtenerModelos() {
//        $id = $_REQUEST["id"];
//        $m = \Models\Modelos::ListarModelos($id);
//        $array = [
//            "modelo" => $m,];
//        return $array;
    }

    public function ObtenerColores() {
//        $id = $_REQUEST["id"];
//        $c = \Models\Colores::ListarColores($id);
//        $array = [
//            "color" => $c,];
//        return $array;
    }

}
