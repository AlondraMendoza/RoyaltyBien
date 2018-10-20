<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AlmacenistaSubproductos extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Modelousuario");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        }
        $id = $this->session->userdata('id');
        if (!$this->Modelousuario->TienePerfil($id, 5)) {
            redirect('usuario/logueado');
        }
        $this->load->database();
    }

    public function VerificarClaveProd() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modeloclasificador");
        $fila = $this->Modeloclasificador->BuscarClaveProducto($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }

    public function VerificarSubproducto() {
        $id = $this->input->post_get('sub_id', TRUE);
        $valor = $this->input->post_get('valor', TRUE);
        $this->db->set("Verificado", $valor);
        $this->db->where("IdSubproductosDevoluciones", $id);
        $this->db->update("SubproductosDevoluciones");
        print("correcto");
    }

    public function EntradaSubproductos() {
        $infoheader["titulo"] = "Almacén de SubProductos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modeloalmacenista");
        $infocontent["griferia"] = $this->Modeloalmacenista->ListarGriferia();
        $this->load->view('almacenistasubproductos/EntradaSubproductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function SalidaSubproductos() {
        $infoheader["titulo"] = "Almacén de SubProductos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modeloalmacenista");
        $infocontent["griferia"] = $this->Modeloalmacenista->ListarGriferia();
        $this->load->view('almacenistasubproductos/SalidaSubproductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function AlmacenSubproductos() {
        $infoheader["titulo"] = "Almacén de SubProductos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modeloalmacenista");
        $infocontent["subproductosdetalle"] = $this->Modeloalmacenista->ListarSubproductosDetalle();
        $infocontent["subproductosunicos"] = $this->Modeloalmacenista->ListarSubproductosUnicos();
        $this->load->view('almacenistasubproductos/AlmacenSubproductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function VerificarClave() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modeloalmacenista");
        $fila = $this->Modeloalmacenista->BuscarClaveSubproductos($clave);
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
        $this->load->model("Modeloalmacenista");
        $id = $this->Modeloalmacenista->GuardarSubproducto($id, $cantidad);
        if ($id != null) {
            print("correcto");
        } else {
            return "error";
        }
    }

    public function GuardarSalidaSubproductos() {
        $id = $this->input->post_get('id', TRUE);
        $cantidad = $this->input->post_get('cantidad', TRUE);
        $this->load->model("Modeloalmacenista");
        $resp = $this->Modeloalmacenista->SalidaSub($id, $cantidad);
        if ($resp == "correcto") {
            print("Correcto");
        } else {
            print("Error");
        }
    }

    public function VerificarClaveExistencia() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modeloalmacenista");
        $fila = $this->Modeloalmacenista->BuscarClaveSubproductos($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->Descripcion;
            $infocontent["id"] = $fila->IdCGriferia;
            $data = $this->Modeloalmacenista->ExistenciasSubproductos($fila->IdCGriferia);
            $infocontent["existencia"] = $data;
        }
        print json_encode($infocontent);
    }

    public function FechaIngles($date) {
        if ($date) {
            $fecha = $date;
            $hora = "";
            # separamos la fecha recibida por el espacio de separación entre
            # la fecha y la hora
            $fechaHora = explode(" ", $date);
            if (count($fechaHora) == 2) {
                $fecha = $fechaHora[0];
                $hora = $fechaHora[1];
            }
            # cogemos los valores de la fecha
            $values = preg_split('/(\/|-)/', $fecha);
            if (count($values) == 3) {
                # devolvemos la fecha en formato ingles
                if ($hora && count(explode(":", $hora)) == 3) {
                    # si la hora esta separada por : y hay tres valores...
                    $hora = explode(":", $hora);
                    return date("Ymd H:i:s", mktime($hora[0], $hora[1], $hora[2], $values[1], $values[0], $values[2]));
                } else {
                    return date("Ymd", mktime(0, 0, 0, $values[1], $values[0], $values[2]));
                }
            }
        }
        return "";
    }

}
