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

    public function EntradaProductos() {
        $infoheader["titulo"] = "Almacén: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("modeloalmacenista");
        $this->load->view('almacenista/EntradaProductos', $infocontent);
        $this->load->view('template/footerd', '');
    }
    
     public function SalidaProductos() {
        $infoheader["titulo"] = "Almacén: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("modeloalmacenista");
        $this->load->view('almacenista/SalidaProductos', $infocontent);
        $this->load->view('template/footerd', '');
    }
    
    public function VerificarClaveProd() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("modeloalmacenista");
        $fila = $this->modeloalmacenista->BuscarClaveProd($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->producto."/".$fila->modelo."/".$fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }
    
    public function VerificarClaveTarima() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("modeloalmacenista");
        $fila = $this->modeloalmacenista->BuscarClaveTarima($clave);
        if ($fila != "No se encontró la tarima") {
            $infocontent["id"] = $fila->IdTarimas;
        }
        print json_encode($infocontent);
    }
    
    //por producto
     public function VerificarClaveTarimaP() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("modeloalmacenista");
        $fila = $this->modeloalmacenista->BuscarClaveTarimaP($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }
    
    public function GuardarTarimasAlmacen() {
        $idtarima = $this->input->post_get('idtarima', TRUE);
        $this->load->model("modeloalmacenista");
        $resp = $this->modeloalmacenista->GuardarProductosTarima($idtarima);
        if ($resp == "Existe") {
            print("Existe");
        } else if ($resp == "correcto") {
            print("Correcto");
        } else {
            print("Error");
        }
    }
    
    //Por producto
    public function GuardarTarimasAlmacenP() {
        $idProducto = $this->input->post_get('idProducto', TRUE);
        $this->load->model("modeloalmacenista");
        $resp = $this->modeloalmacenista->GuardarProductosTarimaP($idProducto);
        if ($resp == "Existe") {
            print("Existe");
        } else if ($resp == "correcto") {
            print("Correcto");
        } else {
            print("Error");
        }
    }
    
    public function SalirTarimasAlmacen() {
        $idtarima = $this->input->post_get('idtarima', TRUE);
        $this->load->model("modeloalmacenista");
        $resp = $this->modeloalmacenista->SalirTarima($idtarima);
        if($resp != null){
            $query = $this->modeloalmacenista->SalirProductoAlmacen($resp);
        if ($query == "correcto") {
            print("Correcto");
        }
        else {
            print("Error");
        }
        }else {
            print ("NoExiste");
        }
    }
    //Por producto
    public function SalirTarimasAlmacenP() {
        $idproducto = $this->input->post_get('idtarima', TRUE);
        $this->load->model("modeloalmacenista");
        $resp = $this->modeloalmacenista->SalirTarimaP($idproducto);
        if($resp != null){
            $query = $this->modeloalmacenista->SalirProductoAlmacenP($resp);
        if ($query == "correcto") {
            print("Correcto");
        }
        else {
            print("Error");
        }
        }else {
            print ("NoExiste");
        }
    }
    
    public function GuardarAlmacen(){
        $id = $this->input->post_get('id', TRUE);
        $this->load->model("modeloalmacenista");
        //Verifica q no exista
        $verificador = $this->modeloalmacenista->VerificarProd($id);
        if($verificador == "bien"){
            //Guarda el producto
        $fila = $this->modeloalmacenista->GuardarEntradaAlmacen($id);
        print("bien");
        }
    }
    
   public function VerificarClaveTarimaAlmacen() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("modeloalmacenista");
        $fila = $this->modeloalmacenista->BuscarClaveTarima2($clave);
        if ($fila != "No se encontró la tarima") {
            $infocontent["id"] = $fila->IdTarimas;
        }
        print json_encode($infocontent);
    }
    //Por producto
    public function VerificarClaveTarimaAlmacenP() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("modeloalmacenista");
        $fila = $this->modeloalmacenista->BuscarClaveTarimaP($clave);
        if ($fila != "No se encontró el producto") {
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }
    
}
