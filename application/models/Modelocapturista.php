<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modelocapturista extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
        
    public function ListarHornos() {

        $this->db->select('*');
        $this->db->from('Hornos h');
        $this->db->where('h.Activo', 1);
        $query = $this->db->get();
        return $query;
    }
    
    public function BuscarClave($clave){
        $this->db->select("p.Nombre, p.APaterno, p.AMaterno, pu.IdPuestos");
        $this->db->from("Personas p");
        $this->db->join("Puestos pu", "p.IdPersonas=pu.PersonasId");
        $this->db->where("p.Activo", 1);
        $this->db->where("pu.AreasId", 2);
        $this->db->where("pu.Nombre", "Hornero");
        $this->db->where("pu.Clave", $clave);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return $fila->row();
        } else {
            return "No se encontró la persona";
        }
    }

    public function ListarProductos() {

        $this->db->select('*');
        $this->db->from('CProductos cp');
        $this->db->where('cp.Activo', 1);
        $this->db->where('cp.Nombre !=', 'Accesorios');
        $query = $this->db->get();
        return $query;
    }

    public function ListarCarros() {

        $this->db->select('*');
        $this->db->from('Carros c');
        $this->db->where('c.Activo', 1);//falta agregar la sucursal
        $query = $this->db->get();
        return $query;
    }

    public function ListarModelos($id) {

        $this->db->select('m.Nombre, m.IdModelos, cpm.Imagen');
        $this->db->from('CProductos p');
        $this->db->join('CProductosModelos cpm', 'p.IdCProductos=cpm.CProductosId');
        $this->db->join('Modelos m', 'cpm.ModelosId=m.IdModelos');
        $this->db->where('p.Activo', 1);
        $this->db->where('cpm.Activo', 1);
        $this->db->where('p.IdCProductos', $id);
        $query = $this->db->get();
        return $query;
    }

    public function ListarColores($id) {

        $this->db->select('c.*');
        $this->db->from('Colores c');
        $this->db->join('ModelosColores mc', 'c.IdColores=mc.ColoresId');
        $this->db->join('Modelos m', 'mc.ModelosId=m.IdModelos');
        $this->db->where('c.Activo', 1);
        $this->db->where('m.IdModelos', $id);
        $this->db->Order_by('ClaveImportacion');
        $query = $this->db->get();
        return $query;
    }
    
    public function ListarProductosGuardados($carro,$horno,$prod,$mod,$col,$piezas,$fecha,$hornero){
        //Guarda y despues lo muestra
        try {
             $datos = array(
            'CProductosId'=> $prod,
            'ColoresId'=> $col,
            'CarrosId'=>$carro,
            'HornosId'=>$horno,
            'FechaQuemado'=> $fecha,
            'UsuariosId'=>IdUsuario(),
            'Activo'=>1,
            'Clasificado'=>0,
            'ModelosId'=>$mod,
            'HorneroQuema'=>$hornero,
            'FechaCaptura' => date('Y-m-d | H:i:sa')
            );
             $lista=array();
            for ($i = 0; $i < $piezas; $i++) {
                //$this->db->set('FechaCaptura', 'NOW()', FALSE);
                $this->db->insert('Productos', $datos);  
                $id=$this->db->insert_id();
                $HistorialQuemado=array( 'Fecha'=>$fecha, 'UsuariosId'=>IdUsuario(), 'MovimientosProductosId'=>1,
                    'Activo'=>1, 'ProductosId'=>$id);
                $this->db->insert('HistorialProducto', $HistorialQuemado);
                $HistorialCaptura= array('Fecha' => date('Y-m-d | H:i:sa'), 'UsuariosId'=>IdUsuario(), 'MovimientosProductosId'=>2,
                    'Activo'=>1, 'ProductosId'=>$id);
                //$this->db->set('Fecha', 'NOW()', FALSE);
                $this->db->insert('HistorialProducto', $HistorialCaptura);
                array_push($lista, $id);
            }
            return $lista;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            return "mal";
        }
        }
    
        public function Buscar($row) {
            $this->db->select('p.FechaQuemado, cp.Nombre as producto, ca.Nombre as carro, h.NHorno, m.Nombre as modelo, c.Nombre as color');
            $this->db->from('Productos p');
            $this->db->join('CProductos cp', 'p.CProductosId=cp.IdCProductos');
            $this->db->join('Carros ca', 'p.CarrosId=ca.IdCarros');
            $this->db->join('Hornos h', 'p.HornosId=h.IdHornos');
            $this->db->join('Modelos m', 'p.ModelosId=m.IdModelos');
            $this->db->join('Colores c', 'p.ColoresId=c.IdColores');
            $this->db->where('p.IdProductos', $row);
            $this->db->where('p.Activo', 1);
            $query = $this->db->get();
            return $query;
        }
        
        public function ListarAccesoriosGuardados($fecha){
        try {
             $datos = array(
            'CProductosId'=> 7,
            'FechaQuemado'=> $fecha,
            'UsuariosId'=>IdUsuario(),
            'FechaCaptura' => date('Y-m-d | H:i:sa')
            );             
            //$this->db->set('FechaCaptura', 'NOW()', FALSE);
            $this->db->insert('CarrosAccesorios', $datos);  
            $id = $this->db->insert_id();
            $this->db->select('*');
            $this->db->from('CarrosAccesorios');
            $this->db->where('IdCarrosAccesorios', $id);
            $query = $this->db->get();
            return $query;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            return "mal";
        }
        
        }
 
       
        public function GenerarReporteQ($fechainicio, $fechafin) {
        $fechainicio = $this->FechaIngles($fechainicio);
        $fechafin = $this->FechaIngles($fechafin);
        $Usuario = IdUsuario();
       //Agregar count y group
        $query = $this->db->query("select count(*) as cuantos, h.NHorno as horno, cp.Nombre as producto,m.Nombre as modelo,co.Nombre as color from "
                . "Productos p left join CProductos cp on cp.IdCProductos=p.CProductosId "
                . "left join Modelos m on m.IdModelos=p.ModelosId left join Colores co on co.IdColores=p.ColoresId left join Hornos"
                . " h on p.HornosId=h.IdHornos where  date(FechaQuemado) "
                . "BETWEEN $fechainicio AND $fechafin AND p.UsuariosId = $Usuario " .  " group by horno ,m.IdModelos, cp.IdCProductos, co.IdColores");
        //print_r($this->db->get_compiled_select());
        return $query;
    }

    public function Hornos() {
        $this->db->select('h.NHorno, h.IdHornos');
        $this->db->from("Hornos h");
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


}

?>