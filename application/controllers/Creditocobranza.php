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

}
?>

