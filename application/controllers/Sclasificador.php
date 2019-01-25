<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sclasificador extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Modelousuario");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        }
        $id = $this->session->userdata('id');
        if (!$this->Modelousuario->TienePerfil($id, 5)) {
            redirect('usuario/logueado');
        }
        $this->load->database();
    }

    public function CapturaDevolucion() {
        $this->load->model("Modeloclasificador");
        $infoheader["titulo"] = "Clasificador: Royalty Ceramic";

        $this->load->view('template/headerd', $infoheader);
        $this->load->view('sclasificador/CapturaDevolucion', '');
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
    public function VerificarClaveProdReimprimir() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modeloclasificador");
        $fila = $this->Modeloclasificador->BuscarClaveProducto($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
            $infocontent["codigo"] = $this->CodigoBarras($fila->IdProductos);
        }
        print json_encode($infocontent);
    }
    public function Devoluciones() {
        $infoheader["titulo"] = "Devoluciones: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $infocontent["hoy"] = date("d/m/Y");
        $this->load->view('sclasificador/Devoluciones', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function DevolucionesCapturadas() {
        $fechainicio = $this->input->post_get('fechainicio', TRUE);
        $fechafin = $this->input->post_get('fechafin', TRUE);
        $this->load->model("Modelocedis");
        $infocontent["devolucionescapturadas"] = $this->Modelocedis->DevolucionesCapturadas($fechainicio, $fechafin);
        $this->load->view('sclasificador/DevolucionesCapturadas', $infocontent);
    }

    public function VerificarSubproducto() {
        $id = $this->input->post_get('sub_id', TRUE);
        $valor = $this->input->post_get('valor', TRUE);
        $this->db->set("Verificado", $valor);
        $this->db->where("IdSubproductosDevoluciones", $id);
        $this->db->update("SubproductosDevoluciones");
        print("correcto");
    }

    public function ProcesarDevolucion() {
        $id = $this->input->post_get('dev_id', TRUE);
        $this->db->set("VerificadaSupervisor", "Si");
        $this->db->where("IdDevoluciones", $id);
        $this->db->update("Devoluciones");
        print("correcto");
    }

    public function EntradaSubproductos() {
        $infoheader["titulo"] = "Almacén de SubProductos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modeloalmacenista");
        $infocontent["griferia"] = $this->Modeloalmacenista->ListarGriferia();
        $this->load->view('sclasificador/EntradaSubproductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function SalidaSubproductos() {
        $infoheader["titulo"] = "Almacén de SubProductos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modeloalmacenista");
        $infocontent["griferia"] = $this->Modeloalmacenista->ListarGriferia();
        $this->load->view('sclasificador/SalidaSubproductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function AlmacenSubproductos() {
        $infoheader["titulo"] = "Almacén de SubProductos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modeloalmacenista");
        $infocontent["subproductosdetalle"] = $this->Modeloalmacenista->ListarSubproductosDetalle();
        $infocontent["subproductosunicos"] = $this->Modeloalmacenista->ListarSubproductosUnicos();
        $this->load->view('sclasificador/AlmacenSubproductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function VerificarClave() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modeloalmacenista");
        $fila = $this->Modeloalmacenista->BuscarClaveSubproductos($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->Descripcion;
            $infocontent["id"] = $fila->IdCGriferia;
        }
        print json_encode($infocontent);
    }
   
    public function CodigoBarras($id)
    {
        $this->load->model("Modelosclasificador");
        $producto = $this->Modeloclasificador->ObtenerProducto($id);
        return date_format(date_create($producto->FechaCaptura), 'dmY').str_pad($producto->IdProductos, 10, '0', STR_PAD_LEFT);
    }
    public function GuardarSubproductosAlmacen() {
    $id = $this->input->post_get('idsubproducto', TRUE);
    $cantidad = $this->input->post_get('cantidad', TRUE);
    $this->load->model("Modeloalmacenista");
    $id = $this->Modeloalmacenista->GuardarSubproducto($id, $cantidad);
    if ($id != null) {
    print("correcto");
    } else {
    return "error";
    }
    }

    public function GuardarSalidaSubproductos() {
    $id = $this->input->post_get('id', TRUE);
    $cantidad = $this->input->post_get('cantidad', TRUE);
    $this->load->model("Modeloalmacenista");
    $resp = $this->Modeloalmacenista->SalidaSub($id, $cantidad);
    if ($resp == "correcto") {
    print("Correcto");
    } else {
    print("Error");
    }
    }

    public function VerificarClaveExistencia() {
    $clave = $this->input->post_get('clave', TRUE);
    $this->load->model("Modeloalmacenista");
    $fila = $this->Modeloalmacenista->BuscarClaveSubproductos($clave);
    $infocontent["nombre"] = "No se encontró el producto";
    if ($fila != "No se encontró el producto") {
    $infocontent["nombre"] = $fila->Descripcion;
    $infocontent["id"] = $fila->IdCGriferia;
    $data = $this->Modeloalmacenista->ExistenciasSubproductos($fila->IdCGriferia);
    $infocontent["existencia"] = $data;
    }
    print json_encode($infocontent);
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

    public function ClasificacionesTrabajador() {
    $this->load->model("Modeloclasificador");
    $infoheader["titulo"] = "Administrador: Royalty Ceramic";
    $infocontent["hoy"] = date("d/m/Y");
    $this->load->view('template/headerd', $infoheader);
    $this->load->view('sclasificador/ClasificacionesTrabajador', $infocontent);
    $this->load->view('template/footerd', '');
    }

    public function CargarClasificacionesTrabajador() {
    $fechainicio = $this->input->post_get('fechainicio', TRUE);
    $fechafin = $this->input->post_get('fechafin', TRUE);
    $this->load->model("Modelosclasificador");
    $infocontent["clasificaciones"] = $this->Modelosclasificador->ConsultarClasificacionesTrabajador($fechainicio,
    $fechafin);
    $this->load->view('sclasificador/CargarClasificacionesTrabajador', $infocontent);
    }
    public function ReimprimirEtiqueta(){
    $infoheader["titulo"] = "Reimprimir Etiqueta";
    $infocontent["hoy"] = date("d/m/Y");
    $this->load->view('template/headerd', $infoheader);
    $this->load->view('sclasificador/ReimprimirEtiqueta');
    $this->load->view('template/footerd', '');
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

            /* Aquí se agrega el texto a la etiqueta*/
            
            $agregado="";
            if (strpos($text, '*') !== false) {
                $agregado="           TARIMA";
            }
            else{
                $this->load->model("Modeloclasificador");
                $producto=$this->Modeloclasificador->ObtenerProducto($id);
                $agregado=$producto->NombreProducto." | ".$producto->Modelo." | ".$producto->Color;
            }
            
            imagettftext($image, 22, 0, 440, $img_height + $text_height + 10, $black, $font, $text."\n".$agregado);

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
}