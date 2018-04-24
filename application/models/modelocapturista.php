<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modelocapturista extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
        
    public function ListarHornos() {
//        $con = new Conexion();
//        $query = "SELECT Hornos.* FROM Hornos where Activo=1";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
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
//        $con = new Conexion();
//        $query = "SELECT CProductos.* FROM CProductos where Activo=1 and Nombre != 'Accesorios'";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
        $this->db->select('*');
        $this->db->from('CProductos cp');
        $this->db->where('cp.Activo', 1);
        $this->db->where('cp.Nombre !=', 'Accesorios');
        $query = $this->db->get();
        return $query;
    }

    public function ListarCarros() {
//        $con = new Conexion();
//        $query = "SELECT Carros.* FROM Carros where Activo=1";
//        //falta agregar la sucursal
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
        $this->db->select('*');
        $this->db->from('Carros c');
        $this->db->where('c.Activo', 1);//falta agregar la sucursal
        $query = $this->db->get();
        return $query;
    }

    public function ListarModelos($id) {
//        $con = new Conexion();
//        $query = "SELECT m.Nombre, CPM.Imagen, m.IdModelos from CProductos as p join CProductosModelos "
//                . "as CPM on p.IdCProductos=CPM.CProductosId join Modelos as m on CPM.ModelosId=m.IdModelos "
//                . "where p.Activo=1 and p.IdCProductos=$id";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
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
//        $con = new Conexion();
//        $query = "SELECT c.Nombre, c.Descripcion, c.IdColores from Colores as c join ModelosColores
//        as MC on c.IdColores=MC.ColoresId join Modelos as m on MC.ModelosId=m.IdModelos
//        where c.Activo=1 and m.IdModelos=$id";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
        $this->db->select('c.*');
        $this->db->from('Colores c');
        $this->db->join('ModelosColores mc', 'c.IdColores=mc.ColoresId');
        $this->db->join('Modelos m', 'mc.ModelosId=m.IdModelos');
        $this->db->where('c.Activo', 1);
        $this->db->where('m.IdModelos', $id);
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
            'HorneroQuema'=>$hornero
            );
             $lista=array();
            for ($i = 0; $i < $piezas; $i++) {
                $this->db->set('FechaCaptura', 'NOW()', FALSE);
                $this->db->insert('Productos', $datos);  
                $id=$this->db->insert_id();
                $HistorialQuemado=array( 'Fecha'=>$fecha, 'UsuariosId'=>1, 'MovimientosProductosId'=>1,
                    'Activo'=>1, 'ProductosId'=>$id);
                $this->db->insert('HistorialProducto', $HistorialQuemado);
                $HistorialCaptura= array('UsuariosId'=>1, 'MovimientosProductosId'=>2,
                    'Activo'=>1, 'ProductosId'=>$id);
                $this->db->set('Fecha', 'NOW()', FALSE);
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
            );             
            $this->db->set('FechaCaptura', 'NOW()', FALSE);
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
        
        

}

?>