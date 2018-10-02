<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cedis extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Modelousuario");
        $this->load->model("Modelocedis");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        }
        $id = $this->session->userdata('id');
        if (!$this->Modelousuario->TienePerfil($id, 6)) {
            redirect('usuario/logueado');
        }
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
        $this->load->view('cedis/EntradaProducto', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function VerificarClaveProd() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modelocedis");
        $fila = $this->Modelocedis->BuscarClaveProducto($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }

    public function VerificarClaveTarima() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modelocedis");
        $fila = $this->Modelocedis->BuscarClaveTarima($clave);
        if ($fila != "No se encontró la tarima") {
            $infocontent["id"] = $fila->IdTarimas;
        }
        print json_encode($infocontent);
    }

    public function GuardarTarimasCedis() {
        $idtarima = $this->input->post_get('idtarima', TRUE);
        $this->load->model("Modelocedis");
        $resp = $this->Modelocedis->GuardarProductosTarima($idtarima);
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
        $this->load->model("Modelocedis");
        if ($this->Modelocedis->ProductoEnCedis($idproducto)) {
            print("Existe");
        } else {
            $this->Modelocedis->GuardarProductoCedis($idproducto);
            print("Correcto");
        }
    }

    public function CapturaPedidos() {
        $infoheader["titulo"] = "Captura Pedidos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modelocedis");
        $infocontent["ListaPedidosCapturados"] = $this->Modelocedis->ListaCompletaPedidosCapturados();
        $infocontent["ListaPedidosLiberados"] = $this->Modelocedis->ListaCompletaPedidosLiberados();
        $infocontent["ListaPedidosEntregados"] = $this->Modelocedis->ListaCompletaPedidosEntregados();
        $this->load->view('cedis/CapturaPedidos', $infocontent);

        $this->load->view('template/footerd', '');
    }

    public function VerificarProductoCedis() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modelocedis");
        $fila = $this->Modelocedis->BuscarProductoCedis($clave);
        $infocontent["nombre"] = $fila;
        if ($fila != "No se encontró el producto" && $fila != "No está en cedis" && $fila != "El producto ya se encuentra en un pedido") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }

    function SubirImagenPedido() {
        $pedidoid = $this->input->post('pedidoid');
        $config['upload_path'] = 'public/imagenespedidos/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '4000';
        $fecha = date('Y-m-d-hi-s-a');
        $config['file_name'] = "pedido" . $fecha . $pedidoid;
        $config['max_width'] = '4024';
        $config['max_height'] = '4008';
        $this->load->library('upload', $config);
        $this->upload->do_upload();
        $file_info = $this->upload->data();
        $ruta = "public/imagenespedidos/" . $file_info["file_name"];
        $this->Modelocedis->SubirImagenPedido($ruta, $pedidoid);
        redirect('cedis/AbrirPedido?pedidoid=' . $pedidoid);
    }

    public function GuardarPedidoCedis() {
        $cliente = $this->input->post_get('cliente', TRUE);
        $this->load->model("Modelocedis");
        $idpedido = $this->Modelocedis->GuardarPedido($cliente);
        print($idpedido);
    }

    public function EliminarImagenPedido() {
        $idimagen = $this->input->post_get('idimagen', TRUE);
        $pedidoid = $this->input->post_get('pedidoid', TRUE);
        $this->Modelocedis->EliminarImagenPedido($idimagen);
        redirect('cedis/AbrirPedido?pedidoid=' . $pedidoid);
    }

    public function GuardarDetallePedidoCedis() {
        $idproducto = $this->input->post_get('idproducto', TRUE);
        $idpedido = $this->input->post_get('idpedido', TRUE);
        $this->load->model("Modelocedis");
        $iddetalle = $this->Modelocedis->GuardarDetallePedido($idproducto, $idpedido);
        if ($iddetalle == "En pedido") {
            print("En pedido");
        } else if ($iddetalle == "No solicitado") {
            print("No solicitado");
        } else if ($iddetalle == "Fuera de límite") {
            print("Fuera límite");
        } else {
            print("Correcto");
        }
    }

    public function AbrirPedido() {
        $pedidoid = $this->input->post_get('pedidoid', TRUE);
        $this->load->model("Modelocedis");
        $infoheader["titulo"] = "Pedido: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["ListaProductos"] = $this->Modelocedis->ProductosPedido($pedidoid);
        $infocontent["ListaImagenes"] = $this->Modelocedis->ListaImagenesPedido($pedidoid);
        $infocontent["pedidoid"] = $pedidoid;
        $infocontent["pedido"] = $this->Modelocedis->ObtenerPedido($pedidoid);
        $this->load->view('cedis/AbrirPedido', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function InformacionPedidoVentas() {
        $pedidoid = $this->input->post_get('pedidoid', TRUE);
        $infocontent["pedido"] = $this->Modelocedis->ObtenerPedido($pedidoid);
        $infocontent["ListaProductosAgrupados"] = $this->Modelocedis->ProductosPedidoAgrupados($pedidoid);
        $this->load->view('cedis/InformacionPedidoVentas', $infocontent);
    }

    public function RecargaProductosPedido() {
        $pedidoid = $this->input->post_get('pedidoid', TRUE);
        $infocontent["pedido"] = $this->Modelocedis->ObtenerPedido($pedidoid);
        $infocontent["ListaProductos"] = $this->Modelocedis->ProductosPedido($pedidoid);
        $this->load->view('cedis/RecargaProductosPedido', $infocontent);
    }

    public function SalidaCedis() {
        $pedidoid = $this->input->post_get('pedidoid', TRUE);
        $this->load->model("Modelocedis");
        $this->Modelocedis->SalidaPedido($pedidoid);
        print("correcto");
    }

    public function MaximosMinimos() {
        $infoheader["titulo"] = "Máximos y Mínimos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modelocedis");
        $infocontent["modelos"] = $this->Modelocedis->ListaModelos();
        $this->load->view('cedis/MaximosMinimos', $infocontent);

        $this->load->view('template/footerd', '');
    }

    public function ConfiguracionMaximosMinimos() {
        $infoheader["titulo"] = "Configuración Máximos y Mínimos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modelocedis");
        $infocontent["modelos"] = $this->Modelocedis->ListaModelos();
        $this->load->view('cedis/ConfiguracionMaximosMinimos', $infocontent);

        $this->load->view('template/footerd', '');
    }

    public function GuardarMaximo() {
        $cproducto = $this->input->post_get('cproducto', TRUE);
        $modelo = $this->input->post_get('modelo', TRUE);
        $color = $this->input->post_get('color', TRUE);
        $clasificacion = $this->input->post_get('clasificacion', TRUE);
        $valor = $this->input->post_get('valor', TRUE);
        $this->load->model("Modelocedis");
        $this->Modelocedis->GuardarMaximo($cproducto, $modelo, $color, $clasificacion, $valor);
        print("correcto");
    }

    public function GuardarMinimo() {
        $cproducto = $this->input->post_get('cproducto', TRUE);
        $modelo = $this->input->post_get('modelo', TRUE);
        $color = $this->input->post_get('color', TRUE);
        $clasificacion = $this->input->post_get('clasificacion', TRUE);
        $valor = $this->input->post_get('valor', TRUE);
        $this->load->model("Modelocedis");
        $this->Modelocedis->GuardarMinimo($cproducto, $modelo, $color, $clasificacion, $valor);
        print("correcto");
    }

    public function CapturaDevoluciones() {
        $infoheader["titulo"] = "Devoluciones: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $infocontent["hoy"] = date("d/m/Y");
        $this->load->view('cedis/CapturaDevoluciones', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function DevolucionesCapturadas() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $infocontent["devolucionescapturadas"] = $this->Modelocedis->DevolucionesCapturadas($fechainicio, $fechafin);
        $this->load->view('cedis/DevolucionesCapturadas', $infocontent);
    }

    public function BuscarSubproducto() {
        $texto = $this->input->post_get('texto', TRUE);
        $id = $this->input->post_get('id', TRUE);
        $encontrados = $this->Modelocedis->BuscarSubproducto($texto);
        $infocontent["encontrados"] = $encontrados;
        $infocontent["id"] = $id;
        $this->load->view('cedis/BuscarSubproducto', $infocontent);
    }

    public function GuardarDevolucion() {
        $cliente = $this->input->post_get('cliente', TRUE);
        $motivo = $this->input->post_get('motivo', TRUE);
        $responsable = $this->input->post_get('responsable', TRUE);
        $this->load->model("Modelosclasificador");
        $iddevolucion = $this->Modelosclasificador->GuardarDevolucion($cliente, $motivo, $responsable);
        print($iddevolucion);
    }

    public function GuardarDetalleDevolucion() {
        $id_producto = $this->input->post_get('producto_id', TRUE);
        $id_devolucion = $this->input->post_get('devolucion_id', TRUE);
        $this->load->model("Modelosclasificador");
        $iddetalle = $this->Modelosclasificador->GuardarDetalleDevolucion($id_producto, $id_devolucion);
        print($iddetalle);
    }

    public function GuardarSubproducto() {
        $detalle_id = $this->input->post_get("detalle_id");
        $subproducto_id = $this->input->post_get("subproducto_id");
        $iddetalle = $this->Modelocedis->GuardarSubproducto($subproducto_id, $detalle_id);
        print($iddetalle);
    }

}

//Guardar fecha de presalida
//Capturar el cliente al guardar pedido
//Marcar de abierta la tarima al guardar detalle de pedidos
//Eliminar tabla de detallepedidos
//Probar que al querer guardar un producto en un pedido ya se encuentre configurado en alguno

//Listar pedidos capturados
