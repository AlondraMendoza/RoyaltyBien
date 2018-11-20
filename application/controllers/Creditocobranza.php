<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Creditocobranza extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Modelousuario");
        $this->load->model("Modeloventas");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        }
        $id = $this->session->userdata('id');
        if (!$this->Modelousuario->TienePerfil($id, 9)) {
            redirect('usuario/logueado');
        }
    }

    public function Pedidos() {
        $infoheader["titulo"] = "Pedidos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modelocedis");
        $infocontent["ListaPedidosCapturados"] = $this->Modelocedis->ListaCompletaPedidosCapturados();
        $infocontent["ListaPedidosLiberados"] = $this->Modelocedis->ListaCompletaPedidosLiberados();
        $infocontent["ListaPedidosEntregados"] = $this->Modelocedis->ListaCompletaPedidosEntregados();
        $this->load->view('creditocobranza/Pedidos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function LiberarPedido() {
        $pedido = $this->input->post_get('id', TRUE);
        $observacionliberacion = $this->input->post_get('observacionliberacion', TRUE);
        $this->db->set("FechaLiberacion", date('Y-m-d | H:i:sa'));
        $this->db->set("UsuarioLiberaId", IdUsuario());
        $this->db->set("ObservacionLiberacion", $observacionliberacion);
        $this->db->set("Estatus", "Liberado");
        $this->db->where("IdPedidos", $pedido);
        $this->db->update("Pedidos");
        print("correcto");
    }
    
    public function Reportes() {
        $this->load->model("Modeloventas");
        $infoheader["titulo"] = "Crédito y Cobranza: Royalty Ceramic";
        $infocontent["hoy"] = date("d/m/Y");
        $infocontent["clasificaciones"] = $this->Modeloventas->Clasificaciones();
        $infocontent["productos"] = $this->Modeloventas->Productos();
        $infocontent["modelos"] = $this->Modeloventas->Modelos(0);
        $infocontent["colores"] = $this->Modeloventas->Colores(0);
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('creditocobranza/Reportes', $infocontent);
        $this->load->view('template/footerd', '');
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
        $defecto = $this->input->post_get('defecto', TRUE);
        $adefecto = json_decode($defecto);
        $this->load->model("Modeloventas");
        $infocontent["productos"] = $this->Modeloventas->GenerarReporte($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $adefecto);
        $this->load->view('creditocobranza/GenerarReporte', $infocontent);
    }

}
?>

