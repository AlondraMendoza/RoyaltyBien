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
		$this->db->where('u.Activo', 1);
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

    public function Soportes() {
        $this->db->select('s.*,per.Nombre as Nombre1, per.APaterno as Paterno1, per2.Nombre as Nombre2, per2.APaterno as Paterno2');
        $this->db->from('Soportes s');
        $this->db->join("Usuarios u", "u.IdUsuarios=s.UsuarioCapturaId", "left");
        $this->db->join("Personas per", "per.IdPersonas=u.PersonasId", "left");
        $this->db->join("Usuarios u2", "u2.IdUsuarios=s.UsuarioContestaId", "left");
        $this->db->join("Personas per2", "per2.IdPersonas=u2.PersonasId", "left");
        /* Si es un usuario diferente a Alondra entonces se listarán sólo los que cada uno capturó, si es Alondra verá todos */
        if (IdUsuario() > 1) {
            $this->db->where('s.UsuarioCapturaId', IdUsuario());
        }
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
    
    public function ObtenerDetalleDefectos($producto_id) {
        $ultimaclasificacion = $this->Clasificacion($producto_id);
        if ($ultimaclasificacion != null) {
            $this->db->select("d.Nombre as Defecto, hc.FechaClasificacion as Fecha, a.Nombre as Area, CONCAT(per.Nombre,' ',per.APaterno) as Persona");
            $this->db->from("HistorialClasificacionDefectos hcd");
            $this->db->join("Defectos d", "d.IdDefectos=hcd.DefectosId");
            $this->db->join("HistorialClasificacion hc", "hcd.HistorialClasificacionId= hc.IdHistorialClasificacion");
            $this->db->join("Puestos p", "hcd.PuestosId=p.IdPuestos");
            $this->db->join("Areas a", "p.AreasId=a.IdAreas");
            $this->db->join("Personas per", "p.PersonasId= per.IdPersonas");
            $this->db->where("hc.ProductosId", $producto_id);
            $this->db->where("hc.IdHistorialClasificacion", $ultimaclasificacion->IdHistorialClasificacion);
            //$this->db->order_by("hc.IdHistorialClasificacion", "desc");
            $fila = $this->db->get();
            return $fila;
        } else {
            return null;
        }
    }

    public function GuardarSoporte($mensaje) {
        $datos = array(
            'Mensaje' => $mensaje,
            'UsuarioCapturaId' => IdUsuario(),
            'Fecha' => date('Y-m-d | H:i:sa')
        );
        $this->db->insert('Soportes', $datos);
        return "correcto";
    }

    public function GuardarRespuesta($respuesta, $soporteid) {
        $this->db->set("Respuesta", $respuesta);
        $this->db->set("UsuarioContestaId", IdUsuario());
        $this->db->where("IdSoportes", $soporteid);
        $this->db->update("Soportes");
    }

}

?>
