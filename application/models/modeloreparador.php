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
        $this->db->where("p.IdProductos", $producto_id);
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
        $fila = $this->db->get();
        return $fila;
       
        
    }
    
}
?>