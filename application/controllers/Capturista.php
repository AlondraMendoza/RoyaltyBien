<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Capturista extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Modelousuario");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        }
        $id = $this->session->userdata('id');
        if (!$this->Modelousuario->TienePerfil($id, 2)) {
            redirect('usuario/logueado');
        }
    }

    public function index() {
        $datos["nombre"] = "Cadena ejemplo";
        $datos["apellido"] = "Cadena ejemplo 2";
        $this->load->view('capturista/index', $datos);
    }

    public function VerificarClave() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modelocapturista");
        $fila = $this->Modelocapturista->BuscarClave($clave);
        $infocontent["nombrec"] = "No se encontró la persona";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombrec"] = $fila->Nombre . " " . $fila->APaterno . " " . $fila->AMaterno;
            $infocontent["idpu"] = $fila->IdPuestos;
        }
        print json_encode($infocontent);
    }

    public function capturaCarro() {
        $infoheader["titulo"] = "Capturista: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modelocapturista");
        $infocontent["hornos"] = $this->Modelocapturista->ListarHornos();
        $infocontent["carros"] = $this->Modelocapturista->ListarCarros();
        $infocontent["productos"] = $this->Modelocapturista->ListarProductos();
        $this->load->view('capturista/capturaCarro', $infocontent);
        $this->load->view('template/footerd', '');

    }

    public function capturaAccesorios() {
        $infoheader["titulo"] = "Capturista: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modelocapturista");
        $this->load->view('capturista/capturaAccesorios', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ObtenerModelos() {
        $id = $this->input->post_get('id', TRUE);
        $this->load->model("Modelocapturista");
        $infocontent["modelos"] = $this->Modelocapturista->ListarModelos($id);
        $this->load->view('capturista/ObtenerModelos', $infocontent);

    }

    public function ObtenerColores() {
        $id = $this->input->post_get('id', TRUE);
        $this->load->model("Modelocapturista");
        $infocontent["colores"] = $this->Modelocapturista->ListarColores($id);
        $this->load->view('capturista/ObtenerColores', $infocontent);

    }

    public static function FechaIngles($date) {
        if ($date) {
            $fecha = $date;
            $hora = "";

            # separamos la fecha recibida por el espacio de separación entre
            # la fecha y la hora
            $fechaHora = explode(" ", $date);
            if (count($fechaHora) == 2) {
                $fecha = $fechaHora[0];
                $hora = $fechaHora[1];
            }

            # cogemos los valores de la fecha
            $values = preg_split('/(\/|-)/', $fecha);
            if (count($values) == 3) {
                # devolvemos la fecha en formato ingles
                if ($hora && count(explode(":", $hora)) == 3) {
                    # si la hora esta separada por : y hay tres valores...
                    $hora = explode(":", $hora);
                    return date("Ymd H:i:s", mktime($hora[0], $hora[1], $hora[2], $values[1], $values[0], $values[2]));
                } else {
                    return date("Ymd", mktime(0, 0, 0, $values[1], $values[0], $values[2]));
                }
            }
        }
        return "";
    }

    public function Resultados() {
        $carro = $this->input->post_get('carro', TRUE);
        $horno = $this->input->post_get('horno', TRUE);
        $prod = $this->input->post_get('prod', TRUE);
        $mod = $this->input->post_get('mod', TRUE);
        $col = $this->input->post_get('col', TRUE);
        $piezas = $this->input->post_get('piezas', TRUE);
        $fecha = $this->input->post_get('fecha', TRUE);
        $hornero = $this->input->post_get('hornero', TRUE);
        $this->load->model("Modelocapturista");
        $infocontent["lista"] = $this->Modelocapturista->ListarProductosGuardados($carro, $horno, $prod, $mod, $col, $piezas, $this->FechaIngles($fecha), $hornero);
        //$infocontent["prod"] = $this->Modelocapturista->ObtenerProductoId($infocontent["id"]);
        $this->load->view('capturista/Resultados', $infocontent);
    }

    public function ResultadosAccesorios() {
        $fecha = $this->input->post_get('fecha', TRUE);
        $this->load->model("Modelocapturista");
        $infocontent["lista"] = $this->Modelocapturista->ListarAccesoriosGuardados($this->FechaIngles($fecha));
        $this->load->view('capturista/ResultadosAccesorios', $infocontent);
    }
    
    public function ReporteQuemado() {
        $this->load->model("Modelocapturista");
        $infoheader["titulo"] = "Capturista: Royalty Ceramic";
        $infocontent["Nombre"] = "";
        $infocontent["hoy"] = date("d/m/Y");
        $infocontent["hornos"] = $this->Modelocapturista->Hornos();
        $infocontent["productos"] = $this->Modelocapturista->ProductosQuemado();
        $infocontent["modelos"] = $this->Modelocapturista->ModelosQuemado(0);
        $infocontent["colores"] = $this->Modelocapturista->Colores(0);
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('capturista/ReporteQuemado', $infocontent);
        $this->load->view('template/footerd', '');
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
        $this->load->model("Modelocapturista");
        $infocontent["productos"] = $this->Modelocapturista->GenerarReporteQ($fechainicio, $fechafin, $ahornos, $aproducto, $amodelo, $acolor);
        $this->load->view('capturista/GenerarReporteQ', $infocontent);
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
        $this->load->model("Modelocapturista");
        $infocontent["por"] = $por;
        $infocontent["productos"] = $this->Modelocapturista->GenerarConcentradoQ($fechainicio, $fechafin, $ahornos, $aproducto, $amodelo, $acolor, $por);
        $this->load->view('capturista/GenerarConcentradoQ', $infocontent);
    }

}
