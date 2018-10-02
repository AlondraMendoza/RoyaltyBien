<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Ventas extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Modelousuario");
        $this->load->model("Modeloventas");
        if (!EstaLogueado()) {
            redirect('usuario/index');
        }
        $id = $this->session->userdata('id');
        if (!$this->Modelousuario->TienePerfil($id, 8)) {
            redirect('usuario/logueado');
        }
    }

    public function InventarioCedis() {
        $infoheader["titulo"] = "Inventario Cedis: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modelocedis");
        $infocontent["modelos"] = $this->Modelocedis->ListaModelos();
        $this->load->view('ventas/InventarioCedis', $infocontent);

        $this->load->view('template/footerd', '');
    }

    public function CargaInfoModelo() {
        $modelo = $this->input->post_get('modelo_id', TRUE);
        $this->load->model("Modeloventas");
        $infocontent["modelo"] = $this->Modeloventas->ObtenerModelo($modelo);
        $this->load->view('administrador/CargaInfoModelo', $infocontent);
    }

    public function Pedidos() {
        $infoheader["titulo"] = "Pedidos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modelocedis");
        $infocontent["ListaPedidosCapturados"] = $this->Modelocedis->ListaCompletaPedidosCapturados();
        $infocontent["ListaPedidosLiberados"] = $this->Modelocedis->ListaCompletaPedidosLiberados();
        $infocontent["ListaPedidosEntregados"] = $this->Modelocedis->ListaCompletaPedidosEntregados();
        $this->load->view('ventas/Pedidos', $infocontent);
        $this->load->view('template/footerd', '');
    }

    public function ImportarPedido() {
        if (!empty($_FILES['file']['name'])) {
            $pathinfo = pathinfo($_FILES["file"]["name"]);
            if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls') && $_FILES['file']['size'] > 0) {
                // Nombre Temporal del Archivo
                $inputFileName = $_FILES['file']['tmp_name'];
                //Lee el Archivo usando ReaderFactory
                $reader = ReaderFactory::create(Type::XLSX);
                $reader->setShouldFormatDates(true);
                // Abrimos el archivo
                $reader->open($inputFileName);
                $count = 1;
                //Numero de Hojas en el Archivo
                $data = array(
                    'UsuariosId' => IdUsuario(),
                    'Activo' => 1,
                    'Cliente' => $this->input->post_get('cliente', TRUE),
                    'NotaCredito' => $this->input->post_get('notacredito', TRUE),
                    'NotaCedis' => $this->input->post_get('notacedis', TRUE),
                    'FechaRegistro' => date('Y-m-d | h:i:sa'),
                    'Estatus' => 'Solicitado'
                );
                $this->db->insert('Pedidos', $data);
                $idpedido = $this->db->insert_id();
                foreach ($reader->getSheetIterator() as $sheet) {
                    // Numero de filas en el documento EXCEL
                    foreach ($sheet->getRowIterator() as $row) {
                        // Lee los Datos despues del encabezado
                        // El encabezado se encuentra en la primera fila
                        if ($count > 1) {
                            //Prosesamiento de row de excel
                            $cantidad = $row[0];
                            $clave = $row[1];
                            $descripcion = $row[2];
                            //$cproducto = substr($clave, 0, 3);
                            $producto = $this->Modeloventas->ObtenerProductoImportacion($clave);
                            //$cproductoid = $this->Modeloventas->ObtenerCProductoImportacion($cproducto);
//                            $modelo = substr($clave, 6, 3);
//                            $modeloid = $this->Modeloventas->ObtenerModeloImportacion($modelo);
//                            $color = substr($clave, 4, 2);
//                            $colorid = $this->Modeloventas->ObtenerColorImportacion($color);
//                            $clasificacion = substr($clave, 3, 1);
//                            $clasificacionid = $this->Modeloventas->ObtenerClasificacionImportacion($clasificacion);
                            $data = array(
                                'Activo' => 1,
                                'Cantidad' => $cantidad,
                                'CProductosId' => $producto->CProductosId,
                                'ModelosId' => $producto->ModelosId,
                                'ColoresId' => $producto->ColoresId,
                                'ClasificacionesId' => $producto->ClasificacionesId,
                                'PedidosId' => $idpedido,
                                'Descripcion' => $descripcion
                            );
                            $this->db->insert('PedidosVentas', $data);
                        }
                        $count++;
                    }
                }
                // cerramos el archivo EXCEL
                $reader->close();
            } else {
                echo "Seleccione un tipo de Archivo Valido";
            }
        } else {
            echo "Seleccione un Archivo EXCEL";
        }
        $this->session->set_flashdata('correcto', 'Pedido registrado correctamente!');
        redirect("ventas/Pedidos");
    }

    public function AbrirPedido() {
        $pedidoid = $this->input->post_get('pedidoid', TRUE);
        $this->load->model("Modelocedis");
        $infoheader["titulo"] = "Pedido: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $infocontent["ListaProductos"] = $this->Modelocedis->ProductosPedido($pedidoid);
        $infocontent["pedidoid"] = $pedidoid;
        $infocontent["pedido"] = $this->Modelocedis->ObtenerPedido($pedidoid);
        $this->load->view('ventas/AbrirPedido', $infocontent);
        $this->load->view('template/footerd', '');
    }

}
?>

