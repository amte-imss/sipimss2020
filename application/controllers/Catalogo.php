<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @author      : JZDP & Chekoarcs
 * */
class Catalogo extends MY_Controller {

    const LISTA = 'lista', LIMIT = 25, NUEVA = 'agregar', EDITAR = 'editar',
    CREAR = 'crear', LEER= 'leer', ACTUALIZAR = 'actualizar', ELIMINAR = 'eliminar',
    EXPORTAR = 'exportar', IMPORTAR='importar';

    function __construct() {
        parent::__construct();
        $this->load->library('Form_validation');
        $this->load->model('Catalogo_model', 'catalogo');
        $this->load->config('catalogos');
    }

    public function index() {
        redirect(site_url());
    }

    public function categoria($opcion = '')
    {
        $this->load->model('Catalogo_model', 'catalogo');
        // $this->load->config('catalogos');
        $filtros = $this->config->item('catalogo.categorias');
        $c_query = $this->config->item('catalogo.grupos_categorias');
        $c_query['select'] = array('grupos_categorias.id_grupo_categoria', 'grupos_categorias.nombre grupo_categoria');
        $output = [];
        $output['js'] = '/catalogo/categorias.js';
        $output['exportar'] = site_url('catalogo/categoria/exportar');
        switch ($opcion) {
            case Catalogo::LISTA:
                $params = $this->input->get(null, true);
                $filtros['limit'] = isset($params['pageSize'])? $params['pageSize']:Catalogo::LIMIT;
                $filtros['offset'] = isset($params['pageIndex'])?  ($filtros['limit']*($params['pageIndex']-1)):0;
                $filtros['where'] = $this->prepara_filtros($params, array('id_grupo_categoria'=>'categorias.id_grupo_categoria'));
                $filtros['like'] = $this->prepara_filtros($params, array(
                    'clave_categoria' => 'clave_categoria',
                    'categoria' => 'categorias.nombre'
                ));
                $registros['data'] = $this->catalogo->get_registros('catalogo.categorias', $filtros);
                // $registros['query'] = $this->db->last_query();
                $filtros['total'] = true;
                $total = $this->catalogo->get_registros('catalogo.categorias', $filtros)[0]['total'];
                $registros['length'] = $total;
                header('Content-Type: application/json; charset=utf-8;');
                echo json_encode($registros);
                break;
            case Catalogo::NUEVA:
                $post = [];
                $post = $this->input->post(null, true);
                $this->nueva_categoria($post);
                break;
            case Catalogo::EDITAR:
                $post = [];
                $post = $this->input->post(null, true);
                $this->editar_categoria($post);
                break;
            case Catalogo::ELIMINAR:
                $post = $this->input->post(null, true);
                $this->eliminar_categoria($post);
                break;
            case Catalogo::EXPORTAR:
                $file_name = 'catalogo_categorias_' . date('Ymd_his', time());
                $filtros = $this->config->item('catalogo.categorias');
                $registros['data'] = $this->catalogo->get_registros('catalogo.categorias', $filtros);
                $registros['columnas'] = array(
                    'id_categoria','categoria', 'id_grupo_categoria',
                    'grupo_categoria','clave_categoria',
                );
                $this->exportar_xls($registros['columnas'], $registros['data'], null, null, $file_name);
            default:
                //$grupos_categorias = array('id_grupo_categoria' => '', 'grupo_categoria' => 'Seleccionar');
                $grupos_categorias = $this->catalogo->get_registros('catalogo.grupos_categorias', $c_query);
                array_unshift($grupos_categorias,  array('id_grupo_categoria' => '', 'grupo_categoria' => 'Seleccionar'));
                $output['var_js'] = array(
                    array(
                        "name" => 'g_grupos_categorias',
                        'value' => json_encode($grupos_categorias)
                    )
                );
                $output['scripts_adicionales'] = array($this->load->view('tc_template/var_js_view.tpl.php', $output, true));
                $view = $this->load->view('catalogo/grid_general.tpl.php', $output, true);
                $this->template->setMainContent($view);
                $this->template->getTemplate();
                break;
        }
    }

    private function eliminar_categoria(&$params)
    {
        if($this->input->post())
        {
            $where_ids = array('id_categoria' => $params['id_categoria']);
            $result = $this->catalogo->delete_registros('catalogo.categorias', $where_ids);
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($result);
        }
    }

    private function nueva_categoria(&$params)
    {
        if($this->input->post())
        {
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('insert_catalogo_categorias'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations); //Añadir validaciones
            if ($this->form_validation->run() == TRUE)
            {
                $form = array(
                    'nombre' => $params['categoria'],
                    'clave_categoria' => $params['clave_categoria'],
                    'id_grupo_categoria' => $params['id_grupo_categoria']
                );
                $result = $this->catalogo->insert_registro('catalogo.categorias', $form);
            }else
            {
                $result['success'] = false;
                $result['message'] = validation_errors();
            }
            $result['data'] = $params;
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($result);
        }
    }

    private function editar_categoria(&$params)
    {
        if($this->input->post())
        {
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('update_catalogo_categorias'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations); //Añadir validaciones
            if ($this->form_validation->run() == TRUE)
            {
                $form = array(
                    'nombre' => $params['categoria'],
                    'clave_categoria' => $params['clave_categoria'],
                    'id_grupo_categoria' => $params['id_grupo_categoria']
                );
                $ids = array(
                    'id_categoria' => $params['id_categoria']
                );
                $result = $this->catalogo->update_registro('catalogo.categorias', $form, $ids);
            }else
            {
                $result['success'] = false;
                $result['message'] = validation_errors();
            }
            $result['data'] = $params;
            $result['data']['id_categoria'] = $ids['id_categoria'];
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($result);
        }
    }
    
