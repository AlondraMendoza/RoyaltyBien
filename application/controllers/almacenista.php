<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Almacenista extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function capturaGriferia() {
        $infoheader["titulo"] = "Almacén: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("modeloalmacenista");
        $infocontent["griferia"] = $this->modeloalmacenista->ListarGriferia();
        $this->load->view('almacenista/capturaGriferia', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function VerificarClave() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("modeloalmacenista");
        $fila = $this->modeloalmacenista->BuscarClave($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->Descripcion;
            $infocontent["id"] = $fila->IdCGriferia;
        }
        print json_encode($infocontent);
    }
    
    public function ResultadosGriferia() {
        $id = $this->input->post_get('id', TRUE);
        $cantidad = $this->input->post_get('cantidad', TRUE);
        $this->load->model("modeloalmacenista");
        $infocontent["lista"] = $this->modeloalmacenista->ListarGriferiaGuardada($id,$cantidad);
        $this->load->view('almacenista/ResultadosGriferia', $infocontent);
    }
}
