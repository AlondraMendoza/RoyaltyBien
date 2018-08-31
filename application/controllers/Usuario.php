<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usuario extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('encryption');
    }

    public function index() {
        $this->load->model("Modelousuario");
        if (EstaLogueado()) {
            redirect('usuario/logueado');
        }
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
            $this->load->model('Modelousuario');
            $contrasena = md5($contrasena);
            $usuario = $this->Modelousuario->usuario_por_nombre_contrasena($nombre, $contrasena);
            if ($usuario) {
                $usuario_data = array(
                    'id' => $usuario->IdUsuarios,
                    'nombre' => $usuario->Nombre,
                    'persona' => $usuario->NombreCompleto,
                    'perfiles' => $this->Modelousuario->ObtenerPerfiles($usuario->IdUsuarios),
                    'idpersona' => $usuario->PersonasId,
                    'logueado' => TRUE
                );
                $this->session->set_userdata($usuario_data);
                redirect('usuario/logueado');
            } else {
                redirect('usuario/index');
            }
        } else {
            redirect('usuario/index');
        }
    }

    public function logueado() {
        $this->load->model("Modelousuario");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        }
        if ($this->session->userdata('logueado')) {
            $data = array();
            $data['nombre'] = $this->session->userdata('nombre');
            $data['persona'] = $this->session->userdata('persona');
            $infoheader["titulo"] = "Royalty Ceramic";
            $this->load->view('template/headerd', $infoheader);
            $id = $this->session->userdata('id');
            $this->load->model('Modelousuario');
            $data['perfiles'] = $this->Modelousuario->ObtenerPerfiles($id);
            //$data['menu'] = $this->Modelousuario->ObtenerMenu($p);
            $this->load->view('usuario/logueado', $data);
            $this->load->view('template/footerd', '');
        } else {
            redirect('usuario/index');
        }
    }

    public function Cuenta() {
        $this->load->model("Modelousuario");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        } else {
            $this->load->model("Modeloadministrador");
            $infoheader["titulo"] = "Administrador: Royalty Ceramic";
            //$persona = $this->input->post_get('persona', TRUE);
            $persona = IdPersona();
            $usuario = $this->Modeloadministrador->Usuario($persona);
            $infocontent["persona"] = $this->Modeloadministrador->ObtenerPersona($persona);
            $infocontent["usuario"] = $usuario;
            $infocontent["ultimopuesto"] = $this->Modeloadministrador->UltimoPuesto($persona);
            $ultimoperfil = "";
            if ($usuario != null) {
                $ultimoperfil = $this->Modeloadministrador->UltimoPerfil($usuario->IdUsuarios);
            } else {
                $ultimoperfil = "No existe Usuario";
            }
            $infocontent["ultimoperfil"] = $ultimoperfil;
            $infocontent["tieneusuario"] = $this->Modeloadministrador->TieneUsuario($persona);
            $this->load->view('template/headerd', $infoheader);
            $this->load->view('usuario/Cuenta', $infocontent);
            $this->load->view('template/footerd', '');
        }
    }

    public function cerrar_sesion() {
        $usuario_data = array(
            'logueado' => FALSE
        );
        $this->session->set_userdata($usuario_data);
        $this->session->sess_destroy();
        redirect('usuario/index');
    }

    public function CargaPerfilesUsuario() {
        $this->load->model("Modeloadministrador");
        $usuario = $this->input->post_get('usuario', TRUE);
        $infocontent["usuario"] = $this->Modeloadministrador->GetUsuario($usuario);
        $infocontent["perfiles"] = $this->Modeloadministrador->PerfilesUsuario($usuario);
        $infocontent["perfilestodos"] = $this->Modeloadministrador->Perfiles();
        $this->load->view('usuario/CargaPerfilesUsuario', $infocontent);
    }

    public function CargaPuestosUsuario() {
        $this->load->model("Modeloadministrador");
        $persona = $this->input->post_get('persona', TRUE);
        $infocontent["persona"] = $this->Modeloadministrador->ObtenerPersona($persona);
        $infocontent["puestos"] = $this->Modeloadministrador->PuestosUsuario($persona);
        $infocontent["puestostodos"] = $this->Modeloadministrador->Puestos();
        $infocontent["areas"] = $this->Modeloadministrador->Areas();
        $this->load->view('usuario/CargaPuestosUsuario', $infocontent);
    }

    public function CambioContra() {
        $this->load->model("Modelousuario");
        $contraactual = $this->input->post_get('contraactual', TRUE);
        $contranueva = $this->input->post_get('contranueva', TRUE);
        $contrasena = md5($contraactual);
        $contrasenanueva = md5($contranueva);
        $nombre = $this->session->userdata('nombre');
        $usuario = $this->Modelousuario->usuario_por_nombre_contrasena($nombre, $contrasena);
        if ($usuario) {
            $this->Modelousuario->GuardarNuevaContra($contrasenanueva);
            print("correcto");
        } else {
            print("nocoincide");
        }
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
