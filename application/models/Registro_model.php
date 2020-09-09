<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author: Cheko
 * @version: 1.0
 * @descr: Clase modelo de consultas para el registro de la precarga de formulario
 * */
class Registro_model extends MY_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    /**
     * Funcion que obtiene los formularios por sección
     * @author Cheko
     * @param $id_seccion Id de la sección
     * @return array Los formularios de la sección en un arreglo
     *
     */
     public function obtener_formularios_por_seccion($id_seccion)
     {
       $this->db->flush_cache();
       $this->db->reset_query();
       $status = array('success' => false, 'message' => 'Hubo un error al hacer la consulta', 'data'=>[]);
       try
       {
           $resultado = $this->db->query("select F.id_formulario, F.nombre, F.label, catalogo.get_arbol_secciones_pinta_padres(ES.id_elemento_seccion)
                                          from catalogo.elementos_seccion ES
                                          inner join ui.formulario F on F.id_elemento_seccion = ES.id_elemento_seccion
                                          where id_seccion = {$id_seccion} and ES.activo is true and F.activo is true")->result_array();
           $status['success'] = true;
           $status['message'] = 'Resultados obtenidos con exito';
           $status['data'] = $resultado;
       }catch(Exception $ex)
       {
           $status['data'] = $ex;
       }
       return $status;
     }

     /**
      * Funcion que obtiene campos del formulario
      * @author Cheko
      * @param type $id_formulario id del formulario
      *
      */
      public function obtener_campos_formulario($id_formulario)
      {
          $this->db->flush_cache();
          $this->db->reset_query();
          $status = array('success' => false, 'message' => 'Hubo un error al hacer la consulta', 'data'=>[]);
          try
          {
              $resultado = $this->db->query("select C.label from ui.formulario F
                                             inner join ui.campos_formulario CF on CF.id_formulario = F.id_formulario
                                             inner join ui.campo C on C.id_campo = CF.id_campo
                                             where F.id_formulario = {$id_formulario};")->result_array();
              $status['success'] = true;
              $status['message'] = 'Resultados obtenidos con exito';
              $columnas = [];
              foreach ($resultado as $key => $value) {
                  foreach ($value as $key => $valor) {
                      array_push($columnas, $valor);
                  }

              }
              //información del docente
              array_push($columnas, "matricula");
              array_push($columnas, "categoria del profesor");
              array_push($columnas, "clave de la categoria del profesor");
              array_push($columnas, "curp");
              array_push($columnas, "nombre");
              array_push($columnas, "apellido_paterno");
              array_push($columnas, "apellido_materno");

              $status['data'] = $columnas;
          }catch(Exception $ex)
          {
              $status['data'] = $ex;
          }
          return $status;
      }
}
