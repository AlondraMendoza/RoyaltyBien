<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cedis extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $datos["nombre"] = "Cadena ejemplo";
        $datos["apellido"] = "Cadena ejemplo 2";
        $this->load->view('capturista/index', $datos);
    }

    public function EntradaTarimas() {
        $infoheader["titulo"] = "Entrada Tarimas: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->view('cedis/EntradaProductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function EntradaProductos() {
        $infoheader["titulo"] = "Entrada Productos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->view('cedis/EntradaProductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function VerificarClaveProd() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("modelocedis");
        $fila = $this->modelocedis->BuscarClaveProducto($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }

    public function VerificarClaveTarima() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("modelocedis");
        $fila = $this->modelocedis->BuscarClaveTarima($clave);
        if ($fila != "No se encontró la tarima") {
            $infocontent["id"] = $fila->IdTarimas;
        }
        print json_encode($infocontent);
    }

    public function GuardarTarimasCedis() {
        $idtarima = $this->input->post_get('idtarima', TRUE);
        $this->load->model("modelocedis");
        $resp = $this->modelocedis->GuardarProductosTarima($idtarima);
        if ($resp == "Existe") {
            print("Existe");
        } else if ($resp == "correcto") {
            print("Correcto");
        } else {
            print("Error");
        }
    }

    public function GuardarProductoCedis() {
        $idproducto = $this->input->post_get('idproducto', TRUE);
        $this->load->model("modelocedis");
        if ($this->modelocedis->ProductoEnCedis($idproducto)) {
            print("Existe");
        } else {
            $this->modelocedis->GuardarProductoCedis($idproducto);
            print("Correcto");
        }
    }

    public function CapturaPedidos() {
        $infoheader["titulo"] = "Captura Pedidos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("modelocedis");
        $infocontent["ListaPedidos"] = $this->modelocedis->ListaCompletaPedidos();
        $this->load->view('cedis/CapturaPedidos', $infocontent);

        $this->load->view('template/footerd', '');
    }

    public function VerificarProductoCedis() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("modelocedis");
        $fila = $this->modelocedis->BuscarProductoCedis($clave);
        $infocontent["nombre"] = $fila;
        if ($fila != "No se encontró el producto" && $fila != "No está en cedis" && $fila != "El producto ya se encuentra en un pedido") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }

    public function GuardarPedidoCedis() {
        $cliente = $this->input->post_get('cliente', TRUE);
        $this->load->model("modelocedis");
        $idpedido = $this->modelocedis->GuardarPedido($cliente);
        print($idpedido);
    }

    public function GuardarDetallePedidoCedis() {
        $idproducto = $this->input->post_get('idproducto', TRUE);
        $idpedido = $this->input->post_get('idpedido', TRUE);
        $this->load->model("modelocedis");
        $iddetalle = $this->modelocedis->GuardarDetallePedido($idproducto, $idpedido);
        if ($iddetalle == "En pedido") {
            print("En pedido");
        } else {
            print("Correcto");
        }
    }

    public function AbrirPedido() {
        $pedidoid = $this->input->post_get('pedidoid', TRUE);
        $this->load->model("modelocedis");
        $infocontent["ListaProductos"] = $this->modelocedis->ProductosPedido($pedidoid);
        $infocontent["pedidoid"] = $pedidoid;
        $this->load->view('cedis/AbrirPedido', $infocontent);
    }

}

//Guardar fecha de presalida
//Capturar el cliente al guardar pedido
//Marcar de abierta la tarima al guardar detalle de pedidos
//Eliminar tabla de detallepedidos
//Probar que al querer guardar un producto en un pedido ya se encuentre configurado en alguno

//Listar pedidos capturados