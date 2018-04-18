<?php

/**
 * Created by PhpStorm.
 * User: Windows7
 * Date: 13/04/2018
 * Time: 02:59 PM
 */
class Usuario_Model extends CI_Model
{
    public $iD;
    public $nombre;
    public $correo;
    public $zip;
    public $activo;

     public function cliente_model_get($id){
        $this->db->where(array('id'=>$id,'activo'=>1));
        $query =$this->db->get('clientes');
        $rows = $query->custom_row_object(0, 'Usuario_Model');
        if (isset($rows)){
            $rows->id=intval($rows->id);
            $rows->activo=intval($rows->activo);
        }
        return $rows;

   }

   public function set_Datos($data_cruda){
         //setea campos enviados con los del modelo
         foreach ( $data_cruda as $nombre_campo =>$valor_Campo ){
                if(property_exists('Usuario_Model',$nombre_campo)){
                    $this->$nombre_campo=$valor_Campo;
                }
         }
         if($this->activo==null){
             $this->activo=1;
         }
         $this->nombre=strtoupper($this->nombre);
         return $this;

   }

   public  function insert(){
       //verificar que el correo  exsite
       $query=$this->db->get_where('clientes',array('correo'=>$this->correo));
       $cliente_correo=$query->row();

       if(isset($cliente_correo)){
           //si existe la columna
           $respuesta=array(
               'success'=>FALSE,
               'message'=>'El correo electronico ya ha sido registrado',


           );
           // $this->response($respuesta,REST_Controller::HTTP_BAD_REQUEST);

           return $respuesta;

       }


       $success=$this->db->insert('clientes',$this);
       if($success){
           $respuesta=array(
               'success'=>TRUE,
               'message'=>'Usuario resgitrado',
               'cliente_id'=>$this->db->insert_id()

           );
          // $this->response($respuesta,REST_Controller::HTTP_OK);

       }else{
           $respuesta=array(
               'success'=>FALSE,
               'message'=>'Error al registrar al cliente',
               'error'=>$this->db->_error_message(),
               'errr_num'=>$this->db->_error_number()

           );
          // $this->response($respuesta,REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

       }
       return $respuesta;

   }
   /* public function cliente_model_get($id){
        $query = $this->db->query('call sp_usuario_consulta_clave('.$id.')');
        $rows = $query->custom_row_object(0, 'Usuario_Model');
        return $rows;

    }*/


}