<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @fecha	: 09052017
 * @author      : LEAS
 * */
class Validacion extends Informacion_docente {

    function __construct() {
        parent::__construct();
        $this->load->model('Normativo_model', 'normativo');
        $this->load->model('Docente_model', 'docente');
        $this->load->model("Catalogo_model", "cm");
    }

    public function index() {
    }

    public function detalle_censo_docente($id_docente = null, $tipo = 1){
         
        //Sesión de usuario obtiene la sesión de la cuenta
        $datos_sesion = $this->get_datos_sesion();
        if ($datos_sesion) {//Valida los datos de la sesión o la información de la sesíon
            if (is_null($id_docente)) {
                //$id_docente = $datos_sesion[En_datos_sesion::ID_DOCENTE];
                show_404();//                
            }
            $this->load->library('template_item_perfil');
            $rol_valida = $this->get_rol_aplica($datos_sesion, null);
            //pr($rol_valida);
            $this->template_item_perfil->set_rol_valida($rol_valida['roles_filtro']);
            $datos_generales = $this->dm->get_datos_generales($id_docente, null, $rol_valida);
            if (empty($datos_generales)) {
                show_404();//                                
            }
            //if(empty($rol_valida['roles_filtro'])){//si el rol no tiene acceso a la validacion de informacion del docente                
                //show_404();//                
            //}
            if($tipo==1){
                $this->template_item_perfil->set_tipoVistaDocente(Template_item_perfil::VIEW_VALIDACION);//Tipo de vista
            }else{
                $this->template_item_perfil->set_tipoVistaDocente(Template_item_perfil::VIEW_DOCENTE);//Tipo de vista

            }
            $datos_elemento_seccion = $this->get_detalle_registros_censo($id_docente);
            //Datos de la validacion de la seccion
            $this->load->model('ConvocatoriaV2_model', 'convocatoria');
            $datos_validacion_seccion = $this->convocatoria->get_validaciones_seccion($id_docente, $datos_sesion['convocatoria']['id_convocatoria']);
            $datos_get_ratificacion = $this->convocatoria->get_ratificacion($id_docente, $datos_sesion['convocatoria']['id_convocatoria']);
            

            //JS para renderizar formularios e información del docente principalmente
            //pr($datos_elemento_seccion);
            $this->template_item_perfil->set_registro_censo($datos_elemento_seccion); //Agrega registros
            $this->template_item_perfil->set_registro_validacion_seccion($datos_validacion_seccion); //Agrega registros
            $this->template_item_perfil->set_datos_ratificacion($datos_get_ratificacion); //Agrega registros
            $datos_files_js = $this->get_files_js_formularios_c($id_docente);
            $this->template_item_perfil->set_files_js_formularios($datos_files_js);
            
            $this->load->model("Docente_model", "dm");
            /* carga datos generales */
            
            //pr($datos_generales);
            
                //Status de la validacion del registros del censo del docente
                $this->template_item_perfil->set_status_validacion($datos_generales['id_status_validacion']);
                if($rol_valida['aplica_bandera_separarV1_v2']){
                    $this->template_item_perfil->set_permite_validacion($datos_generales['permite_validacion']);
                    $this->template_item_perfil->set_permite_ratificacion($datos_generales['permite_ratificacion']);
                }
                $this->load->library('curp', array('curp' => $datos_generales['curp'])); //Ingresa datos del curp
                $datos_generales['edad'] = $this->curp->getEdad(); //Calcula la edad del usuario
                 if(is_null($datos_generales['fecha_nacimiento'])){
                     $datos_generales['fecha_nacimiento'] = $this->curp->getFechaNac();                     
                    }
                    $this->template_item_perfil->set_datos_generales($datos_generales); //Información general del docente 
                
                /* Cargar imagen de perfil */
            $datos_imagen_docente = $this->dm->get_imagen_perfil($id_docente); //Obtener imagen del docente
            $this->template_item_perfil->set_datos_imagen($datos_imagen_docente);
            /* carga datos imss */
            $this->template_item_perfil->set_datos_imss(
                    $this->dm->get_historico_datos_generales($id_docente) +
                    array('matricula' => $datos_generales['matricula'])); //Información IMSS
            //Ejecutar datos de registro de perfil          
            $vista = $this->template_item_perfil->get_template_registro(
                $this->template_item_perfil->get_vistas_regisatros_censo_inicio(null, 
                '/perfil/inicio/item_ficha_actividad_impresion', 
                '/perfil/inicio/item_datos_generales_impresion',
                '/perfil/inicio/item_datos_imss_impresion', 
                '/perfil/inicio/item_carrusel_impresion', 
                '/perfil/inicio/tab_perfil_impresion'), 
                '/perfil/inicio/inicio_docente.tpl.php',                
                null , $id_docente               
                
            );
            //pr($vista);
            $this->template->setMainContent($vista);
            //$this->template->getTemplate(true, 'tc_template/impresion.tpl.php');
            $this->template->getTemplate();
        }

    }
    public function guarda_val_seccion(){
        
        if ($this->input->is_ajax_request()) {
            $respuesta = ['tp_msg'=>'success', 'mensaje'=>'La'];
            if ($this->input->post()) {
                $post = $this->input->post(null,true);
                //pr($post);
                $id_docente = decrypt_base64($post['docente']);
                $id_seccion = $post['seccion_validacion'];
                $datos_sesion = $this->get_datos_sesion();
                $rol_valida = $this->get_rol_aplica($datos_sesion, null);//Rol que aplica
                $this->load->library('template_item_perfil');
                $this->template_item_perfil->set_rol_valida($rol_valida['rol_aplica']);
                $this->template_item_perfil->set_tipoVistaDocente(Template_item_perfil::VIEW_VALIDACION);//Tipo de vista
                $datos_elemento_seccion = $this->get_detalle_registros_censo($id_docente, $id_seccion);
                $validaciones = ['mensaje'=>''];
                $this->valida_elementos_seccion($post, $datos_elemento_seccion, $validaciones, 1);
                if(strlen($validaciones['mensaje'])>0){
                    $respuesta['tp_msg'] = 'danger';
                    $respuesta['mensaje'] = $validaciones['mensaje'];
                }else{//Guardar registros
                    
                    $validaciones['data']['id_docente'] = $id_docente;
                    $validaciones['data']['id_validador'] = $datos_sesion['id_docente'];
                    $validaciones['data']['id_convocatoria'] = $datos_sesion['convocatoria']['id_convocatoria'];
                    $validaciones['data']['id_seccion'] = $id_seccion;
                    $validaciones['respuesta'] = $respuesta; 
                    
                    //pr($validaciones['data']);
                    $this->load->model('ConvocatoriaV2_model', 'convocatoria');
                    $this->convocatoria->upsert_validacion_secciones($validaciones);
                    $respuesta =  $validaciones['respuesta'];
                }
                //pr($datos_elemento_seccion);
            
            //pr($post);

                
            }else{
                $respuesta['tp_msg'] = 'warning';
                $respuesta['mensaje'] = 'Por favor, complete la validación de cada registro de la sección';

            } 
            
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($respuesta);
            exit();
        }
    }

