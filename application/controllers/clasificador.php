<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clasificador extends CI_Controller {

    function __construct() {
        parent::__construct();
        //$this->load->helper("cadenas");
    }

    public function index() {
        $infoheader["titulo"] = "Clasificador: Royalty Ceramic";
        $infocontent["Nombre"] = "Tania Torres";
        $infocontent["hoy"] = date("d/m/Y");
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('clasificador/index', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ObtenerHornos() {
        $d = $this->input->post_get('d', TRUE);
        $this->load->model("modeloclasificador");
        $infocontent["hornos"] = $this->modeloclasificador->ListaHornos($this->FechaIngles($d));
        $infocontent["d"] = $this->FechaIngles($d);
        $this->load->view('clasificador/ObtenerHornos', $infocontent);
        // $dia = $this->FechaIngles($d);
//        $hornos = \Models\Hornos::ListaHornosDia($dia);
//        $arreglo = [
//            "hornos" => $hornos,
//            "dia" => $dia
//        ];
//        return $arreglo;
    }

    public function FechaIngles($date) {
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

    public function ObtenerProductos() {

        $horno = $this->input->post_get('horno', TRUE);
        $fecha = $this->input->post_get('fecha', TRUE);
        $this->load->model("modeloclasificador");
        $infocontent["productos"] = $this->modeloclasificador->ListaProductos($this->FechaIngles($fecha), $horno);
        $infocontent["dia"] = $fecha;
        $infocontent["horno"] = $horno;
        $this->load->view('clasificador/ObtenerProductos', $infocontent);
    }

    public function ObtenerModelos() {
        $horno = $this->input->post_get('horno', TRUE);
        $fecha = $this->input->post_get('fecha', TRUE);
        $cprod = $this->input->post_get('cprod', TRUE);
        $this->load->model("modeloclasificador");
        $infocontent["modelos"] = $this->modeloclasificador->ListaModelos($this->FechaIngles($fecha), $horno, $cprod);
        $infocontent["dia"] = $fecha;
        $infocontent["cprod"] = $cprod;
        $infocontent["horno"] = $horno;
        $this->load->view('clasificador/ObtenerModelos', $infocontent);
//        $horno = $_REQUEST["horno"];
//        $fecha = $_REQUEST["fecha"];
//        $cprod = $_REQUEST["cprod"];
//        $dia = $this->FechaIngles($fecha);
//        $modelos = \Models\Productos::ListarModelosHornoFechaProducto($dia, $horno, $cprod);
//        $arreglo = [
//            "dia" => $dia,
//            "modelos" => $modelos
//        ];
//        return $arreglo;
    }

    public function CargarColoresClasificacion() {
//        $horno = $_REQUEST["horno"];
//        $fecha = $_REQUEST["fecha"];
//        $cprod = $_REQUEST["cprod"];
//        $mod = $_REQUEST["mod"];
//        $dia = $this->FechaIngles($fecha);
//        $colores = \Models\Productos::ListarColoresClasificacion($dia, $horno, $cprod, $mod);
//        $arreglo = [
//            "dia" => $dia,
//            "colores" => $colores,
//            "cprod" => $cprod
//        ];
//        return $arreglo;
    }

    public function TablaProductos() {
//        $horno = $_REQUEST["horno"];
//        $fecha = $_REQUEST["fecha"];
//        $cprod = $_REQUEST["cprod"];
//        $mod = $_REQUEST["mod"];
//        $color = $_REQUEST["color"];
//        $dia = $this->FechaIngles($fecha);
//        $catdefectos = \Models\FuncionesUsuario::CategoriasDefectos();
//        $productos = \Models\Productos::ProductosSeleccion($dia, $horno, $cprod, $mod, $color);
//        $arreglo = [
//            "dia" => $dia,
//            "productos" => $productos,
//            "mod" => $mod,
//            "cprod" => $cprod,
//            "catdefectos" => $catdefectos,
//        ];
//        return $arreglo;
    }

    public function CargarDefectos() {
//        $idcat = $_REQUEST["cat_id"];
//        $idprod = $_REQUEST["idprod"];
//        $defectos = \Models\FuncionesUsuario::ListarDefectos($idcat);
//        $arreglo = [
//            "defectos" => $defectos,
//            "idprod" => $idprod,
//        ];
//        return $arreglo;
    }

}
