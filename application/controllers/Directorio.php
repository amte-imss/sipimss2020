<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Description of Directorio
*
* @author chrigarc
*/
class Directorio extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template->setTitle('Directorio');
        $this->load->model('Directorio_model', 'directorio');
    }

    public function index()
    {
        $datos_sesion = $this->get_datos_sesion();
        if ($datos_sesion)
        {//Valida que exista sesión
            $id_usuario = $datos_sesion[En_datos_sesion::ID_USUARIO];
            //            pr($datos_sesion);
            //***** Valida tipo de usuario
            // $this->load->library('LNiveles_acceso');
            // $niveles = $this->modulo->get_niveles_acceso($id_usuario, 'usuario');
            $result['mostrar_filtros'] = FALSE;
            /* if ($this->lniveles_acceso->nivel_acceso_valido(array(LNiveles_acceso::Admin, LNiveles_acceso::NIVEL_CENTRAL), $niveles))
            {//Valida un nivel central o administrador
                //            pr($niveles);
                $result['mostrar_filtros'] = TRUE; //Mostrar filtros de unidad
            }*/

            //***** Fin de validación de tipo de usuario
            //Cargar información de directorios
            //        $this->load->model('Directorio_model', 'dir');
            //        $result['info_directorios'] = $this->dir->get_datos_directorio();
            //        pr($result);
            $this->template->setTitle('Directorio');
            $main_content = $this->load->view('directorio/directorio.tpl.php', $result, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        }
    }

    public function nombramiento()
    {
        try {
            $this->db->schema = 'directorio';

            $crud = $this->new_crud();
            $crud->set_table('nombramiento');
            $crud->set_subject('Nombramientos');
            $crud->display_as('clave_nombramiento', 'Clave');
            $crud->display_as('nombre', 'Nombre del nombramiento');
            $crud->display_as('descripcion', 'Descripción');

            $crud->columns('clave_nombramiento', 'nombre', 'descripcion'); //Definir columnas a mostrar en el listado y su orden
            $crud->set_rules('clave_nombramiento', 'Clave', 'trim|required');
            $crud->set_rules('nombre', 'Nombre del nombramiento', 'trim|required');

            $output = $crud->render();
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            $main_content = $view['contenido'];
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function niveles_acceso_nombramientos_permitidos()
    {
        try {
            $this->db->schema = 'directorio';

            $crud = $this->new_crud();
            $crud->set_table('roles_nombramientos');
            $crud->set_subject('Relacion de actividad de registro entre nivel de acceso y nombramiento');
            $crud->display_as('clave_rol', 'Rol');
            $crud->display_as('clave_nombramiento', 'Clave del nombramiento');

            $crud->set_primary_key('clave_rol', 'sistema.roles'); //Definir llaves primarias, asegurar correcta relación
            $crud->set_primary_key('clave_nombramiento', 'clave_nombramiento'); //Definir llaves primarias, asegurar correcta relación

            $crud->columns('clave_rol', 'clave_nombramiento'); //Definir columnas a mostrar en el listado y su orden

            $crud->set_relation('clave_rol', 'sistema.roles', 'nombre', null, 'nombre'); //Establecer relaciones
            $crud->set_relation('clave_nombramiento', 'nombramiento', 'clave_nombramiento', null, 'clave_nombramiento'); //Establecer relaciones

            $crud->set_rules('clave_nombramiento', 'Clave del nombramiento', 'trim|required');
            $crud->set_rules('clave_rol', 'Rol', 'trim|required');

            $output = $crud->render();
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            $main_content = $view['contenido'];
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function relacion_niveles_acceso_nombramientos()
    {
        try {
            $this->db->schema = 'directorio';

            $crud = $this->new_crud();
            $crud->set_table('roles_nombramientos_sistema');
            $crud->set_subject('Relación entre los niveles de acceso y nombramientos');
            $crud->display_as('clave_nombramiento', 'Nombramiento');
            $crud->display_as('clave_rol', 'Nivel de acceso');
            $crud->display_as('id_plantilla', 'Plantilla');

            $crud->set_primary_key('clave_rol', 'sistema.roles'); //Definir llaves primarias, asegurar correcta relación
            $crud->set_primary_key('clave_nombramiento', 'clave_nombramiento'); //Definir llaves primarias, asegurar correcta relación
            $crud->set_primary_key('id_plantilla', 'ui.plantillas'); //Definir llaves primarias, asegurar correcta relación

            $crud->columns('clave_nombramiento', 'clave_rol', 'id_plantilla'); //Definir columnas a mostrar en el listado y su orden

            $crud->set_relation('clave_rol', 'sistema.roles', 'nombre', null, 'nombre'); //Establecer relaciones
            $crud->set_relation('id_plantilla', 'ui.plantillas', 'nombre', null, 'nombre'); //Establecer relaciones
            $crud->set_relation('clave_nombramiento', 'nombramiento', 'clave_nombramiento', null, 'clave_nombramiento'); //Establecer relaciones

            $crud->set_rules('clave_nombramiento', 'Nombramiento', 'trim|required');
            $crud->set_rules('clave_rol', 'Nivel de acceso', 'trim|required');
            $crud->set_rules('id_plantilla', 'Plantilla', 'trim|required');

            $output = $crud->render();
            $view['contenido'] = $this->load->view('catalogo/gc_output', $output, true);
            $main_content = $view['contenido'];
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function get_registros_directorio($tipo_nivel = '', $delegacion = null)
  {
      $datos_sesion = $this->get_datos_sesion();
      if ($datos_sesion)
      {//Valida que exista sesión
          $id_usuario = $datos_sesion[En_datos_sesion::ID_USUARIO];
//            $clave_unidad = $this->get_datos_sesion(En_datos_sesion::CLAVE_UNIDAD);
//***** Valida tipo de usuario
          $this->load->library('LNiveles_acceso');

          $niveles = $this->modulo->get_niveles_acceso($id_usuario, 'usuario');

          $valida_nivel_acceso = $this->lniveles_acceso->nivel_acceso_valido(array(LNiveles_acceso::Admin, LNiveles_acceso::NIVEL_CENTRAL), $niveles);
//            pr($niveles);
//            pr($valida_nivel_acceso);
          if ($valida_nivel_acceso)
          {//Valida un nivel central o administrador
              switch ($tipo_nivel)
              {
                  case 1:
//                        $filtros['u.grupo_tipo_unidad!='] = 'UMAE'; //Para el caso de tipo de unidad qie es umae, para delegacional, trae todas las unidades de su delegación
                      $filtros["(u.grupo_tipo_unidad not in ('CUMAE','UMAE') or u.grupo_tipo_unidad is null )"] = null; //Para el caso de tipo de unidad qie es umae, para delegacional, trae todas las unidades de su delegación
                      break;
                  case 2:
//                        $filtros['u.grupo_tipo_unidad'] = 'UMAE'; //Para el caso de tipo de unidad qie es umae, para delegacional, trae todas las unidades de su delegación
                      $filtros["u.grupo_tipo_unidad in ('CUMAE','UMAE')"] = null; //Para el caso de tipo de unidad qie es umae, para delegacional, trae todas las unidades de su delegación
                      break;
                  default :
                      $filtros = null;
              }
          } else
          {//Nivel 2
//Valida tipo unidad en la sesión, si es un UMAE o un delegacionl
              if ($datos_sesion[En_datos_sesion::IS_UMAE])
              {//Valida si es una UMAE
                  $filtros["u.grupo_tipo_unidad in ('CUMAE','UMAE')"] = null; //Para el caso de tipo de unidad qie es umae, para delegacional, trae todas las unidades de su delegación
                  $filtros['u.clave_unidad'] = $datos_sesion[En_datos_sesion::CLAVE_UNIDAD]; //No debe existir una umae
              } else
              {//Is delegacional
                  $filtros['z.id_delegacion'] = $datos_sesion[En_datos_sesion::ID_DELEGACION]; //Para el caso de tipo de unidad qie es umae, para delegacional, trae todas las unidades de su delegación
                  $filtros["(u.grupo_tipo_unidad not in ('CUMAE','UMAE') or u.grupo_tipo_unidad is null )"] = null; //Para el caso de tipo de unidad qie es umae, para delegacional, trae todas las unidades de su delegación
              }
          }
//filtro de nombramiento por rol Inicio ****************************
          $caracter_separador = '';
          $concat = '';
          foreach ($niveles as $n)
          {
              $concat .= $caracter_separador . $n['id_rol'];
              $caracter_separador = ',';
          }
          $concat = 'rnm.id_rol in(' . $concat . ')';
          $filtros[$concat] = null; //No debe existir una umae
//Fin **************************************************************
//            pr($filtros);
//            exit();
//***** Fin de validación de tipo de usuario
          $select = ["d.id_directorio", "d.clave_nombramiento", "d.matricula",
              "d.nombre", "d.apellido_p", "d.apellido_m",
              "d.titulo", "d.telefonos", "d.observaciones", "u.clave_unidad",
              "u.nombre AS nombre_unidad", "z.clave_delegacional", "n.nombre nombre_nombramiento",
              "concat(d.nombre, ' ', d.apellido_p, ' ', d.apellido_m ) nombre_completo", "d.email",
          ];
          $result['data'] = $this->dir->get_datos_directorio($filtros, $select);
          $result['length'] = count($result['data']);
          header('Content-Type: application/json; charset=utf-8;');
          $json = json_encode($result);
          echo $json;
      }
  }

  public function get_busqueda_siap_($matricula, $delegacion)
  {
      echo json_encode($this->get_busqueda_siap($matricula, $delegacion, '*'));
  }

  public function editar()
  {
      $data_post = $this->input->post(NULL, TRUE);
      $this->load->library('Empleados_siap');
//        $datos_siap = $this->empleados_siap->buscar_usuario_siap($data_post['clave_delegacional'], $data_post['matricula']);
      $filtro_validacion_asiganado['d.matricula'] = $data_post['matricula']; //Filtro de registro de directorio
      $select = ["id_directorio", "matricula"];
      $data_validacion = $this->dir->get_datos_registro_directorio($filtro_validacion_asiganado, $select);
      if (empty($data_validacion) || (count($data_validacion) < 2 and $data_validacion[0]['id_directorio'] == $data_post['id_registro_directorio']))
      {//Valida que no este asignado el usuario a más de una unidad
          $anterior = $this->dir->get_datos_registro_directorio(array('id_directorio'=>$data_post['id_registro_directorio']), array('matricula'));
          if(count($anterior) > 0 && $anterior[0]['matricula'] != $data_post['matricula'])
          {
              $this->limpiar_registro($data_post['id_registro_directorio'], false);
          }
          $filtro['d.id_directorio'] = $data_post['id_registro_directorio']; //Filtro de registro de directorio
          $select = ["datos_siap", "matricula"];
          $data = $this->dir->get_datos_registro_directorio($filtro, $select);
          if (!empty($data) and trim($data_post['matricula']) == trim($data[0]['matricula']) and ! empty($data[0]['datos_siap']))
          {
              $datos_siap[En_tpmsg::SUCCESS] = 1;
              $datos_siap['empleado'] = (array) json_decode($data[0]['datos_siap']); //Datos de siap
          } else
          {
              $datos_siap = $this->get_busqueda_siap($data_post['matricula'], $data_post['clave_delegacional'], '*');
          }
          //valida
//        $datos_siap = $this->empleados_siap->buscar_usuario_siap($data_post['clave_delegacional'], $data_post['matricula']);
//Agrega datos de siap
//        if ($datos_siap['tp_msg'] == En_tpmsg::SUCCESS AND ! empty($datos_siap['empleado']))
          if ($datos_siap[En_tpmsg::SUCCESS] == 1 AND ! empty($datos_siap['empleado']))
          {//Valida que entregue información del empleado
              $datos_update = array(
                  'matricula' => $data_post['matricula'],
                  'nombre' => $data_post['nombre'],
                  'apellido_p' => $data_post['apellido_p'],
                  'apellido_m' => $data_post['apellido_m'],
                  'telefonos' => $data_post['telefonos'],
                  'titulo' => $data_post['titulo'],
                  'observaciones' => $data_post['observaciones'],
                  'email' => $data_post['email'],
              );
//        pr($datos_siap['empleado']);
              $datos_update['datos_siap'] = json_encode($datos_siap['empleado']);
              $result = $this->dir->update_directorio($data_post['id_registro_directorio'], $datos_update);
          } else
          {
              $result['success'] = 0;
          }
//Fin obtener clave delegacional
//Carga datos del registro

          if ($result['success'] == 1)
          {
              $select = ["d.id_directorio", "d.clave_nombramiento", "d.matricula",
                  "d.nombre", "d.apellido_p", "d.apellido_m",
                  "d.titulo", "d.telefonos", "d.observaciones", "u.clave_unidad",
                  "u.nombre AS nombre_unidad", "z.clave_delegacional", "n.nombre nombre_nombramiento",
                  "concat(d.nombre, ' ', d.apellido_p, ' ', d.apellido_m ) nombre_completo", "d.email"
              ];
              $data = $this->dir->get_datos_directorio($filtro, $select);
//            $result['tmp'] = $datos_update;
              $result['data'] = (!empty($data)) ? $data[0] : [];
              $result['message'] = 'La información de ' . $data[0]['nombre_nombramiento'] . ' en la unidad ' . $data[0]['nombre_unidad'] . ' se ha actualizado correctamente. ';
              /* Modificaciones para guardar los datos del renglon de directorio */
              if (!empty($data))
              {
                  $data = $data[0];
                  $datos_siap['clave_unidad'] = $data['clave_unidad'];
                  $datos_siap['clave_delegacional'] = $data['clave_delegacional'];
                  $result['sistemas_die'] = $this->registro_usuarios_die($data_post, $datos_siap);
              }
          } else
          {
              $result['message'] = 'No fue posible actualizar la información de ' . $data_post['nombre_nombramiento'] . ' en la unidad ' . $data_post['nombre_unidad'] . '. Por favor intentelo más tarde. ';
          }
      } else
      {
          $result['success'] = 0;
          $result['message'] = 'La matrícula  ' . $data_post['matricula'] . ' ya fue asignada a otra unidad. Por favor verifique la información.';
      }


      header('Content-Type: application/json; charset=utf-8;');
      $json = json_encode($result);
      echo $json;
  }

  private function registro_usuarios_die($data_post = [], $datos_siap = [])
  {
      $this->load->library('seguridad');
      $this->load->model('Ditto_model', 'ditto');
      // $this->load->model('CORES_model', 'cores');
      $ditto['usuario'] = $datos_siap['empleado'];
      $ditto['grupo'] = $data_post['clave_nombramiento'];
      $ditto['email'] = $data_post['email'];
      $ditto['clave_unidad'] = $datos_siap['clave_unidad'];
      $ditto['clave_delegacional'] = $datos_siap['clave_delegacional'];
      $ditto_status = $this->ditto->registro_usuario($ditto);
      $cores['usuario'] = $datos_siap['empleado'];
      $cores['grupo'] = $data_post['clave_nombramiento'];
      $cores['email'] = $data_post['email'];
      //      $cores_status = $this->cores->registro_usuario($cores); //queda fuera temporalmente
      $sistemas = array(
          'DITTO' => $ditto_status,
              //        'CORES' => $cores_status,
              //   'SIAP' => $datos_siap
      );
      return $sistemas;
  }

  public function exportar_datos($nivel = null)
  {
      $datos_sesion = $this->get_datos_sesion();
      if ($datos_sesion)
      {
          $filtros = array();
          $id_usuario = $datos_sesion[En_datos_sesion::ID_USUARIO];
          $this->load->library('LNiveles_acceso');

          $niveles = $this->modulo->get_niveles_acceso($id_usuario, 'usuario');
          $valida_nivel_acceso = $this->lniveles_acceso->nivel_acceso_valido(array(LNiveles_acceso::Admin,
              LNiveles_acceso::NIVEL_CENTRAL), $niveles);
          if ($valida_nivel_acceso)
          {//Valida un nivel central o administrador
//                switch ($tipo_nivel) {
//                    case 1:
//                        $filtros['u.grupo_tipo_unidad!='] = 'UMAE'; //Para el caso de tipo de unidad que es umae, para delegacional, trae todas las unidades de su delegación
//                        $filtros['u.umae'] = FALSE; //No debe existir una umae
//                        break;
//                    case 2:
//                        $filtros['u.grupo_tipo_unidad'] = 'UMAE'; //Para el caso de tipo de unidad que es umae, para delegacional, trae todas las unidades de su delegación
//                        $filtros['u.umae'] = TRUE; //No debe existir una umae
//                        break;
//                    default :
//                        $filtros = null;
//                }
              $filtros = null;
          } else
          {//Nivel 2
//Valida tipo unidad en la sesión, si es un UMAE o un delegacionl
              if ($datos_sesion[En_datos_sesion::IS_UMAE])
              {//Valida si es una UMAE
//                    $filtros['u.grupo_tipo_unidad'] = 'UMAE'; //Para el caso de tipo de unidad qie es umae, para delegacional, trae todas las unidades de su delegación
                  $filtros["u.grupo_tipo_unidad in ('CUMAE','UMAE')"] = null; //Para el caso de tipo de unidad qie es umae, para delegacional, trae todas las unidades de su delegación
                  $filtros['u.clave_unidad'] = $datos_sesion[En_datos_sesion::CLAVE_UNIDAD]; //No debe existir una umae
              } else
              {//Is delegacional
                  $filtros['z.id_delegacion'] = $datos_sesion[En_datos_sesion::ID_DELEGACION]; //Para el caso de tipo de unidad qie es umae, para delegacional, trae todas las unidades de su delegación
                  $filtros["(u.grupo_tipo_unidad not in ('CUMAE','UMAE') or u.grupo_tipo_unidad is null )"] = null; //Para el caso de tipo de unidad qie es umae, para delegacional, trae todas las unidades de su delegación
              }
          }
//filtro de nombramiento por rol Inicio ****************************
          $caracter_separador = '';
          $concat = '';
          foreach ($niveles as $n)
          {
              $concat .= $caracter_separador . $n['id_rol'];
              $caracter_separador = ',';
          }
          $concat = 'rnm.id_rol in(' . $concat . ')';
          $filtros[$concat] = null; //No debe existir una umae
//Fin **************************************************************

          $columnas = array('Clave de unidad', 'Unidad', 'Nombramiento', 'Matrícula', 'Nombre', 'Apellido paterno', 'Apellido materno', 'Correo electrónico', 'Teléfonos', 'Título', 'Comentarios');

          $select = array(
              "u.clave_unidad",
              "u.nombre AS nombre_unidad",
              'n.nombre nombre_nombramiento',
              "d.matricula",
              "d.nombre",
              "d.apellido_p",
              "d.apellido_m",
              "d.email",
              "d.telefonos",
              "d.titulo",
              "d.observaciones"
          );

          $resultado = $this->dir->get_datos_directorio($filtros, $select);
          $file_name = 'directorio_usuarios_' . date('Ymd_his', time());
          $this->exportar_xls($columnas, $resultado, ['umae'], null, $file_name);
      }
  }

  public function limpiar_registro($id = null, $imprime_json = true){
      $status['salida'] = 'Parametros invalidos';
      if(!is_null($id))
      {
          $status['salida'] = $this->dir->limpiar_registro($id);
      }
      if($imprime_json)
      {
          echo json_encode($status);
      }
  }

}
