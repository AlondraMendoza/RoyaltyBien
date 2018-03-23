<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Administrador extends CI_Controller {

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

    public function Reportes() {
        $this->load->model("modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $infocontent["Nombre"] = "Alondra Mendoza";
        $infocontent["hoy"] = date("d/m/Y");
        $infocontent["clasificaciones"] = $this->modeloadministrador->Clasificaciones();
        $infocontent["productos"] = $this->modeloadministrador->Productos();
        $infocontent["modelos"] = $this->modeloadministrador->Modelos(0);
        $infocontent["colores"] = $this->modeloadministrador->Colores(0);
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/Reportes', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ObtenerModelos() {
        $producto = $this->input->post_get('producto', TRUE);
        $this->load->model("modeloadministrador");
        $infocontent["modelos"] = $this->modeloadministrador->Modelos($producto);
        $this->load->view('administrador/ObtenerModelos', $infocontent);
    }

    public function ObtenerColores() {
        $modelo = $this->input->post_get('modelo', TRUE);
        $this->load->model("modeloadministrador");
        $infocontent["colores"] = $this->modeloadministrador->Colores($modelo);
        $this->load->view('administrador/ObtenerColores', $infocontent);
    }

    public function GenerarReporte() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $clasificacion = $this->input->post_get('clasificacion', TRUE);
        $aclasificacion = json_decode($clasificacion);

        $producto = $this->input->post_get('producto', TRUE);
        $aproducto = json_decode($producto);
        $modelo = $this->input->post_get('modelo', TRUE);
        $amodelo = json_decode($modelo);
        $color = $this->input->post_get('color', TRUE);
        $acolor = json_decode($color);
        $this->load->model("modeloadministrador");
        $infocontent["productos"] = $this->modeloadministrador->GenerarReporte($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor);
        $this->load->view('administrador/GenerarReporte', $infocontent);
    }

    public function GenerarConcentrado() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $clasificacion = $this->input->post_get('clasificacion', TRUE);
        $aclasificacion = json_decode($clasificacion);
        $producto = $this->input->post_get('producto', TRUE);
        $aproducto = json_decode($producto);
        $modelo = $this->input->post_get('modelo', TRUE);
        $amodelo = json_decode($modelo);
        $color = $this->input->post_get('color', TRUE);
        $acolor = json_decode($color);
        $por = $this->input->post_get('por', TRUE);
        $this->load->model("modeloadministrador");
        $infocontent["por"] = $por;
        $infocontent["productos"] = $this->modeloadministrador->GenerarConcentrado($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $por);
        $this->load->view('administrador/GenerarConcentrado', $infocontent);
    }

}