   /**
     * Función que muestra la vista para cargar csv
     * @author Lima
     * @param  $view['status']['result']== 1 Si Cargo el CSV Redirige a Precarga
     *
     * Inicia Carga Categoría por Lote */
    public function categoria_csv()
    {
      if ($this->input->post())
      {     // SI EXISTE UN ARCHIVO EN POST
          $config['upload_path'] = './uploads/';      // CONFIGURAMOS LA RUTA DE LA CARGA PARA LA LIBRERIA UPLOAD
          $config['allowed_types'] = 'csv';           // CONFIGURAMOS EL TIPO DE ARCHIVO A CARGAR
          $config['max_size'] = '1000';               // CONFIGURAMOS EL PESO DEL ARCHIVO
          $this->load->library('upload', $config);    // CARGAMOS LA LIBRERIA UPLOAD
          $view['status']['result'] = false;
          if ($this->upload->do_upload())
          { //Se ejecuta la validación de datos
              $this->load->library('csvimport');
              $file_data = $this->upload->data();     //BUSCAMOS LA INFORMACIÓN DEL ARCHIVO CARGADO
              $file_path = './uploads/' . $file_data['file_name'];         // CARGAMOS LA URL DEL ARCHIVO
              if ($this->csvimport->get_array($file_path))
              {              // EJECUTAMOS EL METODO get_array() DE LA LIBRERIA csvimport PARA BUSCAR SI EXISTEN DATOS EN EL ARCHIVO Y VERIFICAR SI ES UN CSV VALIDO
                  $csv_array = $this->csvimport->get_array($file_path);   //SI EXISTEN DATOS, LOS CARGAMOS EN LA VARIABLE $csv_array
                  $view['status'] = $this->catalogo->carga_masiva($csv_array,$file_data);
                  //pr($view['status']);
                  //$this->reporte_registro($view['status']);
              } else
              {
                  $view['status']['msg'] = "Formato inválido";
              }
          } else
          {
              $view['status']['msg'] = "Formato inválido";
          }

          if($view['status']['result']==1)
          {
            redirect('precarga');
          }  
      }
      #Muestra Vista del Template Carga CSV
      $main_content = $this->load->view('catalogo/formulario_carga_categorias.tpl.php', array(), true);
      $this->template->setMainContent($main_content);
      $this->template->getTemplate();
    }
    /* Termina Carga Categoría por Lote */

