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
    
    public function ListarGriferiaGuardada($id, $cantidad){
       try {
             $datos = array(
            'CGriferiaId'=> $id,
            'Cantidad'=> $cantidad,
            'UsuariosId'=>1,
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
            'UsuariosIdEntrada'=> 1,
            );             
            $this->db->set('FechaEntrada', 'NOW()', FALSE);
            $this->db->insert('InventariosAlmacen', $datos);  
    }
}
?>
