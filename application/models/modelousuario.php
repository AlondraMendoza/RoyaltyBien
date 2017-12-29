<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modelousuario extends CI_Model {

    function __construct() {
        parent::__construct();
    }

     public function usuario_por_nombre_contrasena($nombre, $contrasena){
      $this->db->select('IdUsuarios, Nombre');
      $this->db->from('Usuarios');
      $this->db->where('Nombre', $nombre);
      $this->db->where('Contrasena', $contrasena);
      $consulta = $this->db->get();
      $resultado = $consulta->row();
      return $resultado;
   }
   
   public function ObtenerPerfiles($id){
       $this->db->select('p.Nombre');
       $this->db->from('Perfiles p');
       $this->db->join('PerfilesUsuarios pu', 'p.IdPerfiles= pu.PerfilesId');
       $this->db->where('pu.UsuariosId', $id);
       $this->db->where('p.Activo',1);
       $this->db->where('pu.Activo',1);
       $consulta = $this->db->get();
       return $consulta;
   }
   
    public static function CategoriasDefectos() {
//        $con = new Conexion();
//        $query = "SELECT c.Nombre,"
//                . "c.IdCatDefectos "
//                . "FROM CategoriasDefectos c "
//                . "WHERE c.Activo=1 ";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
    }

    public static function ListarDefectos($cat_id) {
//        $con = new Conexion();
//        $query = "SELECT d.Nombre,"
//                . "d.IdDefectos "
//                . "FROM Defectos d "
//                . "WHERE d.Activo=1 "
//                . "AND d.CatDefectosId=$cat_id";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
    }

}

?>