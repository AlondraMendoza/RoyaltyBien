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
        $infoheader["titulo"] = "Capturista: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("modelocapturista");
        $infocontent["hornos"] = $this->modelocapturista->ListarHornos();
        $infocontent["carros"] = $this->modelocapturista->ListarCarros();
        $infocontent["productos"] = $this->modelocapturista->ListarProductos();
        $this->load->view('capturista/capturaCarro', $infocontent);
        $this->load->view('template/footerd', '');
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
        $id = $this->input->post_get('id', TRUE);
        $this->load->model("modelocapturista");
        $infocontent["modelos"] = $this->modelocapturista->ListarModelos($id);
        $this->load->view('capturista/ObtenerModelos', $infocontent);
//        $id = $_REQUEST["id"];
//        $m = \Models\Modelos::ListarModelos($id);
//        $array = [
//            "modelo" => $m,];
//        return $array;
    }

    public function ObtenerColores() {
        $id = $this->input->post_get('id', TRUE);
        $this->load->model("modelocapturista");
        $infocontent["colores"] = $this->modelocapturista->ListarColores($id);
        $this->load->view('capturista/ObtenerColores', $infocontent);
//        $id = $_REQUEST["id"];
//        $c = \Models\Colores::ListarColores($id);
//        $array = [
//            "color" => $c,];
//        return $array;
    }
    
    public function Resultados() {
        $carro = $this->input->post_get('carro', TRUE);
        $horno = $this->input->post_get('horno', TRUE);
        $prod = $this->input->post_get('prod', TRUE);
        $mod = $this->input->post_get('mod', TRUE);
        $col = $this->input->post_get('col', TRUE);
        $piezas = $this->input->post_get('piezas', TRUE);
        $fecha = $this->input->post_get('fecha', TRUE);
        $infoheader["titulo"] = "Capturista: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("modelocapturista");
        $infocontent["Lista"] = $this->modelocapturista->ListarProductosGuardados($carro,$horno,$prod,$mod,$col,$piezas,$fecha);
        $this->load->view('capturista/Resultados', $infocontent);
        $this->load->view('template/footerd', '');
    }

}
