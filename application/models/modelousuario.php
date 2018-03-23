<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modelousuario extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function usuario_por_nombre_contrasena($nombre, $contrasena) {
        $this->db->select("u.IdUsuarios, u.Nombre,concat(p.Nombre,' ',p.APaterno)as NombreCompleto");
        $this->db->from('Usuarios u');
        $this->db->join('Personas p', 'p.IdPersonas= u.PersonasId');
        $this->db->where('u.Nombre', $nombre);
        $this->db->where('u.Contrasena', $contrasena);
        $consulta = $this->db->get();
        $resultado = $consulta->row();
        return $resultado;
    }

    public function ObtenerPerfiles($id) {
        $this->db->select('p.Nombre, p.IdPerfiles,p.Color,p.Icono,p.Color2');
        $this->db->from('Perfiles p');
        $this->db->join('PerfilesUsuarios pu', 'p.IdPerfiles= pu.PerfilesId');
        $this->db->where('pu.UsuariosId', $id);
        $this->db->where('p.Activo', 1);
        $this->db->where('pu.Activo', 1);
        $consulta = $this->db->get();
        return $consulta;
    }

    public function ObtenerMenu($id) {
        $this->db->select('*');
        $this->db->from('Menus');
        $this->db->where('PerfilesId', $id);
        $arreglo = $this->db->get();
        return $arreglo;
    }

    public function ObtenerReportes($id) {
        $this->db->select('*');
        $this->db->from('Menus');
        $this->db->where('PerfilesId', $id);
        $this->db->where('Tipo', 'Reporte');
        $arreglo = $this->db->get();
        return $arreglo;
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

    public function TienePerfil($id, $perfil) {
        $this->db->select('p.Nombre, p.IdPerfiles');
        $this->db->from('Perfiles p');
        $this->db->join('PerfilesUsuarios pu', 'p.IdPerfiles= pu.PerfilesId');
        $this->db->where('pu.UsuariosId', $id);
        $this->db->where('p.Activo', 1);
        $this->db->where('pu.Activo', 1);
        $this->db->where('p.IdPerfiles', $perfil);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

}

?>