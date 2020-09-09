<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que hace la precarga DIE de la
 * profecionalización docente
 * @author Cheko
 *
 */
class Registro extends Core_secciones
{
    const IMPORTAR = 'cargar';

    function __construct()
    {
        parent::__construct();
          $this->load->library('form_complete');
          $this->load->model('Secciones_model', 'secciones');
          $this->load->model('Catalogo_model', 'catalogo');
          $this->load->model('Registro_model','registro');
    }

    public function index($metodo = '')
    {
        switch ($metodo) {
            case Registro::IMPORTAR:
                if($this->input->post())
                {
                  $datos = $this->input->post();
                  $columnas = $this->registro->obtener_campos_formulario($datos['id_formulario'])['data'];
                  $respuesta = json_decode($this->agregar_csv($datos, "censo"));
                  $output['mensaje'] = $respuesta['msj'];
                }
                break;
            default:
                //echo 'DEFAULT METHOD';
                break;
        }
        $secciones = $this->secciones->get_seccion();
        $output['secciones'] = $secciones;
        //$output['formularios'] = $formularios;
        $view = $this->load->view('registro/registro.tpl.php', $output, true);
        $this->template->setMainContent($view);
        $this->template->getTemplate();
    }

    /**
     * Función que maneja la petición
     * para poder exportar la estructura
     * de un formulario
     * @author Cheko
     * @param type $modulo tabla exportar
     * @param type $id id del formulario
     *
     */
    public function exportar($formulario, $id){
          $peticion = $this->input->get(null,true);
          $file_name = 'formulario_'.$formulario.'_'.date('Ymd_his', time());
          $columnas = $this->registro->obtener_campos_formulario($id)['data'];
          $this->exportar_xls_csv($file_name, $columnas, []);
    }

    /**
     * Función para exportar en excel de una tabla
     * @author Cheko
     * @param type $nombre_archivo Nombre del archivo
     * @param type $modulo Nombre del modulo
     * @param Array $columnas Los encabezados del excel
     * @param Array $datos Los datos de las columnas(renglones)
     *
     */
    private function exportar_xls_csv($nombre_archivo=NULL, $columnas=[], $datos=[])
    {
        if($nombre_archivo == NULL || count($columnas) < 1)
        {
            return $this->restfull_respuesta('error', 'No se puede hacer la exportación a falta de datos', []);
        }
        else
        {
          $registros['data'] = $datos;
          $registros['columnas'] = $columnas;
        }

        return $this->exportar_xls($registros['columnas'], $registros['data'], null, null, $nombre_archivo);
    }


