<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usuario extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function iniciar_sesion() {
        $infoheader["titulo"] = "Royalty Ceramic";
        $this->load->view('template/headerLogin', $infoheader);
        $data = array();
        $this->load->view('usuario/iniciar_sesion', $data);
        $this->load->view('template/footerLogin', '');
    }

    public function iniciar_sesion_post() {
        if ($this->input->post()) {
            $nombre = $this->input->post('nombre');
            $contrasena = $this->input->post('contrasena');
            $this->load->model('modelousuario');
            $usuario = $this->modelousuario->usuario_por_nombre_contrasena($nombre, $contrasena);
            if ($usuario) {
                $usuario_data = array(
                    'id' => $usuario->IdUsuarios,
                    'nombre' => $usuario->Nombre,
                    'persona' => $usuario->NombreCompleto,
                    'perfiles' => $this->modelousuario->ObtenerPerfiles($usuario->IdUsuarios),
                    'logueado' => TRUE
                );
                $this->session->set_userdata($usuario_data);
                redirect('usuario/logueado');
            } else {
                $infoheader["titulo"] = "Royalty Ceramic";
                $this->load->view('template/headerLogin', $infoheader);
                $data["mensaje"] = "error";
                $this->load->view('usuario/iniciar_sesion', $data);
                $this->load->view('template/footerLogin', '');
            }
        } else {
            $this->iniciar_sesion();
        }
    }

    public function logueado() {
        if ($this->session->userdata('logueado')) {
            $data = array();
            $data['nombre'] = $this->session->userdata('nombre');
            $data['persona'] = $this->session->userdata('persona');
            $infoheader["titulo"] = "Royalty Ceramic";
            $this->load->view('template/headerd', $infoheader);
            $id = $this->session->userdata('id');
            $this->load->model('modelousuario');
            $data['perfiles'] = $this->modelousuario->ObtenerPerfiles($id);
            //$data['menu'] = $this->modelousuario->ObtenerMenu($p);
            $this->load->view('usuario/logueado', $data);
            $this->load->view('template/footerd', '');
        } else {
            redirect('usuario/iniciar_sesion');
        }
    }

    public function cerrar_sesion() {
        $usuario_data = array(
            'logueado' => FALSE
        );
        $this->session->set_userdata($usuario_data);
        $this->session->sess_destroy();
        redirect('usuario/iniciar_sesion');
    }

    //Verificar metodo
    /* public function ObtenerUsuario() {
      $usuario = new \Models\Usuarios();
      $usuario->set("IdUsuarios", 1);
      return $usuario;
      }

      public function index() {
      $lista = $this->ObtenerUsuario()->ObtenerPerfiles();

      $array = [
      "listaperfiles" => $lista,
      "otravariable" => 5,];
      return $array;
      } */
}
