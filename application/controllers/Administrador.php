<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Administrador extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Modelousuario");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        }
        $id = $this->session->userdata('id');
        if (!$this->Modelousuario->TienePerfil($id, 3)) {
            redirect('usuario/logueado');
        }
    }

    public function Reportes() {
        $this->load->model("Modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $infocontent["hoy"] = date("d/m/Y");
        $infocontent["clasificaciones"] = $this->Modeloadministrador->Clasificaciones();
        $infocontent["productos"] = $this->Modeloadministrador->Productos();
        $infocontent["modelos"] = $this->Modeloadministrador->Modelos(0);
        $infocontent["colores"] = $this->Modeloadministrador->Colores(0);
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/Reportes', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ReportesDefectos() {
        $this->load->model("Modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $infocontent["hoy"] = date("d/m/Y");
        $infocontent["clasificaciones"] = $this->Modeloadministrador->Clasificaciones();
        $infocontent["productos"] = $this->Modeloadministrador->Productos();
        $infocontent["modelos"] = $this->Modeloadministrador->Modelos(0);
        $infocontent["colores"] = $this->Modeloadministrador->Colores(0);
        $infocontent["defectos"] = $this->Modeloadministrador->Defectos();
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/ReportesDefectos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ReporteQuemado() {
        $this->load->model("Modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $infocontent["Nombre"] = "Alondra Mendoza";
        $infocontent["hoy"] = date("d/m/Y");
        $infocontent["hornos"] = $this->Modeloadministrador->Hornos();
        $infocontent["productos"] = $this->Modeloadministrador->ProductosQuemado();
        $infocontent["modelos"] = $this->Modeloadministrador->ModelosQuemado(0);
        $infocontent["colores"] = $this->Modeloadministrador->Colores(0);
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/ReporteQuemado', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ReporteQAcc() {
        $this->load->model("Modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $infocontent["Nombre"] = "Alondra Mendoza";
        $infocontent["hoy"] = date("d/m/Y");
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/ReporteQAcc', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ObtenerModelos() {
        $producto = $this->input->post_get('producto', TRUE);
        $this->load->model("Modeloadministrador");
        $infocontent["modelos"] = $this->Modeloadministrador->Modelos($producto);
        $this->load->view('administrador/ObtenerModelos', $infocontent);
    }

    public function ObtenerColores() {
        $modelo = $this->input->post_get('modelo', TRUE);
        $this->load->model("Modeloadministrador");
        $infocontent["colores"] = $this->Modeloadministrador->Colores($modelo);
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
        $defecto = $this->input->post_get('defecto', TRUE);
        $adefecto = json_decode($defecto);
        $this->load->model("Modeloadministrador");
        $infocontent["productos"] = $this->Modeloadministrador->GenerarReporte($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $adefecto);
        $this->load->view('administrador/GenerarReporte', $infocontent);
    }

    public function GenerarDetalleSeleccionado() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $nombre = $this->input->post_get('nombre', TRUE);
        $clasificacion = $this->input->post_get('clasificacion', TRUE);
        $aclasificacion = json_decode($clasificacion);
        $producto = $this->input->post_get('producto', TRUE);
        $por = $this->input->post_get('por', TRUE);
        $aproducto = json_decode($producto);
        $modelo = $this->input->post_get('modelo', TRUE);
        $amodelo = json_decode($modelo);
        $color = $this->input->post_get('color', TRUE);
        $acolor = json_decode($color);
        $this->load->model("Modeloadministrador");
        $infocontent["productos"] = $this->Modeloadministrador->GenerarDetalleSeleccionado($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $por, $nombre);
        $this->load->view('administrador/GenerarDetalleSeleccionado', $infocontent);
    }

    public function GenerarDetalleSeleccionadoQ() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $nombre = $this->input->post_get('nombre', TRUE);
        $hornos = $this->input->post_get('hornos', TRUE);
        $ahornos = json_decode($hornos);
        $producto = $this->input->post_get('producto', TRUE);
        $por = $this->input->post_get('por', TRUE);
        $aproducto = json_decode($producto);
        $modelo = $this->input->post_get('modelo', TRUE);
        $amodelo = json_decode($modelo);
        $color = $this->input->post_get('color', TRUE);
        $acolor = json_decode($color);
        $this->load->model("Modeloadministrador");
        $infocontent["productos"] = $this->Modeloadministrador->GenerarDetalleSeleccionadoQ($fechainicio, $fechafin, $ahornos, $aproducto, $amodelo, $acolor, $por, $nombre);
        $this->load->view('administrador/GenerarDetalleSeleccionadoQ', $infocontent);
    }

    public function GenerarReporteQAcc() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $this->load->model("Modeloadministrador");
        $infocontent["productos"] = $this->Modeloadministrador->GenerarReporteQAcc($fechainicio, $fechafin);
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
        $this->load->model("Modeloadministrador");
        $infocontent["por"] = $por;
        $infocontent["productos"] = $this->Modeloadministrador->GenerarConcentrado($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $por);
        $this->load->view('administrador/GenerarConcentrado', $infocontent);
    }

    public function GenerarConcentradoDefectos() {
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
        $por = $this->input->post_get('por', TRUE);
        $this->load->model("Modeloadministrador");
        $infocontent["por"] = $por;
        $infocontent["productos"] = $this->Modeloadministrador->GenerarConcentradoDefectos($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $adefecto, $por);
        $this->load->view('administrador/GenerarConcentradoDefectos', $infocontent);
    }

    public function GenerarReporteDefectos() {
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
        $this->load->model("Modeloadministrador");
        $infocontent["productos"] = $this->Modeloadministrador->GenerarReporteDefectos($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $adefecto);
        $this->load->view('administrador/GenerarReporteDefectos', $infocontent);
    }

    public function GenerarDetalleSeleccionadoDefectos() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $nombre = $this->input->post_get('nombre', TRUE);
        $clasificacion = $this->input->post_get('clasificacion', TRUE);
        $aclasificacion = json_decode($clasificacion);
        $producto = $this->input->post_get('producto', TRUE);
        $por = $this->input->post_get('por', TRUE);
        $aproducto = json_decode($producto);
        $modelo = $this->input->post_get('modelo', TRUE);
        $amodelo = json_decode($modelo);
        $color = $this->input->post_get('color', TRUE);
        $acolor = json_decode($color);
        $defecto = $this->input->post_get('defecto', TRUE);
        $adefecto = json_decode($defecto);
        $this->load->model("Modeloadministrador");
        $infocontent["productos"] = $this->Modeloadministrador->GenerarDetalleSeleccionadoDefectos($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $adefecto, $por, $nombre);
        $this->load->view('administrador/GenerarDetalleSeleccionadoDefectos', $infocontent);
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
        $this->load->model("Modeloadministrador");
        $infocontent["productos"] = $this->Modeloadministrador->GenerarReporteQ($fechainicio, $fechafin, $ahornos, $aproducto, $amodelo, $acolor);
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
        $this->load->model("Modeloadministrador");
        $infocontent["por"] = $por;
        $infocontent["productos"] = $this->Modeloadministrador->GenerarConcentradoQ($fechainicio, $fechafin, $ahornos, $aproducto, $amodelo, $acolor, $por);
        $this->load->view('administrador/GenerarConcentradoQ', $infocontent);
    }

    public function CapturaPerfiles() {
        $this->load->model("Modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $infocontent["usuarios"] = $this->Modeloadministrador->Usuarios();
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/CapturaPerfiles', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ExpedienteUsuario() {
        $this->load->model("Modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $persona = $this->input->post_get('persona', TRUE);
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
        $this->load->view('administrador/ExpedienteUsuario', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function CargaPerfilesUsuario() {
        $this->load->model("Modeloadministrador");
        $usuario = $this->input->post_get('usuario', TRUE);
        $infocontent["usuario"] = $this->Modeloadministrador->GetUsuario($usuario);
        $infocontent["perfiles"] = $this->Modeloadministrador->PerfilesUsuario($usuario);
        $infocontent["perfilestodos"] = $this->Modeloadministrador->Perfiles();
        $this->load->view('administrador/CargaPerfilesUsuario', $infocontent);
    }

    public function CargaPuestosUsuario() {
        $this->load->model("Modeloadministrador");
        $persona = $this->input->post_get('persona', TRUE);
        $infocontent["persona"] = $this->Modeloadministrador->ObtenerPersona($persona);
        $infocontent["puestos"] = $this->Modeloadministrador->PuestosUsuario($persona);
        $infocontent["puestostodos"] = $this->Modeloadministrador->Puestos();
        $infocontent["areas"] = $this->Modeloadministrador->Areas();
        $this->load->view('administrador/CargaPuestosUsuario', $infocontent);
    }

    public function AgregarPerfil() {
        $this->load->model("Modeloadministrador");
        $usuario = $this->input->post_get('usuario', TRUE);
        $perfil = $this->input->post_get('perfil', TRUE);
        $id = $this->Modeloadministrador->AgregarPerfil($usuario, $perfil);
        if ($id != null) {
            print_r("correcto");
        }
    }

    public function AgregarPuesto() {
        $this->load->model("Modeloadministrador");
        $persona = $this->input->post_get('persona', TRUE);
        $puesto = $this->input->post_get('puesto', TRUE);
        $clave = $this->input->post_get('clave', TRUE);
        $area = $this->input->post_get('area', TRUE);
        $id = $this->Modeloadministrador->AgregarPuesto($persona, $puesto, $area, $clave);
        if ($id != null) {
            print_r("correcto");
        }
    }

    public function EliminarPerfil() {
        $this->load->model("Modeloadministrador");
        $usuario = $this->input->post_get('usuario', TRUE);
        $perfil = $this->input->post_get('perfil', TRUE);
        $this->Modeloadministrador->EliminarPerfil($usuario, $perfil);
        print_r("correcto");
    }

    public function EliminarPuesto() {
        $this->load->model("Modeloadministrador");
        $persona = $this->input->post_get('persona', TRUE);
        $puesto = $this->input->post_get('puesto', TRUE);
        $this->Modeloadministrador->EliminarPuesto($persona, $puesto);
        print_r("correcto");
    }

    public function Productos() {
        $this->load->model("Modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $infocontent["productos"] = $this->Modeloadministrador->ProductosT();
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/Productos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function DetalleProd() {
        $this->load->model("Modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $producto = $this->input->post_get('producto', TRUE);
        $infocontent["producto"] = $producto;
        //$infocontent["producto"] = $this->Modeloadministrador->ProductosT($producto);
        $infocontent["modelos"] = $this->Modeloadministrador->ObtenerModelos($producto);
        $infocontent["todos"] = $this->Modeloadministrador->TodosModelos();
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/DetalleProd', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function DetalleMod() {
        $this->load->model("Modeloadministrador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $modelo = $this->input->post_get('modelo', TRUE);
        $infocontent["modelo"] = $modelo;
        $infocontent["colores"] = $this->Modeloadministrador->ObtenerColores($modelo);
        $infocontent["todos"] = $this->Modeloadministrador->TodosColores();
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/DetalleMod', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function DesactivarProducto() {
        $this->load->model("Modeloadministrador");
        $producto = $this->input->post_get('producto', TRUE);
        $this->Modeloadministrador->DesactivarProducto($producto);
        print_r("correcto");
    }

    public function ActivarProducto() {
        $this->load->model("Modeloadministrador");
        $producto = $this->input->post_get('producto', TRUE);
        $this->Modeloadministrador->ActivarProducto($producto);
        print_r("correcto");
    }

    public function NuevoProducto() {
        $this->load->model("Modeloadministrador");
        $nombre = $this->input->post_get('nombre', TRUE);
        $this->Modeloadministrador->NuevoProducto($nombre);
        print_r("correcto");
    }

    public function DesactivarModelo() {
        $this->load->model("Modeloadministrador");
        $codigo = $this->input->post_get('codigo', TRUE);
        $this->Modeloadministrador->DesactivarModelo($codigo);
        print_r("correcto");
    }

    public function ActivarModelo() {
        $this->load->model("Modeloadministrador");
        $codigo = $this->input->post_get('codigo', TRUE);
        $this->Modeloadministrador->ActivarModelo($codigo);
        print_r("correcto");
    }

    public function SeleccionModelo() {
        $this->load->model("Modeloadministrador");
        $nombre = $this->input->post_get('nombre', TRUE);
        $producto = $this->input->post_get('producto', TRUE);
        $this->Modeloadministrador->SeleccionModelo($nombre, $producto);
        print_r("correcto");
    }

    public function NuevoModelo() {
        $this->load->model("Modeloadministrador");
        $nombre = $this->input->post_get('nombre', TRUE);
        $producto = $this->input->post_get('producto', TRUE);
        $this->Modeloadministrador->NuevoModelo($nombre, $producto);
        print_r("correcto");
    }

    public function DesactivarColor() {
        $this->load->model("Modeloadministrador");
        $color = $this->input->post_get('color', TRUE);
        $modelo = $this->input->post_get('modelo', TRUE);
        $this->Modeloadministrador->DesactivarColor($color, $modelo);
        print_r("correcto");
    }

    public function SeleccionColor() {
        $this->load->model("Modeloadministrador");
        $color = $this->input->post_get('color', TRUE);
        $modelo = $this->input->post_get('modelo', TRUE);
        $this->Modeloadministrador->SeleccionColor($color, $modelo);
        print_r("correcto");
    }

    public function NuevoColor() {
        $this->load->model("Modeloadministrador");
        $color = $this->input->post_get('color', TRUE);
        $modelo = $this->input->post_get('modelo', TRUE);
        $this->Modeloadministrador->NuevoColor($color, $modelo);
        print_r("correcto");
    }

    public function GuardarEmpleado() {
        $nombre = $this->input->post_get('nombre', TRUE);
        $apellidop = $this->input->post_get('apellidop', TRUE);
        $apellidom = $this->input->post_get('apellidom', TRUE);
        $nempleado = $this->input->post_get('nempleado', TRUE);
        $this->load->model("Modeloadministrador");
        $this->Modeloadministrador->GuardarEmpleado($nombre, $apellidop, $apellidom, $nempleado);
        redirect('administrador/CapturaPerfiles');
    }

    public function CrearUsuario() {
        $persona = $this->input->post_get('persona_id', TRUE);
        $this->load->model("Modeloadministrador");
        $this->Modeloadministrador->CrearUsuario($persona);
        redirect('administrador/ExpedienteUsuario?persona=' . $persona);
    }

    public function CancelarUsuario() {
        $persona = $this->input->post_get('persona_id', TRUE);
        $this->load->model("Modeloadministrador");
        $this->Modeloadministrador->CancelarUsuario($persona);
        redirect('administrador/ExpedienteUsuario?persona=' . $persona);
    }

    public function ActivarUsuario() {
        $persona = $this->input->post_get('persona_id', TRUE);
        $this->load->model("Modeloadministrador");
        $this->Modeloadministrador->ActivarUsuario($persona);
        redirect('administrador/ExpedienteUsuario?persona=' . $persona);
    }

    public function ConfiguracionMaximosMinimos() {
        $infoheader["titulo"] = "Configuración Máximos y Mínimos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("modelocedis");
        $infocontent["modelos"] = $this->modelocedis->ListaModelos();
        $this->load->view('administrador/ConfiguracionMaximosMinimos', $infocontent);

        $this->load->view('template/footerd', '');
    }

    public function InventarioCedis() {
        $infoheader["titulo"] = "Inventario Cedis: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("modelocedis");
        $infocontent["modelos"] = $this->modelocedis->ListaModelos();
        $this->load->view('administrador/InventarioCedis', $infocontent);

        $this->load->view('template/footerd', '');
    }

    public function CargaInfoModelo() {
        $modelo = $this->input->post_get('modelo_id', TRUE);
        $this->load->model("Modeloadministrador");
        $infocontent["modelo"] = $this->Modeloadministrador->ObtenerModelo($modelo);
        $this->load->view('administrador/CargaInfoModelo', $infocontent);
    }

    public function InventarioCedisTarimas() {
        $infoheader["titulo"] = "Inventario Cedis Tarimas: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modeloadministrador");
        $infocontent["tarimas"] = $this->Modeloadministrador->ObtenerTarimasCedis();
        $infocontent["productos"] = $this->Modeloadministrador->ObtenerProdSinTarimaCedis();
        $this->load->view('administrador/InventarioCedisTarimas', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ExpedienteTarima() {
        $infoheader["titulo"] = "Almacén: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $tarima_id = $this->input->post_get('tarima_id', TRUE);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modeloalmacenista");
        $infocontent["producto"] = $this->Modeloalmacenista->ObtenerProducto($tarima_id);
        $infocontent["historiales"] = $this->Modeloalmacenista->HistorialMovimientosTarima($tarima_id);
        $infocontent["ubicacion"] = $this->Modeloalmacenista->Ubicacion($tarima_id);
        /* $infocontent["tarima"] = $this->ModeloClasificador->EstatusTarima($producto_id);
          $infocontent["tarimaid"] = $this->ModeloClasificador->EstatusTarimaId($producto_id);
          $infocontent["clasificaciones"] = $this->ModeloClasificador->ClasificacionesProducto($producto_id);
          $infocontent["entarimados"] = $this->ModeloClasificador->EntarimadosProducto($producto_id); */
        $infocontent["codigo"] = $this->Modeloalmacenista->CodigoBarrasTarimaTexto($tarima_id);
        $this->load->view('almacenista/ExpedienteTarima', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function barcodevista($filepath = "", $text = "", $size = "20", $orientation = "horizontal", $code_type = "code128", $print = false, $SizeFactor = 1) {
        $text = $this->input->post_get('text', TRUE);
        $code_string = "";
        // Translate the $text into barcode the correct $code_type
        if (in_array(strtolower($code_type), array("code128", "code128b"))) {
            $chksum = 104;
            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $code_array = array(" " => "212222", "!" => "222122", "\"" => "222221", "#" => "121223", "$" => "121322", "%" => "131222", "&" => "122213", "'" => "122312", "(" => "132212", ")" => "221213", "*" => "221312", "+" => "231212", "," => "112232", "-" => "122132", "." => "122231", "/" => "113222", "0" => "123122", "1" => "123221", "2" => "223211", "3" => "221132", "4" => "221231", "5" => "213212", "6" => "223112", "7" => "312131", "8" => "311222", "9" => "321122", ":" => "321221", ";" => "312212", "<" => "322112", "=" => "322211", ">" => "212123", "?" => "212321", "@" => "232121", "A" => "111323", "B" => "131123", "C" => "131321", "D" => "112313", "E" => "132113", "F" => "132311", "G" => "211313", "H" => "231113", "I" => "231311", "J" => "112133", "K" => "112331", "L" => "132131", "M" => "113123", "N" => "113321", "O" => "133121", "P" => "313121", "Q" => "211331", "R" => "231131", "S" => "213113", "T" => "213311", "U" => "213131", "V" => "311123", "W" => "311321", "X" => "331121", "Y" => "312113", "Z" => "312311", "[" => "332111", "\\" => "314111", "]" => "221411", "^" => "431111", "_" => "111224", "\`" => "111422", "a" => "121124", "b" => "121421", "c" => "141122", "d" => "141221", "e" => "112214", "f" => "112412", "g" => "122114", "h" => "122411", "i" => "142112", "j" => "142211", "k" => "241211", "l" => "221114", "m" => "413111", "n" => "241112", "o" => "134111", "p" => "111242", "q" => "121142", "r" => "121241", "s" => "114212", "t" => "124112", "u" => "124211", "v" => "411212", "w" => "421112", "x" => "421211", "y" => "212141", "z" => "214121", "{" => "412121", "|" => "111143", "}" => "111341", "~" => "131141", "DEL" => "114113", "FNC 3" => "114311", "FNC 2" => "411113", "SHIFT" => "411311", "CODE C" => "113141", "FNC 4" => "114131", "CODE A" => "311141", "FNC 1" => "411131", "Start A" => "211412", "Start B" => "211214", "Start C" => "211232", "Stop" => "2331112");
            $code_keys = array_keys($code_array);
            $code_values = array_flip($code_keys);
            for ($X = 1; $X <= strlen($text); $X++) {
                $activeKey = substr($text, ($X - 1), 1);
                $code_string .= $code_array[$activeKey];
                $chksum = ($chksum + ($code_values[$activeKey] * $X));
            }
            $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

            $code_string = "211214" . $code_string . "2331112";
        } elseif (strtolower($code_type) == "code128a") {
            $chksum = 103;
            $text = strtoupper($text); // Code 128A doesn't support lower case
            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $code_array = array(" " => "212222", "!" => "222122", "\"" => "222221", "#" => "121223", "$" => "121322", "%" => "131222", "&" => "122213", "'" => "122312", "(" => "132212", ")" => "221213", "*" => "221312", "+" => "231212", "," => "112232", "-" => "122132", "." => "122231", "/" => "113222", "0" => "123122", "1" => "123221", "2" => "223211", "3" => "221132", "4" => "221231", "5" => "213212", "6" => "223112", "7" => "312131", "8" => "311222", "9" => "321122", ":" => "321221", ";" => "312212", "<" => "322112", "=" => "322211", ">" => "212123", "?" => "212321", "@" => "232121", "A" => "111323", "B" => "131123", "C" => "131321", "D" => "112313", "E" => "132113", "F" => "132311", "G" => "211313", "H" => "231113", "I" => "231311", "J" => "112133", "K" => "112331", "L" => "132131", "M" => "113123", "N" => "113321", "O" => "133121", "P" => "313121", "Q" => "211331", "R" => "231131", "S" => "213113", "T" => "213311", "U" => "213131", "V" => "311123", "W" => "311321", "X" => "331121", "Y" => "312113", "Z" => "312311", "[" => "332111", "\\" => "314111", "]" => "221411", "^" => "431111", "_" => "111224", "NUL" => "111422", "SOH" => "121124", "STX" => "121421", "ETX" => "141122", "EOT" => "141221", "ENQ" => "112214", "ACK" => "112412", "BEL" => "122114", "BS" => "122411", "HT" => "142112", "LF" => "142211", "VT" => "241211", "FF" => "221114", "CR" => "413111", "SO" => "241112", "SI" => "134111", "DLE" => "111242", "DC1" => "121142", "DC2" => "121241", "DC3" => "114212", "DC4" => "124112", "NAK" => "124211", "SYN" => "411212", "ETB" => "421112", "CAN" => "421211", "EM" => "212141", "SUB" => "214121", "ESC" => "412121", "FS" => "111143", "GS" => "111341", "RS" => "131141", "US" => "114113", "FNC 3" => "114311", "FNC 2" => "411113", "SHIFT" => "411311", "CODE C" => "113141", "CODE B" => "114131", "FNC 4" => "311141", "FNC 1" => "411131", "Start A" => "211412", "Start B" => "211214", "Start C" => "211232", "Stop" => "2331112");
            $code_keys = array_keys($code_array);
            $code_values = array_flip($code_keys);
            for ($X = 1; $X <= strlen($text); $X++) {
                $activeKey = substr($text, ($X - 1), 1);
                $code_string .= $code_array[$activeKey];
                $chksum = ($chksum + ($code_values[$activeKey] * $X));
            }
            $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

            $code_string = "211412" . $code_string . "2331112";
        } elseif (strtolower($code_type) == "code39") {
            $code_array = array("0" => "111221211", "1" => "211211112", "2" => "112211112", "3" => "212211111", "4" => "111221112", "5" => "211221111", "6" => "112221111", "7" => "111211212", "8" => "211211211", "9" => "112211211", "A" => "211112112", "B" => "112112112", "C" => "212112111", "D" => "111122112", "E" => "211122111", "F" => "112122111", "G" => "111112212", "H" => "211112211", "I" => "112112211", "J" => "111122211", "K" => "211111122", "L" => "112111122", "M" => "212111121", "N" => "111121122", "O" => "211121121", "P" => "112121121", "Q" => "111111222", "R" => "211111221", "S" => "112111221", "T" => "111121221", "U" => "221111112", "V" => "122111112", "W" => "222111111", "X" => "121121112", "Y" => "221121111", "Z" => "122121111", "-" => "121111212", "." => "221111211", " " => "122111211", "$" => "121212111", "/" => "121211121", "+" => "121112121", "%" => "111212121", "*" => "121121211");

            // Convert to uppercase
            $upper_text = strtoupper($text);

            for ($X = 1; $X <= strlen($upper_text); $X++) {
                $code_string .= $code_array[substr($upper_text, ($X - 1), 1)] . "1";
            }

            $code_string = "1211212111" . $code_string . "121121211";
        } elseif (strtolower($code_type) == "code25") {
            $code_array1 = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
            $code_array2 = array("3-1-1-1-3", "1-3-1-1-3", "3-3-1-1-1", "1-1-3-1-3", "3-1-3-1-1", "1-3-3-1-1", "1-1-1-3-3", "3-1-1-3-1", "1-3-1-3-1", "1-1-3-3-1");

            for ($X = 1; $X <= strlen($text); $X++) {
                for ($Y = 0; $Y < count($code_array1); $Y++) {
                    if (substr($text, ($X - 1), 1) == $code_array1[$Y])
                        $temp[$X] = $code_array2[$Y];
                }
            }

            for ($X = 1; $X <= strlen($text); $X += 2) {
                if (isset($temp[$X]) && isset($temp[($X + 1)])) {
                    $temp1 = explode("-", $temp[$X]);
                    $temp2 = explode("-", $temp[($X + 1)]);
                    for ($Y = 0; $Y < count($temp1); $Y++)
                        $code_string .= $temp1[$Y] . $temp2[$Y];
                }
            }

            $code_string = "1111" . $code_string . "311";
        } elseif (strtolower($code_type) == "codabar") {
            $code_array1 = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "-", "$", ":", "/", ".", "+", "A", "B", "C", "D");
            $code_array2 = array("1111221", "1112112", "2211111", "1121121", "2111121", "1211112", "1211211", "1221111", "2112111", "1111122", "1112211", "1122111", "2111212", "2121112", "2121211", "1121212", "1122121", "1212112", "1112122", "1112221");

            // Convert to uppercase
            $upper_text = strtoupper($text);

            for ($X = 1; $X <= strlen($upper_text); $X++) {
                for ($Y = 0; $Y < count($code_array1); $Y++) {
                    if (substr($upper_text, ($X - 1), 1) == $code_array1[$Y])
                        $code_string .= $code_array2[$Y] . "1";
                }
            }
            $code_string = "11221211" . $code_string . "1122121";
        }

        // Pad the edges of the barcode
        $code_length = 20;
        if ($print) {
            $text_height = 30;
        } else {
            $text_height = 0;
        }

        for ($i = 1; $i <= strlen($code_string); $i++) {
            $code_length = $code_length + (integer) (substr($code_string, ($i - 1), 1));
        }

        if (strtolower($orientation) == "horizontal") {
            $img_width = $code_length * $SizeFactor;
            $img_height = $size;
        } else {
            $img_width = $size;
            $img_height = $code_length * $SizeFactor;
        }

        $image = imagecreate($img_width, $img_height + $text_height);
        $black = imagecolorallocate($image, 0, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);

        imagefill($image, 0, 0, $white);
        if ($print) {
            imagestring($image, 5, 31, $img_height, $text, $black);
        }

        $location = 10;
        for ($position = 1; $position <= strlen($code_string); $position++) {
            $cur_size = $location + ( substr($code_string, ($position - 1), 1) );
            if (strtolower($orientation) == "horizontal")
                imagefilledrectangle($image, $location * $SizeFactor, 0, $cur_size * $SizeFactor, $img_height, ($position % 2 == 0 ? $white : $black));
            else
                imagefilledrectangle($image, 0, $location * $SizeFactor, $img_width, $cur_size * $SizeFactor, ($position % 2 == 0 ? $white : $black));
            $location = $cur_size;
        }

        // Draw barcode to the screen or save in a file
        if ($filepath == "") {
            header('Content-type: image/png');
            imagepng($image);
            imagedestroy($image);
        } else {
            imagepng($image, $filepath);
            imagedestroy($image);
        }
    }

    public function Cambios() {
        $infoheader["titulo"] = "Cambios Sistema: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('administrador/Cambios');
        $this->load->view('template/footerd', '');
    }

}