    /**
     * Funcion que agrega el csv
     * @author Cheko
     * @param type $peticion Tipo de peticion y datos de la peticion
     * @param type $modulo El modulo para saber de donde es el archivo (elementos catalogo o cursos)
     * @param type $id Identificador para saver de que modulo especifico es
     *
     */
    private function agregar_csv($peticion, $modulo)
    {
        $config['upload_path']          = './uploads/'.$modulo.'/';
        $config['allowed_types']        = 'csv';
        //$config['max_size']             = 100;
        //$config['max_width']            = 1024;
        //$config['max_height']           = 768;
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('fformulario'))
        {
            $error = array('error' => $this->upload->display_errors());
            return $this->restfull_respuesta('error', 'No se pudo cargar el archivo', $error);
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            //pr($data);
            $this->load->library('csvimport');
            $nombre_archivo = $data['upload_data']['full_path'];
            $datos['id_usuario'] = 1; //cambiar el id
            $datos['modelo'] = "Registro_model";
            $datos['fecha'] = '2018-02-20';
            $datos['nombre_archivo'] = $data['upload_data']['file_name'];
            $datos['peso'] = $data['upload_data']['file_size'];
            $datos['funcion'] = "insertar_".$modulo;
            $datos['modulo'] = $modulo;
            $column_headers = $this->registro->obtener_campos_formulario($peticion['id_formulario'])['data'];
            $delimiter = ',';
            $datos['carga'] = $this->csvimport->get_array($nombre_archivo, $column_headers, $detect_line_endings=FALSE, $initial_line=FALSE, $delimiter);
            if(!$this->verificar_cabeceras($nombre_archivo, $column_headers))
            {
                return $this->restfull_respuesta('error', "No se pudo cargar el archivo, las columnas son incorrectas, verifique su archivo sobre el nombre de las columnas", []);

            }
            if(count($datos['carga']) < 0)
            {
                return $this->restfull_respuesta('error', "No se puede un archivo vacio", []);
            }

            foreach ($datos['carga'] as $key => $value) {
                $datos['carga'][$key] = array_merge($value,$peticion);
            }
            $guardar = $this->guardar_csv_en_bd($datos);
            $full_path_file = $data['upload_data']['full_path'];
            return $this->restfull_respuesta('ok', "Se cargo el archivo correctamente", $data);

        }
    }

    /**
     * Función que verifica cabeceras del archivo
     * @author Cheko
     * @param type $archivo csv con la primera linea de cabeceras
     * @param type $columnas para verificar si son esa columnas
     * @return boolean regresa verdad o falso si son correctas las cabeceras
     *
     */
     private function verificar_cabeceras($archivo, $columnas)
     {
        $lineas=file($archivo);
        $cabeceras_archivo = explode(',',$lineas[0]);

        if(count($cabeceras_archivo) != count($columnas))
        {
            return false;
        }
        $limpias = [];
        foreach ($limpias as $key => $value) {
            if (in_array(trim($campo), $columnas))
            {

            }
            else
            {
                return false;
            }
        }

        return true;
     }

    /**
     * Función que guarda los datos del csv en la base de datos
     * particularmente en la tabla de precarga y detalles_precarga
     * dependiendo el modulo.
     * @author Cheko
     * @param $datos Los datos en formato de arreglo del archivo csv
     *
     */
    private function guardar_csv_en_bd($datos=[])
    {
        $this->load->helper('date');
        $detallesCarga = [];
        $datosCarga = [];
        if(count($datos)<0)
        {
            return $this->restfull_respuesta('error', "No se cargo correctamente el archivo, verifique que este correcto", $datos);
        }
        else
        {
            $datosCarga['id_usuario'] = 1;//$this->get_datos_sesion()['id_usuario'];
            $datosCarga['nombre_archivo'] = $datos['nombre_archivo'];
            $datosCarga['peso'] = $datos['peso'];
            $datosCarga['modelo'] = $datos['modelo'];
            $datosCarga['funcion'] = $datos['funcion'];
            $insertar['data'] = $this->catalogo->insert_registro('sistema.precargas', $datosCarga);

            $detallesCarga['id_precarga'] = $insertar['data']['data']['id_elemento'];
            $detallesCarga['status'] = "SIN REALIZAR";
            $detallesCarga['tabla_destino'] = $datos['modulo'];
            $detallesCarga['id_tabla_destino'] = NULL;
            $detallesCarga['descripcion_status'] = NULL;

            //$insertar['data'] = $this->catalogo->insert_registro('sistema.detalle_precargas', $detallesCarga);
            foreach ($datos['carga'] as $key => $value) {
                $detallesCarga['detalle_registro'] = json_encode($value);
                $insertar['data'] = $this->catalogo->insert_registro('sistema.detalle_precargas', $detallesCarga);
            }
            return $this->restfull_respuesta('ok', "Se cargo correctamente el archivo", $datos);
        }
    }

    /**
     * Funcion que obtiene las cabeceras para la estructura
     * de un formulario de una seccion
     * @author Cheko
     * @param type $id_formulario Identificador para obtener las cabeceras de formulario
     * @return Array regresa las cabeceras que deben de tener por cada formulario
     *
     */
     private function obtener_cabeceras_formulario($id_formulario)
     {
       return true;
     }

     /**
      * Función que obtiene los formularios por seccion
      * @author Cheko
      * @param type $id_seccion Seccion a la que se le mostraran los formularios
      *
      */
      public function obtener_formularios($id_seccion=NULL)
      {
          $this->agregar_cabeceras();
          if($id_seccion != NULL)
          {
              $query = $this->registro->obtener_formularios_por_seccion($id_seccion);
              $respuesta = $this->restfull_respuesta('ok','Se tiene que tener un id de la seccion para mostrar los formularios', $query);
              echo $respuesta;
          }
          else
          {
              $respuesta = $this->restfull_respuesta('error','Peticion incorrecta, no es el método o hacen falata parámetros',[]);
              echo $respuesta;
          }
      }

      /**
       * Funcion para manejar las respuestas hacia el cliente
       * @author Cheko
       * @param String estatus de la respuesta
       * @param String mensaje de la respuesta
       * @param Array arreglos de datos de la respuesta
       *
       */
      private function restfull_respuesta($status, $msj, $datos)
      {
          $respuesta = [];
          $respuesta['estatus'] = $status;
          $respuesta['msj'] = $msj;
          $respuesta['datos'] = $datos;
          return json_encode($respuesta);
      }

      /**
       * Funcion agregar las cabeceras a la paticion
       * @author Cheko
       *
       */
      private function agregar_cabeceras(){
          header('Content-Type: application/json; charset=utf-8;');
      }
}
