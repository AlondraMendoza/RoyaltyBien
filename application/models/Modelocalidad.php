<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modelocalidad extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
   public function BuscarClave($clave) {
        $this->db->select("p.IdProductos, cp.Nombre as producto, c.Nombre as color, m.Nombre as modelo");
        $this->db->from("Productos p");
        $this->db->join("CProductos cp", "p.CProductosId=cp.IdCProductos");
        $this->db->join("Colores c", "p.ColoresId=c.IdColores");
        $this->db->join("Modelos m", "p.ModelosId=m.IdModelos");
        $this->db->where("p.Activo", 1);
        $this->db->where("p.IdProductos", $clave);
        $this->db->where("p.ClasificacionesId", 5);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return $fila->row();
        } else {
            return "No se encontró el producto";
        }
    }
    
    public function GuardarProductos($idProducto) {
        $existe = false;
        if ($this->ProductoEnAlmacenP($idProducto)) {
            $existe = true;
            return "Existe";
        } else {
            $this->GuardarProductoAlmacenP($idProducto);
            return "correcto";
        }
    }
    
    public function ProductoEnAlmacenP($idProducto) {
        $this->db->select("i.IdInventariosMermas");
        $this->db->from("InventariosMermas i");
        $this->db->where("i.ProductosId", $idProducto);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function GuardarProductoAlmacenP($idProducto) {
        $datos = array(
            'FechaEntrada' => date('Y-m-d | H:i:sa'),
            'ProductosId' => $idProducto,
            'UsuariosId' => IdUsuario(),
            'Activo' => 1,
            'Procesado'=> 0
        );
        //$this->db->set('FechaEntrada', 'NOW()', FALSE);
        $this->db->insert('InventariosMermas', $datos);
    }
    
    public function GuardarProcesar($idProducto){
        $this->db->set("Procesado", 1);
        $this->db->set("FechaProcesado", date('Y-m-d | H:i:sa'));
        $this->db->set("UsuariosIdProcesado", IdUsuario());
        $this->db->where("ProductosId", $idProducto);
        $this->db->where("Procesado", 0);
        $this->db->update("InventariosMermas");
        return "correcto";
    }
    
    public function GenerarReporteQ($fechainicio, $fechafin) {
        $fechainicio = $this->FechaIngles($fechainicio);
        $fechafin = $this->FechaIngles($fechafin);
        $Usuario = IdUsuario();
       //Agregar count y group
        $query= $this->db->query("Select cp.Nombre as producto,m.Nombre as modelo,co.Nombre as color,
            im.FechaEntrada, im.UsuariosId, im.Procesado as Destruido, im.FechaProcesado as FechaDestruccion,
            im.UsuariosIdProcesado as Usuariodestruccion from
            Productos p left join CProductos cp on cp.IdCProductos=p.CProductosId
            left join Modelos m on m.IdModelos=p.ModelosId left join Colores co on co.IdColores=p.ColoresId left join InventariosMermas 
            im on im.ProductosId= p.IdProductos where  date(im.FechaEntrada) 
            BETWEEN $fechainicio AND $fechafin");
        //print_r($this->db->get_compiled_select());
        return $query;
    }
    
    public function GenerarConcentradoQ($fechainicio, $fechafin, $por) {
        $fechainicio = $this->FechaIngles($fechainicio);
        $fechafin = $this->FechaIngles($fechafin);
        //Agregar count y group
//        $query= $this->db->query("select count(*) as cuantos from
//        Productos p left join CProductos cp on cp.IdCProductos=p.CProductosId
//        left join Modelos m on m.IdModelos=p.ModelosId left join Colores co on co.IdColores=p.ColoresId
//        left join InventariosMermas im on im.ProductosId= p.IdProductos
//        where  date(im.FechaEntrada) BETWEEN $fechainicio AND $fechafin group by m.IdModelos, cp.IdCProductos, co.IdColores");
        //print_r($this->db->get_compiled_select());
        $campo = "";
        switch ($por) {
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

        $query = $this->db->query("select count(*) as cuantos, $campo from Productos p left join CProductos cp on cp.IdCProductos=p.CProductosId "
                . "left join Modelos m on m.IdModelos=p.ModelosId left join Colores co on co.IdColores=p.ColoresId "
                . "left join InventariosMermas im on im.ProductosId= p.IdProductos where date(im.FechaEntrada) BETWEEN $fechainicio AND $fechafin" ." group by " . $por);
        return $query;
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
    
    public function Usuario($UsuarioId) {
        $this->db->select('p.*');
        $this->db->from('Personas p');
        $this->db->join('Usuarios u', 'p.UsuariosId=u.IdUsuarios');
        $this->db->where('u.IdUsuarios', $UsuarioId);
        $query = $this->db->get()->row();
        return $query;
    }
}
?>
