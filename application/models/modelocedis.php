<?php

//return str_pad((int) $number,$n,"0",STR_PAD_LEFT); Espero te sirva Tania del futuro, Gracias Tania del pasado.
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modelocedis extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function BuscarClaveProducto($clave) {
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

    public function BuscarClaveTarima($clave) {
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
            'FechaEntrada' => date('Y-m-d | h:i:sa'),
            'ProductosId' => $idproducto,
            'UsuariosIdEntrada' => 1,
            'Activo' => 1
        );
        $this->db->insert('InventariosCedis', $datos);
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
            'UsuariosId' => 1,
            'Activo' => 1,
            'Cliente' => $cliente,
            'FechaRegistro' => date('Y-m-d | h:i:sa')
        );
        $this->db->insert("Pedidos", $datos);
        return $this->db->insert_id();
    }

    public function GuardarDetallePedido($idproducto, $idpedido) {
        if ($this->VerificarProductoPedido($idproducto) == "correcto") {
            $this->db->set("PedidosId", $idpedido);
            $this->db->set("FechaPresalida", date('Y-m-d | h:i:sa'));
            $this->db->where("ProductosId", $idproducto);
            $this->db->update("InventariosCedis");
            $this->GuardarTarimaAbierta($idproducto);
        } else {
            return "En pedido";
        }
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
            $this->db->set("FechaApertura", date('Y-m-d | h:i:sa'));
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

    public function ListaCompletaPedidos() {
        $this->db->select("p.*");
        $this->db->from("Pedidos p");
        $this->db->where("p.Activo", 1);
        $this->db->where("p.FechaSalida", null);
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

    public function ResumenProductosPedido($pedidoid) {
        /*
          SELECT count(*),cp.Nombre,m.Nombre FROM `InventariosCedis` i
          join Productos p on p.IdProductos=i.ProductosId
          join CProductos cp on p.CProductosId=cp.IdCProductos
          join Modelos m on p.ModelosId=m.IdModelos
          where PedidosId=14
          GROUP BY p.CProductosId,p.ModelosId
         * $this->db->group_by('h.IdHornos');
         */
        $this->db->select("count(*) as cantidad, cp.Nombre as producto, m.Nombre as modelo");
        $this->db->from("InventariosCedis i");
        $this->db->join("Productos p", "p.IdProductos=i.ProductosId");
        $this->db->join("CProductos cp", "p.CProductosId=cp.IdCProductos");
        $this->db->join("Modelos m", "p.ModelosId=m.IdModelos");
        $this->db->where("i.Activo", 1);
        $this->db->where("i.PedidosId", $pedidoid);
        $this->db->group_by('p.CProductosId');
        $this->db->group_by('p.ModelosId');
        $fila = $this->db->get();
        return $fila;
    }

    public function SalidaPedido($pedidoid) {
        foreach ($this->ProductosPedido($pedidoid)->result() as $row) {
            $this->db->set("FechaSalida", date('Y-m-d | h:i:sa'));
            $this->db->where("ProductosId", $row->IdProductos);
            $this->db->update("InventariosCedis");
        }
        $this->db->set("FechaSalida", date('Y-m-d | h:i:sa'));
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
        $fila = $this->db->get();
        return $fila;
    }

    public function ProductosCedis($modelo, $color, $clasificacion, $producto) {
        //print("SELECT count(*) as cuantos from InventariosCedis ic JOIN Productos p on p.IdProductos=ic.ProductosId JOIN CProductos cp on cp.IdCProductos=p.CProductosId where cp.IdCProductos= " . $producto . " AND Clasificacion(p.IdProductos)=" . $clasificacion . " AND ic.FechaSalida is null AND p.ModelosId= " . $modelo . " AND p.ColoresId= " . $color . " GROUP BY p.IdProductos");
        $query = $this->db->query("SELECT count(*) as cuantos from InventariosCedis ic JOIN Productos p on p.IdProductos=ic.ProductosId JOIN CProductos cp on cp.IdCProductos=p.CProductosId where cp.IdCProductos= " . $producto . " AND Clasificacion(p.IdProductos)=" . $clasificacion . " AND ic.FechaSalida is null AND p.ModelosId= " . $modelo . " AND ic.Activo=1 AND p.ColoresId= " . $color . " ");
        $row = $query->row();
        if (isset($row)) {
            return $row->cuantos;
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
                'Fecha' => date('Y-m-d | h:i:sa'),
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
            $this->db->set("Fecha", date('Y-m-d | h:i:sa'));
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
                'Fecha' => date('Y-m-d | h:i:sa'),
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
            $this->db->set("Fecha", date('Y-m-d | h:i:sa'));
            $this->db->set("UsuariosId", IdUsuario());
            $this->db->where("ModelosId", $modelo);
            $this->db->where("CProductosId", $cproducto);
            $this->db->where("ColoresId", $color);
            $this->db->where("ClasificacionesId", $clasificacion);
            $this->db->where("Tipo", "CEDIS");
            $this->db->update("MaximosMinimos");
        }
    }

}

?>
