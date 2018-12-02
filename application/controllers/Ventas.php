<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once APPPATH . 'third_party/Spout/Autoloader/autoload.php';

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Ventas extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Modelousuario");
        $this->load->model("Modeloventas");
        $this->load->model("Modelocedis");
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
        $this->load->view('ventas/CargaInfoModelo', $infocontent);
    }

    public function CargarInfoModeloSinClasificar() {
        $modelo = $this->input->post_get('modelo_id', TRUE);
        $this->load->model("Modeloventas");
        $infocontent["modelo"] = $this->Modeloventas->ObtenerModelo($modelo);
        $this->load->view('ventas/CargarInfoModeloSinClasificar', $infocontent);
    }

    public function Pedidos() {
        $infoheader["titulo"] = "Pedidos: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modelocedis");
        $infocontent["hoy"] = date("d/m/Y");
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
                $fechaentrega=$this->input->post_get('fechaentrega', TRUE);
                $fechaentrega = $this->FechaIngles($fechaentrega);
                //Numero de Hojas en el Archivo
                $data = array(
                    'UsuariosId' => IdUsuario(),
                    'Activo' => 1,
                    'ClientesId' => $this->input->post_get('cliente', TRUE),
                    'NotaCredito' => $this->input->post_get('notacredito', TRUE),
                    'NotaCedis' => $this->input->post_get('notacedis', TRUE),
                    'FechaPosibleEntrega' => $fechaentrega,
                    'Folio' => $this->input->post_get('folio', TRUE),
                    'Serie' => $this->input->post_get('serie', TRUE),
                    'FechaRegistro' => date('Y-m-d | H:i:sa'),
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
                            if($producto!=null)
                            {
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
                            else{
                                /*Entra aquí si es de grifería*/ 
                                $producto = $this->Modeloventas->ObtenerSubProductoImportacion($clave);   
                                $data = array(
                                    'Activo' => 1,
                                    'Cantidad' => $cantidad,
                                    'CGriferiaId' => $producto->IdCGriferia,
                                    'PedidosId' => $idpedido,
                                    'Descripcion' => $descripcion
                                );
                                $this->db->insert('SubPedidosVentas', $data);
                            }
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

    public function ReimportarPedido() {
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
//                $data = array(
//                    'UsuariosId' => IdUsuario(),
//                    'Activo' => 1,
//                    'Cliente' => $this->input->post_get('cliente', TRUE),
//                    'NotaCredito' => $this->input->post_get('notacredito', TRUE),
//                    'NotaCedis' => $this->input->post_get('notacedis', TRUE),
//                    'FechaRegistro' => date('Y-m-d | H:i:sa'),
//                    'Estatus' => 'Solicitado'
//                );
//                $this->db->insert('Pedidos', $data);
                $idpedido = $this->input->post_get('pedido_id', TRUE);
                /** Se desactivan los productos actuales */
                $productosactuales = $this->Modelocedis->ProductosPedidoAgrupados($idpedido);
                $this->db->set("Activo", 0);
                $this->db->where("PedidosId", $idpedido);
                $this->db->update("PedidosVentas");
                /** Se desactivan los subproductos actuales */
                $subproductosactuales = $this->Modelocedis->SubProductosPedidoAgrupados($idpedido);
                $this->db->set("Activo", 0);
                $this->db->where("PedidosId", $idpedido);
                $this->db->update("SubPedidosVentas");

                /* Actualizo el usuario que modificó pedido */
                $this->db->set("UsuarioModificaId", IdUsuario());
                $this->db->where("IdPedidos", $idpedido);
                $this->db->update("Pedidos");
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
                            if($producto!=null)
                            {
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
                            else{
                                /*Entra aquí si es de grifería*/ 
                                $producto = $this->Modeloventas->ObtenerSubProductoImportacion($clave);   
                                $data = array(
                                    'Activo' => 1,
                                    'Cantidad' => $cantidad,
                                    'CGriferiaId' => $producto->IdCGriferia,
                                    'PedidosId' => $idpedido,
                                    'Descripcion' => $descripcion
                                );
                                $this->db->insert('SubPedidosVentas', $data);
                            }
                            //$cproductoid = $this->Modeloventas->ObtenerCProductoImportacion($cproducto);
//                            $modelo = substr($clave, 6, 3);
//                            $modeloid = $this->Modeloventas->ObtenerModeloImportacion($modelo);
//                            $color = substr($clave, 4, 2);
//                            $colorid = $this->Modeloventas->ObtenerColorImportacion($color);
//                            $clasificacion = substr($clave, 3, 1);
//                            $clasificacionid = $this->Modeloventas->ObtenerClasificacionImportacion($clasificacion);
                            
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

    public function InventarioSinClasificar() {
        $infoheader["titulo"] = "Inventario Cedis: Royalty Ceramic";
        $this->load->view('template/headerd', $infoheader);
        $this->load->model("Modelocedis");
        $infocontent["modelos"] = $this->Modelocedis->ListaModelos();
        $this->load->view('ventas/InventarioSinClasificar', $infocontent);
        $this->load->view('template/footerd', '');
    }
    
    public function Reportes() {
        $this->load->model("Modeloventas");
        $infoheader["titulo"] = "Ventas: Royalty Ceramic";
        $infocontent["hoy"] = date("d/m/Y");
        $infocontent["clasificaciones"] = $this->Modeloventas->Clasificaciones();
        $infocontent["productos"] = $this->Modeloventas->Productos();
        $infocontent["modelos"] = $this->Modeloventas->Modelos(0);
        $infocontent["colores"] = $this->Modeloventas->Colores(0);
        $this->load->view('template/headerd', $infoheader);
        $this->load->view('ventas/Reportes', $infocontent);
        $this->load->view('template/footerd', '');
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
        $this->load->model("Modeloventas");
        $infocontent["productos"] = $this->Modeloventas->GenerarDetalleSeleccionado($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $por, $nombre);
        $this->load->view('ventas/GenerarDetalleSeleccionado', $infocontent);
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
        $this->load->model("Modeloventas");
        $infocontent["productos"] = $this->Modeloventas->GenerarReporte($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $adefecto);
        $this->load->view('ventas/GenerarReporte', $infocontent);
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
        $this->load->model("Modeloventas");
        $infocontent["por"] = $por;
        $infocontent["productos"] = $this->Modeloventas->GenerarConcentrado($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $por);
        $this->load->view('ventas/GenerarConcentrado', $infocontent);
    }
    public function ConsultarCliente()
    {
        $texto = $this->input->post_get('texto', TRUE);
        $infocontent["clientes"] = $this->Modeloventas->ConsultarCliente($texto);
        $this->load->view('ventas/ConsultarCliente', $infocontent);
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
    public function CambioContabilizar()
    {
        $pedido = $this->input->post_get('pedido_id', TRUE);
        $p = $this->Modelocedis->ObtenerPedido($pedido);
        $this->db->set("CheckContabilizar", !$p->CheckContabilizar);
        $this->db->where("IdPedidos", $pedido);
        $this->db->update("Pedidos");
        print "correcto";
    }
}
?>

