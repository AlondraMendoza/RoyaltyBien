<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sclasificador extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("modelousuario");
        if (!EstaLogueado()) {
            redirect('usuario/iniciar_sesion');
        }
        $id = $this->session->userdata('id');
        if (!$this->modelousuario->TienePerfil($id, 3)) {
            redirect('usuario/logueado');
        }
    }

    public function CapturaDevolucion() {
        $this->load->model("modeloclasificador");
        $infoheader["titulo"] = "Clasificador: Royalty Ceramic";

        $this->load->view('template/headerd', $infoheader);
        $this->load->view('sclasificador/CapturaDevolucion', '');
        $this->load->view('template/footerd', '');
    }

    public function VerificarClaveProd() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("modeloclasificador");
        $fila = $this->modeloclasificador->BuscarClaveProducto($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }

    public function GuardarDevolucion() {
        $cliente = $this->input->post_get('cliente', TRUE);
        $motivo = $this->input->post_get('motivo', TRUE);
        $responsable = $this->input->post_get('responsable', TRUE);
        $this->load->model("modelosclasificador");
        $iddevolucion = $this->modelosclasificador->GuardarDevolucion($cliente, $motivo, $responsable);
        print($iddevolucion);
    }

    public function GuardarDetalleDevolucion() {
        $idproducto = $this->input->post_get('idproducto', TRUE);
        $iddevolucion = $this->input->post_get('iddevolucion', TRUE);
        $this->load->model("modelosclasificador");
        $iddetalle = $this->modelosclasificador->GuardarDetalleDevolucion($idproducto, $iddevolucion);
        if ($iddetalle == "existe") {
            print("Existe");
        } else if ($iddetalle != null) {
            print("Correcto");
        }
    }

}
