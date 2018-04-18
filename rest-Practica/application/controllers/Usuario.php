<?php

/**
 * Created by PhpStorm.
 * User: Windows7
 * Date: 13/04/2018
 * Time: 12:46 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Usuario extends REST_Controller {

    public function __construct()
    {
        //llamado del constructor padre
        parent::__construct();
        $this->load->database();
        $this->load->model('Usuario_Model');

    }

    public function paginar_get(){
        $this->load->helper('paginacion_helper');
        $pagina_solicitada=$this->uri->segment(3);//parametro uno
        $por_pagina=$this->uri->segment(4);//para metro de paginacion 5,10, o 20
        $campos_visualizar=array('id','nombre');
        $respuesta=paginar_todo('clientes',$pagina_solicitada,$por_pagina,$campos_visualizar);
        $this->response($respuesta);

    }

    public function usuario_add_put(){
        $data=$this->put();
        //libreria de codeinteger para validar los datos
        $this->load->library('form_validation');
        $this->form_validation->set_data($data);
        //https://www.codeigniter.com/userguide3/libraries/form_validation.html
       /* $this->form_validation->set_rules('correo','correo electronico','required|valid_email');
        $this->form_validation->set_rules('nombre','correo electronico','required|min_length[2]');*/

       if($this->form_validation->run('usuario_put')){
            //si todas las reglas se cumplen, regresa true
            $cliente=$this->Usuario_Model->set_datos($data);
            $respuesta=$cliente->insert();
            if($respuesta['success']==False){
                $this->response($respuesta,REST_Controller::HTTP_BAD_REQUEST);
            }else{
                $this->response($respuesta,REST_Controller::HTTP_OK);
           }

       }else{
           $respuesta=array(

               'success'=>FALSE,
               'message'=>'Hay errores en la informacion',
               'object'=>$this->form_validation->get_errores_arreglo()
           );
            $this->response($respuesta,REST_Controller::HTTP_BAD_REQUEST);
        }


    }
    public function usuario_get()
    {
        //http://localhost:8081/rest-edukapp/index.php/Usuario/usuario/10->segmento3
        //http://localhost:8081/rest-edukapp/index.php/Usuario/usuario/2?format=xml obtener formato xml, gracias a la libreria format
        $usuario_id=$this->uri->segment(3);
        if(!isset($usuario_id)){
            $respuesta=array(

                'success'=>FALSE,
                'message'=>'Necesitas el ID del usuario',
                'object'=>null
            );
            $this->response($respuesta,REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
        $usuario=$this->Usuario_Model->cliente_model_get($usuario_id);
        if(isset($usuario)){
            //quita la propiedad antes de enviar el objeto
            unset($usuario->telefono1);
            $respuesta=array(

                'success'=>TRUE,
                'message'=>'Registro cargado correctamente',
                'object'=>$usuario
            );
            $this->response($respuesta);
        }else{
            $respuesta=array(

                'success'=>FALSE,
                'message'=>'El registro con el Id '.$usuario_id.', no existe',
                'object'=>null
            );
            $this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);
        }
        $this->response($usuario);
       // echo $usuario_id;
    }


    /*public function users_get(){
       $query = $this->db->query('call sp_usuarios_consulta()');
        //$query = $this->db->query('call sp_clientes_consulta()');

        $fila=$query->row();

        foreach ($query->result() as $fila ){
            $usuario[]=array(
                'clave:'=>intval($fila->cve_Usuario),
                'name'=>$fila->Nombre,
                'nick'=>$fila->Nick_Usuario,
                'perfil'=>$fila->Perfil,
                'type'=>$fila->Tipo,
            );
        }
        if(!isset($fila)){
            $respuesta=array(

                'success'=>FALSE,
                'message'=>'Registros no cargados',
                'objects'=>null
            );

        }else{
            $respuesta=array(

                'success'=>TRUE,
                'message'=>'Registros cargados exitosamente',
                'objects'=>$usuario
            );
        }
        $respuesta=array(

            'success'=>TRUE,
            'message'=>'Registros no cargados',
            'objects'=>null
        );
       echo json_encode($respuesta);

    }*/

}