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
    
    public function ListarProductosGuardados($carro,$horno,$prod,$mod,$col,$piezas,$fecha){
        //Guarda y despues lo muestra
        try {
             $datos = array(
            'CProductosId'=> $prod,
            'ColoresId'=> $col,
            'CarrosId'=>$carro,
            'HornosId'=>$horno,
            'FechaQuemado'=> $fecha,
            'UsuariosId'=>1,
            'Activo'=>1,
            'Clasificado'=>0,
            'ModelosId'=>$mod
            );
            //falta repetir dependiendo de piezas
            for ($i = 0; $i <= $piezas; $i++) {
                $this->db->set('FechaCaptura', 'NOW()', FALSE);
                $this->db->insert('Productos', $datos);  
            }
            return "bien";
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            return "mal";
        }
        }
    

}

?>