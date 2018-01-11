<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cedis extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $datos["nombre"] = "Cadena ejemplo";
        $datos["apellido"] = "Cadena ejemplo 2";
        $this->load->view('capturista/index', $datos);
    }

    public function EntradaTarimas() {
        $infoheader["titulo"] = "Entrada Tarimas: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->view('cedis/EntradaProductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function EntradaProductos() {
        $infoheader["titulo"] = "Entrada Productos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->view('cedis/EntradaProductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function VerificarClaveProd() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("modelocedis");
        $fila = $this->modelocedis->BuscarClaveProducto($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }

    public function VerificarClaveTarima() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("modelocedis");
        $fila = $this->modelocedis->BuscarClaveTarima($clave);
        if ($fila != "No se encontró la tarima") {
            $infocontent["id"] = $fila->IdTarimas;
        }
        print json_encode($infocontent);
    }

    public function GuardarTarimasCedis() {
        $idtarima = $this->input->post_get('idtarima', TRUE);
        $this->load->model("modelocedis");
        $resp = $this->modelocedis->GuardarProductosTarima($idtarima);
        if ($resp == "Existe") {
            print("Existe");
        } else if ($resp == "correcto") {
            print("Correcto");
        } else {
            print("Error");
        }
    }

    public function GuardarProductoCedis() {
        $idproducto = $this->input->post_get('idproducto', TRUE);
        $this->load->model("modelocedis");
        if ($this->modelocedis->ProductoEnCedis($idproducto)) {
            print("Existe");
        } else {
            $this->modelocedis->GuardarProductoCedis($idproducto);
            print("Correcto");
        }
    }

}
