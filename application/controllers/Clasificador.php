<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clasificador extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Modelousuario");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        }
        $id = $this->session->userdata('id');
        if (!$this->Modelousuario->TienePerfil($id, 1)) {
            redirect('usuario/logueado');
        }
    }

    public function index() {
        $this->load->model("Modeloclasificador");
        $infoheader["titulo"] = "Clasificador: Royalty Ceramic";
        $infocontent["Nombre"] = "Tania Torres";
        $infocontent["hoy"] = date("d/m/Y");
        $infocontent["hoyingles"] = date("m/d/Y");
        $infocontent["colores"] = $this->Modeloclasificador->ListaTodosColores();
        $infocontent["clasificaciones"] = $this->Modeloclasificador->Clasificaciones();
        //$infocontent["hornos"] = $this->Modeloclasificador->ListaHornos($this->FechaIngles(date("d/m/Y")));
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('clasificador/index', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ObtenerHornos() {
        $d = $this->input->post_get('d', TRUE);
        $this->load->model("Modeloclasificador");
        $infocontent["hornos"] = $this->Modeloclasificador->ListaHornos($this->FechaIngles($d));
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
        $this->load->model("Modeloclasificador");
        $infocontent["productos"] = $this->Modeloclasificador->ListaProductos($this->FechaIngles($fecha), $horno);
        $infocontent["dia"] = $fecha;
        $infocontent["horno"] = $horno;
        $this->load->view('clasificador/ObtenerProductos', $infocontent);
    }

    public function ObtenerModelos() {
        $horno = $this->input->post_get('horno', TRUE);
        $fecha = $this->input->post_get('fecha', TRUE);
        $cprod = $this->input->post_get('cprod', TRUE);
        $this->load->model("Modeloclasificador");
        //print_r($cprod . ' ' . $horno . ' ' . $fecha);
        $infocontent["modelos"] = $this->Modeloclasificador->ListaModelos($this->FechaIngles($fecha), $horno, $cprod);
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

    public function ObtenerColores() {
        $horno = $this->input->post_get('horno', TRUE);
        $fecha = $this->input->post_get('fecha', TRUE);
        $cprod = $this->input->post_get('cprod', TRUE);
        $mod = $this->input->post_get('mod', TRUE);

//        $mod = $_REQUEST["mod"];
//        $dia = $this->FechaIngles($fecha);
        $this->load->model("Modeloclasificador");
        $infocontent["colores"] = $this->Modeloclasificador->ListaColores($this->FechaIngles($fecha), $horno, $cprod, $mod);
        $infocontent["dia"] = $fecha;
        $infocontent["cprod"] = $cprod;
        $infocontent["horno"] = $horno;
        $infocontent["mod"] = $mod;
        $this->load->view('clasificador/ObtenerColores', $infocontent);
        // $colores = \Models\Productos::ListarColoresClasificacion($dia, $horno, $cprod, $mod);
//        $arreglo = [
//            "dia" => $dia,
//            "colores" => $colores,
//            "cprod" => $cprod
//        ];
//        return $arreglo;
    }

    public function GuardarClasificacion() {
        $idclasi = $this->input->post_get('idclasi', TRUE);
        $idprod = $this->input->post_get('idprod', TRUE);
        $defecto1 = $this->input->post_get('defecto1', TRUE);
        $defecto2 = $this->input->post_get('defecto2', TRUE);
        $puestodefecto2 = $this->input->post_get('puestodefecto2', TRUE);
        $puestodefecto1 = $this->input->post_get('puestodefecto1', TRUE);
        $fueratono = $this->input->post_get('fueratono', TRUE);
        $this->load->model("Modeloclasificador");
        $idclasificacion = $this->Modeloclasificador->GuardarClasificacion($idprod, $idclasi, $fueratono);
        $this->Modeloclasificador->GuardarDefectos($defecto1, $puestodefecto1, $defecto2, $puestodefecto2, $idclasificacion);
        print("correcto");
    }

    public function TablaProductos() {
        $horno = $this->input->post_get('horno', TRUE);
        $fecha = $this->input->post_get('fecha', TRUE);
        $cprod = $this->input->post_get('cprod', TRUE);
        $mod = $this->input->post_get('mod', TRUE);
        $color = $this->input->post_get('color', TRUE);
        $this->load->model("Modeloclasificador");
        $infocontent["mod"] = $mod;
        $infocontent["cprod"] = $cprod;
        $infocontent["color"] = $color;
        $infocontent["clasificaciones"] = $this->Modeloclasificador->Clasificaciones();
        $infocontent["productos"] = $this->Modeloclasificador->ProductosSeleccion($this->FechaIngles($fecha), $horno, $cprod, $mod, $color);
        $this->load->view('clasificador/TablaProductos', $infocontent);
    }

    public function TablaProductosReclasificar() {
        $producto = $this->input->post_get('producto', TRUE);
        $this->load->model("Modeloclasificador");
        $infocontent["clasificaciones"] = $this->Modeloclasificador->Clasificaciones();
        $infocontent["producto"] = $this->Modeloclasificador->ProductosSeleccionReclasificar($producto);
        $this->load->view('clasificador/TablaProductosReclasificar', $infocontent);
    }

    public function GuardarProcesado() {
        $producto = $this->input->post_get('producto', TRUE);
        $this->load->model("Modeloclasificador");
        $this->Modeloclasificador->GuardarProcesado($producto);
    }

    public function CargarDefectos() {
        $idcat = $this->input->post_get('cat_id', TRUE);
        $idprod = $this->input->post_get('idprod', TRUE);
        $ndef = $this->input->post_get('ndef', TRUE);
        $infocontent["idprod"] = $idprod;
        $infocontent["ndef"] = $ndef;
        $this->load->model("Modeloclasificador");
        $infocontent["defectos"] = $this->Modeloclasificador->ListarDefectos($idcat);
        $this->load->view('clasificador/CargarDefectos', $infocontent);
    }

    public function VerificarEmpleado() {
        $clave = $this->input->post_get('clave', TRUE);
        $categoria = $this->input->post_get('categoria', TRUE);
        $this->load->model("Modeloclasificador");
        $fila = $this->Modeloclasificador->BuscarClavePuesto($clave, $categoria);
        $infocontent["nombre"] = "No se encontró trabajador";
        if ($fila != "No se encontró trabajador") {
            $infocontent["nombre"] = $fila->Nombre . ' ' . $fila->APaterno . ' ' . $fila->AMaterno;
            $infocontent["puesto_id"] = $fila->IdPuestos;
        }
        print json_encode($infocontent);
    }

    public function barcode($filepath = "", $text = "", $size = "20", $orientation = "horizontal", $code_type = "code128", $print = false, $SizeFactor = 1) {
        //$text = $this->input->post_get('text', TRUE);
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

    public function barcodeventana($filepath = "", $text = "", $size = "100", $orientation = "horizontal", $code_type = "code128", $print = true, $SizeFactor = 4.5) {
        $text = $this->input->post_get('text', TRUE);
        $id = substr($text, 9, 19);

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
        $image = imagecreate($img_width, $img_height + $text_height + 120);
        $black = imagecolorallocate($image, 0, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);

        imagefill($image, 0, 0, $white);
        if ($print) {
            //imagestring($image, 5, 441, $img_height, $text, $black);
            $font = "fonts/arial.ttf";
            /* Aquí se agrega el texto a la etiqueta */
            imagettftext($image, 30, 0, 350, $img_height + $text_height + 10, $black, $font, $text . "\n.$id");
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

    public function EnviarTicket() {
        $codigo = $this->input->post_get('codigo', TRUE);
        $producto_id = $this->input->post_get('producto_id', TRUE);
        $this->barcodeventana('codigos/codigo' . $producto_id . '.png', $codigo, 550, 'vertical', 'code128', true);
//        $this->load->library('AutoPrint');
//        $this->autoprint = new AutoPrint();
//        $this->autoprint->AddPage();
//        $this->autoprint->SetFont('Arial', '', 20);
//        //$this->autoprint->Cell(25, 20, 'GRADO', 'TB', 0, 'L', '1');
//        $this->barcode('codigos/codigo' . $producto_id . '.png', $codigo, 250, 'vertical', 'code128', true);
//        $this->autoprint->Image("codigos/codigo" . $producto_id . ".png", 6, 1, 170, 130);
//        //Open the print dialog
//        $this->autoprint->AutoPrint();
//        $this->autoprint->Output();
    }

    public function GuardarAccesorios() {
        $fueratono = $this->input->post_get('fueratonoaccesorio', TRUE);
        $iddefecto1 = $this->input->post_get('iddefecto1', TRUE);
        $iddefecto2 = $this->input->post_get('iddefecto2', TRUE);
        $colorseleccionado = $this->input->post_get('colorseleccionado', TRUE);
        $clavepuesto1 = $this->input->post_get('clavepuesto1', TRUE);
        $clavepuesto2 = $this->input->post_get('clavepuesto2', TRUE);
        $idclasi = $this->input->post_get('clasificacionseleccionada', TRUE);
        $this->load->model("Modeloclasificador");
        $idproducto = $this->Modeloclasificador->GuardarAccesorio($colorseleccionado);
        $idclasificacion = $this->Modeloclasificador->GuardarClasificacion($idproducto, $idclasi, $fueratono);
        $this->Modeloclasificador->GuardarDefectos($iddefecto1, $clavepuesto1, $iddefecto2, $clavepuesto2, $idclasificacion);
        print($idproducto);
    }

    public function EntradaProductos() {
        $infoheader["titulo"] = "Clasificación: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modeloclasificador");
        $infocontent["hoyingles"] = date("m/d/Y");
        $this->load->view('clasificador/EntradaProductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function VerificarClaveProd() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modeloclasificador");
        $fila = $this->Modeloclasificador->BuscarClaveProducto($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }

    public function GuardarTarima() {
        $this->load->model("Modeloclasificador");
        $idtarima = $this->Modeloclasificador->GuardarTarima();
        print($idtarima);
    }

    public function GuardarDetalleTarima() {
        $idproducto = $this->input->post_get('idproducto', TRUE);
        $idtarima = $this->input->post_get('idtarima', TRUE);
        $this->load->model("Modeloclasificador");
        $iddetalle = $this->Modeloclasificador->GuardarDetalleTarima($idproducto, $idtarima);
        if ($iddetalle == "existe") {
            print("Existe");
        } else if ($iddetalle != null) {
            print("Correcto");
        }
    }

    public function Reclasificar() {
        $infoheader["titulo"] = "Reclasificar: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modeloclasificador");
        $this->load->view('clasificador/Reclasificar', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ReclasificarGeneral() {
        $infoheader["titulo"] = "Reclasificar: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("modeloclasificador");
        $this->load->view('clasificador/ReclasificarGeneral', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function VerificarClaveProdDevoluciones() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modeloclasificador");
        $fila = $this->Modeloclasificador->BuscarClaveProductoDevoluciones($clave);
        $infocontent["nombre"] = "No se encontró el producto en devoluciones";
        if ($fila != "No se encontró el producto en devoluciones") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }

    public function Devoluciones() {
        $infoheader["titulo"] = "Devoluciones: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $infocontent["hoy"] = date("d/m/Y");
        $this->load->view('clasificador/Devoluciones', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function DevolucionesCapturadas() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $this->load->model("modelocedis");
        $infocontent["devolucionescapturadas"] = $this->modelocedis->DevolucionesCapturadas($fechainicio, $fechafin);
        $this->load->view('clasificador/DevolucionesCapturadas', $infocontent);
    }

    public function ReentarimarAccidentes() {
        $this->load->model("modeloclasificador");
        $infoheader["titulo"] = "Devoluciones: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $infocontent["accidentes"] = $this->modeloclasificador->AccidentesSinProcesar();
        $this->load->view('clasificador/ReentarimarAccidentes', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ExpedienteProducto() {
        $infoheader["titulo"] = "Reclasificar: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $producto_id = $this->input->post_get('producto_id', TRUE);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modeloclasificador");
        $this->load->model("Modelousuario");
        $infocontent["producto"] = $this->Modeloclasificador->ObtenerProducto($producto_id);
        $infocontent["historiales"] = $this->Modeloclasificador->HistorialMovimientosProducto($producto_id);
        $infocontent["ubicacion"] = $this->Modeloclasificador->Ubicacion($producto_id);
        $infocontent["clasificacion"] = $this->Modeloclasificador->Clasificacion($producto_id);
        $infocontent["tarima"] = $this->Modeloclasificador->EstatusTarima($producto_id);
        $infocontent["tarimaid"] = $this->Modeloclasificador->EstatusTarimaId($producto_id);
        $infocontent["pedido"] = $this->Modeloclasificador->EstatusPedido($producto_id);
        $infocontent["defectos"] = $this->Modelousuario->ObtenerDefectos($producto_id);
        $infocontent["clasificaciones"] = $this->Modeloclasificador->ClasificacionesProducto($producto_id);
        $infocontent["entarimados"] = $this->Modeloclasificador->EntarimadosProducto($producto_id);
        $infocontent["codigo"] = $this->Modeloclasificador->CodigoBarrasTexto($producto_id);
        $infocontent["reparacion"] = $this->Modeloclasificador->ObtenerReparaciones($producto_id);
        $this->load->view('clasificador/ExpedienteProducto', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function BusquedaProductos() {
        $infoheader["titulo"] = "Clasificación: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modeloclasificador");
        $this->load->view('clasificador/BusquedaProducto', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ClasificacionesFecha() {
        $this->load->model("Modeloclasificador");
        $infoheader["titulo"] = "Administrador: Royalty Ceramic";
        $infocontent["hoy"] = date("d/m/Y");
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('clasificador/ClasificacionesFecha', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function CargarClasificacionesFecha() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $this->load->model("Modeloclasificador");
        $infocontent["clasificaciones"] = $this->Modeloclasificador->ConsultarClasificacionesFecha($fechainicio, $fechafin);
        $this->load->view('clasificador/CargarClasificacionesFecha', $infocontent);
    }

    public function CargarClasificacionesFechaDetalle() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $producto = $this->input->post_get('producto', TRUE);
        $modelo = $this->input->post_get('modelo', TRUE);
        $color = $this->input->post_get('color', TRUE);
        $clasificacion = $this->input->post_get('clasificacion', TRUE);
        $this->load->model("Modeloclasificador");
        $infocontent["clasificaciones"] = $this->Modeloclasificador->ConsultarClasificacionesFechaDetalle($fechainicio, $fechafin, $producto, $modelo, $color, $clasificacion);
        $this->load->view('clasificador/CargarClasificacionesFechaDetalle', $infocontent);
    }

}
