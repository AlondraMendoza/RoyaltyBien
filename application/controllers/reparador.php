<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clasificador extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("modelousuario");
        if (!EstaLogueado()) {
            redirect('usuario/iniciar_sesion');
        }
        $id = $this->session->userdata('id');
        if (!$this->modelousuario->TienePerfil($id, 1)) {
            redirect('usuario/logueado');
        }
    }
    
    public function ExpedienteProducto() {
        $infoheader["titulo"] = "Reparador: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $producto_id = $this->input->post_get('producto_id', TRUE);
        $this->load->model("modeloreparador");
        $infocontent["producto"] = $this->modeloreparador->ObtenerProducto($producto_id);
        $infocontent["defecto"] = $this->modeloreparador->ObtenerDefectos($producto_id);
        $this->load->view('reparador/ExpedienteProducto', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function BusquedaProductos() {
        $infoheader["titulo"] = "Reparador: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("modeloreparador");
        $this->load->view('reparador/BusquedaProductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

}

?>

