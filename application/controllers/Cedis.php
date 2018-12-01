<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cedis extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Modelousuario");
        $this->load->model("Modelocedis");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        }
        $id = $this->session->userdata('id');
        if (!$this->Modelousuario->TienePerfil($id, 6)) {
            redirect('usuario/logueado');
        }
    }

    public function index()
    {
        $datos["nombre"] = "Cadena ejemplo";
        $datos["apellido"] = "Cadena ejemplo 2";
        $this->load->view('capturista/index', $datos);
    }

    public function EntradaTarimas()
    {
        $infoheader["titulo"] = "Entrada Tarimas: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->view('cedis/EntradaProductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function EntradaProductos()
    {
        $infoheader["titulo"] = "Entrada Productos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->view('cedis/EntradaProductos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function VerificarClaveProd()
    {
        $clave = $this->input->post_get('clave', true);
        $this->load->model("Modelocedis");
        $fila = $this->Modelocedis->BuscarClaveProducto($clave);
        $infocontent["nombre"] = "";
        switch($fila)
        {
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

    public function VerificarClaveTarima()
    {
        $clave = $this->input->post_get('clave', true);
        $this->load->model("Modelocedis");
        $fila = $this->Modelocedis->BuscarClaveTarima($clave);
        $infocontent["nombre"] = "";
        switch($fila)
        {
            case "No se encontró la tarima":
            $infocontent["nombre"] = "No se encontró la tarima";
            break;
            case "No se marcó salida de almacén":
            $infocontent["nombre"] = "No se marcó salida de almacén";
            break;
            default:
            $infocontent["id"] = $fila->IdTarimas;
        }
        print json_encode($infocontent);
    }

    public function GuardarTarimasCedis()
    {
        $idtarima = $this->input->post_get('idtarima', true);
        $this->load->model("Modelocedis");
        $resp = $this->Modelocedis->GuardarProductosTarima($idtarima);
        if ($resp == "Existe") {
            print("Existe");
        } else if ($resp == "correcto") {
            print("Correcto");
        } else {
            print("Error");
        }
    }

    public function GuardarProductoCedis()
    {
        $idproducto = $this->input->post_get('idproducto', true);
        $this->load->model("Modelocedis");
        if ($this->Modelocedis->ProductoEnCedis($idproducto)) {
            print("Existe");
        } else {
            $this->Modelocedis->GuardarProductoCedis($idproducto);
            print("Correcto");
        }
    }

    public function CapturaPedidos()
    {
        $infoheader["titulo"] = "Captura Pedidos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $this->load->model("Modelocedis");
        $infocontent["ListaPedidosCapturados"] = $this->Modelocedis->ListaCompletaPedidosCapturados();
        $infocontent["ListaPedidosLiberados"] = $this->Modelocedis->ListaCompletaPedidosLiberados();
        $infocontent["ListaPedidosEntregados"] = $this->Modelocedis->ListaCompletaPedidosEntregados();
        $this->load->view('cedis/CapturaPedidos', $infocontent);

        $this->load->view('template/footerd', '');
    }

    public function VerificarProductoCedis()
    {
        $clave = $this->input->post_get('clave', true);
        $this->load->model("Modelocedis");
        $fila = $this->Modelocedis->BuscarProductoCedis($clave);
        $infocontent["nombre"] = $fila;
        if ($fila != "No se encontró el producto" && $fila != "No está en cedis" && $fila != "El producto ya se encuentra en un pedido") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }

    public function SubirImagenPedido()
    {
        $pedidoid = $this->input->post('pedidoid');
        $observacion = $this->input->post_get('observacioncedis', true);
        $this->db->set("ObservacionSalida", $observacion);
        $this->db->where("IdPedidos", $pedidoid);
        $this->db->update("Pedidos");
        if (!empty($_FILES['userfile']['name'])) {
            $config['upload_path'] = 'public/imagenespedidos/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '4000';
            $fecha = date('Y-m-d-hi-s-a');
            $config['file_name'] = "pedido" . $fecha . $pedidoid;
            $config['max_width'] = '4024';
            $config['max_height'] = '4008';
            $this->load->library('upload', $config);
            $this->upload->do_upload();
            $file_info = $this->upload->data();
            $ruta = "public/imagenespedidos/" . $file_info["file_name"];
            $this->Modelocedis->SubirImagenPedido($ruta, $pedidoid);
        }
        redirect('cedis/AbrirPedido?pedidoid=' . $pedidoid);
    }

    public function GuardarPedidoCedis()
    {
        $cliente = $this->input->post_get('cliente', true);
        $this->load->model("Modelocedis");
        $idpedido = $this->Modelocedis->GuardarPedido($cliente);
        print($idpedido);
    }

    public function EliminarImagenPedido()
    {
        $idimagen = $this->input->post_get('idimagen', true);
        $pedidoid = $this->input->post_get('pedidoid', true);
        $this->Modelocedis->EliminarImagenPedido($idimagen);
        redirect('cedis/AbrirPedido?pedidoid=' . $pedidoid);
    }

    public function GuardarDetallePedidoCedis()
    {
        $idproducto = $this->input->post_get('idproducto', true);
        $idpedido = $this->input->post_get('idpedido', true);
        $this->load->model("Modelocedis");
        $iddetalle = $this->Modelocedis->GuardarDetallePedido($idproducto, $idpedido);
        if ($iddetalle == "En pedido") {
            print("En pedido");
        } else if ($iddetalle == "No solicitado") {
            print("No solicitado");
        } else if ($iddetalle == "Fuera de límite") {
            print("Fuera límite");
        } else {
            print("Correcto");
        }
    }

    public function AbrirPedido()
    {
        $pedidoid = $this->input->post_get('pedidoid', true);
        $this->load->model("Modelocedis");
        $infoheader["titulo"] = "Pedido: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["ListaProductos"] = $this->Modelocedis->ProductosPedido($pedidoid);
        $infocontent["ListaImagenes"] = $this->Modelocedis->ListaImagenesPedido($pedidoid);
        $infocontent["pedidoid"] = $pedidoid;
        $infocontent["pedido"] = $this->Modelocedis->ObtenerPedido($pedidoid);
        $this->load->view('cedis/AbrirPedido', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function InformacionPedidoVentas()
    {
        $pedidoid = $this->input->post_get('pedidoid', true);
        $infocontent["pedido"] = $this->Modelocedis->ObtenerPedido($pedidoid);
        $infocontent["ListaProductosAgrupados"] = $this->Modelocedis->ProductosPedidoAgrupados($pedidoid);
        $this->load->view('cedis/InformacionPedidoVentas', $infocontent);
    }

    public function RecargaProductosPedido()
    {
        $pedidoid = $this->input->post_get('pedidoid', true);
        $infocontent["pedido"] = $this->Modelocedis->ObtenerPedido($pedidoid);
        $infocontent["ListaProductos"] = $this->Modelocedis->ProductosPedido($pedidoid);
        $this->load->view('cedis/RecargaProductosPedido', $infocontent);
    }

    public function SalidaCedis()
    {
        $pedidoid = $this->input->post_get('pedidoid', true);
        $this->load->model("Modelocedis");
        $this->Modelocedis->SalidaPedido($pedidoid);
        print("correcto");
    }

    public function MaximosMinimos()
    {
        $infoheader["titulo"] = "Máximos y Mínimos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modelocedis");
        $infocontent["modelos"] = $this->Modelocedis->ListaModelos();
        $this->load->view('cedis/MaximosMinimos', $infocontent);

        $this->load->view('template/footerd', '');
    }

    public function ConfiguracionMaximosMinimos()
    {
        $infoheader["titulo"] = "Configuración Máximos y Mínimos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modelocedis");
        $infocontent["modelos"] = $this->Modelocedis->ListaModelos();
        $this->load->view('cedis/ConfiguracionMaximosMinimos', $infocontent);

        $this->load->view('template/footerd', '');
    }

    public function GuardarMaximo()
    {
        $cproducto = $this->input->post_get('cproducto', true);
        $modelo = $this->input->post_get('modelo', true);
        $color = $this->input->post_get('color', true);
        $clasificacion = $this->input->post_get('clasificacion', true);
        $valor = $this->input->post_get('valor', true);
        $this->load->model("Modelocedis");
        $this->Modelocedis->GuardarMaximo($cproducto, $modelo, $color, $clasificacion, $valor);
        print("correcto");
    }

    public function GuardarMinimo()
    {
        $cproducto = $this->input->post_get('cproducto', true);
        $modelo = $this->input->post_get('modelo', true);
        $color = $this->input->post_get('color', true);
        $clasificacion = $this->input->post_get('clasificacion', true);
        $valor = $this->input->post_get('valor', true);
        $this->load->model("Modelocedis");
        $this->Modelocedis->GuardarMinimo($cproducto, $modelo, $color, $clasificacion, $valor);
        print("correcto");
    }

    public function CapturaDevoluciones()
    {
        $infoheader["titulo"] = "Devoluciones: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "Alondra Mendoza";
        $infocontent["hoy"] = date("d/m/Y");
        $this->load->view('cedis/CapturaDevoluciones', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function DevolucionesCapturadas()
    {
        $fechainicio = $this->input->post_get('fechainicio', true);
        $fechafin = $this->input->post_get('fechafin', true);
        $infocontent["devolucionescapturadas"] = $this->Modelocedis->DevolucionesCapturadas($fechainicio, $fechafin);
        $this->load->view('cedis/DevolucionesCapturadas', $infocontent);
    }

    public function BuscarSubproducto()
    {
        $texto = $this->input->post_get('texto', true);
        $id = $this->input->post_get('id', true);
        $encontrados = $this->Modelocedis->BuscarSubproducto($texto);
        $infocontent["encontrados"] = $encontrados;
        $infocontent["id"] = $id;
        $this->load->view('cedis/BuscarSubproducto', $infocontent);
    }

    public function GuardarDevolucion()
    {
        $cliente = $this->input->post_get('cliente', true);
        $motivo = $this->input->post_get('motivo', true);
        $responsable = $this->input->post_get('responsable', true);
        $this->load->model("Modelocedis");
        $iddevolucion = $this->Modelocedis->GuardarDevolucion($cliente, $motivo, $responsable);
        print($iddevolucion);
    }

    public function GuardarDetalleDevolucion()
    {
        $id_producto = $this->input->post_get('producto_id', true);
        $id_devolucion = $this->input->post_get('devolucion_id', true);
        $this->load->model("Modelosclasificador");
        $iddetalle = $this->Modelosclasificador->GuardarDetalleDevolucion($id_producto, $id_devolucion);
        print($iddetalle);
    }

    public function GuardarSubproducto()
    {
        $detalle_id = $this->input->post_get("detalle_id");
        $subproducto_id = $this->input->post_get("subproducto_id");
        $iddetalle = $this->Modelocedis->GuardarSubproducto($subproducto_id, $detalle_id);
        print($iddetalle);
    }

    public function capturaCedis()
    {
        $infoheader["titulo"] = "Cedis: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "";
        $this->load->model("Modelocedis");
        $infocontent["productos"] = $this->Modelocedis->ListarProductos();
        $infocontent["clasificacion"] = $this->Modelocedis->ListarClasificaciones();
        $this->load->view('cedis/capturaCedis', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ObtenerModelos()
    {
        $id = $this->input->post_get('id', true);
        $this->load->model("Modelocedis");
        $infocontent["modelos"] = $this->Modelocedis->ListarModelos($id);
        $this->load->view('cedis/ObtenerModelos', $infocontent);
    }

    public function ObtenerColores()
    {
        $id = $this->input->post_get('id', true);
        $this->load->model("Modelocedis");
        $infocontent["colores"] = $this->Modelocedis->ListarColores($id);
        $this->load->view('cedis/ObtenerColores', $infocontent);
    }

    public function Guardado()
    {
        $prod = $this->input->post_get('prod', true);
        $mod = $this->input->post_get('mod', true);
        $col = $this->input->post_get('col', true);
        $clasi = $this->input->post_get('clasi', true);
        $this->load->model("Modelocedis");
        $idprod = $this->Modelocedis->GuardarProductos($prod, $mod, $col, $clasi);
        $producto = $this->Modelocedis->ObtenerProducto($idprod);
        $fechaformateada = date_format(date_create($producto->FechaCaptura), 'dmY');
        print(str_pad($idprod, 10, '0', STR_PAD_LEFT) . "*" . $fechaformateada);
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
            
            imagettftext($image, 15, 0, 450, $img_height + $text_height + 10, $black, $font, $text."\n".$agregado);

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
    
     public function BusquedaProductos() {
        $infoheader["titulo"] = "Cedis: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["Nombre"] = "";
        $this->load->model("Modelocedis");
        $this->load->view('cedis/BusquedaProducto', $infocontent);
        $this->load->view('template/footerd', '');
    }
    
    public function ExpedienteProducto() {
        $infoheader["titulo"] = "Cedis: Royalty Ceramic";
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
        $this->load->view('cedis/ExpedienteProducto', $infocontent);
        $this->load->view('template/footerd', '');
    }
    
     public function VerificarClaveProdExpediente() {
        $clave = $this->input->post_get('clave', TRUE);
        $this->load->model("Modelocedis");
        $fila = $this->Modelocedis->BuscarClaveProductoExpediente($clave);
        $infocontent["nombre"] = "No se encontró el producto";
        if ($fila != "No se encontró el producto") {
            $infocontent["nombre"] = $fila->producto . "/" . $fila->modelo . "/" . $fila->color;
            $infocontent["id"] = $fila->IdProductos;
        }
        print json_encode($infocontent);
    }

    public function ReportePedido()
    {
        // Se carga el modelo alumno
        $this->load->model('Modelocedis');
        // Se carga la libreria fpdf
        $this->load->library('pdf');
        $idpedido = $this->input->post_get('idpedido', true);
        // Se obtienen los alumnos de la base de datos

        $productos = $this->Modelocedis->ResumenProductosPedido($idpedido);
        $pedido=$this->Modelocedis->ObtenerPedido($idpedido);
        $usuariocrea=$this->Modelocedis->UsuarioCreaPedido($idpedido);
        $usuariolibera=$this->Modelocedis->UsuarioLiberaCredito($idpedido);
        $usuarioentrega=$this->Modelocedis->UsuarioEntregaPedido($idpedido);
        $cliente=$this->Modelocedis->ObtenerCliente($pedido->ClientesId);
        
        // Creacion del PDF
        /*
        * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
        * heredó todos las variables y métodos de fpdf
        */
        $this->pdf = new Pdf();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
    
        /* Se define el titulo, márgenes izquierdo, derecho y
        * el color de relleno predeterminado
        */
        $this->pdf->SetTitle("Reporte Pedido");
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
        $this->pdf->SetFillColor(200,200,200);
    
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 8);
        /*
        * TITULOS DE COLUMNAS
        *
        * $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
        */

            

        $this->pdf->MultiCell(80,4,"PARA: \n ".utf8_decode($cliente->Nombre). "\n ",0,'L',0);
        $this->pdf->SetXY(80,25);
        $this->pdf->MultiCell(50,4,utf8_decode("DOMICILIO FISCAL: \n CARR. LEÓN SILAO KM 15.5 \n Guanajuato León LOS SAUCES \n 37545 \n MÉXICO"),0,'L',0);
        $this->pdf->SetXY(150,25);
        $this->pdf->MultiCell(40,4,utf8_decode("Facturas Cliente: ".$pedido->Serie.$pedido->Folio." \n ".Date("Y-m-d")),0,'L',0);
        $this->pdf->SetXY(10,50);
        $this->pdf->Cell(190,0,'','T',0,'C','1');
        $this->pdf->Ln(7);
        $this->pdf->SetXY(10,60);
        $this->pdf->Cell(30,7,utf8_decode($usuariocrea),'B',0,'C',0);
        $this->pdf->Cell(10,0,'','',0,'C',0);
        $this->pdf->Cell(30,7,utf8_decode($usuariolibera),'B',0,'C',0);
        $this->pdf->Cell(10,0,'','',0,'C',0);
        $this->pdf->Cell(30,7,utf8_decode($usuarioentrega),'B',0,'C',0);
        $this->pdf->Cell(10,0,'','',0,'C',0);
        $this->pdf->Cell(30,7,utf8_decode($cliente->Nombre),'B',0,'C',0);
        $this->pdf->Cell(10,0,'','',0,'C',0);
        $this->pdf->Cell(30,7,"",'B',0,'C',0);
        $this->pdf->Ln(7);
        $this->pdf->SetXY(10,68);
        $this->pdf->Cell(30,7,"Ventas",'',0,'C',0);
        $this->pdf->Cell(10,0,'','',0,'C',0);
        $this->pdf->Cell(30,7,utf8_decode("Libera Crédito y Cobranza"),'',0,'C',0);
        $this->pdf->Cell(10,0,'','',0,'C',0);
        $this->pdf->Cell(30,7,utf8_decode("Entrega Almacén"),'',0,'C',0);
        $this->pdf->Cell(10,0,'','',0,'C',0);
        $this->pdf->Cell(30,7,utf8_decode("Recibe Cliente"),'',0,'C',0);
        $this->pdf->Cell(10,0,'','',0,'C',0);
        $this->pdf->Cell(30,7,utf8_decode("Guardia en turno"),'',0,'C',0);
        $this->pdf->Ln(15);

        $this->pdf->Cell(25,7,'CANTIDAD','TBL',0,'C','1');
        $this->pdf->Cell(40,7,'UNIDAD DE MEDIDA','TBL',0,'C','1');
        $this->pdf->Cell(25,7,utf8_decode('CÓDIGO'),'TBL',0,'C','1');
        $this->pdf->Cell(90,7,utf8_decode('DESCRIPCIÓN'),'TBLR',0,'C','1');
        $this->pdf->Ln(7);

        
        // La variable $x se utiliza para mostrar un número consecutivo
        $x = 1;
        foreach ($productos->result() as $producto) {
            $codigo=$this->Modelocedis->CodigoProducto($producto->IdProductos);
        // se imprime el numero actual y despues se incrementa el valor de $x en uno
        // Se imprimen los datos de cada alumno
        $this->pdf->Cell(25,5,$producto->cantidad,'BL',0,'C',0);
        $this->pdf->Cell(40,5,"Pieza",'BL',0,'C',0);
        $this->pdf->Cell(25,5,utf8_decode($codigo),'BL',0,'C',0);
        $this->pdf->Cell(90,5,utf8_decode($producto->producto." | ".$producto->modelo." | ".$producto->color." | ". $producto->clasificacion),'BLR',0,'L',0);
        //Se agrega un salto de linea
        $this->pdf->Ln(5);
        }
        /*
        * Se manda el pdf al navegador
        *
        * $this->pdf->Output(nombredelarchivo, destino);
        *
        * I = Muestra el pdf en el navegador
        * D = Envia el pdf para descarga
        *
        */
        $this->pdf->Output("ReportePedido.pdf", 'I');
    }
    public function InventarioCedis() {
        $infoheader["titulo"] = "Inventario Cedis: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modelocedis");
        $infocontent["modelos"] = $this->Modelocedis->ListaModelos();
        $this->load->view('cedis/InventarioCedis', $infocontent);
        $this->load->view('template/footerd', '');
    }
    public function CargaInfoModelo() {
        $modelo = $this->input->post_get('modelo_id', TRUE);
        $this->load->model("Modeloventas");
        $infocontent["modelo"] = $this->Modeloventas->ObtenerModelo($modelo);
        $this->load->view('cedis/CargaInfoModelo', $infocontent);
    }
    public function InventarioCedisCX() {
        $infoheader["titulo"] = "Inventario Cedis: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modelocedis");
        $infocontent["modelos"] = $this->Modelocedis->ListaModelos();
        $this->load->view('cedis/InventarioCedisCX', $infocontent);
        $this->load->view('template/footerd', '');
    }
    public function CargaInfoModeloCX() {
        $modelo = $this->input->post_get('modelo_id', TRUE);
        $this->load->model("Modeloventas");
        $infocontent["modelo"] = $this->Modeloventas->ObtenerModelo($modelo);
        $this->load->view('cedis/CargaInfoModeloCX', $infocontent);
    }
    public function ConsultarCliente()
    {
        $texto = $this->input->post_get('texto', TRUE);
        $this->load->model("Modeloventas");
        $infocontent["clientes"] = $this->Modeloventas->ConsultarCliente($texto);
        $this->load->view('cedis/ConsultarCliente', $infocontent);
    }
    public function CambioEnviada()
    {
        $dev = $this->input->post_get('devolucion_id', TRUE);
        $d = $this->Modelocedis->ObtenerDevolucion($dev);
        $this->db->set("CheckEnviada", !$d->CheckEnviada);
        $this->db->where("IdDevoluciones", $dev);
        $this->db->update("Devoluciones");
        print "correcto";
    }
}

//Guardar fecha de presalida
//Capturar el cliente al guardar pedido
//Marcar de abierta la tarima al guardar detalle de pedidos
//Eliminar tabla de detallepedidos
//Probar que al querer guardar un producto en un pedido ya se encuentre configurado en alguno

//Listar pedidos capturados
