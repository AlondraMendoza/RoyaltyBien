<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Cedis extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model("modelousuario");
		$this->load->model("modelocedis");
		if (!EstaLogueado()) {
			redirect('usuario/index');
		}
		$id = $this->session->userdata('id');
		if (!$this->modelousuario->TienePerfil($id, 6)) {
			redirect('usuario/logueado');
		}
	}

	public function index()
	{
		$datos["nombre"] = "Cadena ejemplo";
		$datos["apellido"] = "Cadena ejemplo 2";
		$this->load->view('capturista/index', $datos);
	}

	public function EntradaTarimas()
	{
		$infoheader["titulo"] = "Entrada Tarimas: Royalty Ceramic";
		$this->load->view('template/headerd', $infoheader);
		$infocontent["Nombre"] = "Alondra Mendoza";
		$this->load->view('cedis/EntradaProductos', $infocontent);
		$this->load->view('template/footerd', '');
	}

	public function EntradaProductos()
	{
		$infoheader["titulo"] = "Entrada Productos: Royalty Ceramic";
		$this->load->view('template/headerd', $infoheader);
		$infocontent["Nombre"] = "Alondra Mendoza";
		$this->load->view('cedis/EntradaProductos', $infocontent);
		$this->load->view('template/footerd', '');
	}

	public function VerificarClaveProd()
	{
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

	public function VerificarClaveTarima()
	{
		$clave = $this->input->post_get('clave', TRUE);
		$this->load->model("modelocedis");
		$fila = $this->modelocedis->BuscarClaveTarima($clave);
		if ($fila != "No se encontró la tarima") {
			$infocontent["id"] = $fila->IdTarimas;
		}
		print json_encode($infocontent);
	}

	public function GuardarTarimasCedis()
	{
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

	public function GuardarProductoCedis()
	{
		$idproducto = $this->input->post_get('idproducto', TRUE);
		$this->load->model("modelocedis");
		if ($this->modelocedis->ProductoEnCedis($idproducto)) {
			print("Existe");
		} else {
			$this->modelocedis->GuardarProductoCedis($idproducto);
			print("Correcto");
		}
	}

	public function CapturaPedidos()
	{
		$infoheader["titulo"] = "Captura Pedidos: Royalty Ceramic";
		$this->load->view('template/headerd', $infoheader);
		$infocontent["Nombre"] = "Alondra Mendoza";
		$this->load->model("modelocedis");
		$infocontent["ListaPedidos"] = $this->modelocedis->ListaCompletaPedidos();
		$this->load->view('cedis/CapturaPedidos', $infocontent);

		$this->load->view('template/footerd', '');
	}

	public function VerificarProductoCedis()
	{
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

	public function GuardarPedidoCedis()
	{
		$cliente = $this->input->post_get('cliente', TRUE);
		$this->load->model("modelocedis");
		$idpedido = $this->modelocedis->GuardarPedido($cliente);
		print($idpedido);
	}

	public function GuardarDetallePedidoCedis()
	{
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

	public function AbrirPedido()
	{
		$pedidoid = $this->input->post_get('pedidoid', TRUE);
		$this->load->model("modelocedis");
		$infocontent["ListaProductos"] = $this->modelocedis->ProductosPedido($pedidoid);
		$infocontent["pedidoid"] = $pedidoid;
		$this->load->view('cedis/AbrirPedido', $infocontent);
	}

	public function SalidaCedis()
	{
		$pedidoid = $this->input->post_get('pedidoid', TRUE);
		$this->load->model("modelocedis");
		$this->modelocedis->SalidaPedido($pedidoid);
		print("correcto");
	}

	public function MaximosMinimos()
	{
		$infoheader["titulo"] = "Máximos y Mínimos: Royalty Ceramic";
		$this->load->view('template/headerd', $infoheader);
		$this->load->model("modelocedis");
		$infocontent["modelos"] = $this->modelocedis->ListaModelos();
		$this->load->view('cedis/MaximosMinimos', $infocontent);

		$this->load->view('template/footerd', '');
	}

	public function ConfiguracionMaximosMinimos()
	{
		$infoheader["titulo"] = "Configuración Máximos y Mínimos: Royalty Ceramic";
		$this->load->view('template/headerd', $infoheader);
		$this->load->model("modelocedis");
		$infocontent["modelos"] = $this->modelocedis->ListaModelos();
		$this->load->view('cedis/ConfiguracionMaximosMinimos', $infocontent);

		$this->load->view('template/footerd', '');
	}

	public function GuardarMaximo()
	{
		$cproducto = $this->input->post_get('cproducto', TRUE);
		$modelo = $this->input->post_get('modelo', TRUE);
		$color = $this->input->post_get('color', TRUE);
		$clasificacion = $this->input->post_get('clasificacion', TRUE);
		$valor = $this->input->post_get('valor', TRUE);
		$this->load->model("modelocedis");
		$this->modelocedis->GuardarMaximo($cproducto, $modelo, $color, $clasificacion, $valor);
		print("correcto");
	}

	public function GuardarMinimo()
	{
		$cproducto = $this->input->post_get('cproducto', TRUE);
		$modelo = $this->input->post_get('modelo', TRUE);
		$color = $this->input->post_get('color', TRUE);
		$clasificacion = $this->input->post_get('clasificacion', TRUE);
		$valor = $this->input->post_get('valor', TRUE);
		$this->load->model("modelocedis");
		$this->modelocedis->GuardarMinimo($cproducto, $modelo, $color, $clasificacion, $valor);
		print("correcto");
	}

	public function CapturaDevoluciones()
	{
		$infoheader["titulo"] = "Devoluciones: Royalty Ceramic";
		$this->load->view('template/headerd', $infoheader);
		$infocontent["Nombre"] = "Alondra Mendoza";
		$infocontent["devolucionescapturadas"]= $this->modelocedis->DevolucionesCapturadas();
		$this->load->view('cedis/CapturaDevoluciones', $infocontent);
		$this->load->view('template/footerd', '');
	}

	public function BuscarSubproducto()
	{
		$texto = $this->input->post_get('texto', TRUE);
		$id = $this->input->post_get('id', TRUE);
		$encontrados = $this->modelocedis->BuscarSubproducto($texto);
		$infocontent["encontrados"] = $encontrados;
		$infocontent["id"] = $id;
		$this->load->view('cedis/BuscarSubproducto', $infocontent);
	}

	public function GuardarDevolucion()
	{
		$cliente = $this->input->post_get('cliente', TRUE);
		$motivo = $this->input->post_get('motivo', TRUE);
		$responsable = $this->input->post_get('responsable', TRUE);
		$this->load->model("modelosclasificador");
		$iddevolucion = $this->modelosclasificador->GuardarDevolucion($cliente, $motivo, $responsable);
		print($iddevolucion);
	}
	public function GuardarDetalleDevolucion()
	{
		$id_producto = $this->input->post_get('producto_id', TRUE);
		$id_devolucion = $this->input->post_get('devolucion_id', TRUE);
		$this->load->model("modelosclasificador");
		$iddetalle = $this->modelosclasificador->GuardarDetalleDevolucion($id_producto, $id_devolucion);
		print($iddetalle);
	}
	public function GuardarSubproducto()
	{
		$detalle_id=$this->input->post_get("detalle_id");
		$subproducto_id=$this->input->post_get("subproducto_id");
		$iddetalle = $this->modelocedis->GuardarSubproducto($subproducto_id, $detalle_id);
		print($iddetalle);
	}

}

//Guardar fecha de presalida
//Capturar el cliente al guardar pedido
//Marcar de abierta la tarima al guardar detalle de pedidos
//Eliminar tabla de detallepedidos
//Probar que al querer guardar un producto en un pedido ya se encuentre configurado en alguno

//Listar pedidos capturados
