<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modeloadministrador extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function Productos() {
        $this->db->select('*');
        $this->db->from("CProductos");
        $this->db->where("Activo=", 1);
        return $this->db->get();
    }

    public function Clasificaciones() {
        $this->db->select('c.Letra,c.Color,c.IdClasificaciones');
        $this->db->from("Clasificaciones c");
        $this->db->where("Activo=", 1);
        return $this->db->get();
    }

    public function Modelos($producto) {

        $this->db->select('m.*');
        $this->db->from("CProductosModelos cm");
        $this->db->join("Modelos m", "m.IdModelos=cm.ModelosId");
        if ($producto > 0) { //Si producto=0 significa que seleccionó Todos por lo que se deben devolver todos los modelos
            $this->db->where("CProductosId=", $producto);
        }
        $this->db->where("cm.Activo=", 1);
        $this->db->group_by('m.IdModelos');
        return $this->db->get();
    }

    public function Colores($modelo) {
        $this->db->select('c.*');
        $this->db->from("ModelosColores mc");
        $this->db->join("Colores c", "c.IdColores=mc.ColoresId");
        if ($modelo > 0) { //Si modelo=0 significa que seleccionó Todos por lo que se deben devolver todos los colores
            $this->db->where("ModelosId=", $modelo);
        }

        $this->db->group_by('c.IdColores');
        return $this->db->get();
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

    public function GenerarReporte($fechainicio, $fechafin, $clasificacion, $producto, $modelo, $color) {
        $fechainicio = $this->FechaIngles($fechainicio);
        $fechafin = $this->FechaIngles($fechafin);
        $parteclasificacion = "";
        $parteproducto = "";
        $partemodelo = "";
        $partecolor = "";
        if ($clasificacion > 0) {
            $parteclasificacion = " AND Clasificacion(p.IdProductos) =" . $clasificacion;
        }
        if ($producto > 0) {
            $parteproducto = " AND p.CProductosId =" . $producto;
        }
        if ($modelo > 0) {
            $partemodelo = " AND p.ModelosId =" . $modelo;
        }
        if ($color > 0) {
            $partecolor = " AND p.ColoresId =" . $color;
        }
        $query = $this->db->query("select p.IdProductos,cp.Nombre as producto,m.Nombre as modelo,co.Nombre as color from Productos p left join CProductos cp on cp.IdCProductos=p.CProductosId left join Modelos m on m.IdModelos=p.ModelosId left join Colores co on co.IdColores=p.ColoresId where  date(FechaCaptura) BETWEEN $fechainicio AND $fechafin" . $parteclasificacion . $parteproducto . $partemodelo . $partecolor);
        return $query;
    }

    public function GenerarConcentrado($fechainicio, $fechafin, $clasificacion, $producto, $modelo, $color, $por) {
        $fechainicio = $this->FechaIngles($fechainicio);
        $fechafin = $this->FechaIngles($fechafin);
        $parteclasificacion = "";
        $parteproducto = "";
        $partemodelo = "";
        $partecolor = "";
        if ($clasificacion > 0) {
            $parteclasificacion = " AND Clasificacion(p.IdProductos) =" . $clasificacion;
        }
        if ($producto > 0) {
            $parteproducto = " AND p.CProductosId =" . $producto;
        }
        if ($modelo > 0) {
            $partemodelo = " AND p.ModelosId =" . $modelo;
        }
        if ($color > 0) {
            $partecolor = " AND p.ColoresId =" . $color;
        }
        $campo = "";
        switch ($por) {
            case "Clasificacion(p.IdProductos)":
                $campo = "Clasificacion(p.IdProductos) as Nombre";
                break;
            case "cp.IdCproductos":
                $campo = "cp.Nombre";
                break;
            case "m.IdModelos":
                $campo = "m.Nombre";
                break;
            case "co.IdColores":
                $campo = "co.Nombre";
                break;
        }
        $query = $this->db->query("select count(*) as cuantos, $campo from Productos p left join CProductos cp on cp.IdCProductos=p.CProductosId left join Modelos m on m.IdModelos=p.ModelosId left join Colores co on co.IdColores=p.ColoresId where  date(FechaCaptura) BETWEEN $fechainicio AND $fechafin" . $parteclasificacion . $parteproducto . $partemodelo . $partecolor . " group by " . $por);
        return $query;
    }

    public function Clasificacion($producto_id) {
        $this->db->select("c.Letra,c.Color");
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

    public function ClasificacionObj($clasificacion_id) {
        $this->db->select("c.Letra,c.Color");
        $this->db->from("Clasificaciones c");
        $this->db->where("c.IdClasificaciones", $clasificacion_id);
        $fila = $this->db->get()->row();
        if ($fila != null) {
            return $fila;
        } else {
            return "";
        }
    }

}
?>