    private function valida_elementos_seccion($post, $elementos_validar, &$validacion, $tipo_val = 1){
        switch($tipo_val){
            case 1://Valida la seccion que entra por pos con los elementos de censo de la seccion 
                foreach ($elementos_validar as $key => $value){
                    if (!isset($post[$value['id_censo']])){
                        $validacion['mensaje'] = 'Por favor, complete la validación de cada registro de la sección <b>' . $value['lbl_seccion'] . '</b>';
                        return 0;            
                    }else{
                        if($post[$value['id_censo']]=='no' && strlen(trim($post['comentario_seccion']))== 0){                    
                            $validacion['mensaje'] = 'El campo Comentario es requerido ';
                            return 0;
                        }else{
                            $validacion['elementos_censo'][$value['id_censo']] = $post[$value['id_censo']];   
                        }
                    }            
                }
                $validacion['data']['elementos_censo'] = json_encode($validacion['elementos_censo']);
                $validacion['data']['comentario'] = $post['comentario_seccion'];
            break;
            case 3://valida todos los registros de censo del docente con los validados
                $elementos_validar = $this->ordena_elementos_validacion_seccion($elementos_validar);
                //pr($post);
                $mensaje = [];
                foreach ($post as $key => $value){                
                    if (!isset($elementos_validar[$value['id_seccion']]['elementos_censo'][$value['id_censo']])){    
                        if(!isset($mensaje[$value['id_seccion']])){
                            $mensaje[$value['id_seccion']] = 'Por favor, complete la validación de cada registro de la sección <b>' . $value['lbl_seccion'] . '</b>';                                  
                        }
                    }else{
                        /*if($post[$value['id_censo']]=='no' && strlen($post['comentario_seccion'])== 0){                    
                            $validacion['mensaje'] = 'El campo Comentario es requerido ';
                            return 0;
                        }else{
                            $validacion['elementos_censo'][$value['id_censo']] = $post[$value['id_censo']];   
                        }*/
                    }            
                }
                $validacion['mensaje'] = implode('<br>', $mensaje);
                
            break;
            case 2:
                $mensaje = [];
                if(strlen($post['ratificacion'])==0){
                    $mensaje[] = "El campo Ratificación es obligatorio";
                }else if($post['ratificacion']==2){//No lo ratifica
                    if(strlen($post['comentario_ratificacion'])==0){
                        $mensaje[] = "El campo Comentario es obligatorio";
                    }                                    
                }
                $validacion['data']['comentario'] = $post['comentario_ratificacion'];
                $validacion['data']['ratificado'] = $post['ratificacion'];
                $validacion['mensaje'] = implode('<br>', $mensaje);
            break;
        }
        
        
    }

