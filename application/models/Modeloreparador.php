<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modeloreparador extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function ObtenerProducto($producto_id) {
        $this->db->select("p.*,cpm.Imagen as foto,cp.Nombre as NombreProducto,c.Nombre as Color,m.Nombre as Modelo");
        $this->db->from("Productos p");
        $this->db->join("CProductosModelos cpm", "p.ModelosId=cpm.ModelosId AND p.CProductosId=cpm.CProductosId");
        $this->db->join("CProductos cp", "p.CProductosId=cp.IdCProductos");
        $this->db->join("Colores c", "p.ColoresId=c.IdColores");
        $this->db->join("Modelos m", "p.ModelosId=m.IdModelos");
        $this->db->join("HistorialClasificacion hc","p.IdProductos=hc.ProductosId");
        $this->db->where("p.IdProductos", $producto_id);
        //$this->db->where("hc.ClasificacionesId", 5);
        // print($this->db->get_compiled_select());
        $fila = $this->db->get()->row();
        //print($producto_id);
        return $fila;
    }
    
    public function ObtenerDefectos($producto_id){
        $this->db->select("d.Nombre, hc.FueraTono");
        $this->db->from("Defectos d");
        $this->db->join("HistorialClasificacionDefectos hcd","d.IdDefectos=hcd.DefectosId");
        $this->db->join("HistorialClasificacion hc","hcd.HistorialClasificacionId= hc.IdHistorialClasificacion");
        $this->db->where("hc.ProductosId",$producto_id);
        $this->db->order_by("hc.IdHistorialClasificacion","desc");
        $fila = $this->db->get();
        return $fila;
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
    
    public function CodigoBarrasTexto($producto_id) {
        $producto = $this->Producto($producto_id);
        $fecha = date_format(date_create($producto->FechaCaptura), 'dmY');
        $codigo = $fecha . "-" . str_pad($producto->IdProductos, 10);
        return $codigo;
    }
    
    public function Producto($idprod) {
        $this->db->select("*");
        $this->db->from("Productos");
        $this->db->where("IdProductos", $idprod);
        return $this->db->get()->row();
    }
    
    public function ObtenerDiagnosticos() {
        $this->db->select("*");
        $this->db->from("CReparaciones");
        $this->db->where("Activo", true);
        return $this->db->get();
        
    }
    
    public function GuardarReparacion($producto, $diagnostico, $solucion) {
        $reparacion = array(
            'ProductosId' => $producto,
            'Fecha' => date('Y-m-d | H:i:sa'),
            'CReparacionesId' => $diagnostico,
            'Solucion' => $solucion,
            'UsuariosId' => IdUsuario()
        );
        $this->db->insert('Reparaciones', $reparacion);
        $Historial= array(
            'Fecha' => date('Y-m-d | H:i:sa'),
            'UsuariosId'=>IdUsuario(),
            'MovimientosProductosId'=>9,
            'Activo'=>1,
            'ProductosId'=>$producto
        );
        //$this->db->set('Fecha', 'NOW()', FALSE);
        $this->db->insert('HistorialProducto', $Historial);
    }
    
    
}
?>