    public function delegacion() {
        try {
            $this->db->schema = 'catalogo';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('delegaciones');

            $crud->set_subject('Delegación');
            $crud->display_as('clave_delegacional', 'Clave');
            $crud->display_as('nombre', 'Nombre de la delegación');
            $crud->display_as('id_region', 'Región');
            $crud->display_as('configuraciones', 'Configuraciones');
            $crud->display_as('latitud', 'Latitud');
            $crud->display_as('longitud', 'Longitud');
            $crud->display_as('fecha', 'Fecha');
            $crud->display_as('activo', 'Activo');


            $crud->set_primary_key('id_region', 'regiones'); //Definir llaves primarias, asegurar correcta relación
            $crud->set_primary_key('id_delegacion', 'delegaciones');

            $crud->set_relation('id_region', 'regiones', 'nombre', null, 'nombre'); //Establecer relaciones

            $crud->columns('clave_delegacional', 'nombre', 'id_region', 'latitud', 'longitud', 'fecha', 'activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave_delegacional', 'nombre', 'id_region', 'latitud', 'longitud', 'configuraciones', 'fecha', 'activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave_delegacional', 'nombre', 'id_region', 'latitud', 'longitud', 'configuraciones', 'fecha', 'activo'); //Definir campos que se van a editar y su orden

            $crud->change_field_type('activo', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));

            $crud->set_rules('nombre', 'Nombre de categoría', 'trim|required');
            $crud->set_rules('fecha', 'Fecha', 'trim|required');
            $crud->set_rules('activo', 'Activo', 'required');

            $crud->unset_delete();
            $output = $crud->render();
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            //$main_content = $this->load->view('admin/admin', $view, true);
            $main_content = $view['contenido'];
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function departamento($opcion = '', $id = null)
    {
        $this->load->model('Catalogo_model', 'catalogo');
        // $this->load->config('catalogos');
        $filtros = $this->config->item('catalogo.departamentos_instituto');
        $output = [];
        $output['js'] = '/catalogo/departamento.js';
        $output['exportar'] = site_url('catalogo/departamento/exportar');
        switch ($opcion) {
            case Catalogo::LISTA:
                $params = $this->input->get(null, true);
                $filtros['limit'] = isset($params['pageSize'])? $params['pageSize']:Catalogo::LIMIT;
                $filtros['offset'] = isset($params['pageIndex'])?  ($filtros['limit']*($params['pageIndex']-1)):0;
                $filtros['like'] = $this->prepara_filtros($params, array(
                    'departamento' => 'departamentos.nombre',
                    'clave_departamental' => 'departamentos.clave_departamental',
                    'clave_unidad' => 'departamentos.clave_unidad'
                ));
                // pr($filtros);
                $registros['data'] = $this->catalogo->get_registros('catalogo.departamentos_instituto departamentos', $filtros);
                // $registros['query'] = $this->db->last_query();
                $filtros['total'] = true;
                $total = $this->catalogo->get_registros('catalogo.departamentos_instituto departamentos', $filtros)[0]['total'];
                $registros['length'] = $total;
                header('Content-Type: application/json; charset=utf-8;');
                echo json_encode($registros);
                break;
            case Catalogo::NUEVA:
                $post = [];
                $post = $this->input->post(null, true);
                $this->nuevo_departamento($post);
                break;
            case Catalogo::EDITAR:
                $post = [];
                $post = $this->input->post(null, true);
                $this->editar_departamento($post);
                break;
            case Catalogo::ELIMINAR:
               $post = $this->input->post(null, true);
               $this->eliminar_departamento($post);
               break;
            case Catalogo::EXPORTAR:
                $file_name = 'catalogo_adscripciones_' . date('Ymd_his', time());
                $filtros = $this->config->item('catalogo.departamentos_instituto');
                $registros['data'] = $this->catalogo->get_registros('catalogo.departamentos_instituto departamentos', $filtros);
                $registros['columnas'] = array(
                   "id_departamento_instituto",
                   "Adscripción",
                   "Clave departamental",
                   "Clave unidad",
                   "Activa"
                );
                $this->exportar_xls($registros['columnas'], $registros['data'], null, null, $file_name);
                break;
            default:
                $view = $this->load->view('catalogo/grid_general.tpl.php', $output, true);
                $this->template->setMainContent($view);
                $this->template->getTemplate();
                break;
        }
    }

    private function eliminar_departamento(&$params)
    {
      if($this->input->post())
      {
          $where_ids = array('id_departamento_instituto' => $params['id_departamento_instituto']);
          $result = $this->catalogo->delete_registros('catalogo.departamentos_instituto', $where_ids);
          header('Content-Type: application/json; charset=utf-8;');
          echo json_encode($result);
      }
    }

    private function nuevo_departamento(&$params = [])
    {
        if($this->input->post())
        {
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('insert_catalogo_departamento'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations); //Añadir validaciones
            if ($this->form_validation->run() == TRUE)
            {
                $form = array(
                    'nombre' => $params['departamento'],
                    'clave_unidad' => $params['clave_unidad'],
                    'clave_departamental' => $params['clave_departamental']
                );
                $result = $this->catalogo->insert_registro('catalogo.departamentos_instituto', $form);
            }else
            {
                $result['success'] = false;
                $result['message'] = validation_errors();
            }
            $result['data'] = $params;
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($result);
        }
    }

    private function editar_departamento($params = [])
    {
        if($this->input->post())
        {
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('update_catalogo_departamento'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations); //Añadir validaciones
            if ($this->form_validation->run() == TRUE)
            {
                $form = array(
                    'nombre' => $params['departamento'],
                    'clave_unidad' => $params['clave_unidad'],
                    'clave_departamental' => $params['clave_departamental']
                );
                $ids = array(
                    'id_departamento_instituto' => $params['id_departamento_instituto']
                );
                $result = $this->catalogo->update_registro('catalogo.departamentos_instituto', $form, $ids);
            }else
            {
                $result['success'] = false;
                $result['message'] = validation_errors();
            }
            $result['data'] = $params;
            $result['data']['id_departamento_instituto'] = $ids['id_departamento_instituto'];
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($result);
        }
    }

    public function grupo_categoria()
    {
        try {
            $this->db->schema = 'catalogo';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('grupos_categorias');

            $crud->set_subject('Grupo categoría');
            $crud->display_as('id_grupo_categoria', 'ID');
            $crud->display_as('clave', 'Clave');
            $crud->display_as('nombre', 'Nombre del grupo-categoría');
            $crud->display_as('descripcion', 'Descripción');

            $crud->set_primary_key('id_grupo_categoria', 'grupos_categorias'); //Definir llaves primarias, asegurar correcta relación

            $crud->columns('id_grupo_categoria', 'clave', 'nombre'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave', 'nombre', 'descripcion'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave', 'nombre', 'descripcion'); //Definir campos que se van a editar y su orden

            $crud->set_rules('nombre', 'Nombre del grupo-categoría', 'trim|required');

            $crud->unset_delete();
            $output = $crud->render();

            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            // $main_content = $this->load->view('admin/admin', $view, true);
            $main_content = $view['contenido'];
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function region()
    {
        try {
            $this->db->schema = 'catalogo';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('regiones');

            $crud->set_subject('Región');
            $crud->display_as('id_region', 'ID');
            $crud->display_as('clave_regional', 'Clave');
            $crud->display_as('nombre', 'Nombre de la región');
            $crud->display_as('configuraciones', 'Configuraciones');
            $crud->display_as('fecha', 'Fecha');
            $crud->display_as('activo', 'Activo');

            $crud->set_primary_key('id_region', 'regiones'); //Definir llaves primarias, asegurar correcta relación

            $crud->columns('id_region', 'clave_regional', 'nombre', 'fecha', 'activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave_regional', 'nombre', 'fecha', 'configuraciones', 'activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave_regional', 'nombre', 'fecha', 'configuraciones', 'activo'); //Definir campos que se van a editar y su orden

            $crud->change_field_type('activo', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));

            $crud->set_rules('nombre', 'Nombre de la región', 'trim|required');
            $crud->set_rules('clave_regional', 'Clave', 'trim|required');
            $crud->set_rules('fecha', 'Fecha', 'trim|required');
            $crud->set_rules('activo', 'Activo', 'required');

            $crud->unset_delete();
            $output = $crud->render();

            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            // $main_content = $this->load->view('admin/admin', $view, true);
            $main_content = $view['contenido'];
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function tipo_unidad()
    {
        try {
            $this->db->schema = 'catalogo';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('tipos_unidades');

            $crud->set_subject('Tipo de unidad');
            $crud->display_as('id_tipo_unidad', 'ID');
            $crud->display_as('nombre', 'Nombre del tipo de unidad');
            $crud->display_as('clave', 'Clave');
            $crud->display_as('descripcion', 'Descripción');
            $crud->display_as('activa', 'Activo');

            $crud->set_primary_key('id_tipo_unidad', 'tipos_unidades'); //Definir llaves primarias, asegurar correcta relación

            $crud->columns('id_tipo_unidad', 'nombre', 'clave', 'activa'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave', 'nombre', 'descripcion', 'activa'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave', 'nombre', 'descripcion', 'activa'); //Definir campos que se van a editar y su orden

            $crud->change_field_type('activa', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));

            $crud->set_rules('nombre', 'Nombre del tipo de unidad', 'trim|required');
            $crud->set_rules('activa', 'Activo', 'required');

            $crud->unset_delete();
            $output = $crud->render();

            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            //pr($view['contenido']); exit();
            // $main_content = $this->load->view('admin/admin', $view, true);
            $main_content = $view['contenido'];
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function unidad_instituto($opcion = '', $id = null)
    {
        $this->load->model('Catalogo_model', 'catalogo');
        // $this->load->config('catalogos');
        $filtros = $this->config->item('catalogo.unidades_instituto');
        $output = [];
        $output['js'] = '/catalogo/unidad_instituto.js';
        $output['exportar'] = site_url('catalogo/unidad_instituto/exportar');
        $post = [];
        switch ($opcion) {
            case Catalogo::LISTA:
                $params = $this->input->get(null, true);
                $filtros['limit'] = isset($params['pageSize'])? $params['pageSize']:Catalogo::LIMIT;
                $filtros['offset'] = isset($params['pageIndex'])?  ($filtros['limit']*($params['pageIndex']-1)):0;
                $filtros['like'] = $this->prepara_filtros($this->input->get(null, true),
                    array(
                        'unidad'=>'unidades.nombre',
                        'delegacion' => 'delegacion.nombre',
                        'clave_presupuestal' => 'unidades.clave_presupuestal',
                        'clave_unidad' => 'unidades.clave_unidad',
                        'es_umae' => 'unidades.grupo_tipo_unidad',
                    )
                );
                $filtros['where'] = $this->prepara_filtros($this->input->get(null, true),
                    array(
                        'anio' => 'unidades.anio'
                    )
                );
                $registros['data'] = $this->catalogo->get_registros('catalogo.unidades_instituto unidades', $filtros);
                // $registros['query'] = $this->db->last_query();
                $filtros['total'] = true;
                $total = $this->catalogo->get_registros('catalogo.unidades_instituto unidades', $filtros)[0]['total'];
                $registros['length'] = $total;
                header('Content-Type: application/json; charset=utf-8;');
                echo json_encode($registros);
                break;
            case Catalogo::NUEVA:
                $this->nueva_unidad_instituto($post);
                break;
            case Catalogo::EDITAR:
                $post['id_unidad'] = $id;
                $this->editar_unidad_instituto($post);
                break;
            case Catalogo::EXPORTAR:
                $file_name = 'catalogo_categorias_' . date('Ymd_his', time());
                $filtros = $this->config->item('catalogo.unidades_instituto completo');
                $registros['data'] = $this->catalogo->get_registros('catalogo.unidades_instituto unidades', $filtros);
                $registros['columnas'] = array(
                    'id_unidad_instituto', 'nombre', 'clave_unidad', 'anio',
                    'nivel_atencion', 'id_tipo_unidad', 'umae', 'clave_presupuestal',
                    'longitud', 'latitud', 'id_region', 'grupo_tipo_unidad', 'activa',
                    'grupo_delegacion', 'direccion_fisica', 'entidad_federativa',
                    'unidad_principal', 'nombre_unidad_principal',
                    'clave_unidad_principal', 'tipo_unidad', 'id_delegacion',
                    'delegacion'
                );
                $this->exportar_xls($registros['columnas'], $registros['data'], null, null, $file_name);
                break;
            default:
                $view = $this->load->view('catalogo/grid_general.tpl.php', $output, true);
                $this->template->setMainContent($view);
                $this->template->getTemplate();
                break;
        }
    }

    private function nueva_unidad_instituto(&$params = [])
    {
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $output = [];
        if($this->input->post())
        {
            $post = $this->input->post(null, true);
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('catalogo_unidades_instituto'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations); //Añadir validaciones
            if($this->form_validation->run() == TRUE)
            {
                $post['umae'] = $post['umae'] == 1? true:false;
                $post['activa'] = $post['activa'] == 1? true:false;
                $post['longitud'] = is_numeric($post['longitud'])?$post['longitud']:null;
                $post['latitud'] = is_numeric($post['latitud'])?$post['latitud']:null;
                $output['status'] = $this->catalogo->insert_registro('catalogo.unidades_instituto', $post);
            }else
            {
                pr(validation_errors());
            }
        }
        $filtros = $this->config->item('catalogo.unidades_instituto');
        $tipos_unidades = $this->catalogo->get_registros('catalogo.unidades_instituto unidades', $filtros);
        $filtros = $this->config->item('catalogo.regiones');
        $regiones = $this->catalogo->get_registros('catalogo.regiones', $filtros);

        $output['post']['data'] = $params;
        $output['delegaciones'] = dropdown_options($this->catalogo->get_delegaciones(null, array('oficinas_centrales'=>true)), 'clave_delegacional', 'nombre');
        $output['tipos_unidades'] = dropdown_options($tipos_unidades, 'id_tipo_unidad', 'tipo_unidad');
        $output['regiones'] = dropdown_options($regiones, 'id_region', 'region');
        $view = $this->load->view('catalogo/formulario_unidad_instituto.tpl.php', $output, true);
        $this->template->setMainContent($view);
        $this->template->getTemplate();
    }

    private function editar_unidad_instituto(&$params = [])
    {
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $output = [];
        $filtros = $this->config->item('catalogo.unidades_instituto');
        $tipos_unidades = $this->catalogo->get_registros('catalogo.unidades_instituto unidades', $filtros);
        if($this->input->post())
        {
            $post = $this->input->post(null, true);
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('catalogo_unidades_instituto'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations); //Añadir validaciones
            if($this->form_validation->run() == TRUE)
            {
                $post['umae'] = $post['umae'] == 1? true:false;
                $post['activa'] = $post['activa'] == 1? true:false;
                $post['longitud'] = is_numeric($post['longitud'])?$post['longitud']:null;
                $post['latitud'] = is_numeric($post['latitud'])?$post['latitud']:null;
                $where_ids = array(
                    'id_unidad_instituto' => $params['id_unidad']
                );
                $output['status'] = $this->catalogo->update_registro('catalogo.unidades_instituto', $post, $where_ids);
            }else
            {
                pr(validation_errors());
            }
        }
        $filtros = $this->config->item('catalogo.regiones');
        $regiones = $this->catalogo->get_registros('catalogo.regiones', $filtros);
        $filtros = $this->config->item('catalogo.unidades_instituto completo');
        $filtros['where'] = array(
            'id_unidad_instituto' => $params['id_unidad']
        );
        $unidad = $this->catalogo->get_registros('catalogo.unidades_instituto unidades', $filtros);
        if(empty($unidad))
        {
            redirect('catalogo/unidad_instituto');
        }
        $output['post'] = $unidad[0];
        // pr($output['post']);
        $output['delegaciones'] = dropdown_options($this->catalogo->get_delegaciones(null, array('oficinas_centrales'=>true)), 'clave_delegacional', 'nombre');
        $output['tipos_unidades'] = dropdown_options($tipos_unidades, 'id_tipo_unidad', 'tipo_unidad');
        $output['regiones'] = dropdown_options($regiones, 'id_region', 'region');
        $view = $this->load->view('catalogo/formulario_unidad_instituto.tpl.php', $output, true);
        $this->template->setMainContent($view);
        $this->template->getTemplate();
    }

    public function regla_dependencia($opcion = '')
    {
        $filtros = $this->config->item('catalogo.reglas_dependencia_catalogos');
        $c_query = $this->config->item('catalogo.catalogo');
        $output = [];
        $output['js'] = '/catalogo/regla_dependencia.js';
        $output['exportar'] = site_url('catalogo/regla_dependencia/exportar');
        switch ($opcion) {
          case Catalogo::LISTA:
              $params = $this->input->get(null, true);
              $filtros['limit'] = isset($params['pageSize'])? $params['pageSize']:Catalogo::LIMIT;
              $filtros['offset'] = isset($params['pageIndex'])?  ($filtros['limit']*($params['pageIndex']-1)):0;
              /*
              $filtros['where'] = $this->prepara_filtros($params, array('id_grupo_categoria'=>'categorias.id_grupo_categoria'));
              $filtros['like'] = $this->prepara_filtros($params, array(
                  'clave_categoria' => 'clave_categoria',
                  'categoria' => 'categorias.nombre'
              ));
              */
              $registros['data'] = $this->catalogo->get_registros('catalogo.reglas_dependencia_catalogos reglas', $filtros);
              // $registros['query'] = $this->db->last_query();
              $filtros['total'] = true;
              $total = $this->catalogo->get_registros('catalogo.reglas_dependencia_catalogos', $filtros)[0]['total'];
              $registros['length'] = $total;
              header('Content-Type: application/json; charset=utf-8;');
              echo json_encode($registros);
              break;
          case Catalogo::NUEVA:
              $post = [];
              $post = $this->input->post(null, true);
              $this->nueva_regla_dependencia($post);
              break;
          case Catalogo::EDITAR:
              $post = [];
              $post = $this->input->post(null, true);
              $this->editar_regla_dependencia($post);
              break;
          case Catalogo::ELIMINAR:
              $post = $this->input->post(null, true);
              $this->eliminar_regla_dependencia($post);
              break;
          case Catalogo::EXPORTAR:
              $file_name = 'catalogo_reglas_dependencias_' . date('Ymd_his', time());
              $filtros = $this->config->item('catalogo.reglas_dependencia_catalogos');
              $registros['data'] = $this->catalogo->get_registros('catalogo.reglas_dependencia_catalogos reglas', $filtros);
              $registros['columnas'] = array(
                  'clave_regla_dependecia_catalogo', 'nombre',
                  'id_catalogo_hijo', 'id_catalogo_padre', 'descripcion'
              );
              $this->exportar_xls($registros['columnas'], $registros['data'], null, null, $file_name);
          default:
              $catalogos = $this->catalogo->get_registros('catalogo.catalogo', $c_query);
              array_unshift($catalogos,  array('id_catalogo' => '', 'nombre' => 'Seleccionar'));
              $output['var_js'] = array(
                  array(
                      "name" => 'json_categorias',
                      'value' => json_encode($catalogos)
                  )
              );
              $output['scripts_adicionales'] = array($this->load->view('tc_template/var_js_view.tpl.php', $output, true));
              $view = $this->load->view('catalogo/grid_general.tpl.php', $output, true);
              $this->template->setMainContent($view);
              $this->template->getTemplate();
              break;
        }
    }

    private function nueva_regla_dependencia($params)
    {
        $result = [];
        if($this->input->post())
        {
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('catalogo_reglas_dependencia'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations); //Añadir validaciones
            if ($this->form_validation->run() == TRUE)
            {
                $form = $this->input->post(null, true);
                $result = $this->catalogo->insert_registro('catalogo.reglas_dependencia_catalogos', $form, false);
            }else
            {
                $result['success'] = false;
                $result['message'] = validation_errors();
            }
            $result['data'] = $params;
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($result);
        }
    }

    private function editar_regla_dependencia($params)
    {
        if($this->input->post())
        {
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('catalogo_reglas_dependencia'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations); //Añadir validaciones
            if ($this->form_validation->run() == TRUE)
            {
                $form = $this->input->post(null, true);
                unset($form['clave_regla_dependecia_catalogo']);
                $ids = array(
                    'clave_regla_dependecia_catalogo' => $params['clave_regla_dependecia_catalogo']
                );
                $result = $this->catalogo->update_registro('catalogo.reglas_dependencia_catalogos', $form, $ids);
            }else
            {
                $result['success'] = false;
                $result['message'] = validation_errors();
            }
            $result['data'] = $params;
            $result['data']['clave_regla_dependecia_catalogo'] = $ids['clave_regla_dependecia_catalogo'];
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($result);
        }
    }

    private function eliminar_regla_dependencia(&$params)
    {
        if($this->input->post())
        {
           $where_ids = array('clave_regla_dependecia_catalogo' => $params['clave_regla_dependecia_catalogo']);
           $result = $this->catalogo->delete_registros('catalogo.reglas_dependencia_catalogos', $where_ids);
           header('Content-Type: application/json; charset=utf-8;');
           echo json_encode($result);
        }
    }

    public function detalle_reglas_dependencia($regla = '0', $opcion = '')
    {
        $this->load->model('Catalogo_model', 'catalogo');
        // $this->load->config('catalogos');
        $filtros = $this->config->item('catalogo.reglas_dependencia_catalogos');
        $filtros['where'] = array('clave_regla_dependecia_catalogo'=>$regla);
        $regla_dep = $this->catalogo->get_registros('catalogo.reglas_dependencia_catalogos reglas', $filtros);
        if(empty($regla_dep))
        {
            redirect(site_url('catalogo/regla_dependecia'));
        }else
        {
            $id_catalogo_padre = $regla_dep[0]['id_catalogo_padre'];
            $id_catalogo_hijo = $regla_dep[0]['id_catalogo_hijo'];
        }
        $filtros = $this->config->item('catalogo.detalle_dependencias_catalogos');
        $filtros['where'] = array('clave_regla_dependecia_catalogo' => $regla);
        $c_query = $this->config->item('catalogo.elementos_catalogos');
        $output = [];
        $output['js'] = '/catalogo/detalle_dependencias_catalogos.js';
        $output['exportar'] = site_url('catalogo/detalle_reglas_dependencia/exportar');
        switch ($opcion) {
            case Catalogo::LISTA:
                $params = $this->input->get(null, true);
                $filtros['limit'] = isset($params['pageSize'])? $params['pageSize']:Catalogo::LIMIT;
                $filtros['offset'] = isset($params['pageIndex'])?  ($filtros['limit']*($params['pageIndex']-1)):0;
                /*
                $filtros['where'] = $this->prepara_filtros($params, array('id_grupo_categoria'=>'categorias.id_grupo_categoria'));
                $filtros['like'] = $this->prepara_filtros($params, array(
                    'clave_categoria' => 'clave_categoria',
                    'categoria' => 'categorias.nombre'
                ));
                */

                $registros['data'] = $this->catalogo->get_registros('catalogo.detalle_dependencias_catalogos', $filtros);
                // $registros['query'] = $this->db->last_query();
                $filtros['total'] = true;
                $total = $this->catalogo->get_registros('catalogo.detalle_dependencias_catalogos', $filtros)[0]['total'];
                $registros['length'] = $total;
                header('Content-Type: application/json; charset=utf-8;');
                echo json_encode($registros);
                break;
            case Catalogo::NUEVA:
                $post = [];
                $post = $this->input->post(null, true);
                $this->nueva_detalle_reglas_dependencia($post);
                break;
            case Catalogo::EDITAR:
                $post = [];
                $post = $this->input->post(null, true);
                $this->editar_detalle_reglas_dependencia($post);
                break;
            case Catalogo::ELIMINAR:
                $post = $this->input->post(null, true);
                $this->eliminar_detalle_reglas_dependencia($post);
                break;
            case Catalogo::EXPORTAR:
                $file_name = 'detalle_reglas_dependencia' . date('Ymd_his', time());
                $filtros = $this->config->item('catalogo.detalle_reglas_dependencia');
                $registros['data'] = $this->catalogo->get_registros('catalogo.detalle_reglas_dependencia', $filtros);
                $registros['columnas'] = array(
                    'id_categoria','categoria', 'id_grupo_categoria',
                    'grupo_categoria','clave_categoria',
                );
                $this->exportar_xls($registros['columnas'], $registros['data'], null, null, $file_name);
            default:
                $c_query['where'] = array('id_catalogo'=>$id_catalogo_padre);
                $elementos_padres = $this->catalogo->get_registros('catalogo.elementos_catalogos', $c_query);
                array_unshift($elementos_padres,  array('id_elemento_catalogo' => '', 'label' => 'Seleccionar'));
                $c_query['where'] = array('id_catalogo'=>$id_catalogo_hijo);
                $elementos_hijos = $this->catalogo->get_registros('catalogo.elementos_catalogos', $c_query);
                array_unshift($elementos_hijos,  array('id_elemento_catalogo' => '', 'label' => 'Seleccionar'));
                $output['var_js'] = array(
                    array(
                        "name" => 'json_elementos_catalogo_padre',
                        'value' => json_encode($elementos_padres)
                    ),
                    array(
                        "name" => 'json_elementos_catalogo_hijo',
                        'value' => json_encode($elementos_hijos)
                    ),
                    array(
                        "name" => 'clave_regla',
                        'value' => '"'.$regla.'"'
                    )
                );
                $output['title'] = "Gestión de relación de la regla {$regla}";
                $output['scripts_adicionales'] = array($this->load->view('tc_template/var_js_view.tpl.php', $output, true));
                $view = $this->load->view('catalogo/grid_general.tpl.php', $output, true);
                $this->template->setMainContent($view);
                $this->template->getTemplate();
                break;
        }
    }

    private function nueva_detalle_reglas_dependencia($params){
        $result = [];
        if($this->input->post())
        {
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('catalogo_detalle_dependencias_catalogos'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations); //Añadir validaciones
            if ($this->form_validation->run() == TRUE)
            {
                $form = $this->input->post(null, true);
                $result = $this->catalogo->insert_registro('catalogo.detalle_dependencias_catalogos', $form, false);
            }else
            {
                $result['success'] = false;
                $result['message'] = validation_errors();
            }
            $result['data'] = $params;
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($result);
        }
    }

    private function editar_detalle_reglas_dependencia($params)
    {
        if($this->input->post())
        {
            $this->config->load('form_validation'); //Cargar archivo con validaciones

            $form = $this->input->post('item', true);
            unset($form['clave_regla_dependecia_catalogo']);
            $ids = $this->input->post('anterior', true);
            $result = $this->catalogo->update_registro('catalogo.detalle_dependencias_catalogos', $form, $ids);

            $result['data'] = $form;
            $result['data']['clave_regla_dependecia_catalogo'] = $ids['clave_regla_dependecia_catalogo'];
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($result);
        }
    }

    private function eliminar_detalle_reglas_dependencia(&$params)
    {
        if($this->input->post())
        {
            $where_ids = $params;
            $result = $this->catalogo->delete_registros('catalogo.detalle_dependencias_catalogos', $where_ids);
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($result);
        }
    }

    /**
     * Función que muestra la vista para cargar csv
     * @author Cheko
     * @param $output La salida a la vista de carga
     *
     */
    private function ver_carga($output)
    {
        if(isset($output['mensaje']))
        {

        }
        else
        {
            $output['mensaje'] = "";
        }
        $view = $this->load->view('catalogo/cargar_csv.php', $output, true);
        $this->template->setMainContent($view);
        $this->template->getTemplate();
    }


    /**
     * Funcion que carga los catalogos y verifica si tiene id
     * para mostrar un catalogo en particular
     * @author Cheko
     * @param type $id identificador del catalago
     * @param type $carga parametro para la peticion GET de carga
     */
    public function ver_catalogos($id=NULL, $carga=NULL)
    {
        $output = [];
        $output['herramientas'] = true;
        $output['form'] = 'formCatalogo';
        $view=null;
        if($id != NULL && $id != "")
        {
            $filtros['where'] = $this->obtener_id_modulo('catalogo', $id);
            $output['catalogo'] = $this->catalogo->get_registros('catalogo.catalogo', $filtros)[0];
            $output['csv'] = true;
            if($output['catalogo']['tipo'] == 'elementos_catalogos'){
                $output['title'] = "Elementos del catalogo";
                $output['form'] = 'formElementosCatalogo';
                $output['js'] = '/catalogo/elementos_catalogos.js';

                $c_filtros = $this->config->item('catalogo.catalogo');
                $grupos_catalogos = $this->catalogo->get_registros('catalogo.catalogo', $c_filtros);
                //pr($grupos_catalogos);
                array_unshift($grupos_catalogos,  array('id_catalogo' => 'Seleccionar', 'nombre' => 'Seleccionar'));
                $output['var_js'] = array(
                    array(
                        "name" => 'todos_catalogos',
                        'value' => json_encode($grupos_catalogos)
                    )
                );
                $output['exportar'] = site_url('catalogo/restfull_modulos/elementos_catalogos/exportar/');
                $output['scripts_adicionales'] = array($this->load->view('tc_template/var_js_view.tpl.php', $output, true));
            }else{
                $output['title'] = "Elementos del catalogo de cursos";
                $output['form'] = 'formCursosCatalogo';
                $output['js'] = '/catalogo/elementos_catalogos_cursos.js';
                $output['exportar'] = site_url('catalogo/restfull_modulos/cursos/exportar/');
            }
            if($carga != NULL && $carga == 'carga')
            {
                $this->ver_carga($output);
            }
            else
            {
                $view = $this->load->view('catalogo/grid_general.tpl.php', $output, true);
            }


        }
        else
        {
            $output['exportar'] = site_url('catalogo/restfull_modulos/catalogo/exportar/');
            $output['csv'] = false;
            $output['title'] = "Lista de catalogos";
            $output['js'] = '/catalogo/catalogos.js';
            $view = $this->load->view('catalogo/grid_general.tpl.php', $output, true);
        }
        $this->template->setMainContent($view);
        $this->template->getTemplate();

    }

    /**
     * Función que lee los catalogos existentes mediante la
     * peticion que se realice (todos o por id).
     * @author Cheko
     * @param type $modulo modulo de catalogo (tabla de la base de datos)
     * @param type $peticion tipo de peticion para leer el catalogo
     * @param type $id identificador del ide especifico
     * @return $respuesta de la peticion
     *
     */
    private function leer_catalogos($modulo, $peticion, $id)
    {
        if($id != NULL)
        {
            $filtros = $this->config->item('catalogo.'.$modulo.' *');
            if($peticion)
            {
                $params = $peticion;
                if($id != NULL)
                {
                    $filtros['where'] = array('id_catalogo'=>$id);
                }

                $filtros['limit'] = isset($params['pageSize']) ? $params['pageSize']:Catalogo::LIMIT;
                $filtros['offset'] = isset($params['pageIndex']) ?  ($filtros['limit']*($params['pageIndex']-1)):0;
                $parametros = [];
                foreach ($params as $clave => $valor)
                {
                    if($valor != '' && $clave != 'pageSize' && $clave != 'pageIndex' && $clave != 'tipo')
                    {
                        if($clave == "id_catalogo" || $clave == "nivel" || $clave == 'anio')
                        {
                            $filtros['where'][$clave] = $valor;

                        }
                        else{
                            $parametros[$clave] = $valor;
                        }
                    }

                }
                if(count($parametros) > 0)
                {
                    $filtros['like'] = $parametros;
                }
                $registros['data'] = $this->catalogo->get_registros('catalogo.'.$modulo, $filtros);
                $filtros['total'] = true;
                $total = $this->catalogo->get_registros('catalogo.'.$modulo, $filtros)[0]['total'];
                $registros['length'] = $total;
                $this->agregar_cabeceras();
                $respuesta = $this->restfull_respuesta('ok', 'Obteniendo elementos de catalogos', $registros);
                return $respuesta;
            }
            else
            {
                $respuesta = $this->restfull_respuesta('error', 'Peticion incorrecta', []);
                return $respuesta;
            }
        }
        else
        {
            $filtros = $this->config->item('catalogo.catalogo *');
            if($peticion)
            {
                $params = $peticion;
                if($id != NULL)
                {
                    $filtros['where'] = array('id_catalogo'=>$id);
                }

                $filtros['limit'] = isset($params['pageSize'])? $params['pageSize']:Catalogo::LIMIT;
                $filtros['offset'] = isset($params['pageIndex'])?  ($filtros['limit']*($params['pageIndex']-1)):0;
                $parametros = [];
                foreach ($params as $clave => $valor)
                {
                    if($valor != '' && $clave != 'pageSize' && $clave != 'pageIndex')
                    {
                        $parametros[$clave] = $valor;
                    }
                }
                if(count($parametros) > 0)
                {
                    $filtros['like'] = $parametros;
                }
                $registros['data'] = $this->catalogo->get_registros('catalogo.catalogo', $filtros);
                $filtros['total'] = true;
                $total = $this->catalogo->get_registros('catalogo.catalogo', $filtros)[0]['total'];
                $registros['length'] = $total;
                $this->agregar_cabeceras();
                $respuesta = $this->restfull_respuesta('ok', 'Obteniendo catalogos', $registros);
                return $respuesta;
            }
            else
            {
                $respuesta = $this->restfull_respuesta('error', 'Peticion incorrecta', []);
                return $respuesta;
            }
        }
    }

    /**
     * Función que crea un nuevo registro con un modulo mediante la
     * peticion que se realice.
     * @author Cheko
     * @param type $modulo Modulo para crear el registro
     * @param type $peticion datos de la peticion
     * @return $respuesta de la peticion
     *
     */
    private function crear_registro($modulo, $peticion){
      if($peticion)
      {
          $datos_nuevos = $peticion;
          //pr($datos_nuevos);
          $registros['data'] = $this->catalogo->insert_registro('catalogo.'.$modulo, $datos_nuevos);
          if($registros['data']['success'])
          {
              $respuesta = $this->restfull_respuesta('ok', $registros['data']['message'], $registros);
              $this->agregar_cabeceras();
              return $respuesta;
          }
          else
          {
              $respuesta = $this->restfull_respuesta('error', $registros['data']['message'], $registros);
              $this->agregar_cabeceras();
              return $respuesta;
          }
      }
    }

    /**
     * Función que actualiza un catalogo mediante la
     * peticion que se realice.
     * @author Cheko
     * @param type $modulo Modulo para actualizar el registro
     * @param type $peticion datos de la peticion
     * @param type $id identificador del registro
     * @return $respuesta de la peticion
     *
     */
    private function actualizar_registro($modulo, $peticion, $id)
    {
        if($id != NULL)
        {
            if($peticion)
            {
                $datos_actualizar = $peticion;
                $registros['data'] = $this->catalogo->update_registro('catalogo.'.$modulo,$datos_actualizar,$this->obtener_id_modulo($modulo,$id));
                if($registros['data']['success'])
                {
                    $respuesta = $this->restfull_respuesta('ok', $registros['data']['message'], $registros);
                    $this->agregar_cabeceras();
                    return $respuesta;
                }
                else
                {
                    $respuesta = $this->restfull_respuesta('error', $registros['data']['message'], $registros);
                    $this->agregar_cabeceras();
                    return $respuesta;
                }
            }
            else
            {
                $respuesta = $this->restfull_respuesta('error', 'Petición no permitida', []);
                $this->agregar_cabeceras();
                return $respuesta;
            }
        }
        else
        {
            $respuesta = $this->restfull_respuesta('error', 'Para editar se necesita el id del modulo', []);
            $this->agregar_cabeceras();
            return $respuesta;
        }
    }

    /**
     * Función que elimina un registro de un modulo mediante la
     * peticion que se realice.
     * @param type $modulo Modulo para eliminar el registro
     * @param type $peticion datos de la peticion
     * @param type $id identificador del registro
     *
     */
    private function eliminar_registro($modulo, $peticion, $id)
    {
        if($id != NULL)
        {
            if($peticion)
            {
                //$datos_actualizar['editable'] = false;
                $registros['data'] = $this->catalogo->update_registro('catalogo.'.$modulo,$datos_actualizar,$this->obtener_id_modulo($modulo,$id));
                if($registros['data']['success'])
                {
                    $respuesta = $this->restfull_respuesta('ok', $registros['data']['message'], $registros);
                    $this->agregar_cabeceras();
                    echo $respuesta;
                }
                else
                {
                    $respuesta = $this->restfull_respuesta('error', $registros['data']['message'], $registros);
                    $this->agregar_cabeceras();
                    echo $respuesta;
                }
            }
            else
            {
                $respuesta = $this->restfull_respuesta('error', 'Petición no permitida', []);
                $this->agregar_cabeceras();
                echo $respuesta;
            }
        }
        else
        {
            $respuesta = $this->restfull_respuesta('error', 'Para eliminar se necesita el id del modulo', []);
            $this->agregar_cabeceras();
            echo $respuesta;
        }
    }

    /**
     * Función que te dice que id es el que tiene el modulo(catalogos, curso o elemento_curso)
     * @author Cheko
     * @param type $modulo Que modulo es
     * @param type $id identificador del modulo
     *
     */
     private function obtener_id_modulo($modulo, $id)
     {
         switch ($modulo) {
             case 'catalogo':
                 return array('id_catalogo'=>$id);
                 break;
             case 'elementos_catalogos':
                 return array('id_elemento_catalogo'=>$id);
                 break;
             case 'curso':
                 return array('id_elemento_catalogo'=>$id);
                 break;
             default:
                 return array();
                 break;
         }
     }


    /**
     * Funcion que dependiendo de los metodos de la petición hace la accion de LEER, CREAR, ELIMINAR O EDITAR UN CATALOGO.
     * mini api
     * @author Cheko
     * @param type $modulo Modulo de la peticion
     * @param type $metodo Metodo de la peticion (GET, POST)
     * @param type $id identificador de la peticion /:id
     *
     */
    public function restfull_modulos($modulo=NULL, $metodo='', $id=NULL)
    {
        switch ($metodo) {
            case Catalogo::LEER: //leer los catalogos existente
                $peticion = $this->input->get(null, true);
                //pr($peticion);
                $respuesta = $this->leer_catalogos($modulo, $peticion, $id);
                echo $respuesta;
                break;
            case Catalogo::CREAR: //crear un catalogo
                $peticion = $this->input->post(null,true);
                $respuesta = $this->crear_registro($modulo, $peticion);
                echo $respuesta;
                break;
            case Catalogo::ACTUALIZAR: //actualizar un catalogo
                $peticion = $this->input->post(null,true);
                $respuesta = $this->actualizar_registro($modulo, $peticion, $id);
                echo $respuesta;
                break;
            case Catalogo::ELIMINAR: //eliminar un catalogo
                $peticion = $this->input->post(null,true);
                $respuesta = $this->eliminar_registro($modulo, $peticion, $id);
                echo $respuesta;
                break;
            case Catalogo::EXPORTAR: //exportar una tabla a csv
                $peticion = $this->input->get(null,true);
                $filtros = $this->config->item('catalogo.'.$modulo);
                $file_name = 'catalogo_'.$modulo.'_'.date('Ymd_his', time());
                $datos = $this->catalogo->get_registros('catalogo.'.$modulo, $filtros);
                $columnas = [];
                if($modulo == "catalogo")
                {
                    $columnas = array('id_catalogo','nombre','descripcion','tipo');
                }
                elseif($modulo == "elementos_catalogos")
                {
                    $columnas = array('id_elemento_catalogo','nombre','descripcion','id_catalogo','tipo');
                } elseif ($modulo == 'cursos')
                {
                    $columnas = array('id_elemento_catalogo','nombre','descripcion','id_catalogo','tipo',
                    'id_proceso_educativo','clave_ces','clave_unidad', 'anio','division','fecha_inicio','fecha_fin');
                }
                $this->exportar($file_name, $modulo, $columnas, $datos);
                break;
            case Catalogo::IMPORTAR:   //importa un archivo csv para hacer la carga
                $peticion = $this->input->post(null,true);
                $respuesta = $this->agregar_csv($peticion,$modulo, $id);
                $resp = json_decode($respuesta, true);
                //pr($resp);
                $output['mensaje'] = $resp['msj'];
                $output['catalogo'] = array('tipo' => $modulo, 'id_catalogo' => $id);
                $output['data'] = $resp;
                $this->ver_carga($output);
                break;
            default:
                $respuesta = $this->restfull_respuesta('error', 'Petición no permitida', []);
                echo $respuesta;
                break;
        }
    }

    /**
     * Funcion que agrega el csv
     * @author Cheko
     * @param type $peticion Tipo de peticion y datos de la peticion
     * @param type $modulo El modulo para saber de donde es el archivo (elementos catalogo o cursos)
     * @param type $id Identificador para saver de que modulo especifico es
     *
     */
    private function agregar_csv($peticion, $modulo, $id)
    {
        $config['upload_path']          = './uploads/'.$modulo.'/';
        $config['allowed_types']        = 'csv';
        //$config['max_size']             = 100;
        //$config['max_width']            = 1024;
        //$config['max_height']           = 768;
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('file'))
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
                $datos['id_usuario'] = 1;
                $datos['modelo'] = "Catalogo_model";
                $datos['fecha'] = '2018-02-20';
                $datos['nombre_archivo'] = $data['upload_data']['file_name'];
                $datos['peso'] = $data['upload_data']['file_size'];
                $datos['funcion'] = "insertar_".$modulo;
                $datos['modulo'] = $modulo;

                switch ($modulo) {
                    case 'elementos_catalogos':
                        $column_headers = ['nombre','descripcion','tipo','activo','label','orden','nivel','is_validado'];
                        $delimiter = ',';
                        $datos['carga'] = $this->csvimport->get_array($nombre_archivo, $column_headers, $detect_line_endings=FALSE, $initial_line=FALSE, $delimiter);
                        foreach ($datos['carga'] as $key => $value) {
                            $datos['carga'][$key]['id_catalogo'] = $id;
                        }
                        if(!$this->verificar_cabeceras($nombre_archivo, $column_headers))
                        {
                            return $this->restfull_respuesta('error', "No se pudo cargar el archivo, las columnas son incorrectas, verifique su archivo sobre el nombre de las columnas", []);
                            break;
                        }
                        $guardar = $this->guardar_csv_en_bd($datos);
                        break;
                    case 'cursos':
                        $column_headers = ['nombre','descripcion','tipo','activo','label','orden','nivel','is_validado','id_proceso_educativo','clave_ces','clave_unidad','anio','division','fecha_inicio','fecha_fin'];
                        $delimiter = ',';
                        $datos['carga'] = $this->csvimport->get_array($nombre_archivo, $column_headers, $detect_line_endings=FALSE, $initial_line=FALSE, $delimiter);
                        foreach ($datos['carga'] as $key => $value) {
                            $datos['carga'][$key]['id_catalogo'] = $id;
                        }
                        if(!$this->verificar_cabeceras($nombre_archivo, $column_headers))
                        {
                            return $this->restfull_respuesta('error', "No se pudo cargar el archivo, las columnas son incorrectas, verifique su archivo sobre el nombre de las columnas", []);
                            break;
                        }
                        $guardar = $this->guardar_csv_en_bd($datos);
                        break;
                    default:

                        break;
                }
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

        foreach ($cabeceras_archivo as $key => $value) {
            if (in_array(trim($value), $columnas))
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
     * Función para exportar en excel de una tabla
     * @author Cheko
     * @param type $nombre_archivo Nombre del archivo
     * @param type $modulo Nombre del modulo
     * @param Array $columnas Los encabezados del excel
     * @param Array $datos Los datos de las columnas(renglones)
     *
     */
    private function exportar($nombre_archivo=NULL, $modulo=NULL, $columnas=[], $datos=[])
    {
        if($nombre_archivo == NULL || $modulo == NULL || count($columnas) < 1 || count($datos) < 1)
        {
            return $this->restfull_respuesta('error', 'No se puede hacer la exportación a falta de datos', []);
        }
        else
        {
          //$file_name = 'catalogo_categorias_' . date('Ymd_his', time());

          $registros['data'] = $datos;
          $registros['columnas'] = $columnas;
        }

        return $this->exportar_xls($registros['columnas'], $registros['data'], null, null, $nombre_archivo);
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

    /**
    * Funcion para manejar las respuestas hacia el cliente (PENDIENTE)
    * @author Cheko
    * @param Array datos
    * Array(key,value): reglas
    * Array(key,value): mensajes
    */
    private function desinfectar_datos($datos, $reglas, $mensajes)
    {
        if(isset($reglas) && isset($mensajes))
        {
            foreach ($datos as $key => $value)
            {
                if(isset($reglas[$key]))
                {
                    if(is_string($reglas[$key]))
                    {
                        return true;
                    }
                    else
                    {
                        return true;
                    }
                }
            }
        }
    }

}