    private function ordena_elementos_validacion_seccion($elementos_validados){
        $registros = [];
        foreach($elementos_validados as $keys => $values){
            //pr($values_c);
            $values['elementos_censo'] = json_decode($values['elementos_censo'], true);
            $values_simple = [
                'comentario' => $values['comentario'],
                'elementos_censo' => $values['elementos_censo'],
                'id_seccion' => $values['id_seccion']
            ];                    
            $registros[$values['id_seccion']]['comentario']  = $values['comentario'];                                    
            $registros[$values['id_seccion']]['id_seccion']  = $values['id_seccion'];                                    
            foreach($values_simple['elementos_censo'] as $keysE => $valuesE){
                $registros[$values['id_seccion']]['elementos_censo'][$keysE]  = $valuesE;                                    
            }
                                               
        }
        return $registros;
    }

    
    public function terminar_validacion(){
        if ($this->input->is_ajax_request()) {
            $respuesta = ['tp_msg'=>'success', 'mensaje'=>''];
            if ($this->input->post()) {
                $post = $this->input->post(null,true);
                //pr($post);
                $id_docente = decrypt_base64($post['docente']);                
                $datos_sesion = $this->get_datos_sesion();
                $rol_valida = $this->get_rol_aplica($datos_sesion, null);//Rol que aplica
                $this->load->library('template_item_perfil');
                $this->template_item_perfil->set_rol_valida($rol_valida['rol_aplica']);
                $this->template_item_perfil->set_tipoVistaDocente(Template_item_perfil::VIEW_VALIDACION);//Tipo de vista
                $datos_elemento_seccion = $this->get_detalle_registros_censo($id_docente);
                $this->load->model('ConvocatoriaV2_model', 'convocatoria');
                $datos_validacion_seccion = $this->convocatoria->get_validaciones_seccion($id_docente, $datos_sesion['convocatoria']['id_convocatoria']);
                $validaciones = ['mensaje'=>''];
                $this->valida_elementos_seccion($datos_elemento_seccion, $datos_validacion_seccion, $validaciones, 3);
                if(strlen($validaciones['mensaje'])>0){
                    $respuesta['tp_msg'] = 'danger';
                    $respuesta['mensaje'] = $validaciones['mensaje'];
                }else{//Guardar registros
                    
                    $validaciones['data']['id_docente'] = $id_docente;
                    $validaciones['data']['id_validador'] = $datos_sesion['id_docente'];
                    $validaciones['data']['id_convocatoria'] = $datos_sesion['convocatoria']['id_convocatoria'];
                    $validaciones['respuesta'] = $respuesta; 
                    
                    //pr($validaciones['data']);
                    
                    $this->convocatoria->upsert_finaliza_validacion($validaciones);
                    $respuesta =  $validaciones['respuesta'];
                }
                //pr($datos_elemento_seccion);
            
            //pr($post);

                
            }else{
                $respuesta['tp_msg'] = 'warning';
                $respuesta['mensaje'] = 'Por favor, complete la validación de cada registro de la sección';

            } 
            
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($respuesta);
            exit();
        }
    }
    
