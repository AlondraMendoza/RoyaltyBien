<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sclasificador extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("modelousuario");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        }
        $id = $this->session->userdata('id');
        if (!$this->modelousuario->TienePerfil($id, 5)) {
            redirect('usuario/logueado');
        }
        $this->load->database();
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

    public function Devoluciones() {
        $infoheader["titulo"] = "Devoluciones: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $infocontent["hoy"] = date("d/m/Y");
        $this->load->view('sclasificador/Devoluciones', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function DevolucionesCapturadas() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $this->load->model("modelocedis");
        $infocontent["devolucionescapturadas"] = $this->modelocedis->DevolucionesCapturadas($fechainicio, $fechafin);
        $this->load->view('sclasificador/DevolucionesCapturadas', $infocontent);
    }

    public function VerificarSubproducto() {
        $id = $this->input->post_get('sub_id', TRUE);
        $valor = $this->input->post_get('valor', TRUE);
        $this->db->set("Verificado", $valor);
        $this->db->where("IdSubproductosDevoluciones", $id);
        $this->db->update("SubproductosDevoluciones");
        print("correcto");
    }

    public function ProcesarDevolucion() {
        $id = $this->input->post_get('dev_id', TRUE);
        $this->db->set("VerificadaSupervisor", "Si");
        $this->db->where("IdDevoluciones", $id);
        $this->db->update("Devoluciones");
        print("correcto");
    }

    public function EntradaSubproductos() {
        $infoheader["titulo"] = "Almacén de SubProductos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("modeloalmacenista");
        $infocontent["griferia"] = $this->modeloalmacenista->ListarGriferia();
        $this->load->view('sclasificador/EntradaSubproductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function SalidaSubproductos() {
        $infoheader["titulo"] = "Almacén de SubProductos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("modeloalmacenista");
        $infocontent["griferia"] = $this->modeloalmacenista->ListarGriferia();
        $this->load->view('sclasificador/SalidaSubproductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function AlmacenSubproductos() {
        $infoheader["titulo"] = "Almacén de SubProductos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("modeloalmacenista");
        $infocontent["subproductosdetalle"] = $this->modeloalmacenista->ListarSubproductosDetalle();
        $infocontent["subproductosunicos"] = $this->modeloalmacenista->ListarSubproductosUnicos();
        $this->load->view('sclasificador/AlmacenSubproductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function VerificarClave() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("modeloalmacenista");
        $fila = $this->modeloalmacenista->BuscarClaveSubproductos($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->Descripcion;
            $infocontent["id"] = $fila->IdCGriferia;
        }
        print json_encode($infocontent);
    }

    public function GuardarSubproductosAlmacen() {
        $id = $this->input->post_get('idsubproducto', TRUE);
        $cantidad = $this->input->post_get('cantidad', TRUE);
        $this->load->model("modeloalmacenista");
        $id = $this->modeloalmacenista->GuardarSubproducto($id, $cantidad);
        if ($id != null) {
            print("correcto");
        } else {
            return "error";
        }
    }

    public function GuardarSalidaSubproductos() {
        $id = $this->input->post_get('id', TRUE);
        $cantidad = $this->input->post_get('cantidad', TRUE);
        $this->load->model("modeloalmacenista");
        $resp = $this->modeloalmacenista->SalidaSub($id, $cantidad);
        if ($resp == "correcto") {
            print("Correcto");
        } else {
            print("Error");
        }
    }

    public function VerificarClaveExistencia() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("modeloalmacenista");
        $fila = $this->modeloalmacenista->BuscarClaveSubproductos($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->Descripcion;
            $infocontent["id"] = $fila->IdCGriferia;
            $data = $this->modeloalmacenista->ExistenciasSubproductos($fila->IdCGriferia);
            $infocontent["existencia"] = $data;
        }
        print json_encode($infocontent);
    }

}
