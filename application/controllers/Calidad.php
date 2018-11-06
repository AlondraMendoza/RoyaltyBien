<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Calidad extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Modelousuario");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        }
        $id = $this->session->userdata('id');
        if (!$this->Modelousuario->TienePerfil($id, 10)) {
            redirect('usuario/logueado');
        }
    }

    public function EntradaProductos() {
        $infoheader["titulo"] = "Almacén Mermas: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modelocalidad");
        $this->load->view('calidad/EntradaProductos');
        $this->load->view('template/footerd', '');
    }

    public function VerificarClave() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modelocalidad");
        $fila = $this->Modelocalidad->BuscarClave($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }

    public function GuardarProd() {
        $idProducto = $this->input->post_get('idProducto', TRUE);
        $this->load->model("Modelocalidad");
        $resp = $this->Modelocalidad->GuardarProductos($idProducto);
        if ($resp == "Existe") {
            print("Existe");
        } else if ($resp == "correcto") {
            print("Correcto");
        } else {
            print("Error");
        }
    }

    public function GuardarProcesarMerma() {
        $idProducto = $this->input->post_get('idProducto', TRUE);
        $this->load->model("Modelocalidad");
        $resp1 = $this->Modelocalidad->ProductoEnAlmacenPNoDestruido($idProducto);
        if ($resp1 == true) {
            $resp = $this->Modelocalidad->GuardarProcesar($idProducto);
            if ($resp == "correcto") {
                print("Correcto");
            } else {
                print("Error");
            }
        } else {
            print("Error");
        }
    }

    public function Destruir() {
        $infoheader["titulo"] = "Almacén Mermas: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modelocalidad");
        $infocontent["Nombre"] = "";
        $this->load->view('calidad/Destruir', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ReporteQuemado() {
        $this->load->model("Modelocalidad");
        $infoheader["titulo"] = "Almacén de Mermas: Royalty Ceramic";
        $infocontent["Nombre"] = "";
        $infocontent["hoy"] = date("d/m/Y");
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('calidad/ReporteQuemado', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function GenerarReporteQ() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $this->load->model("Modelocalidad");
        $infocontent["productos"] = $this->Modelocalidad->GenerarReporteQ($fechainicio, $fechafin);
        $this->load->view('calidad/GenerarReporteQ', $infocontent);
    }

    public function GenerarConcentradoQ() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $por = $this->input->post_get('por', TRUE);
        $this->load->model("Modelocalidad");
        $infocontent["por"] = $por;
        $infocontent["productos"] = $this->Modelocalidad->GenerarConcentradoQ($fechainicio, $fechafin, $por);
        $this->load->view('calidad/GenerarConcentradoQ', $infocontent);
    }

}