    public function ratificar_validacion(){
        if ($this->input->is_ajax_request()) {
            $respuesta = ['tp_msg'=>'success', 'mensaje'=>''];
            if ($this->input->post()) {
                $post = $this->input->post(null,true);
                //pr($post);
                //exit();
                $id_docente = decrypt_base64($post['docente']);                
                $datos_sesion = $this->get_datos_sesion();
                $rol_valida = $this->get_rol_aplica($datos_sesion, null);//Rol que aplica
                $this->load->library('template_item_perfil');
                $this->template_item_perfil->set_rol_valida($rol_valida['rol_aplica']);
                $this->template_item_perfil->set_tipoVistaDocente(Template_item_perfil::VIEW_VALIDACION);//Tipo de vista
                $this->load->model('ConvocatoriaV2_model', 'convocatoria');                
                $validaciones = ['mensaje'=>''];
                $this->valida_elementos_seccion($post, null, $validaciones, 2);
                if(strlen($validaciones['mensaje'])>0){
                    $respuesta['tp_msg'] = 'danger';
                    $respuesta['mensaje'] = $validaciones['mensaje'];
                }else{//Guardar registros
                    
                    $validaciones['data']['id_docente'] = $id_docente;
                    $validaciones['data']['id_ratificador_validador'] = $datos_sesion['id_docente'];
                    $validaciones['data']['id_convocatoria'] = $datos_sesion['convocatoria']['id_convocatoria'];
                    $validaciones['respuesta'] = $respuesta; 
                    
                    //pr($validaciones['data']);
                    
                    $this->convocatoria->upsert_finaliza_ratificacion($validaciones);
                    $respuesta =  $validaciones['respuesta'];
                }
                //pr($datos_elemento_seccion);
            
            //pr($post);

                
            }else{
                $respuesta['tp_msg'] = 'warning';
                $respuesta['mensaje'] = 'Por favor, complete la validación de cada registro de la sección';

            } 
            
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($respuesta);
            exit();
        }
    }
    public function listado_docentes(){
        $datos_sesion = $this->get_datos_sesion();
        $rol_aplica = $this->get_rol_aplica($datos_sesion);
        $output['editar_reg_doc_nuevamente'] = $rol_aplica['editar_reg_doc_nuevamente'];    
        $output['bloquea_delegacion'] = $rol_aplica['bloquea_delegacion'];
        $output['btn_activar_registro_docentes_masivo'] = $rol_aplica['btn_activar_registro_docentes_masivo'];
        $output['catalogos']['result_delegacional'] = $this->normativo->get_delegacional();
        array_unshift($output['catalogos']['result_delegacional'], ['clave_delegacional'=>'',"nombre"=>'Selecciona OOAD']); 
        $output['catalogos']['fase_carrera_docente'] = $this->cm->get_fase_carrera_docente();
        array_unshift($output['catalogos']['fase_carrera_docente'], ['id_docente_carrera'=>'',"descripcion"=>'Selecciona...']); 
        $output['catalogos']['estados_validacion'] = $this->get_estados_validacion_censo_c();
        array_unshift($output['catalogos']['estados_validacion'], ['id'=>'',"label"=>'Selecciona...']); 
        $output['catalogos']['ratificado'] = array(["id"=>1, "label"=>"Sí"], ["id"=>2, "label"=>"No"]);
        array_unshift($output['catalogos']['ratificado'], ['id'=>'',"label"=>'Selecciona...']); 
        $datos_sesion = $this->get_datos_sesion();
        $output['permiso'] = 0;
        if(isset($datos_sesion['niveles_acceso_cves'][LNiveles_acceso::Super]) || isset($datos_sesion['niveles_acceso_cves'][LNiveles_acceso::Normativo])){
            $output['permiso'] = 1;
        }
        $this->template->setTitle('Censo de docentes');
        $main_content = $this->load->view('validador/body_lista_docentes.tpl.php', $output, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }

    public function listado_validadores(){
        $output['catalogos']['result_delegacional'] = $this->normativo->get_delegacional();
        array_unshift($output['catalogos']['result_delegacional'], ['clave_delegacional'=>'',"nombre"=>'Selecciona OOAD']); 
        $output['catalogos']['nivel_acceso'] = array(array('clave_rol' => LNiveles_acceso::Validador1, 'descripcion' => 'Validador 1'), array('clave_rol' => LNiveles_acceso::Validador2, 'descripcion' => 'Validador 2'));
        //pr($output);
        array_unshift($output['catalogos']['nivel_acceso'], ['clave_rol'=>'',"descripcion"=>'Selecciona...']); 
        $this->template->setTitle('Listado de validadores');
        $output['cambiar_validador1'] = LNiveles_acceso::Validador1;
        $main_content = $this->load->view('validador/body_lista_validadores.tpl.php', $output, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }
    
    public function docentes(){
        $datos_sesion = $this->get_datos_sesion();
        //pr($datos_sesion);
        $data_post = null;
        if($this->input->post()){
            $data_post = $this->input->post(null, true);                        
        }
        $rol_aplica = $this->get_rol_aplica($datos_sesion,$data_post);
        //pr($rol_aplica);
        
        $output['datos_docentes'] = $this->docente->get_historico_datos_generales(null, null, $rol_aplica);
        //pr($output['datos_docentes']);
        header('Content-Type: application/json; charset=utf-8;');
        echo json_encode($output);
        
    }
    
  

    public function validadores(){
        $datos_sesion = $this->get_datos_sesion();
        //pr($datos_sesion);
        $data_post = null;
        if($this->input->post()){
            $data_post = $this->input->post(null, true);
        }
        $rol_aplica = $this->get_rol_aplica($datos_sesion,$data_post);
        $rol_aplica['rol_docente'] = array(LNiveles_acceso::Validador1, LNiveles_acceso::Validador2);
        //pr($rol_aplica);
        
        $output['datos_docentes'] = $this->docente->get_historico_datos_generales(null, null, $rol_aplica);
        //pr($output['datos_docentes']);
        header('Content-Type: application/json; charset=utf-8;');
        echo json_encode($output);
        
    }

    
    private function get_rol_aplica($datos_sesion, $data_post = null){
        $claves_rol = $this->get_roles_usuario(2);
        $conf=['rol_aplica'=>null, 'filtros'=>null, 'rol_docente'=>LNiveles_acceso::Docente, 'bloquea_delegacion' => 0, 
        'is_entidad_designada' => false,'aplica_bandera_separarV1_v2' => 0, 'editar_reg_doc_nuevamente' => 0 , 'btn_activar_registro_docentes_masivo' => 0,
        'cambiar_validadorN1'=>0
        ];
        $conf['rol_docente']=LNiveles_acceso::Docente;
        $conf['convocatoria'] = $datos_sesion['convocatoria']['id_convocatoria'];
        if(isset($claves_rol[LNiveles_acceso::Normativo])){
            $conf['rol_aplica'] = LNiveles_acceso::Normativo;
            $conf['roles_filtro'] = [LNiveles_acceso::Normativo];
            $conf['editar_reg_doc_nuevamente'] = 1;
            $conf['btn_activar_registro_docentes_masivo'] = 1;
            if(!is_null($data_post) && !empty($data_post['clave_delegacional'])){
                $conf['filtros']['where']['d.clave_delegacional'] = $data_post['clave_delegacional'];
                $conf['filtros']['where']['d.clave_delegacional'] = $data_post['clave_delegacional'];
            }
        }else if(isset($claves_rol[LNiveles_acceso::Validador2])){
            $conf['rol_aplica'] = LNiveles_acceso::Validador2;      
            $conf['roles_filtro'] = [LNiveles_acceso::Validador2];
            $conf['bloquea_delegacion'] = 1;      
            //Entidad designada
            $this->load->model('Catalogo_model', 'catalogo');
            $output =[];
            $output['ooad_select'] = $this->catalogo->get_ooad_select($datos_sesion[En_datos_sesion::ID_USUARIO], 3);
            $output['umae_select'] = $this->catalogo->get_umae_select($datos_sesion[En_datos_sesion::ID_USUARIO], 3);            
            $entidad_designada = false;
            $conf['ooad_usuario'] = $datos_sesion[En_datos_sesion::ID_USUARIO];
            $conf['is_entidad_designada'] = true;
            if(isset($output['ooad_select']) && !empty($output['ooad_select'])){
                $entidad_designada = true;
                $stringIds = implode(',',$output['ooad_select']);
                $conf['ooad'] = $stringIds;
                //pr($conf['ooad']);
                //$conf['filtros']['where_in']['d.clave_delegacional'] = $conf['ooad']; 
            }else{
                $conf['ooad'] = null;
            }                                
            $conf['umae_usuario'] = $datos_sesion[En_datos_sesion::ID_USUARIO];
            if(isset($output['umae_select']) && !empty($output['umae_select'])){                
                $entidad_designada = true;
                $stringIds = implode(',',$output['umae_select']);
                $conf['umae'] = $stringIds;//$output['umae_select'];
                //pr($conf['umae']);
                //$conf['filtros']['where_in']['u.clave_unidad'] = $stringIds; 
                //$conf['is_entidad_designada'] = true;
            }else{
                $conf['umae'] = null;

            }
            //pr($claves_rol);
            if(isset($claves_rol[LNiveles_acceso::Validador1])){
                $conf['roles_filtro'] = [LNiveles_acceso::Validador2, LNiveles_acceso::Validador1];
                
                    $conf['aplica_bandera_separarV1_v2'] = 1;
                    $conf['editar_reg_doc_nuevamente'] = 1;
                    
                    
                    
                $ids_usuario_registrados = $this->get_usuarios_registro_validador($datos_sesion[En_datos_sesion::ID_USUARIO]);
                $stringIds = implode(',',$ids_usuario_registrados);
                $conf['cambiar_validadorN1'] = 1;
                if(!empty($ids_usuario_registrados)){
                    $conf['user_validadorn1'] = $stringIds;                
                }else{
                    $conf['user_validadorn1'] = null;
                }
            }                                
            
            
        }else if(isset($claves_rol[LNiveles_acceso::Validador1])){
            $conf['editar_reg_doc_nuevamente'] = 1;
            $conf['rol_aplica'] = LNiveles_acceso::Validador1;
            $conf['roles_filtro'] = [LNiveles_acceso::Validador1];
            $conf['bloquea_delegacion'] = 1;      
            //$where['d.clave_delegacional'] =  
            $auxFiltroValidador = '(SELECT "count"(*) from sistema.control_registro_usuarios x where x.id_usuario_registra = '.$datos_sesion[En_datos_sesion::ID_USUARIO] .' and active )>0';
            $conf['filtros']['where'][$auxFiltroValidador] = null;                
            $ids_usuario_registrados = $this->get_usuarios_registro_validador($datos_sesion[En_datos_sesion::ID_USUARIO]);
            if(!empty($ids_usuario_registrados)){
                $stringIds = implode(',',$ids_usuario_registrados);
                //$auxFiltro = '(u.clave_unidad=\'' . $datos_sesion['clave_unidad'].'\' or doc.id_usuario IN(' . $stringIds . '))';
                $auxFiltro = 'doc.id_usuario IN(' . $stringIds . ')';
                $conf['filtros']['where'][$auxFiltro] = null;                
            //}else{                
                //$conf['filtros']['where']['u.clave_unidad'] = $datos_sesion['clave_unidad']; 
            }
        }
        return $conf;
    }

    private function get_usuarios_registro_validador($id_user_sesion){
        $this->load->model('Catalogo_model', 'cm');        
        $params['select'] = array('id_usuario_registrado');
        $params['where'] = ['cru.id_usuario_registra'=>$id_user_sesion, 'cru.active'=>true];
        $result = $this->cm->get_registros("sistema.control_registro_usuarios cru", $params);
        $idsUserResult = [];
        if(!empty($result)){
            foreach($result as $val){
                $idsUserResult[] = $val['id_usuario_registrado'];
            }
        }
        return $idsUserResult;
    }

    public function valida_censo(){

    }

    public function registro_docente(){
        $output["texts"] = $this->lang->line('formulario'); //textos del formulario
        //pr($this->input->post());
        if($this->input->post()){
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('form_registro_usuario'); //Obtener validaciones de archivo general
            unset($validations[array_key_last($validations)]);
            $this->form_validation->set_rules($validations); //Añadir validaciones
            //pr($validations);
            
            if ($this->form_validation->run() == TRUE)
            {
                $this->load->model('Administracion_model', 'administracion');
                $is_user = $this->get_datos_sesion(En_datos_sesion::ID_USUARIO);
                $data = array(
                    'matricula' => $this->input->post('reg_usuario', TRUE),
                    'delegacion' => $this->input->post('id_delegacion', TRUE),
                    'username_alias' => (!is_null($this->input->post('username_alias', TRUE)) && !empty($this->input->post('username_alias', TRUE)))?$this->input->post('username_alias', TRUE):null,
                    'email' => $this->input->post('reg_email', true),
                    'password' => $this->input->post('reg_password', TRUE),
                    'grupo' => Administracion_model::DOCENTE,
                    'registro_usuario' => true,
                    'id_usuario_sesion' => $is_user

                );               
                $this->load->library('empleados_siap');
                $this->load->library('seguridad');
                $this->load->model('Usuario_model', 'usuario');
                //pr($data); exit();
                $output['registro_valido'] = $this->usuario->nuevo($data, Usuario_model::SIAP);
                //pr($output);
                if(isset($output['registro_valido']['envia_correo']) && $output['registro_valido']['envia_correo']){
                $data2 = array(
                    'id_usuario_registra' => $is_user,
                    'id_usuario_registrado' => $output['registro_valido']['id_usuario'],
                );
                //pr($data);
                    $this->usuario->save_control_registro_usuarios($data2);                    
                    $this->envia_correo_electronico($data['email'], $data);
                }
                //pr($data);
            }else{
                // pr(validation_errors());;
            }
        }
        $this->load->model('Catalogo_model', 'catalogo');
        $output['delegaciones'] = dropdown_options($this->catalogo->get_delegaciones(null, array('oficinas_centrales' => true)), 'clave_delegacional', 'nombre');
        $main_content = $this->load->view("docente/registro/registro_docente.tpl.php", $output, true);
       
        
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }


    /**
     *  
      *            $datos =  'email'                     'matricula'
      *              'nombre' ,                    'apellido_p',
     *               'apellido_m' ,                    'curp' ,
     *               'sexo' ,                    'rfc',
     *               'status_siap'
            
     */
    private function envia_correo_electronico($email, $datos){
        $this->enviar_correo($email, $datos, 'Censo de profesores', '/docente/email/email_docente_censo.php');
        //$this->load->model('Plantilla_model', 'plantilla');
        //$this->plantilla->send_mail(Plantilla_model::BIENVENIDA_REGISTRO, $parametros);
        //$this->plantilla->send_mail(Plantilla_model::BIENVENIDA_REGISTRO);
    }


    public function registro($correo = null){
        if(is_null($correo)){
            $this->envia_correo_electronico('cenitluis.pumas@gmail.com', ['nombre'=>'Jesús Díaz', 'password'=>'AUSL880811BC6_NOW']);
        }else{
            $this->envia_correo_electronico($correo, ['nombre'=>'Jesús Díaz', 'password'=>'AUSL880811BC6_NOW']);
        }
    }

    public function habilita_edicion(){
        if ($this->input->is_ajax_request()) {
            if($this->input->post()){
               
               $post =   $this->input->post(null, true);
               $datos_sesion = $this->get_datos_sesion();
                              $this->load->model('ConvocatoriaV2_model', 'convocatoria');
                $output = array(
                    'id_docente' => $post['docente'],
                    'id_convocatoria' => $datos_sesion['convocatoria']['id_convocatoria'],
                );
            
               $datos_validacion_seccion = $this->convocatoria->registro_editar_nuevamete($output);
               header('Content-Type: application/json; charset=utf-8;');
               echo json_encode($datos_validacion_seccion);
               exit();
            }
        }
    }
    
    public function registros_validador($docente = null){
        if(!is_null($docente)){
            $datos_sesion = $this->get_datos_sesion();
            $aux_param['rol_docente'] = array(LNiveles_acceso::Validador1);
            $aux_param['is_entidad_designada'] = false;            
            $aux_param['convocatoria'] = $datos_sesion['convocatoria']['id_convocatoria'];
            $aux_param['filtros']['where']['dd.id_docente'] = $docente;
            $output['datos_docente_validador_actual'] = $this->docente->get_historico_datos_generales($docente,null,$aux_param);
            if(count($output['datos_docente_validador_actual'])>0){
                $output['datos_docente_validador_actual'] = $output['datos_docente_validador_actual'][0];
                //pr($output['datos_docente_validador_actual']);
                $datos_sesion = $this->get_datos_sesion();
                $ids_usuario_registrados = $this->get_usuarios_registro_validador($output['datos_docente_validador_actual']['id_usuario']);
                if(count($ids_usuario_registrados)>0){
                    $output['catalogos']['result_delegacional'] = $this->normativo->get_delegacional();
                    array_unshift($output['catalogos']['result_delegacional'], ['clave_delegacional'=>'',"nombre"=>'Selecciona OOAD']); 
                    //pr($ids_usuario_registrados);exit();
                    $stringIds = implode(',',$ids_usuario_registrados);
                    $auxFiltro = 'doc.id_usuario IN(' . $stringIds . ')';
                    $rol_aplica['filtros']['where'][$auxFiltro] = null;
                    $rol_aplica['rol_docente'] = array(LNiveles_acceso::Docente);
                    $rol_aplica['convocatoria'] = $datos_sesion['convocatoria']['id_convocatoria'];
                    $rol_aplica['is_entidad_designada'] = false;            
                    
                    $output['datos_docentes'] = $this->docente->get_historico_datos_generales(null, null, $rol_aplica);
                    unset($rol_aplica['filtros']);
                    $rol_aplica['filtros']['where']['doc.id_usuario not in(select id_usuario_registrado from sistema.control_registro_usuarios)'] = null;
                    $output['datos_docentes_limbo'] = $this->docente->get_historico_datos_generales(null, null, $rol_aplica);
                    //pr(count($output['datos_docentes_imbo']));
                    //$output['datos_docentes'] = [];
                    $output['listado_docentes'] = $this->load->view('validador/listado_docentes.php', $output, true);
                    $output['listado_docentes_limbo'] = $this->load->view('validador/listado_docentes_limbo.php', $output, true);
                    $main_content = $this->load->view('validador/cambio_validador_n1.tpl.php', $output, true);
                    $this->template->setTitle("Validador N1 ");
                    $this->template->setMainContent($main_content);
                    $this->template->getTemplate();
                }else{
                    $main_content = $this->load->view('validador/no_docentes_registrados.php', $output, true);                    
                    $this->template->setMainContent($main_content);
                    $this->template->getTemplate();
                }
        
            }else{
                show_404();//            
            }
        }else{
            show_404();//            
        }
    }

    public function validadores_nivel1($docente = null){
        $datos_sesion = $this->get_datos_sesion();
        //pr($datos_sesion);
        $data_post = null;
        if($this->input->post()){
            $data_post = $this->input->post(null, true);
        }
        $rol_aplica = $this->get_rol_aplica($datos_sesion,$data_post);
        $rol_aplica['rol_docente'] = array(LNiveles_acceso::Validador1);
        //pr($rol_aplica);
        //$rol_aplica['imprime'] = 1;
        $output['datos_docentes'] = $this->docente->get_historico_datos_generales(null, null, $rol_aplica);
        //pr($output['datos_docentes']);
        header('Content-Type: application/json; charset=utf-8;');
        echo json_encode($output);
        
    }
    public function guarda_cambio_validador(){
        if ($this->input->is_ajax_request()) {
            if($this->input->post()){
                $post = $this->input->post(null,true);
                $output = $this->valida_datos_cambio_validador_n1($post);
                if($output['tp_msg']=='success'){
                    $data['datos'] = [
                        'id_usuario_registra'=>$post['validador_nuevo']
                    ];
                    if(isset($post['docentes']) && !is_null($post['docentes'])){
                        $data['condicion'] = [
                            'id_usuario_registra'=>$post['validador_actual'],
                            'id_usuario_registrado'=>$post['docentes'],
                        ];
                    }
                    if(isset($post['docentes_limbo']) && !is_null($post['docentes_limbo'])){

                        $data['limbo'] = [
                            'id_usuario_registra'=>$post['validador_nuevo'],
                            'id_usuario_registrado'=>$post['docentes_limbo'],
                        ];
                    }
                    $this->load->model('Usuario_model', 'usuario');
                   $output = $this->usuario->save_control_registro_usuarios($data, 'update');   
                }

                header('Content-Type: application/json; charset=utf-8;');
                echo json_encode($output);
            }
        }else{
            show_404();
        }
    }
    public function valida_datos_cambio_validador_n1($post){
        $result = ['mensaje'=>'', 'tp_msg'=>'success'];
        $separador = '';
        $error = false;

        if(!isset($post['validador_actual']) ||is_null($post['validador_actual'])){
            $result['mensaje'] .= $separador.'No existe un validador actualmente.';
            $separador = '<br>';
            $result['tp_msg'] = 'danger';
            
        }
        $valida_docentes_seleccionados = !isset($post['docentes']) || is_null($post['docentes']);
        $valida_docentes_seleccionados_limbo = !isset($post['docentes_limbo']) || is_null($post['docentes_limbo']);
        if($valida_docentes_seleccionados && $valida_docentes_seleccionados_limbo){            
            $result['mensaje'] .= $separador .'Debe seleccionar docentes.';
            $separador = '<br>';
            $result['tp_msg'] = 'danger';
            
            
        }
        if(!isset($post['validador_nuevo']) || is_null($post['validador_nuevo'])){
            $result['mensaje'] .= $separador.'Debe seleccionar un validador.';
            $separador = '<br>';
            $result['tp_msg'] = 'danger';
            
        }
        return $result;

    }
    
    public function habilita_edicion_general(){
        if ($this->input->is_ajax_request()) {
            $datos_validacion_seccion= ['success'=> 0];
            
            $datos_sesion = $this->get_datos_sesion();
            $this->load->model('ConvocatoriaV2_model', 'convocatoria');
            $output = array(                
                'id_convocatoria' => $datos_sesion['convocatoria']['id_convocatoria'],
            );
            $rol_aplica = $this->get_rol_aplica($datos_sesion,null);
            $rol_aplica['filtros']['where']['(select count(*) total_registros_censo from censo.censo cc where cc.id_docente = doc.id_docente) = 0'] = null;
            $est_fin = En_estado_validacion_registro::FINALIZA_REGISTRO_CONVOCATORIA;
            $rol_aplica['filtros']['where']['censo.estado_validacion_docente(doc.id_docente) = '.$est_fin] = null;
            //pr($rol_aplica);
            $rol_aplica['select'] = 'doc.id_docente';
            $output['datos_docentes'] = $this->docente->get_historico_datos_generales(null, null, $rol_aplica);
            $output['id_docente'] = [];
            if(count($output['datos_docentes'])>0){
                foreach($output['datos_docentes'] as $key => $value){
                    $output['id_docente'][] = $value['id_docente'];
                }
                //pr($output);
                $datos_validacion_seccion = $this->convocatoria->registro_editar_nuevamete($output);
            }
            //pr($output['datos_docentes']);
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($datos_validacion_seccion);
            exit();
        
        }
    }
    
    
    
}
