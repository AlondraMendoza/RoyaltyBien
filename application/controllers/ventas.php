<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ventas extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("modelousuario");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        }
        $id = $this->session->userdata('id');
        if (!$this->modelousuario->TienePerfil($id, 8)) {
            redirect('usuario/logueado');
        }
    }
    
    public function InventarioCedis() {
        $infoheader["titulo"] = "Inventario Cedis: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("modelocedis");
        $infocontent["modelos"] = $this->modelocedis->ListaModelos();
        $this->load->view('ventas/InventarioCedis', $infocontent);
        
        $this->load->view('template/footerd', '');
    }
    
    public function CargaInfoModelo() {
        $modelo = $this->input->post_get('modelo_id', TRUE);
        $this->load->model("modeloventas");
        $infocontent["modelo"] = $this->modeloventas->ObtenerModelo($modelo);
        $this->load->view('administrador/CargaInfoModelo', $infocontent);
    }


}
?>

