<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Almacenista extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("modelousuario");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        }
        $id = $this->session->userdata('id');
        if (!$this->modelousuario->TienePerfil($id, 4)) {
            redirect('usuario/logueado');
        }
    }

    public function capturaGriferia() {
        $infoheader["titulo"] = "Almacén: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modeloalmacenista");
        $infocontent["griferia"] = $this->Modeloalmacenista->ListarGriferia();
        $this->load->view('almacenista/capturaGriferia', $infocontent);
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

    public function SalidaGriferia() {
        $infoheader["titulo"] = "Almacén: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modeloalmacenista");
        $infocontent["griferia"] = $this->Modeloalmacenista->ListarGriferia();
        $this->load->view('almacenista/SalidaGriferia', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function VerificarClave() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modeloalmacenista");
        $fila = $this->Modeloalmacenista->BuscarClave($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->Descripcion;
            $infocontent["id"] = $fila->IdCGriferia;
        }
        print json_encode($infocontent);
    }

    public function VerificarClaveExistencia() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modeloalmacenista");
        $fila = $this->Modeloalmacenista->BuscarClave($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->Descripcion;
            $infocontent["id"] = $fila->IdCGriferia;
            $data = $this->Modeloalmacenista->Existencias($fila->IdCGriferia);
            $infocontent["existencia"] = $data;
        }
        print json_encode($infocontent);
    }

    public function ResultadosGriferia() {
        $id = $this->input->post_get('id', TRUE);
        $cantidad = $this->input->post_get('cantidad', TRUE);
        $this->load->model("Modeloalmacenista");
        $infocontent["lista"] = $this->Modeloalmacenista->ListarGriferiaGuardada($id, $cantidad);
        $this->load->view('almacenista/ResultadosGriferia', $infocontent);
    }

    public function ResultadosGriferiaSalida() {
        $id = $this->input->post_get('id', TRUE);
        $cantidad = $this->input->post_get('cantidad', TRUE);
        $this->load->model("Modeloalmacenista");
        $resp = $this->Modeloalmacenista->SalidaGrif($id, $cantidad);
        if ($resp == "correcto") {
            print("Correcto");
        } else {
            print("Error");
        }
    }

    public function EntradaProductos() {
        $infoheader["titulo"] = "Almacén: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modeloalmacenista");
        $this->load->view('almacenista/EntradaProductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function SalidaProductos() {
        $infoheader["titulo"] = "Almacén: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modeloalmacenista");
        $this->load->view('almacenista/SalidaProductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function VerificarClaveProd() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modeloalmacenista");
        $fila = $this->Modeloalmacenista->BuscarClaveProd($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }

    public function VerificarClaveTarima() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modeloalmacenista");
        $fila = $this->Modeloalmacenista->BuscarClaveTarima($clave);
        if ($fila != "No se encontró la tarima") {
            $infocontent["id"] = $fila->IdTarimas;
        }
        print json_encode($infocontent);
    }

    //por producto
    public function VerificarClaveTarimaP() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modeloalmacenista");
        $fila = $this->Modeloalmacenista->BuscarClaveTarimaP($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }

    public function GuardarTarimasAlmacen() {
        $idtarima = $this->input->post_get('idtarima', TRUE);
        $this->load->model("Modeloalmacenista");
        $resp = $this->Modeloalmacenista->GuardarProductosTarima($idtarima);
        if ($resp == "Existe") {
            print("Existe");
        } else if ($resp == "correcto") {
            print("Correcto");
        } else {
            print("Error");
        }
    }

    //Por producto
    public function GuardarTarimasAlmacenP() {
        $idProducto = $this->input->post_get('idProducto', TRUE);
        $this->load->model("Modeloalmacenista");
        $resp = $this->Modeloalmacenista->GuardarProductosTarimaP($idProducto);
        if ($resp == "Existe") {
            print("Existe");
        } else if ($resp == "correcto") {
            print("Correcto");
        } else {
            print("Error");
        }
    }

    public function SalirTarimasAlmacen() {
        $idtarima = $this->input->post_get('idtarima', TRUE);
        $this->load->model("Modeloalmacenista");
        $resp = $this->Modeloalmacenista->SalirTarima($idtarima);
        if ($resp != null) {
            $query = $this->Modeloalmacenista->SalirProductoAlmacen($resp);
            if ($query == "correcto") {
                print("Correcto");
            } else {
                print("Error");
            }
        } else {
            print ("NoExiste");
        }
    }
    
    public function SalirTarimasAlmacenAccidente() {
        $idtarima = $this->input->post_get('idtarima', TRUE);
        $Responsable = $this->input->post_get('Responsable', TRUE);
        $Motivo = $this->input->post_get('Motivo', TRUE);
        $this->load->model("Modeloalmacenista");
        $resp = $this->Modeloalmacenista->SalirTarima($idtarima);
        if ($resp != null) {
            $query = $this->Modeloalmacenista->SalirProductoAlmacen($resp);
            $query3 = $this->Modeloalmacenista->AbrirTarima($idtarima);
            $query2 = $this->Modeloalmacenista->GuardarAccidenteT($idtarima, $Responsable, $Motivo);
            if ($query == "correcto") {
                print("Correcto");
            } else {
                print("Error");
            }
        } else {
            print ("NoExiste");
        }
    }

    //Por producto
    public function SalirTarimasAlmacenP() {
        $idproducto = $this->input->post_get('idtarima', TRUE);
        $this->load->model("Modeloalmacenista");
        $resp = $this->Modeloalmacenista->SalirTarimaP($idproducto);
        if ($resp != null) {
            $query = $this->Modeloalmacenista->SalirProductoAlmacenP($resp);
            if ($query == "correcto") {
                print("Correcto");
            } else {
                print("Error");
            }
        } else {
            print ("NoExiste");
        }
    }
    
    //Por producto
    public function SalirTarimasAlmacenPAccidente() {
        $idproducto = $this->input->post_get('idtarima', TRUE);
        $Responsable = $this->input->post_get('Responsable', TRUE);
        $Motivo = $this->input->post_get('Motivo', TRUE);
        $this->load->model("Modeloalmacenista");
        $resp = $this->Modeloalmacenista->SalirTarimaP($idproducto);
        if ($resp != null) {
            $query = $this->Modeloalmacenista->SalirProductoAlmacenP($resp);
            $query2 = $this->Modeloalmacenista->GuardarAccidente($idproducto, $Responsable, $Motivo);
            if ($query == "correcto") {
                print("Correcto");
            } else {
                print("Error");
            }
        } else {
            print ("NoExiste");
        }
    }
    
    //Detalle Por producto de la tarima
    public function GuardadoDetalle() {
        $idTarima = $this->input->post_get('idtarima', TRUE);
        $idProducto = $this->input->post_get('idProducto', TRUE);
        $this->load->model("Modeloalmacenista");
        $resp = $this->Modeloalmacenista->BuscarEnTarima($idProducto, $idTarima);
        if ($resp == true) {
            $query = $this->Modeloalmacenista->GuardarDetalle($idProducto, $idTarima);
            if ($query == "correcto") {
                print("Correcto");
            } else {
                print("Error");
            }
        } else {
            print ("NoExiste");
        }
    }
    

    public function GuardarAlmacen() {
        $id = $this->input->post_get('id', TRUE);
        $this->load->model("Modeloalmacenista");
        //Verifica q no exista
        $verificador = $this->Modeloalmacenista->VerificarProd($id);
        if ($verificador == "bien") {
            //Guarda el producto
            $fila = $this->Modeloalmacenista->GuardarEntradaAlmacen($id);
            print("bien");
        }
    }

    public function VerificarClaveTarimaAlmacen() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modeloalmacenista");
        $fila = $this->Modeloalmacenista->BuscarClaveTarima2($clave);
        if ($fila != "No se encontró la tarima") {
            $infocontent["id"] = $fila->IdTarimas;
        }
        print json_encode($infocontent);
    }

    //Por producto
    public function VerificarClaveTarimaAlmacenP() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modeloalmacenista");
        $fila = $this->Modeloalmacenista->BuscarClaveTarimaP($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }
    
    //Por producto dañado
    public function Verificar() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modeloalmacenista");
        $fila = $this->Modeloalmacenista->BuscarClaveTarimaP($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }

    public function BusquedaTarimas() {
        $infoheader["titulo"] = "Almacén: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modeloalmacenista");
        $infocontent["nombre"] = "No se encontró la tarima";
        $this->load->view('almacenista/BusquedaTarimas', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function VerificarClaveTarima2() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modeloalmacenista");
        $fila = $this->Modeloalmacenista->BuscarClaveTarimaExp($clave);
        $infocontent["nombre"] = "No se encontró la tarima";
        if ($fila != "No se encontró la tarima") {
            $infocontent["nombre"] = $fila->Productos;
            $infocontent["id"] = $fila->IdTarimas;
        }
        print json_encode($infocontent);
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
        $infocontent["tarima"]= $tarima_id;
        $infocontent["pendiente"]=$this->Modeloalmacenista->TarimaenPendiente($tarima_id);
        /* $infocontent["tarima"] = $this->modeloclasificador->EstatusTarima($producto_id);
          $infocontent["tarimaid"] = $this->modeloclasificador->EstatusTarimaId($producto_id);
          $infocontent["clasificaciones"] = $this->modeloclasificador->ClasificacionesProducto($producto_id);
          $infocontent["entarimados"] = $this->modeloclasificador->EntarimadosProducto($producto_id); */
        $infocontent["codigo"] = $this->Modeloalmacenista->CodigoBarrasTarimaTexto($tarima_id);
        $this->load->view('almacenista/ExpedienteTarima', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function InventarioAlmacen() {
        $infoheader["titulo"] = "Inventario: Almacén";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modeloalmacenista");
        $infocontent["modelos"] = $this->Modeloalmacenista->ListaModelos();
        $this->load->view('almacenista/InventarioAlmacen', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function InventarioTarimas() {
        $infoheader["titulo"] = "Inventario: Almacén";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modeloalmacenista");
        $infocontent["modelos"] = $this->Modeloalmacenista->ListaModelos();
        $this->load->view('almacenista/InventarioTarimas', $infocontent);
        $this->load->view('template/footerd', '');
    }
    
    public function CapturaAccidentes() {
        $infoheader["titulo"] = "Almacén: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "";
        $this->load->model("Modeloalmacenista");
        $this->load->view('almacenista/CapturaAccidente', $infocontent);
        $this->load->view('template/footerd', '');
    }
    
    public function CargaInfoModelo() {
        $modelo = $this->input->post_get('modelo_id', TRUE);
        $this->load->model("Modeloalmacenista");
        $infocontent["modelo"] = $this->Modeloalmacenista->ObtenerModelo($modelo);
        $this->load->view('almacenista/CargaInfoModelo', $infocontent);
    }
    
       public function BusquedaProductos() {
        $infoheader["titulo"] = "Almacenista: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "";
        $this->load->model("Modeloalmacenista");
        $this->load->view('almacenista/BusquedaProducto', $infocontent);
        $this->load->view('template/footerd', '');
    }
    
    public function ExpedienteProducto() {
        $infoheader["titulo"] = "Almacenista: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $producto_id = $this->input->post_get('producto_id', TRUE);
        $infocontent["Nombre"] = "";
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
        $this->load->view('almacenista/ExpedienteProducto', $infocontent);
        $this->load->view('template/footerd', '');
    }
    
    public function PeticionAbrirTarima() {
        $idTarima = $this->input->post_get('idtarima', TRUE);
        $this->load->model("Modeloalmacenista");
        $query = $this->Modeloalmacenista->GuardarPeticion($idTarima);
            if ($query == "correcto") {
                print("Correcto");
            } else {
                print("Error");
            }
//        redirect("almacenista/expedientetarima?tarima_id=".$idTarima);
    }
    
    public function AbrirTarima(){
        $idTarima = $this->input->post_get('idtarima', TRUE);
        $this->load->model("Modeloalmacenista");
        $salida = $this->Modeloalmacenista->SalidaEsp($idTarima);
        if ($salida == "correcto") {
            $guardarproductos = $this->Modeloalmacenista->GuardadoEspecial($idTarima);
            $query = $this->Modeloalmacenista->AbrirTarima($idTarima);
                print("Correcto");
            } else {
                print("Error");
            }
            
    }
    
    public function CapturaDevoluciones() {
        $infoheader["titulo"] = "Devoluciones: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $infocontent["hoy"] = date("d/m/Y");
        $this->load->view('almacenista/CapturaDevoluciones', $infocontent);
        $this->load->view('template/footerd', '');
    }
    
    public function BuscarSubproducto() {
        $texto = $this->input->post_get('texto', true);
        $id = $this->input->post_get('id', true);
        $this->load->model("Modeloalmacenista");
        $encontrados = $this->Modeloalmacenista->BuscarSubproducto($texto);
        $infocontent["encontrados"] = $encontrados;
        $infocontent["id"] = $id;
        $this->load->view('almacenista/BuscarSubproducto', $infocontent);
    }
    
    public function GuardarSubproducto2() {
        $detalle_id = $this->input->post_get("detalle_id");
        $subproducto_id = $this->input->post_get("subproducto_id");
        $this->load->model("Modeloalmacenista");
        $iddetalle = $this->Modeloalmacenista->GuardarSubproducto2($subproducto_id, $detalle_id);
        print($iddetalle);
    }
    
    public function GuardarDevolucion() {
        $cliente = $this->input->post_get('cliente', true);
        $motivo = $this->input->post_get('motivo', true);
        $responsable = $this->input->post_get('responsable', true);
        $this->load->model("Modeloalmacenista");
        $iddevolucion = $this->Modeloalmacenista->GuardarDevolucion($cliente, $motivo, $responsable);
        print($iddevolucion);
    }
    
    public function GuardarDetalleDevolucion() {
        $id_producto = $this->input->post_get('producto_id', true);
        $id_devolucion = $this->input->post_get('devolucion_id', true);
        $this->load->model("Modelosclasificador");
        $iddetalle = $this->Modelosclasificador->GuardarDetalleDevolucion($id_producto, $id_devolucion);
        print($iddetalle);
    }

    public function ConsultarCliente() {
        $texto = $this->input->post_get('texto', TRUE);
        $this->load->model("Modeloventas");
        $infocontent["clientes"] = $this->Modeloventas->ConsultarCliente($texto);
        $this->load->view('almacenista/ConsultarCliente', $infocontent);
    }
    
    public function GuardarProductoCedis() {
        $idproducto = $this->input->post_get('idproducto', true);
        $this->load->model("Modelocedis");
        if ($this->Modelocedis->ProductoEnCedis($idproducto)) {
            print("Existe");
        } else {
            $this->Modelocedis->GuardarProductoCedis($idproducto);
            print("Correcto");
        }
    }
    
    public function DevolucionesCapturadas() {
        $fechainicio = $this->input->post_get('fechainicio', true);
        $fechafin = $this->input->post_get('fechafin', true);
        $this->load->model("Modeloalmacenista");
        $infocontent["devolucionescapturadas"] = $this->Modeloalmacenista->DevolucionesCapturadas($fechainicio, $fechafin);
        $this->load->view('almacenista/DevolucionesCapturadas', $infocontent);
    }
    
    
    public function VerificarClaveProd2() {
        $clave = $this->input->post_get('clave', true);
        $this->load->model("Modelocedis");
        $fila = $this->Modelocedis->BuscarClaveProducto($clave);
        $infocontent["nombre"] = "";
        switch ($fila) {
            case "No se encontró el producto":
                $infocontent["nombre"] = "No se encontró el producto";
                break;
            case "No se marcó salida de almacén":
                $infocontent["nombre"] = "No se marcó salida de almacén";
                break;
            default:
                $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
                $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }
    
    public function CambioEnviada() {
        $dev = $this->input->post_get('devolucion_id', TRUE);
        $this->load->model("Modeloalmacenista");
        $d = $this->Modeloalmacenista->ObtenerDevolucion($dev);
        $this->db->set("CheckEnviada", !$d->CheckEnviada);
        $this->db->where("IdDevoluciones", $dev);
        $this->db->update("Devoluciones");
        print "correcto";
    }
}
