<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modeloclasificador extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function ListaHornos($dia) {
        $this->db->select('*');
        $this->db->from('Productos p');
        $this->db->join('Hornos h', 'h.idhornos=p.hornosid');
        $this->db->where('DATE(p.FechaQuemado)', $dia);
        $this->db->where('h.Activo', 1);
        $this->db->where('p.Activo', 1);
        $this->db->where('p.Clasificado', 0);
        $this->db->group_by('h.IdHornos');
        $query = $this->db->get();
        return $query;
    }

    public static function FechaIngles($date) {
//        if ($date) {
//            $fecha = $date;
//            $hora = "";
//
//            # separamos la fecha recibida por el espacio de separación entre
//            # la fecha y la hora
//            $fechaHora = explode(" ", $date);
//            if (count($fechaHora) == 2) {
//                $fecha = $fechaHora[0];
//                $hora = $fechaHora[1];
//            }
//
//            # cogemos los valores de la fecha
//            $values = preg_split('/(\/|-)/', $fecha);
//            if (count($values) == 3) {
//                # devolvemos la fecha en formato ingles
//                if ($hora && count(explode(":", $hora)) == 3) {
//                    # si la hora esta separada por : y hay tres valores...
//                    $hora = explode(":", $hora);
//                    return date("Ymd H:i:s", mktime($hora[0], $hora[1], $hora[2], $values[1], $values[0], $values[2]));
//                } else {
//                    return date("Ymd", mktime(0, 0, 0, $values[1], $values[0], $values[2]));
//                }
//            }
//        }
//        return "";
    }

    public function ProductosPendientesHornos($dia, $horno) {
        $this->db->select('h.*');
        $this->db->from('Productos p');
        $this->db->join('Hornos h', 'h.idhornos=p.hornosid');
        $this->db->where('DATE(p.FechaQuemado)', $dia);
        $this->db->where('h.Activo', 1);
        $this->db->where('p.Activo', 1);
        $this->db->where('p.Clasificado', 0);
        $this->db->where('p.HornosId', $horno);
        $this->db->where('p.HornosId', $horno);
        $this->db->group_by('p.IdProductos');
        $cuantos = $this->db->get()->num_rows();
        return $cuantos;
//        $con = new Conexion();
//        $dia = $con->EscapaCaracteres($dia);
//        $query = "SELECT h.* FROM Productos p JOIN Hornos h ON h.idhornos=p.hornosid WHERE DATE(p.FechaQuemado)='$dia' AND h.Activo=1 AND p.Activo=1 AND p.HornosId=$horno AND p.Clasificado=0  GROUP BY p.IdProductos";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
    }

    public static function ListarProductosHornoFecha($dia, $horno) {
//        $con = new Conexion();
//        $dia = $con->EscapaCaracteres($dia);
//        $query = "SELECT Imagen,IdCProductos,Nombre,HornosId,CProductosId "
//                . "FROM Productos p JOIN CProductos cp "
//                . "ON cp.IdCProductos=p.CProductosid "
//                . "WHERE DATE(p.FechaQuemado)='$dia' "
//                . "AND p.Activo=1 "
//                . "AND p.HornosId=$horno "
//                . "AND p.Clasificado=0 "
//                . "GROUP BY p.CProductosId";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
    }

    public static function ListarProductosHornoFechaCProd($dia, $horno, $cprod) {
//        $con = new Conexion();
//        $dia = $con->EscapaCaracteres($dia);
//        $query = "SELECT count(*)as cuantos "
//                . "FROM Productos p "
//                . "WHERE DATE(p.FechaQuemado)='$dia' "
//                . "AND p.Activo=1 "
//                . "AND p.HornosId=$horno "
//                . "AND p.Clasificado=0 "
//                . "AND p.CProductosId=$cprod ";
//        //print_r("SELECT count(*)as cuantos FROM Productos p JOIN CProductos cp ON cp.IdCProductos=p.CProductosid WHERE DATE(p.FechaQuemado)='$dia' AND p.Activo=1 AND p.HornosId=$horno AND p.Clasificado=0 AND cp.IdCProductos=$cprod GROUP BY p.IdProductos");
//        $datos = $con->Consultar($query);
//        $fila = mysqli_fetch_assoc($datos);
//        $con->Cerrar();
//        return $fila["cuantos"];
    }

    public static function ListarProductosHornoFechaCProdModelo($dia, $horno, $cprod, $modelo) {
//        $con = new Conexion();
//        $dia = $con->EscapaCaracteres($dia);
//        $query = "SELECT count(*)as cuantos "
//                . "FROM Productos p "
//                . "WHERE DATE(p.FechaQuemado)='$dia' "
//                . "AND p.Activo=1 "
//                . "AND p.HornosId=$horno "
//                . "AND p.Clasificado=0 "
//                . "AND p.CProductosId=$cprod "
//                . "AND p.ModelosId=$modelo ";
//        $datos = $con->Consultar($query);
//        $fila = mysqli_fetch_assoc($datos);
//        $con->Cerrar();
//        return $fila["cuantos"];
    }

    public static function ListarModelosHornoFechaProducto($dia, $horno, $cprod) {
//        $con = new Conexion();
//        $dia = $con->EscapaCaracteres($dia);
//        $query = "SELECT m.Nombre,"
//                . "p.ModelosId,"
//                . "p.CProductosId,"
//                . "pm.Imagen,"
//                . "p.HornosId "
//                . "FROM Productos p "
//                . "JOIN Modelos m on m.IdModelos=p.ModelosId "
//                . "JOIN CProductosModelos pm on pm.ModelosId=p.ModelosId "
//                . "WHERE DATE(p.FechaQuemado)='$dia' "
//                . "AND p.Activo=1 "
//                . "AND pm.CProductosId=p.CProductosId "
//                . "AND p.HornosId=$horno "
//                . "AND p.Clasificado=0 "
//                . "AND p.CProductosId=$cprod "
//                . "GROUP BY m.IdModelos";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
    }

    public static function ListarColoresClasificacion($dia, $horno, $cprod, $mod) {
//        $con = new Conexion();
//        $dia = $con->EscapaCaracteres($dia);
//        $query = "SELECT c.Nombre,"
//                . "c.Descripcion,"
//                . "p.ModelosId,"
//                . "p.CProductosId,"
//                . "p.HornosId, "
//                . "c.IdColores "
//                . "FROM Productos p "
//                . "JOIN Colores c on c.IdColores=p.ColoresId "
//                . "WHERE DATE(p.FechaQuemado)='$dia' "
//                . "AND p.Activo=1 "
//                . "AND p.HornosId=$horno "
//                . "AND p.CProductosId = $cprod "
//                . "AND p.ModelosId = $mod "
//                . "AND p.Clasificado = 0 "
//                . "GROUP BY c.IdColores";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
    }

    public static function ProdPendientesColores($dia, $horno, $cprod, $modelo, $color) {
//        $con = new Conexion();
//        $dia = $con->EscapaCaracteres($dia);
//        $query = "SELECT count(*)as cuantos "
//                . "FROM Productos p "
//                . "WHERE DATE(p.FechaQuemado)='$dia' "
//                . "AND p.Activo=1 "
//                . "AND p.HornosId=$horno "
//                . "AND p.Clasificado=0 "
//                . "AND p.CProductosId=$cprod "
//                . "AND p.ModelosId=$modelo "
//                . "AND p.ColoresId=$color ";
//        $datos = $con->Consultar($query);
//        $fila = mysqli_fetch_assoc($datos);
//        $con->Cerrar();
//        return $fila["cuantos"];
    }

    public static function ProductosSeleccion($dia, $horno, $cprod, $mod, $color) {
//        $con = new Conexion();
//        $dia = $con->EscapaCaracteres($dia);
//        $query = "SELECT c.Nombre,"
//                . "c.Descripcion,"
//                . "cp.Nombre as NombreProducto,"
//                . "p.ModelosId,"
//                . "p.CProductosId,"
//                . "p.HornosId, "
//                . "c.IdColores, "
//                . "c.Nombre as NombreColor, "
//                . "m.Nombre as NombreModelo, "
//                . "p.IdProductos "
//                . "FROM Productos p "
//                . "JOIN Modelos m on m.IdModelos=p.ModelosId "
//                . "JOIN CProductos cp on cp.IdCProductos=p.CProductosId "
//                . "JOIN Colores c on c.IdColores=p.ColoresId "
//                . "WHERE DATE(p.FechaQuemado)='$dia' "
//                . "AND p.Activo=1 "
//                . "AND p.HornosId=$horno "
//                . "AND p.ModelosId = $mod "
//                . "AND p.ColoresId = $color "
//                . "AND p.Clasificado = 0 "
//                . "AND p.CProductosId = $cprod ";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
    }

}

?>
