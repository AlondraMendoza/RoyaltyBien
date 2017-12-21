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
               'id' => $usuario->id,
               'nombre' => $usuario->nombre,
               'logueado' => TRUE
            );
            $this->session->set_userdata($usuario_data);
            redirect('usuario/logueado');
         } else {
            redirect('usuario/iniciar_sesion');
         }
      } else {
         $this->iniciar_sesion();
      }
   }
   public function logueado() {
      if($this->session->userdata('logueado')){
         $data = array();
         $data['nombre'] = $this->session->userdata('nombre');
         $this->load->view('usuario/logueado', $data);
      }else{
         redirect('usuario/iniciar_sesion');
      }
   }
   public function cerrar_sesion() {
      $usuario_data = array(
         'logueado' => FALSE
      );
      $this->session->set_userdata($usuario_data);
      redirect('usuario/iniciar_sesion');
   }
}

