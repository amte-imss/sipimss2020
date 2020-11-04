<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene métodos para la carga de la plantilla base del sistema y creación de la paginación
 * @version 	: 1.1.0
 * @author 	: LEAS.
 * @property    : mixed[] Data arreglo de datos de plantilla con la siguisnte estructura array("title"=>null,"nav"=>null,"main_title"=>null,"main_content"=>null);
 * */
class Template_item_perfil {

    const 
        VIEW_DOCENTE = 1,
        VIEW_PERFIL = 2,
        VIEW_VALIDACION = 3        
    ;

    private $elementos;
    private $registros_censo;
    private $grupos_informacion_campos;
    private $campos_elemento_seccion;
    private $campos_seccion;
    private $files_js_formularios;
    private $datos_generales;
    private $datos_imss;
    private $datos_imagen;
    private $nombre_docente;
    private $matricula;
    private $config_secciones;
    private $mostrar_datos_docente;
    private $status_validacion;
    private $tipo_vista;
    private $rol_valida;
    private $registros_validacion_seccion;
    private $registros_ratificacion;

    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->helper('html');
        $this->mostrar_datos_docente = true;
        $this->elementos = array();
        $this->tipo_vista = Template_item_perfil::VIEW_DOCENTE;
        $this->status_validacion = En_estado_validacion_registro::REGISTRO_USUARIO;
        $this->rol_valida = null;
        $this->registros_validacion_seccion = null;
        $this->registros_ratificacion = null;
    }



    /**
     * 
     * @author LEAS
     * @fecha 17/08/2017
     * @param type $registros
     */
    public function set_registro_censo($registros) {
        $this->registros_censo = $registros;
        $this->generaCampos($registros);
    }
    
    public function set_mostrar_datos_docente($mostrar) {
        $this->mostrar_datos_dodcente = $mostrar;
        
    }
    
    public function set_registro_validacion_seccion($registros) {
        if(!empty($registros)){
            //pr($registros);
            $this->registros_validacion_seccion=[];
            foreach($registros as $keys => $values){
                    //pr($values_c);
                    $values['elementos_censo'] = json_decode($values['elementos_censo'], true);
                    $values_simple = [
                        'comentario' => $values['comentario'],
                        'elementos_censo' => $values['elementos_censo'],
                        'id_seccion' => $values['id_seccion']
                    ];                     
                    $this->registros_validacion_seccion[$values['id_seccion']]  = $values_simple;                                    
                                                       
            }
        }
        //pr($this->registros_validacion_seccion);
    }
    public function set_datos_ratificacion($registro) {
        if(!empty($registro)){
            //pr($registros);
            $this->registros_ratificacion = $registro[0];
            
        }
        //pr($this->registros_validacion_seccion);
    }
    
    
    /**
     * Obtiene campos de elementos seccion y de seccion 
     */
    private function generaCampos($registros){
        $this->campos_elemento_seccion = [];
        $this->campos_seccion = [];    
        $this->grupos_informacion_campos = [];
        $config_secciones = $this->get_configuracion_secciones();    
        
//pr($registros);
        foreach($registros as $keys => $values){
            foreach($values['campos'] as $key_c => $values_c){
                //pr($values_c);
                $this->campos_elemento_seccion[$values['id_elemento_seccion']][$key_c] = $values_c['lb_campo'] ;
                $this->campos_seccion[$values['id_seccion']][$key_c] = $values_c['lb_campo'] ;
                $this->grupos_informacion_campos[$values['id_seccion']][$values_c['grupo_informacion_campo']][$key_c] = $values_c;
            }
            
        }
        //pr($this->grupos_informacion_campos);
        //pr($this->campos_elemento_seccion);
        //pr($this->campos_seccion);
    }

    /**Vista del detalle de  */
    public function set_tipoVistaDocente($tipo_vista_docente){
        $this->tipo_vista = $tipo_vista_docente;
    }
    
    public function get_tipoVistaDocente(){
        return $this->tipo_vista;
    }
    
    public function set_status_validacion($status_validacion){
        if($status_validacion == 0){
            $status_validacion = En_estado_validacion_registro::__default;
        }
        $this->status_validacion = $status_validacion;
    }
    public function get_status_validacion(){
        return $this->status_validacion ;
    }

    public function set_rol_valida($rol_valida){
        $this->rol_valida = $rol_valida;
    }

    public function get_rol_valida(){
        return $this->rol_valida ;
    }

    /**
     * @author LEAS
     * @fecha 17/08/2017
     * @return type
     */
    public function get_registro_censo() {
        return $this->registros_censo;
    }

    function get_files_js_formularios() {
        return $this->files_js_formularios;
    }

    function set_files_js_formularios($files_js_formularios) {
        $this->files_js_formularios = $files_js_formularios;
    }
    
    private function get_configuracion_secciones() {
        if(is_null($this->config_secciones)){
        $this->CI->load->model('Secciones_model', 'csm');
        $seccion = $this->CI->csm->get_seccion(); //Obtiene la información de sección
            foreach ($seccion as $value){
                $this->config_secciones[$value['id_seccion']] = $value;
                if(!is_null($value['config']) && strlen($value['config'])>0){
                    $this->config_secciones[$value['id_seccion']]['config'] = json_decode($value['config'], true);
                }else{
                    $this->config_secciones[$value['id_seccion']]['config'] = null;
                }
            }
        }
        return $this->config_secciones;

    }
        /**
     * @author LEAS
     * @fecha 17/08/2017
     * @param type $datos_generales
     */
    public function set_datos_generales($datos_generales) {
        $this->datos_generales = $datos_generales;
        if (!is_null($datos_generales)) {
            //Asigna el nombre del usuario
            $this->nombre_docente = $datos_generales['nombre'] . ' ' . $datos_generales['apellido_p'] . ' ' . $datos_generales['apellido_m'];
            $this->matricula = $datos_generales['matricula'];
        }
    }

    /**
     * @author LEAS
     * @fecha 17/08/2017
     * @return type
     */
    public function get_datos_generales() {
        return $this->datos_generales;
    }

    /**
     * @author LEAS
     * @fecha 17/08/2017
     * @param type $datos_imss
     */
    public function set_datos_imss($datos_imss) {
        $this->datos_imss = $datos_imss;
    }

    /**
     * @author LEAS
     * @fecha 17/08/2017
     * @return type
     */
    public function get_datos_imss() {
        return $this->datos_imss;
    }

    /**
     * @author LEAS
     * @fecha LEAS
     * @param type $tpl_seccion
     * @param type $tpl_item
     * @return type
     */
    public function get_vistas_regisatros_censo($tpl_seccion = 'perfil/seccion_perfil.php', $tpl_item = 'perfil/item_ficha_actividad.php', $tpl_item_generales = 'perfil/item_datos_generales.php', $tpl_item_imss = 'perfil/item_datos_imss.php', $tpl_item_carrucel = 'perfil/item_carrusel.php', $tpl_tab_perfil = 'perfil/tab_perfil.php') {
        $array_elemento_seccion = array();
        $array_carrucel = array();
        //$tmp_resultado = array();
        $string_value = get_elementos_lenguaje(array(En_catalogo_textos::INFORMACION_GENERAL, En_catalogo_textos::COMPROBANTE));
        $datos['string_value'] = $string_value; //Agrega elementos de sección

        /* Datos generales */
        $datos_gen['string_value'] = $string_value;
        $datos_gen['elementos_seccion'] = array_merge($this->get_datos_generales(), $this->get_datos_imss()); //datos_generales
        
        
        //pr($datos_gen);
        if(!is_null($tpl_item_generales)){
            $tmp_resultado['informacion_general'] = $this->CI->load->view($tpl_item_generales, $datos_gen, TRUE); //Agrega item de datos generales
        }
//        $datos['nom_seccion'] = $string_value['title_informacion_general']; //Agrega elementos de sección
//        $tmp_resultado .= $this->CI->load->view($tpl_seccion, $datos, TRUE); //Agrega item de datos generales del docente

        /* Datos imss */
        //$datos_imss['string_value'] = $string_value;
        //$datos_imss['elementos_seccion'] = $this->get_datos_imss(); //datos_generales
        if(!is_null($tpl_item_imss)){
            $tmp_resultado['informacion_imss'] = $this->CI->load->view($tpl_item_imss, $datos_gen, TRUE);
        }
//        $datos['elementos_seccion'] = $this->CI->load->view($tpl_item_imss, $datos_imss, TRUE);
//        $datos['nom_seccion'] = $string_value['title_informacion_imss']; //Agrega elementos de sección
//        $tmp_resultado .= $this->CI->load->view($tpl_seccion, $datos, TRUE); //Agrega item de datos imss al template
//        pr($datos_imss);
//        pr($this->registros_censo);

        foreach ($this->registros_censo as $value) {//Genera todos los items, separados por seccion
            $value['activo'] = ''; //Active siempre limpio
            if (!isset($array_elemento_seccion[$value['id_seccion']])) {
                $array_elemento_seccion[$value['id_seccion']] = array(
                    'lbl_seccion' => $value['lbl_seccion'],
                    'id_seccion' => $value['id_seccion'],
                    'count' => 1,
                    'view' => ''
                );
                $value['activo'] = 'active'; //Activa el primer registro
            }
            $value['string_value'] = $string_value;
            //Carga cada item de las actividades del docente
            //pr($value);
            
            $array_elemento_seccion[$value['id_seccion']]['view'] .= $this->CI->load->view($tpl_item, $value, TRUE); //Concatena los items dentro del carrusel
            $array_elemento_seccion[$value['id_seccion']]['count'] ++; //Incrementa el contador de vistas o item por sección 
        }
        //pr($array_elemento_seccion);
        //Genera el carrucel por seccion 
        foreach ($array_elemento_seccion as $key => $value) {
            $datos = $value; //Agrega elementos de sección label y nombre
            //pr($datos);
            $datos['elementos_seccion'] = $array_elemento_seccion[$key]['view']; //Agrega elementos items contet de carrusel(información del registro)
            $datos['count'] = $array_elemento_seccion[$key]['count']; //Agrega elementos items contet de carrusel(información del registro)
            $aux = $this->CI->load->view($tpl_item_carrucel, $datos, TRUE);
            $array_carrucel['datos'][$key] = array(
                'lbl_seccion' => $value['lbl_seccion'],
                'id_seccion' => $value['id_seccion'],
                'carrusel' => $aux
            );
        }
        $array_carrucel['string_value'] = $string_value;//Atrributo textos para la vista tab perfil
        $array_carrucel['secciones'] = $string_value;
        $array_carrucel['files_js_render_formularios'] = $this->get_files_js_formularios();
        
        $resul_view_tab = $this->CI->load->view($tpl_tab_perfil, $array_carrucel, TRUE);//Genera la vista de tab's, separadas por actividades
//        $datos['elementos_seccion'] = $resul_view_tab;
//        $tmp_resultado .= $this->CI->load->view($tpl_seccion, $datos, TRUE);
        $tmp_resultado['main_content'] = $resul_view_tab;
        return $tmp_resultado;
    }

    /**
     * @author LEAS
     * @fecha LEAS
     * @param type $tpl_seccion
     * @param type $tpl_item
     * @return type
     */
    public function get_vistas_regisatros_censo_inicio($tpl_seccion = 'perfil/seccion_perfil.php', $tpl_item = 'perfil/item_ficha_actividad', $tpl_item_generales = 'perfil/item_datos_generales', $tpl_item_imss = 'perfil/item_datos_imss', $tpl_item_carrucel = 'perfil/item_carrusel', $tpl_tab_perfil = 'perfil/tab_perfil') {
        $array_elemento_seccion = array();
        $array_carrucel = array();
        //$tmp_resultado = array();
        $string_value = get_elementos_lenguaje(array(En_catalogo_textos::INFORMACION_GENERAL, En_catalogo_textos::COMPROBANTE));
        $datos['string_value'] = $string_value; //Agrega elementos de sección
        $secciones_config = $this->get_configuracion_secciones();
        /* Datos generales */
        $datos_gen['string_value'] = $string_value;
        $datos_gen['elementos_seccion'] = array_merge($this->get_datos_generales(), $this->get_datos_imss()); //datos_generales
        $datos_gen['elementos_seccion']['mostrar_datos'] = $this->mostrar_datos_docente;
        
        //pr($datos_gen);
        if(!is_null($tpl_item_generales)){
            $tmp_resultado['informacion_general'] = $this->CI->load->view($tpl_item_generales, $datos_gen, TRUE); //Agrega item de datos generales
        }
//        $datos['nom_seccion'] = $string_value['title_informacion_general']; //Agrega elementos de sección
//        $tmp_resultado .= $this->CI->load->view($tpl_seccion, $datos, TRUE); //Agrega item de datos generales del docente

        /* Datos imss */
        //$datos_imss['string_value'] = $string_value;
        //$datos_imss['elementos_seccion'] = $this->get_datos_imss(); //datos_generales
        if(!is_null($tpl_item_imss)){
            $tmp_resultado['informacion_imss'] = $this->CI->load->view($tpl_item_imss, $datos_gen, TRUE);
        }
//        $datos['elementos_seccion'] = $this->CI->load->view($tpl_item_imss, $datos_imss, TRUE);
//        $datos['nom_seccion'] = $string_value['title_informacion_imss']; //Agrega elementos de sección
//        $tmp_resultado .= $this->CI->load->view($tpl_seccion, $datos, TRUE); //Agrega item de datos imss al template
//        pr($datos_imss);
        //pr($this->registros_censo);
        $i = 1;
        $conf_validacion = [];
        foreach ($this->registros_censo as $value) {//Genera todos los items, separados por seccion          
            $value['activo'] = ''; //Active siempre limpio
            if (!isset($array_elemento_seccion[$value['id_seccion']])) {
                $array_elemento_seccion[$value['id_seccion']] = array(
                    'lbl_seccion' => $value['lbl_seccion'],
                    'id_seccion' => $value['id_seccion'],
                    'id_elemento_seccion' => $value['id_elemento_seccion'],
                    'count' => 1,
                    'view' => ''
                );
                $value['activo'] = 'active'; //Activa el primer registro
            }
            $value['string_value'] = $string_value;
            $value['campos_seccion'] = $this->campos_seccion[$value['id_seccion']]; //Agrega elementos items contet de carrusel(información del registro)
            $value['campos_elemento_seccion'] = $this->campos_elemento_seccion[$value['id_elemento_seccion']]; //Agrega elementos items contet de carrusel(información del registro)
            $value['id_docente'] = $this->datos_generales['id_docente'];
            if(isset($this->registros_validacion_seccion[$value['id_seccion']])){
                $value['validacion_seccion_reg'] = $this->registros_validacion_seccion[$value['id_seccion']];
            }
            //Carga cada item de las actividades del docente
           // pr($secciones_config[$value['id_seccion']]['config']);
            //Configuraciones de seccion
                $array_elemento_seccion[$value['id_seccion']]['pinta_elemento_seccion'] = false;//indica si agrega el nombre de la seccion como columna a la tabla de datos
                $value['is_view_personalizada'] = 0;//Vista personalizada para mostrar los datos en la tabla
                if(!is_null($secciones_config[$value['id_seccion']]['config']) && isset($secciones_config[$value['id_seccion']]['config']['is_personalizado']) &&  $secciones_config[$value['id_seccion']]['config']['is_personalizado'] == 1){                    
                    $value['is_view_personalizada'] = 1;
                    if(!isset($conf_validacion[1][$value['id_seccion']])){
                        $conf_validacion[1][$value['id_seccion']] = $this->get_view_validacion(1, $value);
                    }
                    $value['conf_validacion'][1] = $conf_validacion[1][$value['id_seccion']];                                            
                    //pr($value['is_view_personalizada']);
                    $seccion_name = $secciones_config[$value['id_seccion']]['nombre'];                                        
                    $value['campos_agrupados'] = $this->grupos_informacion_campos[$value['id_seccion']];
                    $array_elemento_seccion[$value['id_seccion']]['view'] .= $this->CI->load->view($tpl_item.'_'.$seccion_name, $value, TRUE); //Concatena los items dentro del carrusel
                }else{
                    $value['pinta_elemento_seccion'] = false; //indica si agrega el nombre de la seccion como columna a la tabla de datos
                    if(!isset($conf_validacion[1][$value['id_seccion']])){
                        $conf_validacion[1][$value['id_seccion']] = $this->get_view_validacion(1, $value);
                    }
                    $value['conf_validacion'][1] = $conf_validacion[1][$value['id_seccion']];                                                                
                    if(!is_null($secciones_config[$value['id_seccion']]['config']) && (!isset($secciones_config[$value['id_seccion']]['config']['id_elementoSeccionDefault'])|| $secciones_config[$value['id_seccion']]['config']['id_elementoSeccionDefault']<=0)){                    
                        $value['pinta_elemento_seccion'] = true; //indica si agrega el nombre de la seccion como columna a la tabla de datos
                        $array_elemento_seccion[$value['id_seccion']]['pinta_elemento_seccion'] = true; //indica si agrega el nombre de la seccion como columna a la tabla de datos
                    }
                    //pr($value);
                    $array_elemento_seccion[$value['id_seccion']]['view'] .= $this->CI->load->view($tpl_item, $value, TRUE); //Concatena los items dentro del carrusel
                    
                }
                $array_elemento_seccion[$value['id_seccion']]['count'] ++; //Incrementa el contador de vistas o item por sección 
                
            }
            //pr($conf_validacion);
            //pr($array_elemento_seccion);
            //Genera el carrucel por seccion 
            foreach ($array_elemento_seccion as $key => $value) {
                $datos = $value; //Agrega elementos de sección label y nombre
                //pr($datos);
                $datos['elementos_seccion'] = $array_elemento_seccion[$key]['view']; //Agrega elementos items contet de carrusel(información del registro)
                $datos['campos_seccion'] = $this->campos_seccion[$value['id_seccion']]; //Agrega elementos items contet de carrusel(información del registro)
                $datos['campos_elemento_seccion'] = $this->campos_elemento_seccion[$value['id_elemento_seccion']]; //Agrega elementos items contet de carrusel(información del registro)
                $datos['count'] = $array_elemento_seccion[$key]['count']; //Agrega elementos items contet de carrusel(información del registro)
                $value['is_view_personalizada'] = 0;//Vista personalizada para mostrar los datos en la tabla
                
                $datos['conf_validacion'][1] = $conf_validacion[1][$value['id_seccion']];                                            
                                                           
                if(!is_null($secciones_config[$value['id_seccion']]['config']) && isset($secciones_config[$value['id_seccion']]['config']['is_personalizado']) &&  $secciones_config[$value['id_seccion']]['config']['is_personalizado'] == 1){                    
                    $value['is_view_personalizada'] = 1;//Vista personalizada para mostrar los datos en la tabla
                    $seccion_name = $secciones_config[$value['id_seccion']]['nombre'];                    
                    $aux = $this->CI->load->view($tpl_item_carrucel.'_'.$seccion_name, $datos, TRUE);
                }else{                                        
                    $aux = $this->CI->load->view($tpl_item_carrucel, $datos, TRUE);
                }
                $array_carrucel['datos'][$key] = array(
                    'lbl_seccion' => $value['lbl_seccion'],
                    'id_seccion' => $value['id_seccion'],
                    'carrusel' => $aux
                );
        }

        if(!isset($conf_validacion[2])){//ratificacion
            $param = null;
            if(!is_null($this->registros_ratificacion)){
                $param['ratificacion'] = $this->registros_ratificacion;
            }
            //pr($param);
            $conf_validacion[2] = $this->get_view_validacion(2, $param);
        }
        if(!isset($conf_validacion[3])){//Validacion final n1
            $conf_validacion[3] = $this->get_view_validacion(3, null);
        }
        if(!isset($conf_validacion[4])){//Validacion final n1
            $conf_validacion[4] = $this->get_view_validacion(4, null);
        }
        $array_carrucel['conf_validacion'][2] = $conf_validacion[2];                                            
        $array_carrucel['conf_validacion'][3] = $conf_validacion[3];
        $array_carrucel['conf_validacion'][4] = $conf_validacion[4];
        $array_carrucel['string_value'] = $string_value;//Atrributo textos para la vista tab perfil
        $array_carrucel['secciones'] = $string_value;
        $array_carrucel['files_js_render_formularios'] = $this->get_files_js_formularios();
        if(!is_null($this->registros_validacion_seccion) && !empty($this->registros_validacion_seccion)){
            $array_carrucel['registros_validacion_seccion'] = $this->registros_validacion_seccion;


        }
        $array_carrucel['files_js_validacion_censo'] = '/validador/validacion_censo.js';
        $array_carrucel['status_validacion_censo'] = $this->status_validacion;
        
        $resul_view_tab = $this->CI->load->view($tpl_tab_perfil, $array_carrucel, TRUE);//Genera la vista de tab's, separadas por actividades
        //        $datos['elementos_seccion'] = $resul_view_tab;
        //        $tmp_resultado .= $this->CI->load->view($tpl_seccion, $datos, TRUE);
        $tmp_resultado['main_content'] = $resul_view_tab;
        return $tmp_resultado;
    }

    /**
     * $tipo, puede ser para la ratificacion = 2; o la validacion n1 por sección = 1; validacion general 3;
     */
    public function get_view_validacion($tipo = 1, $param= null){
        $result = array('view'=>'', 'view_btn_guardar'=>'', 'view_btn_ratificar'=>'', 
        'view_col_val_censo' => false, 'validar_proceso'=>false, 'vista_ratificacion'=>false
        ,'bloqueo_componentes_validacion_secciones' => 1
    );

        $estado_valido = $this->aplica_estado_validacion($tipo, $this->status_validacion);
        if($this->tipo_vista == Template_item_perfil::VIEW_VALIDACION){
            //pr($this->rol_valida . $tipo);
            switch($tipo){            
                case 2://vista de la ratificacion  
                    $paso_validacion = false;
                    if($this->rol_valida ==LNiveles_acceso::Validador2 && $estado_valido['valido_estado']){             
                        $result['view_btn_ratificar'] = $this->CI->load->view('perfil/inicio/validacion/btn_validacion_ratificacion.php', $param, true);
                        $result['validar_proceso'] = true;
                        $paso_validacion = true;
                    }
                    if(!is_null($param) || $paso_validacion){ 
                        $result['view'] = $this->CI->load->view('perfil/inicio/validacion/validacion_ratificacion.php', $param, true);
                        $result['vista_ratificacion'] = true;
                    }
                    
                break;
                case 3://vista de la validación general  
                    if($this->rol_valida ==LNiveles_acceso::Validador1 && $estado_valido['valido_estado']){
                        //$param['valido_estado'] = $estado_valido['valido_estado'];
                        $result['view'] = $this->CI->load->view('perfil/inicio/validacion/validacion_n1_gen.php', $param, true);
                        $result['validar_proceso'] = true;
                    }
                    if($this->rol_valida ==LNiveles_acceso::Normativo){

                    }
                   

                break;
                case 1://Vista de la validacion por seccion
                    //pr($param);
                    if(!is_null($param)){                        
                        $result['view_col_val_censo'] = true;
                        $result['view'] = $this->CI->load->view('perfil/inicio/validacion/validacion_seccion.php', $param, true);
                    }
                    if($this->rol_valida ==LNiveles_acceso::Validador1 && $estado_valido['valido_estado']){                           
                        //pr("<-->".$param['is_view_personalizada'] . " -> " . $param['id_seccion']);
                        if(!is_null($param)){
                            $result['view_btn_guardar'] = $this->CI->load->view('perfil/inicio/validacion/btn_validacion_seccion.php', $param, true);
                            $result['bloqueo_componentes_validacion_secciones'] = 0;
                            //pr($result['view']);                            
                        }
                        $result['validar_proceso'] = true;
                    }
                    
                break;            
                case 4://Vista de la validacion por seccion                                        
                    if($this->rol_valida ==LNiveles_acceso::Validador1 && $estado_valido['valido_estado']){                                                   
                        $result['bloqueo_componentes_validacion_secciones'] = 0;
                        $result['validar_proceso'] = true;
                    }
                    
                break;            
            }
        }
        return $result;
    }

    private function aplica_estado_validacion($tipo = 1, $status_val){
        $array = ['status_val', 'valido_estado'=>false];
        
        switch($tipo){            
            case 2://vista de la ratificacion  
                $array['status_val'][En_estado_validacion_registro::VALIDADO_N1] = true;
            break;
            case 3://vista de la validación general  
                $array['status_val'][En_estado_validacion_registro::PROCESO_VALIDACION_N1] = true;
                
            break;
            default://Vista de la validacion por seccion
            $array['status_val'][En_estado_validacion_registro::FINALIZA_REGISTRO_CONVOCATORIA] = true;
            $array['status_val'][En_estado_validacion_registro::PROCESO_VALIDACION_N1] = true;
            
            
        }
        if(isset($array['status_val'][$status_val])){
            $array['valido_estado'] = true;
        }
        return $array;
    }

    private function get_grupos_informacion($value){

    }

    /**
     * @author LEAS
     * @fecha 13/06/2018
     * @param type Array $datos contiene la siguiente estructura  
     * array('informacion_general'=>'' //Vista de información general del docente, 
     * 'informacion_imss'=>'' //Vista de información del imss, 
     * 'main_content'=>'' //Vista de información del censo 
     * );
     * @param type $tpl  string ruta de archivo view de la plantilla de perfil
     * @param type $tpl_item_imagen template view de imagen del docente
     * @return type
     */
    public function get_template_registro($datos, $tpl = 'perfil/perfil.tpl.php', $tpl_item_imagen = 'perfil/perfil_imagen.php', $id_docente = null) {
        $string_value = get_elementos_lenguaje(array(En_catalogo_textos::INFORMACION_GENERAL));
//        $datos = array('main_content' => $items);
        /* Template de imagen foto de perfil */
        $datos_img['string_value'] = $string_value;
        $datos_img['elementos_seccion'] = $this->get_datos_imagen_ext($this->get_datos_imagen()); //Agrega los datos de la imagen
        $datos_img['nombre_docente'] = $this->get_nombre_docente(); //Agrega nombre del docente
        $datos_img['matricula'] = $this->get_matricula(); //Agrega matricula
        if(!is_null($tpl_item_imagen)){
            $datos['imagen_perfil'] = $this->CI->load->view($tpl_item_imagen, $datos_img, TRUE); //Agrega item de datos generales
        }else{
            $datos['imagen_perfil'] = null;
        }

        //Agrega imagen al template 
        $datos['titulo_seccion'] = $string_value['title_perfil'];
        $datos['id_docente'] = $id_docente;
        $datos['nombre_docente'] = $this->get_nombre_docente();
        //pr($datos);
        $resultado = $this->CI->load->view($tpl, $datos, TRUE);
        return $resultado;
    }

    /**
     * LEAS
     * Date:11/10/2020
     * Carga datos
     */
    public function get_template_registro_inicio($datos, $tpl = 'perfil/perfil.tpl.php', $tpl_item_imagen = 'perfil/perfil_imagen.php') {
        $string_value = get_elementos_lenguaje(array(En_catalogo_textos::INFORMACION_GENERAL));
//        $datos = array('main_content' => $items);
        /* Template de imagen foto de perfil */
        $datos_img['string_value'] = $string_value;
        $datos_img['elementos_seccion'] = $this->get_datos_imagen_ext($this->get_datos_imagen()); //Agrega los datos de la imagen
        $datos_img['nombre_docente'] = $this->get_nombre_docente(); //Agrega nombre del docente
        $datos_img['matricula'] = $this->get_matricula(); //Agrega matricula
        $datos['imagen_perfil'] = $this->CI->load->view($tpl_item_imagen, $datos_img, TRUE); //Agrega item de datos generales
        //Agrega imagen al template 
        $datos['titulo_seccion'] = $string_value['title_perfil'];
        $resultado = $this->CI->load->view($tpl, $datos, TRUE);
        return $resultado;
    }


    function get_nombre_docente() {
        return $this->nombre_docente;
    }

    function get_matricula() {
        return $this->matricula;
    }

    function set_nombre_docente($nombre_docente) {
        $this->nombre_docente = $nombre_docente;
    }

    function set_matricula($matricula) {
        $this->matricula = $matricula;
    }

    function get_datos_imagen() {
        return $this->datos_imagen;
    }

    public function get_datos_imagen_ext($elementos_seccion){
        //$this->CI->load->config('general');
        $ruta_imagen_perfil = $this->CI->config->item('img_perfil_default');
        $result = array('ruta_imagen_perfil'=>$this->CI->config->item('img_perfil_default'), 'file_hidden'=>'<input type="hidden" id="file_cve" name="file_cve" value="">');
        if (isset($elementos_seccion['id_file'])) {//Valida que existe la imagen del usuario
            $result['file_hidden'] = '<input type="hidden" id="file_cve" name="file_cve" value="' . base64_encode($elementos_seccion['id_file']) . '">';
            if (!is_null($elementos_seccion['nombre_fisico']) and ! is_null($elementos_seccion['ruta'])) {
                //Carga imagen del usuario docete
                $result['ruta_imagen_perfil'] = base_url($elementos_seccion['ruta'] . $elementos_seccion['nombre_fisico'] . '.' . $elementos_seccion['extencion']);
                if (file_exists($ruta_imagen_perfil)) {//Valida que exista la imagen del perfil solicitada, si no existe, muestra imagen default
                    $result['ruta_imagen_perfil'] = $this->config->item('img_perfil_default');
                } else {

                }
            } else {
                $result['ruta_imagen_perfil'] = $this->config->item('img_perfil_default');
            }
        }
        return $result;
    }

    function set_datos_imagen($datos_imagen) {
        $this->datos_imagen = $datos_imagen;
    }

}
