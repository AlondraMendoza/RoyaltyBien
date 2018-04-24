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
        $this->db->where('c.Activo', 1);
        $query = $this->db->get();
        return $query;
    }
    
    public function BuscarClave($clave){
        $this->db->select("*");
        $this->db->from("CGriferia c");
        $this->db->where("c.Activo", 1);
        $this->db->where("c.Clave", $clave);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return $fila->row();
        } else {
            return "No se encontró el producto";
        }
    }
    
    public function Existencias($id){
        $Entradas= $this->Entradas($id);
        $Salidas = $this->Salidas($id);
        $Data= $Entradas - $Salidas;
        return $Data;
    }
    
    public function Entradas($id){
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
    public function Salidas($id){
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
    
    public function SalidaGrif($id, $cantidad){
             $datos = array(
            'CGriferiaId'=> $id,
            'Cantidad'=> $cantidad,
            'UsuariosId'=>IdUsuario(),
            'Activo'=>1,
            );             
            $this->db->set('FechaSalida', 'NOW()', FALSE);
            $this->db->insert('AlmacenGriferia', $datos);  
            return "correcto";
    }
    
    public function ListarGriferiaGuardada($id, $cantidad){
       try {
             $datos = array(
            'CGriferiaId'=> $id,
            'Cantidad'=> $cantidad,
            'UsuariosId'=>IdUsuario(),
            'Activo'=>1,
            );             
            $this->db->set('FechaEntrada', 'NOW()', FALSE);
            $this->db->insert('AlmacenGriferia', $datos);  
            $idR = $this->db->insert_id();
            $this->db->select('*');
            $this->db->from('AlmacenGriferia a');
            $this->db->join('CGriferia c','a.CGriferiaId=c.IdCGriferia');
            $this->db->where('IdAlmacenGriferia', $idR);
            $query = $this->db->get();
            return $query;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            return "mal";
        }
    }
    
    public function BuscarClaveProd($clave){
        $this->db->select("p.IdProductos, cp.Nombre as producto, c.Nombre as color, m.Nombre as modelo");
        $this->db->from("Productos p");
        $this->db->join("CProductos cp","p.CProductosId=cp.IdCProductos");
        $this->db->join("Colores c","p.ColoresId=c.IdColores");
        $this->db->join("Modelos m","p.ModelosId=m.IdModelos");
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
        }
        else{
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
        }
        else{
            $this->GuardarProductoAlmacenP($idProducto);
        return "correcto";
        }
    }
    
    public function SalirTarima($idtarima) {
       $id= $this->BuscarEnAlmacen($idtarima);
           return $id;
    }
    
    public function SalirTarimaP($idproducto) {
       $id= $this->BuscarEnAlmacenP($idproducto);
           return $id;
    }
    
    public function BuscarEnAlmacenP($idproducto){
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
        );
        $this->db->set('FechaEntrada', 'NOW()', FALSE);
        $this->db->insert('InventariosAlmacen', $datos);
        //Historial tarima
        $HistorialEntrada= array('UsuariosId'=>IdUsuario(), 'MovimientosTarimasId'=>2,
                    'Activo'=>1, 'TarimasId'=>$idtarima);
        $this->db->set('Fecha', 'NOW()', FALSE);
        $this->db->insert('HistorialTarima', $HistorialEntrada);
        //Historial producto--- Para Inspeccionar con Tania
        $this->db->select("ProductosId");
        $this->db->from("DetalleTarimas");
        $this->db->where("TarimasId", $idtarima);
        $this->db->where("Activo", 1);
        $fila = $this->db->get();
        //if($fila->num_rows != 0) {
            foreach($fila->result() as $row) {
                $Id = $row->ProductosId;
                $HistorialEntradaProd = array('UsuariosId'=>IdUsuario(), 'MovimientosProductosId'=>5,
                    'Activo'=>1, 'ProductosId'=>$Id);
                $this->db->set('Fecha', 'NOW()', FALSE);
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
        );
        $this->db->set('FechaEntrada', 'NOW()', FALSE);
        $this->db->insert('InventariosAlmacen', $datos);
        //Historial
        $HistorialEntrada= array('UsuariosId'=>IdUsuario(), 'MovimientosProductosId'=>5,
                    'Activo'=>1, 'ProductosId'=>$idProducto);
        $this->db->set('Fecha', 'NOW()', FALSE);
        $this->db->insert('HistorialProducto', $HistorialEntrada);  
    }
    
    public function SalirProductoAlmacen($fila) {
        $datos = array(
            'FechaSalida'=> date('Y-m-d | h:i:sa'),
            'UsuariosIdSalida' => IdUsuario(),
        );
        $this->db->where('IdInventariosAlmacen', $fila);
        $this->db->update('InventariosAlmacen', $datos);
        $this->db->select("TarimasId");
        $this->db->from("InventariosAlmacen");
        $this->db->where("IdInventariosAlmacen", $fila);
        $id = $this->db->get()->row()->TarimasId;
        $HistorialSalida= array('UsuariosId'=>IdUsuario(), 'MovimientosTarimasId'=>3,
                    'Activo'=>1, 'TarimasId'=>$id);
        $this->db->set('Fecha', 'NOW()', FALSE);
        $this->db->insert('HistorialTarima', $HistorialSalida);
        return "correcto";
    }
    
    public function SalirProductoAlmacenP($fila) {
        $datos = array(
            'FechaSalida'=> date('Y-m-d | h:i:sa'),
            'UsuariosIdSalida' => IdUsuario(),
        );
        $this->db->where('IdInventariosAlmacen', $fila);
        $this->db->update('InventariosAlmacen', $datos);
        $this->db->select("ProductosId");
        $this->db->from("InventariosAlmacen");
        $this->db->where("IdInventariosAlmacen", $fila);
        $id = $this->db->get()->row()->ProductosId;
        $HistorialSalida= array('UsuariosId'=>IdUsuario(), 'MovimientosProductosId'=>7,
                    'Activo'=>1, 'ProductosId'=>$id);
        $this->db->set('Fecha', 'NOW()', FALSE);
        $this->db->insert('HistorialProducto', $HistorialSalida);
        return "correcto";
    }
    
    public function VerificarProd($id){
        $this->db->select("*");
        $this->db->from("InventariosAlmacen");
        $this->db->where("ProductosId", $id);
        $this->db->where("FechaSalida !=", null);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return "bien";
        }else{
            $this->db->select("*");
            $this->db->from("InventariosAlmacen");
            $this->db->where("ProductosId", $id);
            $linea= $this->db->get();
            if($linea->num_rows()<1){
                return "bien";
        }
        }
    }
    
    public function GuardarEntradaAlmacen($id){
         $datos = array(
            'AlmacenesId'=> 1,
            'ProductosId'=> $id,
            'UsuariosIdEntrada'=> IdUsuario(),
            );             
            $this->db->set('FechaEntrada', 'NOW()', FALSE);
            $this->db->insert('InventariosAlmacen', $datos);  
    }
    
    //Expedientes tarimas
    public function BuscarClaveTarimaExp($clave) {
        $this->db->select("t.IdTarimas, count(d.ProductosId) as Productos");
        $this->db->from("Tarimas t");
        $this->db->join("DetalleTarimas d","t.IdTarimas=d.TarimasId");
        $this->db->where("t.Activo", 1);
        $this->db->where("t.IdTarimas", $clave);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return $fila->row();
        } else {
            return "No se encontró la tarima";
        }
    }
    
    public function ObtenerProducto($tarima_id){
        $this->db->select("t.IdTarimas, d.ProductosId as clave, cp.Nombre, m.Nombre as modelo, c.Nombre as color, cl.Letra, cl.Color as Color1");
        $this->db->from("Tarimas t");
        $this->db->join("DetalleTarimas d", "t.IdTarimas=d.TarimasId");
        $this->db->join("Productos p","d.ProductosId=p.IdProductos");
        $this->db->join("CProductos cp","p.CProductosId=cp.IdCProductos");
        $this->db->join("Modelos m", "p.ModelosId=m.IdModelos");
        $this->db->join("Colores c","p.ColoresId=c.IdColores");
        $this->db->join("HistorialClasificacion h", "h.ProductosId=p.IdProductos");
        $this->db->join("Clasificaciones cl", "h.ClasificacionesId=cl.IdClasificaciones");
        $this->db->where("t.Activo", 1);
        $this->db->where("t.IdTarimas", $tarima_id);
        $this->db->Order_by("h.IdHistorialClasificacion", "desc");
        //print($this->db->get_compiled_select());
        $fila = $this->db->get();
        return $fila;
    }
    
    public function HistorialMovimientosTarima($tarima_id){
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
    
}
?>
