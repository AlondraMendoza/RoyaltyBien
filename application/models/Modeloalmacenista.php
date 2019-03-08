<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modeloalmacenista extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function ListarGriferia() {
        $this->db->select('*');
        $this->db->from('CGriferia c');
        $this->db->where('c.ClasificacionesSubproductosId', 4);
        $this->db->where('c.Activo', 1);
        $query = $this->db->get();
        return $query;
    }

    public function NombreUsuario($id) {
        $this->db->select("*");
        $this->db->from("Usuarios u");
        $this->db->join('Personas p', 'p.IdPersonas=u.PersonasId');
        $this->db->where("u.IdUsuarios", $id);
        $query = $this->db->get()->row();
        return $query;
    }

    public function BuscarClave($clave) {
        $this->db->select("*");
        $this->db->from("CGriferia c");
        $this->db->where("c.Activo", 1);
        $this->db->where("c.ClasificacionesSubproductosId", 4);
        $this->db->where("c.Clave", $clave);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return $fila->row();
        } else {
            return "No se encontró el producto";
        }
    }

    public function BuscarClaveSubproductos($clave) {

        $this->db->select("*");
        $this->db->from("CGriferia c");
        $this->db->where("c.Activo", 1);
        //$this->db->where("c.ClasificacionesSubproductosId", 4);
        $this->db->where("c.Clave", $clave);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return $fila->row();
        } else {
            return "No se encontró el producto";
        }
    }

    public function Existencias($id) {
        $Entradas = $this->Entradas($id);
        $Salidas = $this->Salidas($id);
        $Data = $Entradas - $Salidas;
        return $Data;
    }

    public function ExistenciasSubproductos($id) {
        $Entradas = $this->EntradasSubproductos($id);
        $Salidas = $this->SalidasSubproductos($id);
        $Data = $Entradas - $Salidas;
        return $Data;
    }

    public function EntradasSubproductos($id) {
        $this->db->select("sum(cantidad) as entradas");
        $this->db->from("AlmacenSubproductos a");
        $this->db->where("a.Activo", 1);
        $this->db->where("a.CGriferiaId", $id);
        $this->db->where("a.FechaSalida IS null");
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return $fila->row()->entradas;
        } else {
            return 0;
        }
    }

    public function Tipo($id) {
        $this->db->select("*");
        $this->db->from("AlmacenSubproductos a");
        $this->db->where("a.IdAlmacenSubproductos", $id);
        $fila = $this->db->get();
        if ($fila->row()->FechaSalida == null) {
            return "Entrada";
        } else {
            return "Salida";
        }
    }

    public function Fecha($id) {
        $this->db->select("*");
        $this->db->from("AlmacenSubproductos a");
        $this->db->where("a.IdAlmacenSubproductos", $id);
        $fila = $this->db->get();
        if ($fila->row()->FechaSalida == null) {
            return $fila->row()->FechaEntrada;
        } else {
            return $fila->row()->FechaSalida;
        }
    }

    public function SalidasSubproductos($id) {
        $this->db->select("sum(cantidad) as salidas");
        $this->db->from("AlmacenSubproductos a");
        $this->db->where("a.Activo", 1);
        $this->db->where("a.CGriferiaId", $id);
        $this->db->where("a.FechaEntrada IS null");
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return $fila->row()->salidas;
        } else {
            return 0;
        }
    }

    public function Entradas($id) {
        $this->db->select("sum(cantidad) as entradas");
        $this->db->from("AlmacenGriferia a");
        $this->db->where("a.Activo", 1);
        $this->db->where("a.CGriferiaId", $id);
        $this->db->where("a.FechaSalida IS null");
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return $fila->row()->entradas;
        } else {
            return 0;
        }
    }

    public function Salidas($id) {
        $this->db->select("sum(cantidad) as salidas");
        $this->db->from("AlmacenGriferia a");
        $this->db->where("a.Activo", 1);
        $this->db->where("a.CGriferiaId", $id);
        $this->db->where("a.FechaEntrada IS null");
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return $fila->row()->salidas;
        } else {
            return 0;
        }
    }

    public function SalidaGrif($id, $cantidad) {
        $datos = array(
            'CGriferiaId' => $id,
            'Cantidad' => $cantidad,
            'UsuariosId' => IdUsuario(),
            'Activo' => 1,
            'FechaSalida' => date('Y-m-d | H:i:sa')
        );
        //$this->db->set('FechaSalida', 'NOW()', FALSE);
        $this->db->insert('AlmacenGriferia', $datos);
        return "correcto";
    }

    public function SalidaSub($id, $cantidad) {
        $datos = array(
            'CGriferiaId' => $id,
            'Cantidad' => $cantidad,
            'UsuariosId' => IdUsuario(),
            'Activo' => 1,
            'FechaSalida' => date('Y-m-d | H:i:sa')
        );
        //$this->db->set('FechaSalida', 'NOW()', FALSE);
        $this->db->insert('AlmacenSubproductos', $datos);
        return "correcto";
    }

    public function ListarGriferiaGuardada($id, $cantidad) {
        try {
            $datos = array(
                'CGriferiaId' => $id,
                'Cantidad' => $cantidad,
                'UsuariosId' => IdUsuario(),
                'Activo' => 1,
                'FechaEntrada' => date('Y-m-d | H:i:sa')
            );
            //$this->db->set('FechaEntrada', 'NOW()', FALSE);
            $this->db->insert('AlmacenGriferia', $datos);
            $idR = $this->db->insert_id();
            $this->db->select('*');
            $this->db->from('AlmacenGriferia a');
            $this->db->join('CGriferia c', 'a.CGriferiaId=c.IdCGriferia');
            $this->db->where('IdAlmacenGriferia', $idR);
            $query = $this->db->get();
            return $query;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            return "mal";
        }
    }

    public function ListarSubproductosDetalle() {
        $this->db->select('*');
        $this->db->from('AlmacenSubproductos a');
        $this->db->join('CGriferia c', 'a.CGriferiaId=c.IdCGriferia');
        $this->db->where('a.Activo', 1);
        $query = $this->db->get();
        return $query;
    }

    public function ListarSubproductosUnicos() {
        $this->db->select('*');
        $this->db->from('AlmacenSubproductos a');
        $this->db->join('CGriferia c', 'a.CGriferiaId=c.IdCGriferia');
        $this->db->where('c.Activo', 1);
        $this->db->where('a.Activo', 1);
        $this->db->group_by('c.IdCGriferia');
        $query = $this->db->get();
        return $query;
    }

    public function GuardarSubproducto($id, $cantidad) {
        $datos = array(
            'CGriferiaId' => $id,
            'Cantidad' => $cantidad,
            'UsuariosId' => IdUsuario(),
            'Activo' => 1,
            'FechaEntrada' => date('Y-m-d | H:i:sa')
        );
        //$this->db->set('FechaEntrada', 'NOW()', FALSE);
        $this->db->insert('AlmacenSubproductos', $datos);
        $idR = $this->db->insert_id();
        return $idR;
    }

    public function BuscarClaveProd($clave) {
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

    //por tarima
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

    // por producto
    public function BuscarClaveTarimaP($clave) {
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

    public function BuscarClaveTarima2($clave) {
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

    public function GuardarProductosTarima($idtarima) {
        $existe = false;
        if ($this->ProductoEnAlmacen($idtarima)) {
            $existe = true;
            return "Existe";
        } else {
            $this->GuardarProductoAlmacen($idtarima);
            return "correcto";
        }
    }

    //Por producto
    public function GuardarProductosTarimaP($idProducto) {
        $existe = false;
        if ($this->ProductoEnAlmacenP($idProducto)) {
            $existe = true;
            return "Existe";
        } else {
            $this->GuardarProductoAlmacenP($idProducto);
            return "correcto";
        }
    }

    public function SalirTarima($idtarima) {
        $id = $this->BuscarEnAlmacen($idtarima);
        return $id;
    }

    public function SalirTarimaP($idproducto) {
        $id = $this->BuscarEnAlmacenP($idproducto);
        return $id;
    }

    public function BuscarEnAlmacenP($idproducto) {
        $this->db->select("i.IdInventariosAlmacen");
        $this->db->from("InventariosAlmacen i");
        $this->db->where("i.ProductosId", $idproducto);
        $this->db->where("i.FechaSalida", null);
        $fila = $this->db->get()->row()->IdInventariosAlmacen;
        return $fila;
    }

    public function ProductoEnAlmacen($idtarima) {
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

    public function ProductoEnAlmacenP($idProducto) {
        $this->db->select("i.IdInventariosAlmacen");
        $this->db->from("InventariosAlmacen i");
        $this->db->where("i.ProductosId", $idProducto);
        $this->db->where("i.FechaSalida", null);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function BuscarEnAlmacen($idtarima) {
        $this->db->select("i.IdInventariosAlmacen");
        $this->db->from("InventariosAlmacen i");
        $this->db->where("i.TarimasId", $idtarima);
        $this->db->where("i.FechaSalida", null);
        $fila = $this->db->get()->row()->IdInventariosAlmacen;
        return $fila;
    }

    public function GuardarProductoAlmacen($idtarima) {
        $datos = array(
            'AlmacenesId' => 1,
            'TarimasId' => $idtarima,
            'UsuariosIdEntrada' => IdUsuario(),
            'FechaEntrada' => date('Y-m-d | H:i:sa')
        );
        //$this->db->set('FechaEntrada', 'NOW()', FALSE);
        $this->db->insert('InventariosAlmacen', $datos);
        //Historial tarima
        $HistorialEntrada = array('Fecha' => date('Y-m-d | H:i:sa'), 'UsuariosId' => IdUsuario(), 'MovimientosTarimasId' => 2,
            'Activo' => 1, 'TarimasId' => $idtarima);
        //$this->db->set('Fecha', 'NOW()', FALSE);
        $this->db->insert('HistorialTarima', $HistorialEntrada);
        //Historial producto--- Para Inspeccionar con Tania
        $this->db->select("ProductosId");
        $this->db->from("DetalleTarimas");
        $this->db->where("TarimasId", $idtarima);
        $this->db->where("Activo", 1);
        $fila = $this->db->get();
        //if($fila->num_rows != 0) {
        foreach ($fila->result() as $row) {
            $Id = $row->ProductosId;
            $HistorialEntradaProd = array('Fecha' => date('Y-m-d | H:i:sa'), 'UsuariosId' => IdUsuario(), 'MovimientosProductosId' => 5,
                'Activo' => 1, 'ProductosId' => $Id);
            //$this->db->set('Fecha', 'NOW()', FALSE);
            $this->db->insert('HistorialProducto', $HistorialEntradaProd);
        }
        //}
    }

    //Por producto
    public function GuardarProductoAlmacenP($idProducto) {
        $datos = array(
            'AlmacenesId' => 1,
            'ProductosId' => $idProducto,
            'UsuariosIdEntrada' => IdUsuario(),
            'FechaEntrada' => date('Y-m-d | H:i:sa')
        );
        //$this->db->set('FechaEntrada', 'NOW()', FALSE);
        $this->db->insert('InventariosAlmacen', $datos);
        //Historial
        $HistorialEntrada = array('Fecha' => date('Y-m-d | H:i:sa'), 'UsuariosId' => IdUsuario(), 'MovimientosProductosId' => 5,
            'Activo' => 1, 'ProductosId' => $idProducto);
        //$this->db->set('Fecha', 'NOW()', FALSE);
        $this->db->insert('HistorialProducto', $HistorialEntrada);
    }

    public function SalirProductoAlmacen($fila) {
        $datos = array(
            'FechaSalida' => date('Y-m-d | H:i:sa'),
            'UsuariosIdSalida' => IdUsuario(),
        );
        $this->db->where('IdInventariosAlmacen', $fila);
        $this->db->update('InventariosAlmacen', $datos);
        $this->db->select("TarimasId");
        $this->db->from("InventariosAlmacen");
        $this->db->where("IdInventariosAlmacen", $fila);
        $id = $this->db->get()->row()->TarimasId;
        $HistorialSalida = array('Fecha' => date('Y-m-d | H:i:sa'), 'UsuariosId' => IdUsuario(), 'MovimientosTarimasId' => 3,
            'Activo' => 1, 'TarimasId' => $id);
        //$this->db->set('Fecha', 'NOW()', FALSE);
        $this->db->insert('HistorialTarima', $HistorialSalida);
        return "correcto";
    }

    public function SalirProductoAlmacenP($fila) {
        $datos = array(
            'FechaSalida' => date('Y-m-d | H:i:sa'),
            'UsuariosIdSalida' => IdUsuario(),
        );
        $this->db->where('IdInventariosAlmacen', $fila);
        $this->db->update('InventariosAlmacen', $datos);
        $this->db->select("ProductosId");
        $this->db->from("InventariosAlmacen");
        $this->db->where("IdInventariosAlmacen", $fila);
        $id = $this->db->get()->row()->ProductosId;
        $HistorialSalida = array('Fecha' => date('Y-m-d | H:i:sa'), 'UsuariosId' => IdUsuario(), 'MovimientosProductosId' => 7,
            'Activo' => 1, 'ProductosId' => $id);
        //$this->db->set('Fecha', 'NOW()', FALSE);
        $this->db->insert('HistorialProducto', $HistorialSalida);
        return "correcto";
    }
    
    public function GuardarAccidente($idproducto, $Responsable, $Motivo) {
        $datos = array(
            'CulpableAccidente'=> $Responsable,
            'Motivo' => $Motivo,
            'Tipo' => "Producto",
            'TarimasId' => null,
            'Fecha' => date('Y-m-d | H:i:sa'),
            'UsuariosId' => IdUsuario(),
            'Activo' => 1,
        );
        $this->db->insert('Accidentes', $datos);
        $id=$this->db->insert_id();
        $datos2 = array(
            'AccidentesId'=> $id,
            'ProductosId'=> $idproducto,
            'Procesado'=>0,
            'Dañado'=>1,
        );
        $this->db->insert('DetalleAccidentes', $datos2);
        //Historial 
        $Historial = array('Fecha' => date('Y-m-d | H:i:sa'), 'UsuariosId' => IdUsuario(), 'MovimientosProductosId' => 10,
            'Activo' => 1, 'ProductosId' => $idproducto);
        //$this->db->set('Fecha', 'NOW()', FALSE);
        $this->db->insert('HistorialProducto', $Historial);
        return "correcto";
    }
    
    //Accidente tarima
     public function GuardarAccidenteT($idtarima, $Responsable, $Motivo) {
        $datos = array(
            'CulpableAccidente'=> $Responsable,
            'Motivo' => $Motivo,
            'Tipo' => "Tarima",
            'TarimasId' => $idtarima,
            'Fecha' => date('Y-m-d | H:i:sa'),
            'UsuariosId' => IdUsuario(),
            'Activo' => 1,
        );
        $this->db->insert('Accidentes', $datos);
        $id=$this->db->insert_id();
        //Historial 
        $Historial= array('Fecha' => date('Y-m-d | H:i:sa'), 'UsuariosId' => IdUsuario(), 'MovimientosTarimasId' => 7,
            'Activo' => 1, 'TarimasId' => $idtarima);
        //$this->db->set('Fecha', 'NOW()', FALSE);
        $this->db->insert('HistorialTarima', $Historial);
        return "correcto";
    }

    public function VerificarProd($id) {
        $this->db->select("*");
        $this->db->from("InventariosAlmacen");
        $this->db->where("ProductosId", $id);
        $this->db->where("FechaSalida !=", null);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return "bien";
        } else {
            $this->db->select("*");
            $this->db->from("InventariosAlmacen");
            $this->db->where("ProductosId", $id);
            $linea = $this->db->get();
            if ($linea->num_rows() < 1) {
                return "bien";
            }
        }
    }

    public function GuardarEntradaAlmacen($id) {
        $datos = array(
            'AlmacenesId' => 1,
            'ProductosId' => $id,
            'UsuariosIdEntrada' => IdUsuario(),
            'FechaEntrada' => date('Y-m-d | H:i:sa')
        );
        //$this->db->set('FechaEntrada', 'NOW()', FALSE);
        $this->db->insert('InventariosAlmacen', $datos);
    }

    //Expedientes tarimas
    public function BuscarClaveTarimaExp($clave) {
        $this->db->select("t.IdTarimas, count(d.ProductosId) as Productos");
        $this->db->from("Tarimas t");
        $this->db->join("DetalleTarimas d", "t.IdTarimas=d.TarimasId");
        $this->db->where("t.Activo", 1);
        $this->db->where("t.IdTarimas", $clave);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return $fila->row();
        } else {
            return "No se encontró la tarima";
        }
    }

    public function ObtenerProducto($tarima_id) {
        $this->db->select("t.IdTarimas, d.ProductosId as clave, cp.Nombre, m.Nombre as modelo, c.Nombre as color, cl.Letra, cl.Color as Color1");
        $this->db->from("Tarimas t");
        $this->db->join("DetalleTarimas d", "t.IdTarimas=d.TarimasId");
        $this->db->join("Productos p", "d.ProductosId=p.IdProductos");
        $this->db->join("CProductos cp", "p.CProductosId=cp.IdCProductos");
        $this->db->join("Modelos m", "p.ModelosId=m.IdModelos");
        $this->db->join("Colores c", "p.ColoresId=c.IdColores");
        $this->db->join("HistorialClasificacion h", "h.ProductosId=p.IdProductos");
        $this->db->join("Clasificaciones cl", "h.ClasificacionesId=cl.IdClasificaciones");
        $this->db->where("t.Activo", 1);
        $this->db->where("t.IdTarimas", $tarima_id);
        $this->db->Order_by("h.IdHistorialClasificacion", "desc");
        //print($this->db->get_compiled_select());
        $fila = $this->db->get();
        return $fila;
    }

    public function HistorialMovimientosTarima($tarima_id) {
        $this->db->select("t.*,h.*,mt.Nombre as Movimiento,CONCAT(per.Nombre,per.APaterno) as Persona");
        $this->db->from("HistorialTarima h");
        $this->db->join("MovimientosTarimas mt", "mt.IdMovimientosTarimas=h.MovimientosTarimasId");
        $this->db->join("Usuarios u", "u.IdUsuarios=h.UsuariosId");
        $this->db->join("Personas per", "per.IdPersonas=u.PersonasId");
        $this->db->join("Tarimas t", "t.IdTarimas=h.TarimasId");
        $this->db->where("t.IdTarimas", $tarima_id);
        $this->db->where("h.Activo", 1);
        $fila = $this->db->get();
        return $fila;
    }

    public function Tarima($idtarima) {
        $this->db->select("*");
        $this->db->from("Tarimas");
        $this->db->where("IdTarimas", $idtarima);
        return $this->db->get()->row();
    }

    public function CodigoBarrasTarimaTexto($tarima_id) {
        $tarima = $this->Tarima($tarima_id);
        $fecha = date_format(date_create($tarima->FechaCaptura), 'dmY');
        $codigo = $fecha . "_" . str_pad($tarima->IdTarimas, 10);
        return $codigo;
    }

    public function Ubicacion($tarima_id) {
        $this->db->select("mt.Lugar");
        $this->db->from("HistorialTarima h");
        $this->db->join("MovimientosTarimas mt", "mt.IdMovimientosTarimas=h.MovimientosTarimasId");
        $this->db->where("h.TarimasId", $tarima_id);
        $this->db->where("h.Activo", 1);
        $this->db->Order_by("h.IdHistorialTarima", "desc");
        $fila = $this->db->get()->row();
        if ($fila != null) {
            return $fila->Lugar;
        } else {
            return "";
        }
        return $fila;
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

    public function ProductosAlmacen($modelo, $color, $clasificacion, $producto) {
        //Productos Solos(fuera de tarima)
        $query2 = $this->db->query("SELECT count(*) as cuantos from InventariosAlmacen ia"
                . " JOIN Productos p on ia.ProductosId=p.IdProductos JOIN"
                . " CProductos cp on cp.IdCProductos= p.CProductosId where cp.IdCProductos= " . $producto . " "
                . " AND  Clasificacion(p.IdProductos)= " . $clasificacion . " AND ia.FechaSalida is null "
                . " AND p.ModelosId= " . $modelo . " AND p.ColoresId= " . $color . " ");
        $row = $query2->row();
        if (isset($row)) {
            return $row->cuantos;
        }
    }
    
    public function ProductosTarima($modelo, $color, $clasificacion, $producto) {
        //Productos en tarima
        $query2 = $this->db->query("SELECT count(*) as cuantos from InventariosAlmacen ia"
                . " JOIN Tarimas t on ia.TarimasId=t.IdTarimas JOIN DetalleTarimas dt "
                . "on dt.TarimasId=t.IdTarimas JOIN Productos p on p.IdProductos=dt.ProductosId JOIN"
                . " CProductos cp on cp.IdCProductos= p.CProductosId where cp.IdCProductos= " . $producto . " "
                . " AND  Clasificacion(p.IdProductos)= " . $clasificacion . " AND ia.FechaSalida is null "
                . " AND p.ModelosId= " . $modelo . " AND p.ColoresId= " . $color . " ");
        $row = $query2->row();
        if (isset($row)) {
            return $row->cuantos;
        }
    }
    
    public function BuscarEnTarima($idProducto, $idTarima){
        $this->db->select("dt.*");
        $this->db->from("DetalleTarimas dt");
        $this->db->where("dt.TarimasId", $idTarima);
        $this->db->where("dt.ProductosId", $idProducto);
        $fila = $this->db->get()->row();
        return $fila;
    }
    
    public function GuardarDetalle($idProducto, $idTarima){
        $this->db->select("a.IdAccidentes");
        $this->db->from("Accidentes a");
        $this->db->where("a.TarimasId", $idTarima);
        $ida = $this->db->get()->row();
        $datos = array(
            'AccidentesId'=> $ida->IdAccidentes,
            'ProductosId'=> $idProducto,
            'Procesado'=>0,
            'Dañado'=>1,
        );
        $this->db->insert('DetalleAccidentes', $datos);
        //Historial 
        $Historial= array('Fecha' => date('Y-m-d | H:i:sa'), 'UsuariosId' => IdUsuario(), 'MovimientosProductosId' => 10,
            'Activo' => 1, 'ProductosId' => $idProducto);
       // $this->db->set('Fecha', 'NOW()', FALSE);
        $this->db->insert('HistorialProducto', $Historial);
        return "correcto";
        
    }
    
    public function ProductosEnAlmacenTotal($ModelosId, $ColoresId, $ClasificacionesId, $CProductosId){
        $ProductosTarima = $this->ProductosTarima($ModelosId, $ColoresId, $ClasificacionesId, $CProductosId);
        $ProductoSolo = $this->ProductosAlmacen($ModelosId, $ColoresId, $ClasificacionesId, $CProductosId);
        $Data = $ProductosTarima + $ProductoSolo;
        return $Data;
    }
 
    public function ObtenerModelo($id) {
        $query = $this->db->query("select * from Modelos where IdModelos=$id");
        $fila = $query->row();
        if ($fila != null) {
            return $query->row();
        } else {
            return null;
        }
    }
    
    public function AbrirTarima($idtarima) {
        $datos = array(
            'FechaApertura' => date('Y-m-d | H:i:sa')
        );
        $this->db->where('IdTarimas', $idtarima);
        $this->db->update('Tarimas', $datos);
        
        $historial = array(
            'Fecha'=> date('Y-m-d | H:i:sa'),
            'UsuariosId'=> IdUsuario(),
            'MovimientosTarimasId'=>6,
            'TarimasId'=>$idtarima,
            'Activo'=>1
        );
        $this->db->insert('HistorialTarima', $historial);
        
    }
    
    public function GuardarPeticion($idTarima) {
        $datos= array(
            'TarimasId'=> $idTarima,
            'UsuarioSolicita'=> IdUsuario(),
            'FechaSolicita'=> date('Y-m-d | H:i:sa'),
            'Activo'=>1
        );
        $this->db->insert('TarimasPendientes', $datos);
        return "correcto";
        
    }
    
    public function TarimaenPendiente($tarima_id) {
        $this->db->select("tp.*");
        $this->db->from("TarimasPendientes tp");
        $this->db->where("tp.TarimasId", $tarima_id);
        $fila = $this->db->get()->row();
        return $fila;
        
    }
    
    public function SalidaEsp($idTarima){
        $datos = array(
            'FechaSalida'=>date('Y-m-d | H:i:sa'),
            'UsuariosIdSalida'=> IdUsuario()
        );
        $this->db->where('TarimasId', $idTarima);
        $this->db->update('InventariosAlmacen', $datos);
        return "correcto";
    }
    
    public function GuardadoEspecial($idTarima){
        $this->db->select("dt.ProductosId");
        $this->db->from("DetalleTarimas dt");
        $this->db->where("dt.TarimasId", $idTarima);
        $fila = $this->db->get();
        foreach ($fila->result() as $f){
            $this->GuardarProductoAlmacenP($f->ProductosId);
        }
    }
    
    public function TarimasDestruidas($tarima_id){
        $this->db->select('t.*');
        $this->db->from('Tarimas t');
        $this->db->where('t.IdTarimas', $tarima_id);
        $this->db->where('t.Activo',1);
        $fila = $this->db->get()->row();
        return $fila;
    }
    
    public function BuscarSubproducto($texto) {
        $query = $this->db->query("Select * from CGriferia where Descripcion"
                . " like '%$texto%' or Clave like '%$texto%'");
        return $query;
    }
    
    public function GuardarSubproducto2($subproducto_id, $detalle_id) {
        $datos = array(
            'DetalleDevolucionesId' => $detalle_id,
            'SubproductosId' => $subproducto_id,
            'Verificado' => "No"
        );
        $this->db->insert("SubproductosDevoluciones", $datos);
        return $this->db->insert_id();
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
    
    public function ObtenerDevolucion($id) {
        $this->db->select("d.*");
        $this->db->from("Devoluciones d");
        $this->db->where("d.IdDevoluciones", $id);
        $fila = $this->db->get()->row();
        return $fila;
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
    
}

?>
