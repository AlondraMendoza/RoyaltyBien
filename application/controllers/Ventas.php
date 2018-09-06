<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ventas extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Modelousuario");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        }
        $id = $this->session->userdata('id');
        if (!$this->Modelousuario->TienePerfil($id, 8)) {
            redirect('usuario/logueado');
        }
    }

    public function InventarioCedis() {
        $infoheader["titulo"] = "Inventario Cedis: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modelocedis");
        $infocontent["modelos"] = $this->Modelocedis->ListaModelos();
        $this->load->view('ventas/InventarioCedis', $infocontent);

        $this->load->view('template/footerd', '');
    }

    public function CargaInfoModelo() {
        $modelo = $this->input->post_get('modelo_id', TRUE);
        $this->load->model("Modeloventas");
        $infocontent["modelo"] = $this->Modeloventas->ObtenerModelo($modelo);
        $this->load->view('administrador/CargaInfoModelo', $infocontent);
    }

}
?>

