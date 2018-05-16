<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Administrador extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("modelousuario");
        if (!EstaLogueado()) {
            redirect('usuario/iniciar_sesion');
        }
        $id = $this->session->userdata('id');
        if (!$this->modelousuario->TienePerfil($id, 3)) {
            redirect('usuario/logueado');
        }
    }

    public function Reportes() {
        $this->load->model("modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $infocontent["Nombre"] = "Alondra Mendoza";
        $infocontent["hoy"] = date("d/m/Y");
        $infocontent["clasificaciones"] = $this->modeloadministrador->Clasificaciones();
        $infocontent["productos"] = $this->modeloadministrador->Productos();
        $infocontent["modelos"] = $this->modeloadministrador->Modelos(0);
        $infocontent["colores"] = $this->modeloadministrador->Colores(0);
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/Reportes', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ReporteQuemado() {
        $this->load->model("modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $infocontent["Nombre"] = "Alondra Mendoza";
        $infocontent["hoy"] = date("d/m/Y");
        $infocontent["hornos"] = $this->modeloadministrador->Hornos();
        $infocontent["productos"] = $this->modeloadministrador->ProductosQuemado();
        $infocontent["modelos"] = $this->modeloadministrador->ModelosQuemado(0);
        $infocontent["colores"] = $this->modeloadministrador->Colores(0);
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/ReporteQuemado', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ReporteQAcc() {
        $this->load->model("modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $infocontent["Nombre"] = "Alondra Mendoza";
        $infocontent["hoy"] = date("d/m/Y");
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/ReporteQAcc', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ObtenerModelos() {
        $producto = $this->input->post_get('producto', TRUE);
        $this->load->model("modeloadministrador");
        $infocontent["modelos"] = $this->modeloadministrador->Modelos($producto);
        $this->load->view('administrador/ObtenerModelos', $infocontent);
    }

    public function ObtenerColores() {
        $modelo = $this->input->post_get('modelo', TRUE);
        $this->load->model("modeloadministrador");
        $infocontent["colores"] = $this->modeloadministrador->Colores($modelo);
        $this->load->view('administrador/ObtenerColores', $infocontent);
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
        $this->load->model("modeloadministrador");
        $infocontent["productos"] = $this->modeloadministrador->GenerarReporte($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor);
        $this->load->view('administrador/GenerarReporte', $infocontent);
    }

    public function GenerarReporteQAcc() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $this->load->model("modeloadministrador");
        $infocontent["productos"] = $this->modeloadministrador->GenerarReporteQAcc($fechainicio, $fechafin);
        $this->load->view('administrador/GenerarReporteQAcc', $infocontent);
    }

    public function GenerarConcentrado() {
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
        $por = $this->input->post_get('por', TRUE);
        $this->load->model("modeloadministrador");
        $infocontent["por"] = $por;
        $infocontent["productos"] = $this->modeloadministrador->GenerarConcentrado($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $por);
        $this->load->view('administrador/GenerarConcentrado', $infocontent);
    }

    public function GenerarReporteQ() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $hornos = $this->input->post_get('hornos', TRUE);
        $ahornos = json_decode($hornos);
        $producto = $this->input->post_get('producto', TRUE);
        $aproducto = json_decode($producto);
        $modelo = $this->input->post_get('modelo', TRUE);
        $amodelo = json_decode($modelo);
        $color = $this->input->post_get('color', TRUE);
        $acolor = json_decode($color);
        $this->load->model("modeloadministrador");
        $infocontent["productos"] = $this->modeloadministrador->GenerarReporteQ($fechainicio, $fechafin, $ahornos, $aproducto, $amodelo, $acolor);
        $this->load->view('administrador/GenerarReporteQ', $infocontent);
    }

    public function GenerarConcentradoQ() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $hornos = $this->input->post_get('hornos', TRUE);
        $ahornos = json_decode($hornos);
        $producto = $this->input->post_get('producto', TRUE);
        $aproducto = json_decode($producto);
        $modelo = $this->input->post_get('modelo', TRUE);
        $amodelo = json_decode($modelo);
        $color = $this->input->post_get('color', TRUE);
        $acolor = json_decode($color);
        $por = $this->input->post_get('por', TRUE);
        $this->load->model("modeloadministrador");
        $infocontent["por"] = $por;
        $infocontent["productos"] = $this->modeloadministrador->GenerarConcentradoQ($fechainicio, $fechafin, $ahornos, $aproducto, $amodelo, $acolor, $por);
        $this->load->view('administrador/GenerarConcentradoQ', $infocontent);
    }

    public function CapturaPerfiles() {
        $this->load->model("modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $infocontent["usuarios"] = $this->modeloadministrador->Usuarios();
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/CapturaPerfiles', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ExpedienteUsuario() {
        $this->load->model("modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $persona = $this->input->post_get('persona', TRUE);
        $usuario = $this->modeloadministrador->Usuario($persona);
        $infocontent["persona"] = $this->modeloadministrador->ObtenerPersona($persona);
        $infocontent["usuario"] = $usuario;
        $infocontent["ultimopuesto"] = $this->modeloadministrador->UltimoPuesto($persona);
        $ultimoperfil = "";
        if ($usuario != null) {
            $ultimoperfil = $this->modeloadministrador->UltimoPerfil($usuario->IdUsuarios);
        } else {
            $ultimoperfil = "No existe Usuario";
        }
        $infocontent["ultimoperfil"] = $ultimoperfil;
        $infocontent["tieneusuario"] = $this->modeloadministrador->TieneUsuario($persona);
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/ExpedienteUsuario', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function CargaPerfilesUsuario() {
        $this->load->model("modeloadministrador");
        $usuario = $this->input->post_get('usuario', TRUE);
        $infocontent["usuario"] = $this->modeloadministrador->GetUsuario($usuario);
        $infocontent["perfiles"] = $this->modeloadministrador->PerfilesUsuario($usuario);
        $infocontent["perfilestodos"] = $this->modeloadministrador->Perfiles();
        $this->load->view('administrador/CargaPerfilesUsuario', $infocontent);
    }

    public function CargaPuestosUsuario() {
        $this->load->model("modeloadministrador");
        $persona = $this->input->post_get('persona', TRUE);
        $infocontent["persona"] = $this->modeloadministrador->ObtenerPersona($persona);
        $infocontent["puestos"] = $this->modeloadministrador->PuestosUsuario($persona);
        $infocontent["puestostodos"] = $this->modeloadministrador->Puestos();
        $infocontent["areas"] = $this->modeloadministrador->Areas();
        $this->load->view('administrador/CargaPuestosUsuario', $infocontent);
    }

    public function AgregarPerfil() {
        $this->load->model("modeloadministrador");
        $usuario = $this->input->post_get('usuario', TRUE);
        $perfil = $this->input->post_get('perfil', TRUE);
        $id = $this->modeloadministrador->AgregarPerfil($usuario, $perfil);
        if ($id != null) {
            print_r("correcto");
        }
    }

    public function AgregarPuesto() {
        $this->load->model("modeloadministrador");
        $persona = $this->input->post_get('persona', TRUE);
        $puesto = $this->input->post_get('puesto', TRUE);
        $clave = $this->input->post_get('clave', TRUE);
        $area = $this->input->post_get('area', TRUE);
        $id = $this->modeloadministrador->AgregarPuesto($persona, $puesto, $area, $clave);
        if ($id != null) {
            print_r("correcto");
        }
    }

    public function EliminarPerfil() {
        $this->load->model("modeloadministrador");
        $usuario = $this->input->post_get('usuario', TRUE);
        $perfil = $this->input->post_get('perfil', TRUE);
        $this->modeloadministrador->EliminarPerfil($usuario, $perfil);
        print_r("correcto");
    }

    public function EliminarPuesto() {
        $this->load->model("modeloadministrador");
        $persona = $this->input->post_get('persona', TRUE);
        $puesto = $this->input->post_get('puesto', TRUE);
        $this->modeloadministrador->EliminarPuesto($persona, $puesto);
        print_r("correcto");
    }

    public function Productos() {
        $this->load->model("modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $infocontent["productos"] = $this->modeloadministrador->ProductosT();
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/Productos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function DetalleProd() {
        $this->load->model("modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $producto = $this->input->post_get('producto', TRUE);
        $infocontent["producto"] = $producto;
        //$infocontent["producto"] = $this->modeloadministrador->ProductosT($producto);
        $infocontent["modelos"] = $this->modeloadministrador->ObtenerModelos($producto);
        $infocontent["todos"] = $this->modeloadministrador->TodosModelos();
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/DetalleProd', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function DetalleMod() {
        $this->load->model("modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $modelo = $this->input->post_get('modelo', TRUE);
        $infocontent["modelo"] = $modelo;
        $infocontent["colores"] = $this->modeloadministrador->ObtenerColores($modelo);
        $infocontent["todos"] = $this->modeloadministrador->TodosColores();
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/DetalleMod', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function DesactivarProducto() {
        $this->load->model("modeloadministrador");
        $producto = $this->input->post_get('producto', TRUE);
        $this->modeloadministrador->DesactivarProducto($producto);
        print_r("correcto");
    }

    public function ActivarProducto() {
        $this->load->model("modeloadministrador");
        $producto = $this->input->post_get('producto', TRUE);
        $this->modeloadministrador->ActivarProducto($producto);
        print_r("correcto");
    }

    public function NuevoProducto() {
        $this->load->model("modeloadministrador");
        $nombre = $this->input->post_get('nombre', TRUE);
        $this->modeloadministrador->NuevoProducto($nombre);
        print_r("correcto");
    }

    public function DesactivarModelo() {
        $this->load->model("modeloadministrador");
        $codigo = $this->input->post_get('codigo', TRUE);
        $this->modeloadministrador->DesactivarModelo($codigo);
        print_r("correcto");
    }

    public function ActivarModelo() {
        $this->load->model("modeloadministrador");
        $codigo = $this->input->post_get('codigo', TRUE);
        $this->modeloadministrador->ActivarModelo($codigo);
        print_r("correcto");
    }

    public function SeleccionModelo() {
        $this->load->model("modeloadministrador");
        $nombre = $this->input->post_get('nombre', TRUE);
        $producto = $this->input->post_get('producto', TRUE);
        $this->modeloadministrador->SeleccionModelo($nombre, $producto);
        print_r("correcto");
    }

    public function NuevoModelo() {
        $this->load->model("modeloadministrador");
        $nombre = $this->input->post_get('nombre', TRUE);
        $producto = $this->input->post_get('producto', TRUE);
        $this->modeloadministrador->NuevoModelo($nombre, $producto);
        print_r("correcto");
    }

    public function DesactivarColor() {
        $this->load->model("modeloadministrador");
        $color = $this->input->post_get('color', TRUE);
        $modelo = $this->input->post_get('modelo', TRUE);
        $this->modeloadministrador->DesactivarColor($color, $modelo);
        print_r("correcto");
    }

    public function SeleccionColor() {
        $this->load->model("modeloadministrador");
        $color = $this->input->post_get('color', TRUE);
        $modelo = $this->input->post_get('modelo', TRUE);
        $this->modeloadministrador->SeleccionColor($color, $modelo);
        print_r("correcto");
    }

    public function NuevoColor() {
        $this->load->model("modeloadministrador");
        $color = $this->input->post_get('color', TRUE);
        $modelo = $this->input->post_get('modelo', TRUE);
        $this->modeloadministrador->NuevoColor($color, $modelo);
        print_r("correcto");
    }

    public function GuardarEmpleado() {
        $nombre = $this->input->post_get('nombre', TRUE);
        $apellidop = $this->input->post_get('apellidop', TRUE);
        $apellidom = $this->input->post_get('apellidom', TRUE);
        $nempleado = $this->input->post_get('nempleado', TRUE);
        $this->load->model("modeloadministrador");
        $this->modeloadministrador->GuardarEmpleado($nombre, $apellidop, $apellidom, $nempleado);
        redirect('administrador/CapturaPerfiles');
    }

    public function CrearUsuario() {
        $persona = $this->input->post_get('persona_id', TRUE);
        $this->load->model("modeloadministrador");
        $this->modeloadministrador->CrearUsuario($persona);
        redirect('administrador/ExpedienteUsuario?persona=' . $persona);
    }

    public function CancelarUsuario() {
        $persona = $this->input->post_get('persona_id', TRUE);
        $this->load->model("modeloadministrador");
        $this->modeloadministrador->CrearUsuario($persona);
        redirect('administrador/ExpedienteUsuario?persona=' . $persona);
    }

}
