<?php

//return str_pad((int) $number,$n,"0",STR_PAD_LEFT); 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modelocedis extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function BuscarClaveProducto($clave) {
        $estaalmacen=$this->EstaEnAlmacen($clave);
        if(!$estaalmacen){
            $this->db->select("p.IdProductos, cp.Nombre as producto, c.Nombre as color, m.Nombre as modelo");
            $this->db->from("Productos p");
            $this->db->join("CProductos cp", "p.CProductosId=cp.IdCProductos");
            $this->db->join("Colores c", "p.ColoresId=c.IdColores");
            $this->db->join("Modelos m", "p.ModelosId=m.IdModelos");
            $this->db->where("p.Activo", 1);
            $this->db->where("p.IdProductos", $clave);
            //print($this->db->get_compiled_select());
            $fila = $this->db->get();
            if ($fila->num_rows() > 0) {
                return $fila->row();
            } else {
                return "No se encontró el producto";
            }
        }
        else
        {
            return "No se marcó salida de almacén";
        }
    }
    public function EstaEnAlmacen($idprod) {
        $this->db->select("i.IdInventariosAlmacen");
        $this->db->from("InventariosAlmacen i");
        $this->db->where("i.ProductosId", $idprod);
        $this->db->where("i.FechaSalida", null);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function TarimaEnAlmacen($idtarima) {
        $this->db->select("i.IdInventariosAlmacen");
        $this->db->from("InventariosAlmacen i");
        $this->db->where("i.TarimasId", $idtarima);
        $this->db->where("i.FechaSalida", null);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function BuscarClaveTarima($clave) {
        $estaalmacen=$this->TarimaEnAlmacen($clave);
        if(!$estaalmacen){
            $this->db->select("t.IdTarimas");
            $this->db->from("Tarimas t");
            $this->db->where("t.Activo", 1);
            $this->db->where("t.IdTarimas", $clave);
            $fila = $this->db->get();
            if ($fila->num_rows() > 0) {
                return $fila->row();
            } else {
                return "No se encontró la tarima";
            }
        }
        else
        {
            return "No se marcó salida de almacén";
        }
    }

    public function ProductoEnCedis($idproducto) {
        $this->db->select("i.IdInventariosCedis");
        $this->db->from("InventariosCedis i");
        $this->db->where("i.ProductosId", $idproducto);
        $this->db->where("i.Activo", 1);
        $this->db->where("i.FechaSalida", null);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function GuardarProductoCedis($idproducto) {
        $datos = array(
            'CedisId' => 1,
            'FechaEntrada' => date('Y-m-d | H:i:sa'),
            'ProductosId' => $idproducto,
            'UsuariosIdEntrada' => IdUsuario(),
            'Activo' => 1
        );
        
        $this->db->insert('InventariosCedis', $datos);
        //Historial captura
        $HistorialCaptura = array(
            'UsuariosId' => IdUsuario(),
            'MovimientosProductosId' => 6,
            'Activo' => 1,
            'ProductosId' => $idproducto,
            'Fecha' => date('Y-m-d | H:i:sa')
        );
        $this->db->insert('HistorialProducto', $HistorialCaptura);
    }

    public function SubirImagenPedido($ruta, $pedidoid) {
        $datos = array(
            'Ruta' => $ruta,
            'Fecha' => date('Y-m-d | H:i:sa'),
            'PedidosId' => $pedidoid,
            'UsuariosId' => IdUsuario(),
            'Activo' => 1
        );
        $this->db->insert('ImagenesPedidos', $datos);
    }

    public function GuardarProductosTarima($idtarima) {
        $this->db->select("d.ProductosId");
        $this->db->from("DetalleTarimas d");
        $this->db->where("d.TarimasId", $idtarima);
        $this->db->where("Activo", 1);
        $filas = $this->db->get();
        $existe = false;
        foreach ($filas->result() as $fila):
            if ($this->ProductoEnCedis($fila->ProductosId)) {
                $existe = true;
                return "Existe";
            }
        endforeach;
        foreach ($filas->result() as $fila):
            $this->GuardarProductoCedis($fila->ProductosId);
        endforeach;
        return "correcto";
    }

    public function BuscarProductoCedis($clave) {
        $this->db->select("p.IdProductos, cp.Nombre as producto, c.Nombre as color, m.Nombre as modelo");
        $this->db->from("Productos p");
        $this->db->join("CProductos cp", "p.CProductosId=cp.IdCProductos");
        $this->db->join("Colores c", "p.ColoresId=c.IdColores");
        $this->db->join("Modelos m", "p.ModelosId=m.IdModelos");
        $this->db->where("p.Activo", 1);
        $this->db->where("p.IdProductos", $clave);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            if ($this->ProductoEnCedis($clave)) {
                if ($this->VerificarProductoPedido($clave) == "correcto") {
                    return $fila->row();
                } else {
                    return "El producto ya se encuentra en un pedido";
                }
            } else {
                return "No está en cedis";
            }
        } else {
            return "No se encontró el producto";
        }
    }
  
    public function GuardarPedido($cliente) {
        $datos = array(
            'UsuariosId' => IdUsuario(),
            'Activo' => 1,
            'Cliente' => $cliente,
            'FechaRegistro' => date('Y-m-d | H:i:sa')
        );
        $this->db->insert("Pedidos", $datos);
        return $this->db->insert_id();
    }

    public function GuardarDetallePedido($idproducto, $idpedido) {
        if ($this->VerificarProductoPedido($idproducto) == "correcto") {
            if ($this->VerificarProductoPedidoVenta($idproducto, $idpedido) == "correcto") {
                $this->db->set("PedidosId", $idpedido);
                $this->db->set("FechaPresalida", date('Y-m-d | H:i:sa'));
                $this->db->where("ProductosId", $idproducto);
                $this->db->update("InventariosCedis");
                $this->GuardarTarimaAbierta($idproducto);
            } else if ($this->VerificarProductoPedidoVenta($idproducto, $idpedido) == "No solicitado") {
                return "No solicitado";
            } else {
                return "Fuera de límite";
            }
        } else {
            return "En pedido";
        }
    }

    public function EliminarImagenPedido($idimagen) {
        $this->db->set("Activo", 0);
        $this->db->where("IdImagenesPedidos", $idimagen);
        $this->db->update("ImagenesPedidos");
    }

    public function GuardarTarimaAbierta($idproducto) {
        $this->db->select("d.TarimasId");
        $this->db->from("DetalleTarimas d");
        $this->db->join("Tarimas t", "t.IdTarimas=d.TarimasId");
        $this->db->join("Productos p", "p.IdProductos=d.ProductosId");
        $this->db->where("d.Activo", 1);
        $this->db->where("t.Activo", 1);
        $this->db->where("p.IdProductos", $idproducto);
        $this->db->where("t.FechaApertura", null);
        $fila = $this->db->get()->row();
        if ($fila != null) {
            $this->db->set("FechaApertura", date('Y-m-d | H:i:sa'));
            $this->db->where("IdTarimas", $fila->TarimasId);
            $this->db->update("Tarimas");
        }
    }

    public function VerificarProductoPedido($idproducto) {
        //Si retorna correcto es que el producto está libre para asignarse a un pedido, si no es que ya se encuentra en un pedido
        $this->db->select("i.ProductosId");
        $this->db->from("InventariosCedis i");
        $this->db->where("i.Activo", 1);
        $this->db->where("i.ProductosId", $idproducto);
        $this->db->where("i.PedidosId", null);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return "correcto";
        } else {
            return "Ya tiene pedido";
        }
    }

    public function ObtenerPedidoVentaDeProducto($idproducto, $idpedido) {
        $producto = $this->ObtenerProducto($idproducto);
        $this->db->select("*");
        $this->db->from("PedidosVentas p");
        $this->db->where("Activo", 1);
        $this->db->where("p.CProductosId", $producto->CProductosId);
        $this->db->where("p.ColoresId", $producto->ColoresId);
        $this->db->where("p.ModelosId", $producto->ModelosId);
        $this->db->where("p.ClasificacionesId", $producto->ClasificacionesId);
        $this->db->where("p.PedidosId", $idpedido);
        $fila = $this->db->get()->row();
        return $fila;
    }

    public function VerificarProductoPedidoVenta($idproducto, $idpedido) {
        $pedidoventa = $this->ObtenerPedidoVentaDeProducto($idproducto, $idpedido);
        //Si retorna correcto es que el producto está dentro del límite configurado
        /* Se verifica si es mayor ya que si es igual + el producto que se va a agregar se pasaría del límite */
        if ($pedidoventa == null) {
            return "No solicitado";
        }
        if ($pedidoventa->Cantidad > $this->ObtenerProductosPedido($idpedido, $pedidoventa->IdPedidosVentas)) {
            return "correcto";
        } else {
            return "Fuera de límite";
        }
    }

    public function ListaCompletaPedidos() {
        $this->db->select("p.*");
        $this->db->from("Pedidos p");
        $this->db->where("p.Activo", 1);
        $this->db->where("p.FechaSalida", null);
        $fila = $this->db->get();
        return $fila;
    }

    public function ListaCompletaPedidosLiberados() {
        $this->db->select("p.*");
        $this->db->from("Pedidos p");
        $this->db->where("p.Activo", 1);
        $this->db->where("p.Estatus", "Liberado");
        $fila = $this->db->get();
        return $fila;
    }

    public function ListaCompletaPedidosCapturados() {
        $this->db->select("p.*");
        $this->db->from("Pedidos p");
        $this->db->where("p.Activo", 1);
        $this->db->where("p.Estatus", "Solicitado");
        $fila = $this->db->get();
        return $fila;
    }

    public function ListaCompletaPedidosEntregados() {
        $this->db->select("p.*");
        $this->db->from("Pedidos p");
        $this->db->where("p.Activo", 1);
        $this->db->where("p.Estatus", "Entregado");
        $fila = $this->db->get();
        return $fila;
    }

    public function ObtenerPedido($id) {
        $this->db->select("p.*");
        $this->db->from("Pedidos p");
        $this->db->where("p.IdPedidos", $id);
        $fila = $this->db->get()->row();
        return $fila;
    }
    public function ObtenerDevolucion($id) {
        $this->db->select("d.*");
        $this->db->from("Devoluciones d");
        $this->db->where("d.IdDevoluciones", $id);
        $fila = $this->db->get()->row();
        return $fila;
    }

    public function ListaImagenesPedido($pedidoid) {
        $this->db->select("i.*");
        $this->db->from("ImagenesPedidos i");
        $this->db->where("i.Activo", 1);
        $this->db->where("i.PedidosId", $pedidoid);
        $fila = $this->db->get();
        return $fila;
    }

    public function ProductosPedido($pedidoid) {
        $this->db->select("p.IdProductos, cp.Nombre as producto, c.Nombre as color, m.Nombre as modelo");
        $this->db->from("InventariosCedis i");
        $this->db->join("Productos p", "p.IdProductos=i.ProductosId");
        $this->db->join("CProductos cp", "p.CProductosId=cp.IdCProductos");
        $this->db->join("Colores c", "p.ColoresId=c.IdColores");
        $this->db->join("Modelos m", "p.ModelosId=m.IdModelos");
        $this->db->where("i.Activo", 1);
        $this->db->where("i.PedidosId", $pedidoid);
        $fila = $this->db->get();
        return $fila;
    }

    public function ObtenerProducto($id) {
        $this->db->select("*");
        $this->db->from("Productos p");
        $this->db->where("p.IdProductos", $id);
        $fila = $this->db->get()->row();
        return $fila;
    }

    public function ObtenerPedidoVenta($id) {
        $this->db->select("*");
        $this->db->from("PedidosVentas p");
        $this->db->where("p.IdPedidosVentas", $id);
        $fila = $this->db->get()->row();
        return $fila;
    }

    public function ObtenerProductosPedido($pedidoid, $pedidoventa) {
        $pedidoventa = $this->ObtenerPedidoVenta($pedidoventa);
        $this->db->select("count(*)as cuantos");
        $this->db->from("InventariosCedis i");
        $this->db->join("Productos p", "p.IdProductos=i.ProductosId");
        $this->db->join("CProductos cp", "p.CProductosId=cp.IdCProductos");
        $this->db->join("Colores c", "p.ColoresId=c.IdColores");
        $this->db->join("Modelos m", "p.ModelosId=m.IdModelos");
        $this->db->where("i.Activo", 1);
        $this->db->where("cp.IdCProductos", $pedidoventa->CProductosId);
        $this->db->where("c.IdColores", $pedidoventa->ColoresId);
        $this->db->where("m.IdModelos", $pedidoventa->ModelosId);
        $this->db->where("p.ClasificacionesId", $pedidoventa->ClasificacionesId);
        $this->db->where("i.PedidosId", $pedidoid);
        $fila = $this->db->get()->row()->cuantos;
        return $fila;
    }

    public function ProductosPedidoAgrupados($pedidoid) {
        $this->db->select("*");
        $this->db->from("PedidosVentas i");
        $this->db->where("i.Activo", 1);
        $this->db->where("i.PedidosId", $pedidoid);
        $fila = $this->db->get();
        return $fila;
    }
    public function SubProductosPedidoAgrupados($pedidoid) {
        $this->db->select("*");
        $this->db->from("SubPedidosVentas i");
        $this->db->where("i.Activo", 1);
        $this->db->where("i.PedidosId", $pedidoid);
        $fila = $this->db->get();
        return $fila;
    }

    public function UsuarioCreaPedido($pedidoid) {
        $this->db->select("concat(per.Nombre,' ',per.APaterno) as Nombre");
        $this->db->from("Pedidos p");
        $this->db->join("Usuarios u", "u.IdUsuarios=p.UsuariosId");
        $this->db->join("Personas per", "per.IdPersonas=u.PersonasId");
        $this->db->where("p.IdPedidos", $pedidoid);
        $fila = $this->db->get()->row()->Nombre;
        return $fila;
    }
    public function UsuarioLiberaCredito($pedidoid) {
        $this->db->select("concat(per.Nombre,' ',per.APaterno) as Nombre");
        $this->db->from("Pedidos p");
        $this->db->join("Usuarios u", "u.IdUsuarios=p.UsuarioLiberaId");
        $this->db->join("Personas per", "per.IdPersonas=u.PersonasId");
        $this->db->where("p.IdPedidos", $pedidoid);
        $fila = $this->db->get()->row()->Nombre;
        return $fila;
    }
    public function UsuarioEntregaPedido($pedidoid) {
        $this->db->select("concat(per.Nombre,' ',per.APaterno) as Nombre");        
        $this->db->from("Pedidos p");
        $this->db->join("Usuarios u", "u.IdUsuarios=p.UsuarioEntregaId");
        $this->db->join("Personas per", "per.IdPersonas=u.PersonasId");
        $this->db->where("p.IdPedidos", $pedidoid);
        $fila = $this->db->get()->row()->Nombre;
        return $fila;
    }
    public function ObtenerCliente($cliente_id) {
        $this->db->select("*");        
        $this->db->from("Clientes c");
        $this->db->where("c.IdClientes", $cliente_id);
        $fila = $this->db->get()->row();
        return $fila;
    }
    public function CodigoProducto($producto_id) {
        $producto = $this->ObtenerProducto($producto_id);
        $this->db->select("c.Clave");
        $this->db->from("Claves c");
        $this->db->where("c.CProductosId", $producto->CProductosId);
        $this->db->where("c.ModelosId", $producto->ModelosId);
        $this->db->where("c.ColoresId", $producto->ColoresId);
        $this->db->where("c.ClasificacionesId", $producto->ClasificacionesId);
        $fila = $this->db->get()->row()->Clave;
        return $fila;
    }
    public function ClaveProducto($CProductosId,$ModelosId,$ColoresId,$ClasificacionesId) {
        $this->db->select("c.Clave");
        $this->db->from("Claves c");
        $this->db->where("c.CProductosId", $CProductosId);
        $this->db->where("c.ModelosId", $ModelosId);
        $this->db->where("c.ColoresId", $ColoresId);
        $this->db->where("c.ClasificacionesId", $ClasificacionesId);
        $fila = $this->db->get()->row()->Clave;
        return $fila;
    }
    public function ResumenProductosPedido($pedidoid) {
        $this->db->select("count(*) as cantidad, cp.Nombre as producto, m.Nombre as modelo,c.Nombre as color,cl.Letra as clasificacion,p.IdProductos");
        $this->db->from("InventariosCedis i");
        $this->db->join("Productos p", "p.IdProductos=i.ProductosId");
        $this->db->join("CProductos cp", "p.CProductosId=cp.IdCProductos");
        $this->db->join("Modelos m", "p.ModelosId=m.IdModelos");
        $this->db->join("Colores c", "p.ColoresId=c.IdColores");
        $this->db->join("Clasificaciones cl", "p.ClasificacionesId=cl.IdClasificaciones");
        $this->db->where("i.Activo", 1);
        $this->db->where("i.PedidosId", $pedidoid);
        $this->db->group_by('p.CProductosId');
        $this->db->group_by('p.ModelosId');
        $this->db->group_by('p.ColoresId');
        $this->db->group_by('p.ClasificacionesId');
        $fila = $this->db->get();
        return $fila;
    }

    public function ResumenProductosPedidoAgrupados($pedidoid) {
        $this->db->select("cp.Nombre as producto, m.Nombre as modelo,c.Nombre as color, cl.Letra as clasificacion,Cantidad");
        $this->db->from("PedidosVentas p");
        $this->db->join("CProductos cp", "p.CProductosId=cp.IdCProductos");
        $this->db->join("Modelos m", "p.ModelosId=m.IdModelos");
        $this->db->join("Colores c", "p.ColoresId=c.IdColores");
        $this->db->join("Clasificaciones cl", "p.ClasificacionesId=cl.IdClasificaciones");
        $this->db->where("p.Activo", 1);
        $this->db->where("p.PedidosId", $pedidoid);
        $fila = $this->db->get();
        return $fila;
    }
    public function ResumenSubProductosPedidoAgrupados($pedidoid) {
        $this->db->select("p.Descripcion as producto,p.Cantidad,p.CGriferiaId,cg.Clave,p.IdSubPedidosVentas");
        $this->db->from("SubPedidosVentas p");
        $this->db->join("CGriferia cg", "cg.IdCGriferia=p.CGriferiaId");
        $this->db->where("p.Activo", 1);
        $this->db->where("p.PedidosId", $pedidoid);
        $fila = $this->db->get();
        return $fila;
    }
    public function SalidaPedido($pedidoid) {
        foreach ($this->ProductosPedido($pedidoid)->result() as $row) {
            $this->db->set("FechaSalida", date('Y-m-d | H:i:sa'));
            $this->db->where("ProductosId", $row->IdProductos);
            $this->db->update("InventariosCedis");
            //Historial captura
            $HistorialCaptura = array(
            'UsuariosId' => IdUsuario(),
            'MovimientosProductosId' => 8,
            'Activo' => 1,
            'ProductosId' => $row->IdProductos,
            'Fecha' => date('Y-m-d | H:i:sa')
        );
            $this->db->insert('HistorialProducto', $HistorialCaptura); 
        }
        /**Obtengo  los subproductos del pedido*/
        foreach ($this->ResumenSubProductosPedidoAgrupados($pedidoid)->result() as $sub) {
                $datos = array(
                    'CGriferiaId' => $sub->CGriferiaId,
                    'FechaSalida' => date('Y-m-d | H:i:sa'),
                    'Cantidad' => $sub->Cantidad,
                    'UsuariosId' => IdUsuario(),
                    'Activo' => 1
                );
                $this->db->insert('AlmacenSubproductos', $datos); 
        }

        $this->db->set("FechaSalida", date('Y-m-d | H:i:sa'));
        $this->db->set("Estatus", "Entregado");
        $this->db->set("UsuarioEntregaId", IdUsuario());
        $this->db->where("IdPedidos", $pedidoid);
        $this->db->update("Pedidos");

        
    }

    public function ListaModelos() {
        $this->db->select("*");
        $this->db->from("Modelos");
        $this->db->where("Activo", 1);
        $fila = $this->db->get();
        return $fila;
    }

    public function ListaProductos($modelo) {
        $this->db->select("p.*");
        $this->db->from("CProductosModelos cp");
        $this->db->join("CProductos p", "p.IdCProductos=cp.CProductosId");
        $this->db->where("cp.Activo", 1);
        $this->db->where("p.Activo", 1);
        $this->db->where("ModelosId", $modelo);
        $fila = $this->db->get();
        return $fila;
    }

    public function ListaColores($modelo) {
        $this->db->select("c.*");
        $this->db->from("ModelosColores mc");
        $this->db->join("Colores c", "c.IdColores=mc.ColoresId");
        $this->db->where("c.Activo", 1);
        $this->db->where("ModelosId", $modelo);
        $this->db->Order_by('c.ClaveImportacion');
        $fila = $this->db->get();
        return $fila;
    }

    public function ProductosCedis($modelo, $color, $clasificacion, $producto) {
        //print("SELECT count(*) as cuantos from InventariosCedis ic JOIN Productos p on p.IdProductos=ic.ProductosId JOIN CProductos cp on cp.IdCProductos=p.CProductosId where cp.IdCProductos= " . $producto . " AND Clasificacion(p.IdProductos)=" . $clasificacion . " AND ic.FechaSalida is null AND p.ModelosId= " . $modelo . " AND p.ColoresId= " . $color . " GROUP BY p.IdProductos");
        $query = $this->db->query("SELECT count(*) as cuantos from InventariosCedis ic JOIN Productos p on p.IdProductos=ic.ProductosId JOIN CProductos cp on cp.IdCProductos=p.CProductosId where cp.IdCProductos= " . $producto . " AND Clasificacion(p.IdProductos)=" . $clasificacion . " AND ic.FechaSalida is null AND p.ModelosId= " . $modelo . " AND ic.Activo=1 AND p.Activo=1  AND p.ColoresId= " . $color . " ");
        $row = $query->row();
        if (isset($row)) {
            return $row->cuantos;
        }
    }

    public function ProductosSinClasificar($modelo, $color, $producto) {
        $query = $this->db->query("SELECT count(*) as cuantos from Productos p JOIN CProductos cp on cp.IdCProductos=p.CProductosId where cp.IdCProductos= " . $producto . " AND p.ModelosId= " . $modelo . " AND p.Activo=1 AND p.Clasificado=0 AND p.ColoresId= " . $color . " ");
        $row = $query->row();
        if (isset($row)) {
            return $row->cuantos;
        }
    }

    public function ProductosPedidosVentas($modelo, $color, $clasificacion, $producto) {
        //print("SELECT count(*) as cuantos from PedidosVentas p JOIN Pedidos pe on pe.IdPedidos=p.PedidosId where p.CProductosId= " . $producto . " AND ClasificacionesId=" . $clasificacion . " AND p.Activo=1 AND p.ModelosId= " . $modelo . " AND pe.Activo=1 AND pe.Estatus='Liberado' AND  p.ColoresId= " . $color . " ");
        $query = $this->db->query("SELECT IFNULL(sum(p.Cantidad),0) as cuantos from PedidosVentas p JOIN Pedidos pe on pe.IdPedidos=p.PedidosId where p.CProductosId= " . $producto . " AND ClasificacionesId=" . $clasificacion . " AND p.Activo=1 AND p.ModelosId= " . $modelo . " AND pe.Activo=1 AND pe.Estatus='Liberado' AND  p.ColoresId= " . $color . " AND pe.CheckContabilizar=1");
        $row = $query->row();
        if (isset($row)) {
            return $row->cuantos;
        } else {
            return 0;
        }
    }

    public function ColorMaximosMinimos($productos, $maximo, $minimo) {
        //$query = $this->db->query("SELECT Maximo,Minimo from MaximosMinimos mm where mm.CProductosId= " . $producto . " AND ClasificacionesId=" . $clasificacion . " AND mm.ModelosId= " . $modelo . " AND mm.Activo=1 AND mm.ColoresId= " . $color . " order by IdMaximosMinimos desc");
        //$row = $query->row();
        if ($maximo != "--" && $minimo != "--") {
            if ($productos <= $minimo) {
                return "red";
            } else if ($productos > $minimo && $productos < $maximo) {
                return "green";
            } else {
                return "blue";
            }
        } else {
            return "gray";
        }
    }

    public function MaximoMinimo($modelo, $color, $clasificacion, $producto) {
        $query = $this->db->query("SELECT Maximo,Minimo from MaximosMinimos mm where mm.CProductosId= " . $producto . " AND ClasificacionesId=" . $clasificacion . " AND mm.ModelosId= " . $modelo . " AND mm.Activo=1 AND Tipo='CEDIS' AND mm.ColoresId= " . $color . " order by IdMaximosMinimos desc");
        $row = $query->row();
        if (isset($row)) {
            return $row;
        } else {
            return null;
        }
    }

    public function Clasificacion($producto_id) {
        $this->db->select("c.Letra,c.Color,h.FueraTono");
        $this->db->from("HistorialClasificacion h");
        $this->db->join("Clasificaciones c", "c.IdClasificaciones=h.ClasificacionesId");
        $this->db->where("h.ProductosId", $producto_id);
        $this->db->where("h.Activo", 1);
        $this->db->Order_by("h.IdHistorialClasificacion", "desc");
        $fila = $this->db->get()->row();
        if ($fila != null) {
            return $fila;
        } else {
            return "";
        }
    }

    public function GuardarMaximo($cproducto, $modelo, $color, $clasificacion, $valor) {
        $maximominimo = $this->MaximoMinimo($modelo, $color, $clasificacion, $cproducto);
        if ($maximominimo == null) {
            $datos = array(
                'ModelosId' => $modelo,
                'Fecha' => date('Y-m-d | H:i:sa'),
                'Maximo' => $valor,
                'ColoresId' => $color,
                'ClasificacionesId' => $clasificacion,
                'CProductosId' => $cproducto,
                'UsuariosId' => IdUsuario(),
                'Tipo' => "CEDIS",
                'Activo' => 1
            );
            $this->db->insert('MaximosMinimos', $datos);
        } else {
            $this->db->set("Maximo", $valor);
            $this->db->set("Fecha", date('Y-m-d | H:i:sa'));
            $this->db->set("UsuariosId", IdUsuario());
            $this->db->where("ModelosId", $modelo);
            $this->db->where("CProductosId", $cproducto);
            $this->db->where("ColoresId", $color);
            $this->db->where("ClasificacionesId", $clasificacion);
            $this->db->where("Tipo", "CEDIS");
            $this->db->update("MaximosMinimos");
        }
    }

    public function GuardarMinimo($cproducto, $modelo, $color, $clasificacion, $valor) {
        $maximominimo = $this->MaximoMinimo($modelo, $color, $clasificacion, $cproducto);
        if ($maximominimo == null) {
            $datos = array(
                'ModelosId' => $modelo,
                'Fecha' => date('Y-m-d | H:i:sa'),
                'Minimo' => $valor,
                'ColoresId' => $color,
                'ClasificacionesId' => $clasificacion,
                'CProductosId' => $cproducto,
                'UsuariosId' => IdUsuario(),
                'Tipo' => "CEDIS",
                'Activo' => 1
            );
            $this->db->insert('MaximosMinimos', $datos);
        } else {
            $this->db->set("Minimo", $valor);
            $this->db->set("Fecha", date('Y-m-d | H:i:sa'));
            $this->db->set("UsuariosId", IdUsuario());
            $this->db->where("ModelosId", $modelo);
            $this->db->where("CProductosId", $cproducto);
            $this->db->where("ColoresId", $color);
            $this->db->where("ClasificacionesId", $clasificacion);
            $this->db->where("Tipo", "CEDIS");
            $this->db->update("MaximosMinimos");
        }
    }

    public function GuardarDevolucion($cliente, $motivo, $responsable) {
        $datos = array(
            'Cliente' => $cliente,
            'Motivo' => $motivo,
            'Responsable' => $responsable,
            'UsuarioCapturaId' => IdUsuario(),
            'Activo' => 1,
            'VerificadaSupervisor' => "No",
            'FechaCaptura' => date('Y-m-d | H:i:sa')
        );
        $this->db->insert("Devoluciones", $datos);
        return $this->db->insert_id();
    }

    public function GuardarSubproducto($subproducto_id, $detalle_id) {
        $datos = array(
            'DetalleDevolucionesId' => $detalle_id,
            'SubproductosId' => $subproducto_id,
            'Verificado' => "No"
        );
        $this->db->insert("SubproductosDevoluciones", $datos);
        return $this->db->insert_id();
    }

    public function BuscarSubproducto($texto) {
        $query = $this->db->query("Select * from CGriferia where Descripcion like '%$texto%' or Clave like '%$texto%'");
        return $query;
    }

    public function DevolucionesCapturadas($fechainicio, $fechafin) {
        $fechainicio = $this->FechaIngles($fechainicio);
        $fechafin = $this->FechaIngles($fechafin);
        $query = $this->db->query("select * from Devoluciones d where d.Activo=1 and date(d.FechaCaptura) between '$fechainicio' and '$fechafin'");
        return $query;
    }

    public function DetalleDevolucionesCapturadas($devolucion_id) {
        $this->db->select("p.IdProductos, cp.Nombre as producto, c.Nombre as color, m.Nombre as modelo,dd.IdDetalleDevoluciones");
        $this->db->from("DetalleDevoluciones dd");
        $this->db->join("Devoluciones d", "dd.DevolucionesId=d.IdDevoluciones");
        $this->db->join("Productos p", "p.IdProductos=dd.ProductosId");
        $this->db->join("CProductos cp", "p.CProductosId=cp.IdCProductos");
        $this->db->join("Colores c", "p.ColoresId=c.IdColores");
        $this->db->join("Modelos m", "p.ModelosId=m.IdModelos");
        $this->db->where("p.Activo", 1);
        $this->db->where("d.Activo", 1);
        $this->db->where("dd.DevolucionesId", $devolucion_id);
        //$this->db->where("date(d.FechaCaptura)", date('Y-m-d'));
        return $this->db->get();
    }

    public function SubproductosDetalle($detalle_id) {
        $this->db->select("g.Descripcion,g.Clave,sd.Verificado,sd.IdSubproductosDevoluciones");
        $this->db->from("SubproductosDevoluciones sd");
        $this->db->join("DetalleDevoluciones dd", "dd.IdDetalleDevoluciones=sd.DetalleDevolucionesId");
        $this->db->join("CGriferia g", "g.IdCGriferia=sd.SubproductosId");
        $this->db->where("dd.IdDetalleDevoluciones", $detalle_id);
        return $this->db->get();
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

    public function ListarProductos() {
        $this->db->select('*');
        $this->db->from('CProductos cp');
        $this->db->where('cp.Activo', 1);
        $query = $this->db->get();
        return $query;
    }

    public function ListarModelos($id) {
        $this->db->select('m.Nombre, m.IdModelos, cpm.Imagen');
        $this->db->from('CProductos p');
        $this->db->join('CProductosModelos cpm', 'p.IdCProductos=cpm.CProductosId');
        $this->db->join('Modelos m', 'cpm.ModelosId=m.IdModelos');
        $this->db->where('p.Activo', 1);
        $this->db->where('cpm.Activo', 1);
        $this->db->where('p.IdCProductos', $id);
        $query = $this->db->get();
        return $query;
    }

    public function ListarColores($id) {
        $this->db->select('c.*');
        $this->db->from('Colores c');
        $this->db->join('ModelosColores mc', 'c.IdColores=mc.ColoresId');
        $this->db->join('Modelos m', 'mc.ModelosId=m.IdModelos');
        $this->db->where('c.Activo', 1);
        $this->db->where('m.IdModelos', $id);
        $this->db->Order_by('c.ClaveImportacion');
        $query = $this->db->get();
        return $query;
    }

    public function ListarClasificaciones() {
        $this->db->select('c.*');
        $this->db->from('Clasificaciones c');
        $this->db->where('c.Activo', 1);
        $query = $this->db->get();
        return $query;
    }

    public function GuardarProductos($prod, $mod, $col, $clasi) {
        //Se crea el producto
        $datos = array(
            'CProductosId' => $prod,
            'ColoresId' => $col,
            'UsuariosId' => IdUsuario(),
            'Activo' => 1,
            'Clasificado' => 1,
            'ModelosId' => $mod,
            'FechaCaptura' => date('Y-m-d | H:i:sa')
        );
        $this->db->insert("Productos", $datos);
        $idprod = $this->db->insert_id();
        //Historial captura
        $HistorialCaptura = array(
            'UsuariosId' => IdUsuario(),
            'MovimientosProductosId' => 14,
            'Activo' => 1,
            'ProductosId' => $idprod,
            'Fecha' => date('Y-m-d | H:i:sa')
        );
        $this->db->insert('HistorialProducto', $HistorialCaptura);

        //Clasificación
        $datos2 = array(
            'ProductosId' => $idprod,
            'FechaClasificacion' => date('Y-m-d | H:i:sa'),
            'ClasificacionesId' => $clasi,
            'FueraTono' => 0,
            'UsuariosId' => IdUsuario(),
            'Activo' => 1
        );
        /* Se actualiza la clasificacion actual */
        $this->db->set("ClasificacionesId", $clasi);
        $this->db->where("IdProductos", $idprod);
        $this->db->update("Productos");
        /* Fin clasificación actual */
        $this->db->insert('HistorialClasificacion', $datos2);
        //Historial Clasificación
        $Historial = array(
            'UsuariosId' => IdUsuario(),
            'MovimientosProductosId' => 15,
            'Activo' => 1,
            'ProductosId' => $idprod,
            'Fecha' => date('Y-m-d | H:i:sa')
        );
        $this->db->insert('HistorialProducto', $Historial);
        //Entrada al inventario
        $this->GuardarProductoCedis($idprod);
//        $HistorialEntrada = array(
//            'UsuariosId' => IdUsuario(),
//            'MovimientosProductosId' => 6,
//            'Activo' => 1,
//            'ProductosId' => $idprod,
//            'Fecha' => date('Y-m-d | H:i:sa')
//        );
        //$this->db->insert('HistorialProducto', $HistorialEntrada);
        return $idprod;
    }
    
    public function BuscarClaveProductoExpediente($clave) {
        $this->db->select("p.IdProductos, cp.Nombre as producto, c.Nombre as color, m.Nombre as modelo");
        $this->db->from("Productos p");
        $this->db->join("CProductos cp", "p.CProductosId=cp.IdCProductos");
        $this->db->join("Colores c", "p.ColoresId=c.IdColores");
        $this->db->join("Modelos m", "p.ModelosId=m.IdModelos");
        $this->db->where("p.Activo", 1);
        $this->db->where("p.IdProductos", $clave);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return $fila->row();
        } else {
            return "No se encontró el producto";
        }
    }

}

?>
