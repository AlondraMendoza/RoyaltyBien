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

    public function ProductosQuemado() {
        $this->db->select('*');
        $this->db->from("CProductos");
        $this->db->where("Activo=", 1);
        $this->db->where("IdCProductos!=", 7);
        return $this->db->get();
    }

    public function Clasificaciones() {
        $this->db->select('c.Letra,c.Color,c.IdClasificaciones');
        $this->db->from("Clasificaciones c");
        $this->db->where("Activo=", 1);
        return $this->db->get();
    }

    public function Hornos() {
        $this->db->select('h.NHorno, h.IdHornos');
        $this->db->from("Hornos h");
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

    public function ModelosQuemado($producto) {

        $this->db->select('m.*');
        $this->db->from("CProductosModelos cm");
        $this->db->join("Modelos m", "m.IdModelos=cm.ModelosId");
        if ($producto > 0) { //Si producto=0 significa que seleccionó Todos por lo que se deben devolver todos los modelos
            $this->db->where("CProductosId=", $producto);
        }
        $this->db->where("cm.Activo=", 1);
        $this->db->where("m.IdModelos!=", 12);
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

    public function GenerarReporte($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor) {
        $fechainicio = $this->FechaIngles($fechainicio);
        $fechafin = $this->FechaIngles($fechafin);
        $parteclasificacion = "";
        $parteproducto = "";
        $partemodelo = "";
        $partecolor = "";
        if (count($aclasificacion) > 0) {
            $parteclasificacion = " AND ";
            $contclasi = 1;
            $parteclasificacion .= " ( ";
            foreach ($aclasificacion as $ac) {
                if ($contclasi > 1) {
                    $parteclasificacion .= " OR ";
                }
                $parteclasificacion .= " Clasificacion(p.IdProductos) =" . $ac;
                $contclasi++;
            }
            $parteclasificacion .= " ) ";
        }
        if (count($aproducto) > 0) {
            $parteproducto = " AND ";
            $contprod = 1;
            $parteproducto .= " ( ";
            foreach ($aproducto as $ap) {
                if ($contprod > 1) {
                    $parteproducto .= " OR ";
                }
                $parteproducto .= " p.CProductosId =" . $ap;
                $contprod++;
            }
            $parteproducto .= " ) ";
        }
        if (count($amodelo) > 0) {
            $partemodelo = " AND ";
            $contmod = 1;
            $partemodelo .= " ( ";
            foreach ($amodelo as $am) {
                if ($contmod > 1) {
                    $partemodelo .= " OR ";
                }
                $partemodelo .= " p.ModelosId =" . $am;
                $contmod++;
            }
            $partemodelo .= " ) ";
        }
        if (count($acolor) > 0) {
            $partecolor = " AND ";
            $contcol = 1;
            $partecolor .= " ( ";
            foreach ($acolor as $acol) {
                if ($contcol > 1) {
                    $partecolor .= " OR ";
                }
                $partecolor .= " p.ColoresId =" . $acol;
                $contcol++;
            }
            $partecolor .= " ) ";
        }
        $query = $this->db->query("select count(*) as cuantos,p.IdProductos,cp.Nombre as producto,m.Nombre as modelo,co.Nombre as color from Productos p left join CProductos cp on cp.IdCProductos=p.CProductosId left join Modelos m on m.IdModelos=p.ModelosId left join Colores co on co.IdColores=p.ColoresId where  date(FechaCaptura) BETWEEN $fechainicio AND $fechafin" . $parteclasificacion . $parteproducto . $partemodelo . $partecolor . "GROUP BY cp.IdCProductos, m.IdModelos");
        return $query;
    }

    public function GenerarConcentrado($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $por) {
        $fechainicio = $this->FechaIngles($fechainicio);
        $fechafin = $this->FechaIngles($fechafin);
        $parteclasificacion = "";
        $parteproducto = "";
        $partemodelo = "";
        $partecolor = "";
        if (count($aclasificacion) > 0) {
            $parteclasificacion = " AND ";
            $contclasi = 1;
            $parteclasificacion .= " ( ";
            foreach ($aclasificacion as $ac) {
                if ($contclasi > 1) {
                    $parteclasificacion .= " OR ";
                }
                $parteclasificacion .= " Clasificacion(p.IdProductos) =" . $ac;
                $contclasi++;
            }
            $parteclasificacion .= " ) ";
        }
        if (count($aproducto) > 0) {
            $parteproducto = " AND ";
            $contprod = 1;
            $parteproducto .= " ( ";
            foreach ($aproducto as $ap) {
                if ($contprod > 1) {
                    $parteproducto .= " OR ";
                }
                $parteproducto .= " p.CProductosId =" . $ap;
                $contprod++;
            }
            $parteproducto .= " ) ";
        }
        if (count($amodelo) > 0) {
            $partemodelo = " AND ";
            $contmod = 1;
            $partemodelo .= " ( ";
            foreach ($amodelo as $am) {
                if ($contmod > 1) {
                    $partemodelo .= " OR ";
                }
                $partemodelo .= " p.ModelosId =" . $am;
                $contmod++;
            }
            $partemodelo .= " ) ";
        }
        if (count($acolor) > 0) {
            $partecolor = " AND ";
            $contcol = 1;
            $partecolor .= " ( ";
            foreach ($acolor as $acol) {
                if ($contcol > 1) {
                    $partecolor .= " OR ";
                }
                $partecolor .= " p.ColoresId =" . $acol;
                $contcol++;
            }
            $partecolor .= " ) ";
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

    public function GenerarDetalleSeleccionado($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $por, $nombre) {
        $fechainicio = $this->FechaIngles($fechainicio);
        $fechafin = $this->FechaIngles($fechafin);
        $parteclasificacion = "";
        $parteproducto = "";
        $partemodelo = "";
        $partecolor = "";
        if (count($aclasificacion) > 0) {
            $parteclasificacion = " AND ";
            $contclasi = 1;
            $parteclasificacion .= " ( ";
            foreach ($aclasificacion as $ac) {
                if ($contclasi > 1) {
                    $parteclasificacion .= " OR ";
                }
                $parteclasificacion .= " Clasificacion(p.IdProductos) =" . $ac;
                $contclasi++;
            }
            $parteclasificacion .= " ) ";
        }
        if (count($aproducto) > 0) {
            $parteproducto = " AND ";
            $contprod = 1;
            $parteproducto .= " ( ";
            foreach ($aproducto as $ap) {
                if ($contprod > 1) {
                    $parteproducto .= " OR ";
                }
                $parteproducto .= " p.CProductosId =" . $ap;
                $contprod++;
            }
            $parteproducto .= " ) ";
        }
        if (count($amodelo) > 0) {
            $partemodelo = " AND ";
            $contmod = 1;
            $partemodelo .= " ( ";
            foreach ($amodelo as $am) {
                if ($contmod > 1) {
                    $partemodelo .= " OR ";
                }
                $partemodelo .= " p.ModelosId =" . $am;
                $contmod++;
            }
            $partemodelo .= " ) ";
        }
        if (count($acolor) > 0) {
            $partecolor = " AND ";
            $contcol = 1;
            $partecolor .= " ( ";
            foreach ($acolor as $acol) {
                if ($contcol > 1) {
                    $partecolor .= " OR ";
                }
                $partecolor .= " p.ColoresId =" . $acol;
                $contcol++;
            }
            $partecolor .= " ) ";
        }
        $campo = "";
        switch ($por) {
            case "Clasificacion(p.IdProductos)":
                $campo = "ClasificacionL(p.IdProductos)";
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
        $query = $this->db->query("select p.IdProductos,cp.Nombre as producto,m.Nombre as modelo,co.Nombre as color from Productos p left join CProductos cp on cp.IdCProductos=p.CProductosId left join Modelos m on m.IdModelos=p.ModelosId left join Colores co on co.IdColores=p.ColoresId where  date(FechaCaptura) BETWEEN $fechainicio AND $fechafin" . $parteclasificacion . $parteproducto . $partemodelo . $partecolor . " AND " . $campo . "=" . "'" . $nombre . "'");
        return $query;
    }

    public function GenerarDetalleSeleccionadoQ($fechainicio, $fechafin, $ahornos, $aproducto, $amodelo, $acolor, $por, $nombre) {
        $fechainicio = $this->FechaIngles($fechainicio);
        $fechafin = $this->FechaIngles($fechafin);
        $parteclasificacion = "";
        $parteproducto = "";
        $partemodelo = "";
        $partecolor = "";
        if (count($ahornos) > 0) {
            $parteclasificacion = " AND ";
            $contclasi = 1;
            $parteclasificacion .= " ( ";
            foreach ($ahornos as $ac) {
                if ($contclasi > 1) {
                    $parteclasificacion .= " OR ";
                }
                $parteclasificacion .= " Horno(p.IdProductos) =" . $ac;
                $contclasi++;
            }
            $parteclasificacion .= " ) ";
        }
        if (count($aproducto) > 0) {
            $parteproducto = " AND ";
            $contprod = 1;
            $parteproducto .= " ( ";
            foreach ($aproducto as $ap) {
                if ($contprod > 1) {
                    $parteproducto .= " OR ";
                }
                $parteproducto .= " p.CProductosId =" . $ap;
                $contprod++;
            }
            $parteproducto .= " ) ";
        }
        if (count($amodelo) > 0) {
            $partemodelo = " AND ";
            $contmod = 1;
            $partemodelo .= " ( ";
            foreach ($amodelo as $am) {
                if ($contmod > 1) {
                    $partemodelo .= " OR ";
                }
                $partemodelo .= " p.ModelosId =" . $am;
                $contmod++;
            }
            $partemodelo .= " ) ";
        }
        if (count($acolor) > 0) {
            $partecolor = " AND ";
            $contcol = 1;
            $partecolor .= " ( ";
            foreach ($acolor as $acol) {
                if ($contcol > 1) {
                    $partecolor .= " OR ";
                }
                $partecolor .= " p.ColoresId =" . $acol;
                $contcol++;
            }
            $partecolor .= " ) ";
        }
        $campo = "";
        switch ($por) {
            case "h.IdHornos":
                $campo = "h.NHorno";
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
        $query = $this->db->query("select p.IdProductos,cp.Nombre as producto,m.Nombre as modelo,co.Nombre as color,h.* from Productos p left join CProductos cp on cp.IdCProductos=p.CProductosId left join Modelos m on m.IdModelos=p.ModelosId left join Colores co on co.IdColores=p.ColoresId left join Hornos h on h.IdHornos=p.HornosId where  date(FechaCaptura) BETWEEN $fechainicio AND $fechafin" . $parteclasificacion . $parteproducto . $partemodelo . $partecolor . " AND convert(" . $campo . " , char(2))=" . "'" . $nombre . "'");

        return $query;
    }

    public function GenerarReporteQ($fechainicio, $fechafin, $ahornos, $aproducto, $amodelo, $acolor) {
        $fechainicio = $this->FechaIngles($fechainicio);
        $fechafin = $this->FechaIngles($fechafin);
        $parteclasificacion = "";
        $parteproducto = "";
        $partemodelo = "";
        $partecolor = "";
        if (count($ahornos) > 0) {
            $parteclasificacion = " AND ";
            $contclasi = 1;
            $parteclasificacion .= " ( ";
            foreach ($ahornos as $ac) {
                if ($contclasi > 1) {
                    $parteclasificacion .= " OR ";
                }
                $parteclasificacion .= " Horno(p.IdProductos) =" . $ac;
                $contclasi++;
            }
            $parteclasificacion .= " ) ";
        }
        if (count($aproducto) > 0) {
            $parteproducto = " AND ";
            $contprod = 1;
            $parteproducto .= " ( ";
            foreach ($aproducto as $ap) {
                if ($contprod > 1) {
                    $parteproducto .= " OR ";
                }
                $parteproducto .= " p.CProductosId =" . $ap;
                $contprod++;
            }
            $parteproducto .= " ) ";
        }
        if (count($amodelo) > 0) {
            $partemodelo = " AND ";
            $contmod = 1;
            $partemodelo .= " ( ";
            foreach ($amodelo as $am) {
                if ($contmod > 1) {
                    $partemodelo .= " OR ";
                }
                $partemodelo .= " p.ModelosId =" . $am;
                $contmod++;
            }
            $partemodelo .= " ) ";
        }
        if (count($acolor) > 0) {
            $partecolor = " AND ";
            $contcol = 1;
            $partecolor .= " ( ";
            foreach ($acolor as $acol) {
                if ($contcol > 1) {
                    $partecolor .= " OR ";
                }
                $partecolor .= " p.ColoresId =" . $acol;
                $contcol++;
            }
            $partecolor .= " ) ";
        }
        //$query = $this->db->query("select p.IdProductos,cp.Nombre as producto,m.Nombre as modelo,co.Nombre as color from Productos p left join CProductos cp on cp.IdCProductos=p.CProductosId left join Modelos m on m.IdModelos=p.ModelosId left join Colores co on co.IdColores=p.ColoresId where  date(FechaQuemado) BETWEEN $fechainicio AND $fechafin" . $parteclasificacion . $parteproducto . $partemodelo . $partecolor);
        //Agregar count y group
        $query = $this->db->query("select count(*) as cuantos, h.NHorno as horno, cp.Nombre as producto,m.Nombre as modelo,co.Nombre as color from "
                . "Productos p left join CProductos cp on cp.IdCProductos=p.CProductosId "
                . "left join Modelos m on m.IdModelos=p.ModelosId left join Colores co on co.IdColores=p.ColoresId left join Hornos"
                . " h on p.HornosId=h.IdHornos where  date(FechaQuemado) "
                . "BETWEEN $fechainicio AND $fechafin" . $parteclasificacion . $parteproducto . $partemodelo . $partecolor . " group by horno ,m.IdModelos, cp.IdCProductos, co.IdColores");

        //print_r($this->db->get_compiled_select());
        return $query;
    }

    public function GenerarReporteQAcc($fechainicio, $fechafin) {
        $fechainicio = $this->FechaIngles($fechainicio);
        $fechafin = $this->FechaIngles($fechafin);
        $query = $this->db->query("select count(*) as cuantos, cp.Nombre as producto, FechaQuemado "
                . "from CarrosAccesorios ca left join CProductos cp on cp.IdCProductos=ca.CProductosId "
                . "where ca.CProductosId=7 and date(FechaQuemado) "
                . "BETWEEN $fechainicio AND $fechafin" . " group by FechaQuemado");

        //print_r($this->db->get_compiled_select());
        return $query;
    }

    public function GenerarConcentradoQ($fechainicio, $fechafin, $ahornos, $aproducto, $amodelo, $acolor, $por) {
        $fechainicio = $this->FechaIngles($fechainicio);
        $fechafin = $this->FechaIngles($fechafin);
        $parteclasificacion = "";
        $parteproducto = "";
        $partemodelo = "";
        $partecolor = "";
        if (count($ahornos) > 0) {
            $parteclasificacion = " AND ";
            $contclasi = 1;
            $parteclasificacion .= " ( ";
            foreach ($ahornos as $ac) {
                if ($contclasi > 1) {
                    $parteclasificacion .= " OR ";
                }
                $parteclasificacion .= " p.HornosId =" . $ac;
                $contclasi++;
            }
            $parteclasificacion .= " ) ";
        }
        if (count($aproducto) > 0) {
            $parteproducto = " AND ";
            $contprod = 1;
            $parteproducto .= " ( ";
            foreach ($aproducto as $ap) {
                if ($contprod > 1) {
                    $parteproducto .= " OR ";
                }
                $parteproducto .= " p.CProductosId =" . $ap;
                $contprod++;
            }
            $parteproducto .= " ) ";
        }
        if (count($amodelo) > 0) {
            $partemodelo = " AND ";
            $contmod = 1;
            $partemodelo .= " ( ";
            foreach ($amodelo as $am) {
                if ($contmod > 1) {
                    $partemodelo .= " OR ";
                }
                $partemodelo .= " p.ModelosId =" . $am;
                $contmod++;
            }
            $partemodelo .= " ) ";
        }
        if (count($acolor) > 0) {
            $partecolor = " AND ";
            $contcol = 1;
            $partecolor .= " ( ";
            foreach ($acolor as $acol) {
                if ($contcol > 1) {
                    $partecolor .= " OR ";
                }
                $partecolor .= " p.ColoresId =" . $acol;
                $contcol++;
            }
            $partecolor .= " ) ";
        }
        $campo = "";
        switch ($por) {
            case "h.IdHornos":
                $campo = "h.NHorno as Nombre";
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

        $query = $this->db->query("select count(*) as cuantos, $campo from Productos p left join CProductos cp on cp.IdCProductos=p.CProductosId left join Modelos m on m.IdModelos=p.ModelosId left join Colores co on co.IdColores=p.ColoresId left join Hornos h on h.IdHornos=p.HornosId where  date(FechaQuemado) BETWEEN $fechainicio AND $fechafin" . $parteclasificacion . $parteproducto . $partemodelo . $partecolor . " group by " . $por);
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

    public function Horno($producto_id) {
        $this->db->select("h.NHorno");
        $this->db->from("Hornos h");
        $this->db->join("Productos p", "p.HornosId=h.IdHornos");
        $this->db->where("p.IdProductos", $producto_id);
        $this->db->where("p.Activo", 1);
        $this->db->where("h.Activo", 1);
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

    public function Usuarios() {
        $this->db->select("p.IdPersonas,concat(IFNULL(p.Nombre,'') ,' ',IFNULL(p.APaterno,''),' ',IFNULL(p.AMaterno,''))as NombreCompleto");
        $this->db->from('Personas p');
        $this->db->where('p.Activo', 1);
        $consulta = $this->db->get();
        return $consulta;
    }

    public function Perfiles() {
        $this->db->select('p.Nombre, p.IdPerfiles');
        $this->db->from('Perfiles p');
        $this->db->where('p.Activo', 1);
        $consulta = $this->db->get();
        return $consulta;
    }

    public function Usuario($persona_id) {
        $this->db->select("u.IdUsuarios, u.Nombre,concat(p.Nombre,' ',p.APaterno,' ',p.AMaterno)as NombreCompleto,p.AMaterno,p.APaterno,p.Nombre as NombrePersona,u.Activo");
        $this->db->from('Usuarios u');
        $this->db->join('Personas p', 'p.IdPersonas= u.PersonasId');
        $this->db->where('p.IdPersonas', $persona_id);
        $consulta = $this->db->get()->row();
        return $consulta;
    }

    public function GetUsuario($usuario_id) {
        $this->db->select("u.IdUsuarios, u.Nombre,concat(p.Nombre,' ',p.APaterno,' ',p.AMaterno)as NombreCompleto,p.AMaterno,p.APaterno,p.Nombre as NombrePersona,u.Activo");
        $this->db->from('Usuarios u');
        $this->db->join('Personas p', 'p.IdPersonas= u.PersonasId');
        $this->db->where('u.IdUsuarios', $usuario_id);
        $consulta = $this->db->get()->row();
        return $consulta;
    }

    public function ObtenerPersona($persona_id) {
        $this->db->select("p.IdPersonas,concat(p.Nombre,' ',p.APaterno,' ',p.AMaterno)as NombreCompleto,p.AMaterno,p.APaterno,p.Nombre as NombrePersona");
        $this->db->from('Personas p');
        $this->db->where('p.IdPersonas', $persona_id);
        $consulta = $this->db->get()->row();
        return $consulta;
    }

    public function PuestosUsuario($persona_id) {
        $this->db->select("pu.Nombre,pu.FechaInicio,pu.FechaFin,a.Nombre as Area,pu.IdPuestos,pu.Activo,pu.Clave");
        $this->db->from('Personas p');
        $this->db->join('Puestos pu', 'p.IdPersonas= pu.PersonasId');
        $this->db->join('Areas a', 'a.IdAreas= pu.AreasId');
        $this->db->where('p.IdPersonas', $persona_id);
        $consulta = $this->db->get();
        return $consulta;
    }

    public function UltimoPuesto($persona_id) {
        $this->db->select("p.IdPersonas, pu.Nombre,pu.FechaInicio,pu.FechaFin,a.Nombre as Area,pu.IdPuestos,pu.Activo,pu.Clave");
        $this->db->from('Personas p');
        $this->db->join('Puestos pu', 'p.IdPersonas= pu.PersonasId');
        $this->db->join('Areas a', 'a.IdAreas= pu.AreasId');
        $this->db->where('p.IdPersonas', $persona_id);
        $this->db->where('pu.Activo', 1);
        $this->db->Order_by("pu.FechaInicio", "desc");
        $consulta = $this->db->get()->row();
        return $consulta;
    }

    public function UltimoPerfil($usuario_id) {
        $this->db->select('per.Nombre, per.IdPerfiles,p.FechaInicio,p.FechaFin, p.IdPerfilesUsuarios');
        $this->db->from('PerfilesUsuarios p');
        $this->db->join('Perfiles per', 'per.IdPerfiles= p.PerfilesId');
        $this->db->where('p.UsuariosId', $usuario_id);
        $this->db->where('p.Activo', 1);
        $this->db->Order_by("p.FechaInicio", "desc");
        $consulta = $this->db->get()->row();
        return $consulta;
    }

    public function Puestos() {
        $this->db->select("p.IdPuestos, p.Nombre");
        $this->db->from('Puestos p');
        $this->db->group_by('p.Nombre');
        $consulta = $this->db->get();
        return $consulta;
    }

    public function Areas() {
        $this->db->select("a.Nombre,a.IdAreas");
        $this->db->from('Areas a');
        $consulta = $this->db->get();
        return $consulta;
    }

    public function PerfilesUsuario($usuario_id) {
        $this->db->select('per.Nombre, per.IdPerfiles,p.FechaInicio,p.FechaFin, p.IdPerfilesUsuarios');
        $this->db->from('PerfilesUsuarios p');
        $this->db->join('Perfiles per', 'per.IdPerfiles= p.PerfilesId');
        $this->db->where('p.UsuariosId', $usuario_id);
        $this->db->where('p.Activo', 1);
        $consulta = $this->db->get();
        return $consulta;
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

    public function TieneUsuario($persona) {
        $this->db->select('u.IdUsuarios');
        $this->db->from('Usuarios u');
        //$this->db->where('u.Activo', 1); No verifica activo porque se debe obtener aunque sea cancelado para mostrar el estatus
        $this->db->where('u.PersonasId', $persona);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function TienePuesto($persona_id, $puesto) {
        $this->db->select("p.IdPersonas, pu.Nombre,pu.FechaInicio,pu.FechaFin");
        $this->db->from('Personas p');
        $this->db->join('Puestos pu', 'p.IdPersonas= pu.PersonasId');
        $this->db->where('p.IdPersonas', $persona_id);
        $this->db->where('pu.Nombre', $puesto);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function AgregarPerfil($usuario, $perfil) {
        $Historial = array(
            'UsuariosId' => $usuario,
            'UsuarioAsignaId' => IdUsuario(),
            'PerfilesId' => $perfil,
            'Activo' => 1,
            'FechaInicio' => date('Y-m-d | h:i:sa')
        );
        $this->db->insert('PerfilesUsuarios', $Historial);
        return $this->db->insert_id();
    }

    public function EliminarPerfil($usuario, $perfil) {
        $this->db->set("Activo", 0);
        $this->db->set("FechaFin", date('Y-m-d | h:i:sa'));
        $this->db->where("IdPerfilesUsuarios", $perfil);
        $this->db->update("PerfilesUsuarios");
    }

    public function Persona($usuario) {
        $this->db->select('u.PersonasId');
        $this->db->from("Usuarios u");
        $this->db->where("IdUsuarios", $usuario);
        return $this->db->get()->row();
    }

    public function AgregarPuesto($persona, $puesto, $area, $clave) {
        $Historial = array(
            'Nombre' => $puesto,
            'AreasId' => $area,
            'PersonasId' => $persona,
            'Activo' => 1,
            'Clave' => $clave,
            'UsuarioAsignaId' => IdUsuario(),
            'FechaInicio' => date('Y-m-d | h:i:sa')
        );
        $this->db->insert('Puestos', $Historial);
        return $this->db->insert_id();
    }

    public function EliminarPuesto($persona, $puesto) {
        $this->db->set("Activo", 0);
        $this->db->set("FechaFin", date('Y-m-d | h:i:sa'));
        $this->db->where("IdPuestos", $puesto);
        $this->db->update("Puestos");
    }

    public function ProductosT() {
        $this->db->select('*');
        $this->db->from("CProductos");
        return $this->db->get();
    }

    public function ObtenerModelos($producto) {
        $this->db->select('m.*, cm.Imagen as Imagen, cm.Activo as Activocm, cm.IdCProductosModelos as codigo');
        $this->db->from("CProductosModelos cm");
        $this->db->join("Modelos m", "m.IdModelos=cm.ModelosId");
        $this->db->where("CProductosId=", $producto);
        //$this->db->where("cm.Activo=", 1);
        return $this->db->get();
    }

    public function ObtenerColores($modelo) {
        $this->db->select('c.*');
        $this->db->from("ModelosColores mc");
        $this->db->join("Colores c", "c.IdColores=mc.ColoresId");
        $this->db->where("ModelosId=", $modelo);
        $this->db->where("c.Activo=", 1);
        return $this->db->get();
    }

    public function DesactivarProducto($producto) {
        $this->db->set("Activo", 0);
        $this->db->set("UsuariosId", IdUsuario());
        $this->db->where("IdCProductos", $producto);
        $this->db->update("CProductos");
    }

    public function ActivarProducto($producto) {
        $this->db->set("Activo", 1);
        $this->db->set("UsuariosId", IdUsuario());
        $this->db->where("IdCProductos", $producto);
        $this->db->update("CProductos");
    }

    public function NuevoProducto($nombre) {
        $datos = array(
            'Nombre' => $nombre,
            'Imagen' => null,
            'Activo' => 1,
            'UsuariosId' => IdUsuario(),
        );
        $this->db->insert('CProductos', $datos);
    }

    public function DesactivarModelo($codigo) {
        $this->db->set("Activo", 0);
        $this->db->set("UsuariosId", IdUsuario());
        $this->db->where("IdCProductosModelos", $codigo);
        $this->db->update("CProductosModelos");
    }

    public function ActivarModelo($codigo) {
        $this->db->set("Activo", 1);
        $this->db->set("UsuariosId", IdUsuario());
        $this->db->where("IdCProductosModelos", $codigo);
        $this->db->update("CProductosModelos");
    }

    public function TodosModelos() {
        $this->db->select('m.*');
        $this->db->from("Modelos m");
        $this->db->where("m.Activo=", 1);
        return $this->db->get();
    }

    public function SeleccionModelo($nombre, $producto) {
        $datos = array(
            'CProductosId' => $producto,
            'ModelosId' => $nombre,
            'Imagen' => null,
            'Activo' => 1,
            'UsuariosId' => IdUsuario(),
        );
        $this->db->insert('CProductosModelos', $datos);
    }

    //volver a verificar el id y el if
    public function NuevoModelo($nombre, $producto) {
        $datos = array(
            'Nombre' => $nombre,
            'Activo' => 1,
            'UsuariosId' => IdUsuario(),
        );
        $this->db->insert('Modelos', $datos);
        $id = $this->db->insert_id();
        if ($id != 0) {
            $datosCPM = array(
                'CProductosId' => $producto,
                'ModelosId' => $id,
                'Imagen' => null,
                'Activo' => 1,
                'UsuariosId' => IdUsuario(),
            );
            $this->db->insert('CProductosModelos', $datosCPM);
        }
    }

    public function DesactivarColor($color, $modelo) {
        $this->db->where("ModelosId=", $modelo);
        $this->db->where("ColoresId=", $color);
        $this->db->delete("ModelosColores");
    }

    public function TodosColores() {
        $this->db->select('c.*');
        $this->db->from("Colores c");
        $this->db->where("c.Activo=", 1);
        return $this->db->get();
    }

    public function SeleccionColor($color, $modelo) {
        $datos = array(
            'ModelosId' => $modelo,
            'ColoresId' => $color,
            'UsuariosId' => IdUsuario(),
        );
        $this->db->insert('ModelosColores', $datos);
    }

    public function NuevoColor($color, $modelo) {
        $datos = array(
            'Nombre' => $color,
            'Descripcion' => null,
            'Activo' => 1,
            'UsuariosId' => IdUsuario(),
        );
        $this->db->insert('Colores', $datos);
        $id = $this->db->insert_id();
        if ($id != 0) {
            $datosCPM = array(
                'ModelosId' => $modelo,
                'ColoresId' => $id,
                'UsuariosId' => IdUsuario(),
            );
            $this->db->insert('ModelosColores', $datosCPM);
        }
    }

    public function GuardarEmpleado($nombre, $apellidop, $apellidom, $nempleado) {
        $datos = array(
            'APaterno' => $apellidop,
            'AMaterno' => $apellidom,
            'Nombre' => $nombre,
            'UsuariosId' => IdUsuario(),
            'FechaRegistro' => date('Y-m-d | h:i:sa'),
            'Activo' => 1,
            'NEmpleado' => $nempleado
        );
        $this->db->insert('Personas', $datos);
    }

    public function ExisteUsuario($usuario) {
        $this->db->select('u.IdUsuarios');
        $this->db->from('Usuarios u');
        $this->db->where('u.Nombre', $usuario);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function GenerarUsuario($persona, $cont) {
        $personaobj = $this->ObtenerPersona($persona);
        $nombre = $personaobj->NombrePersona;
        $apaterno = $personaobj->APaterno;
        $usuarionombre = substr($nombre, 0, $cont) . $apaterno;
        if ($this->ExisteUsuario($usuarionombre)) {
            $this->GenerarUsuario($persona, $cont++);
        } else {
            return $this->Normaliza($usuarionombre);
        }
    }

    public function Normaliza($cadena) {
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        $cadena = strtolower($cadena);
        return utf8_encode($cadena);
    }

    public function CrearUsuario($persona) {
        $usuario = $this->GenerarUsuario($persona, 1);
        $datos = array(
            'Nombre' => $usuario,
            'Contrasena' => 'RoyaltyCeramic',
            'PersonasId' => $persona,
            'Activo' => 1,
        );
        $this->db->insert('Usuarios', $datos);
    }

    public function CancelarUsuario($persona) {
        $usuario = $this->Usuario($persona);
        $this->db->set("Activo", 0);
        $this->db->where("IdUsuarios", $usuario->IdUsuarios);
        $this->db->update("Usuarios");
    }

    public function ActivarUsuario($persona) {
        $usuario = $this->Usuario($persona);
        $this->db->set("Activo", 1);
        $this->db->where("IdUsuarios", $usuario->IdUsuarios);
        $this->db->update("Usuarios");
    }

    public function GenerarReporteDefectos($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $adefectos) {
        $fechainicio = $this->FechaIngles($fechainicio);
        $fechafin = $this->FechaIngles($fechafin);
        $parteclasificacion = "";
        $parteproducto = "";
        $partemodelo = "";
        $partecolor = "";
        if (count($aclasificacion) > 0) {
            $parteclasificacion = " AND ";
            $contclasi = 1;
            $parteclasificacion .= " ( ";
            foreach ($aclasificacion as $ac) {
                if ($contclasi > 1) {
                    $parteclasificacion .= " OR ";
                }
                $parteclasificacion .= " Clasificacion(p.IdProductos) =" . $ac;
                $contclasi++;
            }
            $parteclasificacion .= " ) ";
        }
        if (count($aproducto) > 0) {
            $parteproducto = " AND ";
            $contprod = 1;
            $parteproducto .= " ( ";
            foreach ($aproducto as $ap) {
                if ($contprod > 1) {
                    $parteproducto .= " OR ";
                }
                $parteproducto .= " p.CProductosId =" . $ap;
                $contprod++;
            }
            $parteproducto .= " ) ";
        }
        if (count($amodelo) > 0) {
            $partemodelo = " AND ";
            $contmod = 1;
            $partemodelo .= " ( ";
            foreach ($amodelo as $am) {
                if ($contmod > 1) {
                    $partemodelo .= " OR ";
                }
                $partemodelo .= " p.ModelosId =" . $am;
                $contmod++;
            }
            $partemodelo .= " ) ";
        }
        if (count($acolor) > 0) {
            $partecolor = " AND ";
            $contcol = 1;
            $partecolor .= " ( ";
            foreach ($acolor as $acol) {
                if ($contcol > 1) {
                    $partecolor .= " OR ";
                }
                $partecolor .= " p.ColoresId =" . $acol;
                $contcol++;
            }
            $partecolor .= " ) ";
        }
        if (count($adefectos) > 0) {
            $partecolor = " AND ";
            $contcol = 1;
            $partecolor .= " ( ";
            foreach ($acolor as $acol) {
                if ($contcol > 1) {
                    $partecolor .= " OR ";
                }
                $partecolor .= " p.ColoresId =" . $acol;
                $contcol++;
            }
            $partecolor .= " ) ";
        }
        $query = $this->db->query("select count(*) as cuantos,p.IdProductos,cp.Nombre as producto,m.Nombre as modelo,co.Nombre as color from Productos p left join CProductos cp on cp.IdCProductos=p.CProductosId left join Modelos m on m.IdModelos=p.ModelosId left join Colores co on co.IdColores=p.ColoresId join HistorialClasificacion hc on hc.ProductosId=p.IdProductos join HistorialClasificacionDefectos hcd on hcd.HistorialClasificacionId=hc.IdHistorialClasificacion where date(FechaCaptura) BETWEEN $fechainicio AND $fechafin" . $parteclasificacion . $parteproducto . $partemodelo . $partecolor . "GROUP BY cp.IdCProductos, m.IdModelos");
        return $query;
    }

    public function GenerarConcentradoDefectos($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $por) {
        $fechainicio = $this->FechaIngles($fechainicio);
        $fechafin = $this->FechaIngles($fechafin);
        $parteclasificacion = "";
        $parteproducto = "";
        $partemodelo = "";
        $partecolor = "";
        if (count($aclasificacion) > 0) {
            $parteclasificacion = " AND ";
            $contclasi = 1;
            $parteclasificacion .= " ( ";
            foreach ($aclasificacion as $ac) {
                if ($contclasi > 1) {
                    $parteclasificacion .= " OR ";
                }
                $parteclasificacion .= " Clasificacion(p.IdProductos) =" . $ac;
                $contclasi++;
            }
            $parteclasificacion .= " ) ";
        }
        if (count($aproducto) > 0) {
            $parteproducto = " AND ";
            $contprod = 1;
            $parteproducto .= " ( ";
            foreach ($aproducto as $ap) {
                if ($contprod > 1) {
                    $parteproducto .= " OR ";
                }
                $parteproducto .= " p.CProductosId =" . $ap;
                $contprod++;
            }
            $parteproducto .= " ) ";
        }
        if (count($amodelo) > 0) {
            $partemodelo = " AND ";
            $contmod = 1;
            $partemodelo .= " ( ";
            foreach ($amodelo as $am) {
                if ($contmod > 1) {
                    $partemodelo .= " OR ";
                }
                $partemodelo .= " p.ModelosId =" . $am;
                $contmod++;
            }
            $partemodelo .= " ) ";
        }
        if (count($acolor) > 0) {
            $partecolor = " AND ";
            $contcol = 1;
            $partecolor .= " ( ";
            foreach ($acolor as $acol) {
                if ($contcol > 1) {
                    $partecolor .= " OR ";
                }
                $partecolor .= " p.ColoresId =" . $acol;
                $contcol++;
            }
            $partecolor .= " ) ";
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

    public function GenerarDetalleSeleccionadoDefectos($fechainicio, $fechafin, $aclasificacion, $aproducto, $amodelo, $acolor, $por, $nombre) {
        $fechainicio = $this->FechaIngles($fechainicio);
        $fechafin = $this->FechaIngles($fechafin);
        $parteclasificacion = "";
        $parteproducto = "";
        $partemodelo = "";
        $partecolor = "";
        if (count($aclasificacion) > 0) {
            $parteclasificacion = " AND ";
            $contclasi = 1;
            $parteclasificacion .= " ( ";
            foreach ($aclasificacion as $ac) {
                if ($contclasi > 1) {
                    $parteclasificacion .= " OR ";
                }
                $parteclasificacion .= " Clasificacion(p.IdProductos) =" . $ac;
                $contclasi++;
            }
            $parteclasificacion .= " ) ";
        }
        if (count($aproducto) > 0) {
            $parteproducto = " AND ";
            $contprod = 1;
            $parteproducto .= " ( ";
            foreach ($aproducto as $ap) {
                if ($contprod > 1) {
                    $parteproducto .= " OR ";
                }
                $parteproducto .= " p.CProductosId =" . $ap;
                $contprod++;
            }
            $parteproducto .= " ) ";
        }
        if (count($amodelo) > 0) {
            $partemodelo = " AND ";
            $contmod = 1;
            $partemodelo .= " ( ";
            foreach ($amodelo as $am) {
                if ($contmod > 1) {
                    $partemodelo .= " OR ";
                }
                $partemodelo .= " p.ModelosId =" . $am;
                $contmod++;
            }
            $partemodelo .= " ) ";
        }
        if (count($acolor) > 0) {
            $partecolor = " AND ";
            $contcol = 1;
            $partecolor .= " ( ";
            foreach ($acolor as $acol) {
                if ($contcol > 1) {
                    $partecolor .= " OR ";
                }
                $partecolor .= " p.ColoresId =" . $acol;
                $contcol++;
            }
            $partecolor .= " ) ";
        }
        $campo = "";
        switch ($por) {
            case "Clasificacion(p.IdProductos)":
                $campo = "ClasificacionL(p.IdProductos)";
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
        $query = $this->db->query("select p.IdProductos,cp.Nombre as producto,m.Nombre as modelo,co.Nombre as color from Productos p left join CProductos cp on cp.IdCProductos=p.CProductosId left join Modelos m on m.IdModelos=p.ModelosId left join Colores co on co.IdColores=p.ColoresId where  date(FechaCaptura) BETWEEN $fechainicio AND $fechafin" . $parteclasificacion . $parteproducto . $partemodelo . $partecolor . " AND " . $campo . "=" . "'" . $nombre . "'");
        return $query;
    }

}
?>


