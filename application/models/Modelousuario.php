<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modelousuario extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function usuario_por_nombre_contrasena($nombre, $contrasena) {
        $this->db->select("u.IdUsuarios, u.Nombre,concat(p.Nombre,' ',p.APaterno)as NombreCompleto,u.PersonasId");
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

    public function GuardarNuevaContra($contra) {
        $this->db->set("Contrasena", $contra);
        $this->db->where("IdUsuarios", IdUsuario());
        $this->db->update("Usuarios");
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

    public function Clasificacion($producto_id) {
        $this->db->select("c.Letra,c.Color,h.FueraTono,h.IdHistorialClasificacion");
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

    public function ObtenerDefectos($producto_id) {
        $ultimaclasificacion = $this->Clasificacion($producto_id);
        if ($ultimaclasificacion != null) {
            $this->db->select("d.Nombre, hc.FueraTono");
            $this->db->from("HistorialClasificacionDefectos hcd");
            $this->db->join("Defectos d", "d.IdDefectos=hcd.DefectosId");
            $this->db->join("HistorialClasificacion hc", "hcd.HistorialClasificacionId= hc.IdHistorialClasificacion");
            $this->db->where("hc.ProductosId", $producto_id);
            $this->db->where("hc.IdHistorialClasificacion", $ultimaclasificacion->IdHistorialClasificacion);
            //$this->db->order_by("hc.IdHistorialClasificacion", "desc");
            $fila = $this->db->get();
            return $fila;
        } else {
            return null;
        }
    }

}

?>