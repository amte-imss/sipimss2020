<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//use PHPUnit\Framework\TestCase;
/**
 * Clase que contiene pruebas unitarias del core o gestor de formularios
 * @version     : 1.0.0
 * @version     : 1.0.0
 * @author      : LEAS
 * */
class Test_unit_core extends Core_secciones {

    function __construct() {
        parent::__construct();
        $this->load->model('secciones_model', 'sm');
        $this->load->model('catalogo_model', 'cm');
        $this->load->model('formulario_model', 'fm');
        $this->load->library('unit_test');
//        is_object
//is_string
//is_bool
//is_true
//is_false
//is_int
//is_numeric
//is_float
//is_double
//is_array
//is_null
//is_resource
    }

    public function index() {
        pr("Hola pruebas de PHP para core (gestor de formularios)");
        pr("Los siguientes métodos que generan las pruebas unitarias sobre los métodos del modelo formularios"
                . "el prefijo test_ distingue el nombre del método en el formulario"
        );
        pr('test_get_campos_formulario();');
        pr('test_get_catalogos_formulario($params);');
        pr('test_get_cross_datos_actividad_docente($seccion, $cantidad_registros_esperados);');
        pr('test_get_cross_datos_actividad_docente_completo($docente);');
        pr('test_get_detalle_censo($id_censo);');
    }

    public function test_core() {
        $test = 1 + 1;

        $expected_result = 2;

        $test_name = 'Secciones de cores';

        $result = $this->unit->run($test, $expected_result, $test_name);
        pr($result);
    }

    /**
     * @author LEAS
     * @param type $array_A array asociativo A
     * @param type $array_B array asociativo B
     * @return boolean Si los dos arrays contienen las mismas llaves y el mismo valor
     */
    private function is_equals_resultados_key_value($array_A, $array_B) {
        foreach ($array_A as $key => $value) {
            if (!isset($array_B[$key])) {//Si algun indice no e
                return FALSE;
            }
            if ($array_B[$key] != $value) {//Valida que los resultados sean iguales
                return FALSE;
            }
        }
        return true;
    }

    /**
     * @author LEAS
     * Valida la carga completa de información de censo del docente
     * Si ingresa id del docente, no es necesario que inicie sesión si no 
     * la prueba va con la informacion del usuario en sesión actual; si no existe sesión 
     * hace la validación de la prueba cuando no existe usuario
     * 
     */
    public function test_get_cross_datos_actividad_docente_completo($docente = null) {
        $secciones = [
            En_seccion_actividad_docente::FORMACION => 0,
            En_seccion_actividad_docente::ACTIVIDAD_DOCENTE => 3,
        ];
        $datos_sesion = $this->get_datos_sesion();
        //si id de docente es diferente de nulo es precedente, si no, valida que exista sesión iniciadaº
        $id_docente = (is_null($docente)) ? $datos_sesion[En_datos_sesion::ID_DOCENTE] : $docente;
        $result_no_empty_secciones = '';
        $info_docente = $this->fm->get_cross_datos_actividad_docente_completo($id_docente);
        if ($id_docente) {//Validaciones don registros
            //Valida que devuelva un array
            $result = $this->unit->run($info_docente, 'is_array', 'Es un array');
            $result_no_empty = $this->unit->run(count($info_docente) > 0, true, 'Contiene registros (con sesión iniciada)');
            pr($result);
            pr($result_no_empty);
            $count_seccioes = [];
            foreach ($info_docente as $value) {
                if (isset($secciones[$value['id_seccion']])) {
                    $count_seccioes[$value['id_seccion']] = (empty($count_seccioes[$value['id_seccion']])) ? 1 : $count_seccioes[$value['id_seccion']] + 1;
                }
            }
            foreach ($secciones as $key_s => $val_s) {
                if (!isset($count_seccioes[$key_s])) {//Valida que no exista para asignarlo con valor cero
                    $count_existe = (0 == $val_s);
                } else {
                    $count_existe = ($count_seccioes[$key_s] == $val_s);
                }
                $result_no_empty_secciones = $this->unit->run($count_existe, true, 'La seccion ' . $key_s . ' contiene ' . $val_s . ' registros');
                pr($result_no_empty_secciones);
            }
        } else {
            //No existe sesión, valida que no existan registros
            $result = $this->unit->run($info_docente, 'is_array', 'Es un array');
            $result_no_empty = $this->unit->run(count($info_docente) == 0, true, 'No existen registros sin iniciar sesión');
            pr($result);
            pr($result_no_empty);
        }

//        $this->template->setTitle("Test the core");
//        $this->template->setMainContent($result);
//        $this->template->getTemplate();
    }

    /**
     * @author LEAS
     * Test obtiene información censo de profesores de un docente y de una 
     * sección en el modelo de formulario_model
     */
    public function test_get_cross_datos_actividad_docente($seccion = null, $cantidad_registros_esperados = null) {
//        pr($seccion);
//        pr($cantidad_registros_esperados);
        if (is_null($seccion)) {//Secciones especificas o todas las secciones
            $secciones = [
//                En_seccion_actividad_docente::FORMACION => 0,
                En_seccion_actividad_docente::ACTIVIDAD_DOCENTE => 3,
//                En_seccion_actividad_docente::INVESTIGACION => 0,
//                En_seccion_actividad_docente::MATERIAL_EDUCATIVO => 0,
//                En_seccion_actividad_docente::DIRECCION_TESIS => 0,
//                En_seccion_actividad_docente::BECAS_COMISIONES_LABORALES => 0,
//                En_seccion_actividad_docente::COMISIONES_ACADEMICAS => 0,
            ];
        } else {//Sección especifica
            $secciones = [$seccion => $cantidad_registros_esperados];
        }
        $datos_sesion = $this->get_datos_sesion(); //Obtiene los datos de la sesión
        $id_docente = $datos_sesion[En_datos_sesion::ID_DOCENTE];
        $result_no_empty_secciones = '';
        $result_no_empty_registros = '';
        if ($datos_sesion) {//Valida la sesión iniciada
            foreach ($secciones as $key_secciones => $cantidad_seccion) {
                pr('<h3>Prueba para sección ' . $key_secciones . '</h3>');
                $info_docente = $this->fm->get_cross_datos_actividad_docente($id_docente, $key_secciones);
//                pr($info_docente);
                //Valida que devuelva un array
                $result = $this->unit->run($info_docente, 'is_array', 'Regresa un array por sección ' . $key_secciones);
                $result_no_empty = $this->unit->run((isset($info_docente['datos_actividad_docente']) and count($info_docente['datos_actividad_docente'])) > 0, true, 'La sección ' . $key_secciones . ' contiene registros ');
                $count_registros = [];
                if (empty($info_docente['datos_actividad_docente'])) {
                    $count_registros [$key_secciones] = 0;
                }
                foreach ($info_docente['datos_actividad_docente'] as $value) {
                    if (isset($secciones[$value['id_seccion']])) {
                        $count_registros[$value['id_seccion']] = (empty($count_registros[$value['id_seccion']])) ? 1 : $count_registros[$value['id_seccion']] + 1;
                    }
                }
//            $equals = $this->is_equals_resultados_key_value($count_seccioes, $secciones);
                /* Valida que las secciones sean diferentes de cero */
                $equals = (!array_diff_assoc($count_registros, $secciones) and ! empty($count_registros) and ! empty($secciones)); //Valida que los arrays sean iguales
                $result_no_empty_registros = $this->unit->run($equals, true, 'Las seccion indicada contienen los registros indicados');
                pr($result);
                pr($result_no_empty);
                pr($result_no_empty_registros);
            }
        } else {
            $info_docente = $this->fm->get_cross_datos_actividad_docente();
            //No existe sesión, valida que no existan registros
            $result = $this->unit->run($info_docente, 'is_array', 'Es un array');
            $result_no_empty = $this->unit->run(count($info_docente) == 0, true, 'No existen registros sin iniciar sesión');
            pr($result);
            pr($result_no_empty);
        }
    }

    /**
     * 
     * @param type $elemento_seccion el id del elmento que se asocia con un formulario
     * Si el elemento de sección no es especificado, entonces, el test será hecho sobre
     * algunas secciones especificadas al inicio de la funcion 
     */
    public function test_get_campos_formulario($id_elemento_seccion = null) {
        if (!is_null($id_elemento_seccion)) {
            $elementos_seccion = [$id_elemento_seccion];
        } else {
            $elementos_seccion = [19, 'u'/* , 20, 22 */];
        }
        $valida_campos_existentes = [ /* Nombre de catálogos que se comparan para que existan */
            'duracion', 'horas',
            'sede',
            'fecha_inicio',
            'fecha_termino', 'institucion_avala',
            'role_desempenia', 'tipo_comprobante'
        ];

        foreach ($elementos_seccion as $val_es) {
            pr('<h3>Prueba para validar la defenición de un formulario (en espécifico el de Técnicos) que corresponde a la sub seccion con id: ' . $val_es . '</h3>');
            $tmp_es = array('f.id_elemento_seccion' => $val_es);
            $definicion_formulario = $this->fm->get_campos_formulario($tmp_es);
            $result = $this->unit->run($definicion_formulario, 'is_array', 'La función retorna un array con la subsección ' . $val_es);
            $result_no_empty = $this->unit->run(count($definicion_formulario) > 0, true, '¿El formulario relacionado con la subsección con id ' . $val_es . ' cuenta con ' . count($definicion_formulario) . ' campos ' . '?');
            pr($result);
            pr($result_no_empty);
            /* Valida existencia de campos para dicho foremulario */
            $obtener_campos = [];
            foreach ($definicion_formulario as $val) {
                $obtener_campos[$val['nom_campo']] = '';
            }
            foreach ($valida_campos_existentes as $value) {
                $existe_campo = $this->unit->run((isset($obtener_campos[$value])), true, '¿El campo ' . $value . ' existe para el formulario con la subsección ' . $val_es . '?');
                pr($existe_campo);
            }
        }
    }

    /**
     * 
     * @param type $id_censo identificador del censo delo que se esperá información a detalle
     * Si $id_censo es igual que null se hace el test con los registros del docente con 
     * la sección actual
     * @return type Description
     */
    public function test_get_detalle_censo($id_censo = null) {
        $datos_sesion = $this->get_datos_sesion();
        if (!is_null($id_censo)) {
            $censo = [$id_censo];
        } else {
            if ($datos_sesion) {
                $registros = $this->fm->get_registros_censo($datos_sesion[En_datos_sesion::ID_DOCENTE]);
                foreach ($registros as $value) {
                    $censo[] = $value['id_censo'];
                }
            }
        }
        $result = $this->unit->run($datos_sesion, true, 'Sesión iniciada');
        pr($result);
        if ($datos_sesion) {
            foreach ($censo as $id_censo_actual) {
                $definicion_formulario = $this->fm->get_detalle_censo($id_censo_actual);
                $is_vacio = !empty($definicion_formulario);
                $valida_vacios = $this->unit->run($is_vacio, true, 'Existen registros para el id_censo ' . $id_censo_actual);
                pr('*****************************************************');
                pr($valida_vacios);
                if ($is_vacio) {
                    $valida_json = !empty((array) (json_decode($definicion_formulario[0]["formulario_registros"])));
                    $res_registros = $this->unit->run($valida_json, true, '¿Existen json de datos del formulario(id_formulario, nombres de campos y valores) para el registro id_censo =  ' . $id_censo_actual . '?');
                    pr($res_registros);
                }
            }
        }
    }

    /**
     * 
     * @param type $id_elemento_seccion Elemento sección que contiene un formulario
     * @return type Description 
     *  -Valida que el formulario contenga catálogos
     *  -Valida que el método regrese un array con las propiedades de los catalogos
     *  -Valida que los catálogos con nombre definidos en la primera linea existan
     */
    public function test_get_catalogos_formulario($id_elemento_seccion = null) {
        $nom_catalogos = [ /* Nombre de catálogos que se comparan para que existan */
            'duracion',
            'institucion_avala',
            'role_docente',
            'tipo_comprobante',
        ];
        if (!is_null($id_elemento_seccion)) {
            $elementos_seccion = [$id_elemento_seccion];
        } else {
            $elementos_seccion = [19, 20];
        }
        foreach ($elementos_seccion as $id_seccion_elemento) {
            $param = array('f.id_elemento_seccion' => $id_seccion_elemento);
            $catalogos = $this->fm->get_catalogos_formulario($param);
//            pr($catalogos);
            $catalogos_formulario = [];
            pr("<h3>Test para validar catálogos relacionados con el formulario de la subsección " . $id_seccion_elemento . '</h3>');
            foreach ($catalogos as $cat_prop) {
                $catalogos_formulario[$cat_prop['nom_catalogo']] = $cat_prop['id_catalogo'];
            }
            //Valida que la seccción contenga registros 
            $pr_contiene_catalogos = $this->unit->run(count($catalogos) > 0, true, '¿la sección "' . $id_seccion_elemento . '" contiene catálogos ?');
            pr($pr_contiene_catalogos);
            //Valida que sea un array
            $pr_is_array = $this->unit->run($catalogos, 'is_array', '¿Es una array el retorno que representa las propiedades de catálogo');
            pr($pr_is_array);
            foreach ($nom_catalogos as $val_nom_cat) {
                $exist_catalogo = isset($catalogos_formulario[$val_nom_cat]); //Valida que existe el catálogo en el formularioindicado
                $pr_existe = $this->unit->run($exist_catalogo, true, '¿El catálogo ' . $val_nom_cat . ' existe para el formulario con subsección "' . $id_seccion_elemento . '" ?');
                pr($pr_existe);
            }
        }
    }

    public function test_get_elemento_seccion() {
        $secciones = [
//            En_seccion_actividad_docente::FORMACION,
            En_seccion_actividad_docente::ACTIVIDAD_DOCENTE => [3, 2],
//            En_seccion_actividad_docente::INVESTIGACION,
//            En_seccion_actividad_docente::MATERIAL_EDUCATIVO,
//            En_seccion_actividad_docente::DIRECCION_TESIS,
//            En_seccion_actividad_docente::BECAS_COMISIONES_LABORALES,
//            En_seccion_actividad_docente::COMISIONES_ACADEMICAS,
        ];
        foreach ($secciones as $key => $value) {
            $catalogos_res = $this->sm->get_elemento_seccion($key);
            $catalogo_asociativo = [];
            foreach ($catalogos_res as $value_catalogo) {
                $catalogo_asociativo[$value_catalogo['id_elemento_seccion']] = $value_catalogo['label'];
            }
            foreach ($value as $val_sub_secciones) {
                $exist_sub_seccion = isset($catalogo_asociativo[$val_sub_secciones]);
                $pr_existe = $this->unit->run($exist_sub_seccion, true, '¿La sub sección ' . $val_sub_secciones . ' de la sección ' . $key . ' existe?');
                pr($pr_existe);
            }
        }
    }

    public function test_get_elemento_seccion_hijo() {
        $elemento_seccion_padre = [2 => 6, 3 => 4]; //Sub sección padre => cantidad de hijos
        foreach ($elemento_seccion_padre as $key_sub => $cantidad) {
            pr('<h3>Prueba para subsección padre ' . $key_sub . '</h3>');
            $result = $this->sm->get_elemento_seccion_hijo($key_sub);
            $cuenta = count($result);
            $pr_is_array = $this->unit->run($result, 'is_array', '¿Retorna una array?');
            $pr_existe = $this->unit->run($cuenta == $cantidad, true, '¿La subsección padre ' . $key_sub . ' contiene ' . $cantidad . ' registros?');
            pr($pr_is_array);
            pr($pr_existe);
        }
    }

    public function test_get_elementos_catalogos() {

        $catalogos_cantidad = [33 => 4, 32 => 4, 22 => 3, 5 => 1, 'sjj' => 0]; //Catálogo => cantidad de registros a mostrar por catálogo y las condicione 
        $data['select'] = ['ec.id_elemento_catalogo', 'ec.nombre', 'ec.id_catalogo', 'ec.id_catalogo_elemento_padre',
            'ec.label', 'ec.is_validado', 'ec.activo']; //Select

        $data['order_by'] = ['ec.orden', 'ec.nombre'];
        $where[33] = ['ec.id_catalogo' => 33, 'ec.id_elemento_catalogo in(522,523,524,525)' => null];
        $where[32] = ['ec.id_catalogo' => 32, 'ec.id_elemento_catalogo in(513,514,515,516)' => null];
        $where[22] = ['ec.id_catalogo' => 22, '' => null];
        $where['sjj'] = ['ec.id_catalogo' => 'sjj', '' => null];
        $where[5] = ['ec.id_catalogo' => 5, 'ec.id_elemento_catalogo in(142,142)' => null];

        foreach ($where as $key_where => $where_value) {
            $data['where'] = $where_value;
            $result_ec = $this->sm->get_elementos_catalogos($data);
            pr('<h3>Prueba para validar elmentos del catálogo ' . $key_where . '</h3>');
            $pr_is_array = $this->unit->run($result_ec, 'is_array', '¿El retorno es un array?');
            pr($pr_is_array);
            $cuenta = count($result_ec) == $catalogos_cantidad[$key_where];
            $pr_cantidad = $this->unit->run($cuenta, TRUE, '¿El catalogo ' . $key_where . ' contiene ' . $catalogos_cantidad[$key_where] . ' elementos?');
            pr($pr_cantidad);
        }
    }

    public function test_get_sub_secciones() {
        $cantidad_campos = 2;
        $id_docente = 1;
        $id_seccion = 3;
        $sub_secciones = [19, 20];
        $result_dad = $this->fm->get_sub_secciones($id_docente, $id_seccion, null);
        $count = count($result_dad);
        pr('<h3>Prueba para validar información de un registro de censo con id ' . $id_seccion . ' del docente con id ' . $id_docente . '</h3>');
        $pr_is_array = $this->unit->run($result_dad, 'is_array', '¿Es una array el retorno?');
        pr($pr_is_array);
        $pr_cantidad = $this->unit->run($count == $cantidad_campos, TRUE, 
		'¿El docete con id ' . $id_docente . ' ha registrado información en los formularios con subsecciones 19 y 20?'
		);
        pr($pr_cantidad);
        $secciones_elementos = [];
        foreach ($result_dad as $value_s) {
            $secciones_elementos[$value_s['id_elemento_seccion']] = $value_s['label'];
        }
//        pr($secciones_elementos);
        foreach ($sub_secciones as $value_) {
            $existe = isset($secciones_elementos[$value_]);
            $pr_is_existe = $this->unit->run($existe, TRUE, '¿La sub sección con id ' . $value_ . ' pertenece a la seccion ' . $id_seccion);
            pr($pr_is_existe);
        }
    }

    public function test_get_datos_actividad_docente() {
        $cantidad_campos = 8;
        $id_docente = 1;
        $id_seccion = 3;
        $result_dad = $this->fm->get_datos_actividad_docente($id_docente, null, $id_seccion, null);
		pr($result_dad);
        $count = count($result_dad);
        pr('<h3>Prueba para validar información de un registro de censo con id ' . $id_seccion . ' del docente con id ' . $id_docente . '</h3>');
        $pr_is_array = $this->unit->run($result_dad, 'is_array', '¿Es una array el retorno?');
        pr($pr_is_array);
        $pr_cantidad = $this->unit->run($count == $cantidad_campos, TRUE, '¿La cantidad de campos del registro censo con id  ' . $id_seccion . ' contiene ' . $cantidad_campos . ' campos?');
        pr($pr_cantidad);
    }

    /**
     * Datos generales e IMSS del docente
     */
    public function test_get_datos_generales() {
        $this->load->model('Docente_model', 'dom');
        $datos_sesion = $this->get_datos_sesion(); //Obtiene los datos de la sesión
        $id_docente = $datos_sesion[En_datos_sesion::ID_DOCENTE];
        $result = $this->dom->get_datos_generales($id_docente);
        $cantidad_datos = 24;

        $pr_inicio_sesion = $this->unit->run($datos_sesion, TRUE, '¿Sesión iniciada?');
        pr($pr_inicio_sesion);
        $pr_inicio_sesion = $this->unit->run($result, 'is_array', '¿Es un array?');
        pr($pr_inicio_sesion);
        $pr_inicio_sesion = $this->unit->run(count($result) > 0, TRUE, '¿Contiene información del docente con id ' . $id_docente . '?');
        pr($pr_inicio_sesion);
        if (!is_null($result) and ! empty($result)) {
//            $datos_docente = $result[0];
            $pr_inicio_sesion = $this->unit->run($result['matricula'] == '311091488', TRUE, '¿La matrícula 311091488 del docente es correcta?');
            pr($pr_inicio_sesion);
            $pr_inicio_sesion = $this->unit->run($result['curp'] == 'AEGD880623MDFNLN07', TRUE, '¿El curp AEGD880623MDFNLN07 del docente es correcto?');
            pr($pr_inicio_sesion);
        }
//        pr($result);
    }

    public function test_get_historico_datos_generales() {
        $this->load->model('Docente_model', 'dom');
        $datos_sesion = $this->get_datos_sesion(); //Obtiene los datos de la sesión
        $id_docente = $datos_sesion[En_datos_sesion::ID_DOCENTE];
        $result = $this->dom->get_historico_datos_generales($id_docente);
        $cantidad_datos = 21;

        $pr_inicio_sesion = $this->unit->run($datos_sesion, TRUE, '¿Sesión iniciada?');
        pr($pr_inicio_sesion);
        $pr_inicio_sesion = $this->unit->run($result, 'is_array', '¿Es un array?');
        pr($pr_inicio_sesion);
        $pr_inicio_sesion = $this->unit->run(count($result) == $cantidad_datos, true, '¿Contiene ' . $cantidad_datos . ' registros?');
        pr($pr_inicio_sesion);

        if (!is_null($result) and ! empty($result)) {
//            $datos_docente = $result[0];
            $pr_inicio_sesion = $this->unit->run($result['clave_categoria'] == '21390080', TRUE, '¿La categoría 21390080 del docente es correcta?');
            pr($pr_inicio_sesion);
            $pr_inicio_sesion = $this->unit->run($result['clave_departamental'] == '09NC012500', TRUE, '¿El departamento 09NC012500 del docente es correcto?');
            pr($pr_inicio_sesion);
        }
    }

    public function test_get_imagen_perfil() {
        $this->load->model('Docente_model', 'dom');
        $datos_sesion = $this->get_datos_sesion(); //Obtiene los datos de la sesión
        $id_docente = $datos_sesion[En_datos_sesion::ID_DOCENTE];
        $result = $this->dom->get_imagen_perfil($id_docente);
        $pr_inicio_sesion = $this->unit->run($datos_sesion, TRUE, '¿Sesión iniciada?');
        pr($pr_inicio_sesion);
        $pr_inicio_sesion = $this->unit->run($result, 'is_array', '¿Es un array?');
        pr($pr_inicio_sesion);
        $pr_inicio_sesion = $this->unit->run(count($result) > 0, TRUE, '¿Mantiene asociada una imagen de perfil el docente con ' . $id_docente . '?');
        pr($pr_inicio_sesion);
        if (!is_null($result) and ! empty($result)) {
//            $datos_docente = $result[0];
            $pr_inicio_sesion = $this->unit->run($result['matricula'] == '311091488', TRUE, '¿La matrícula 311091488 del docente es correcta?');
            pr($pr_inicio_sesion);
            $pr_inicio_sesion = $this->unit->run($result['nombre_fisico'] == '311091488_1500923059', TRUE, '¿El docente tiene la imagen ' . $result['nombre_fisico'] . '.' . $result['extencion'] . ' asociada?');
            pr($pr_inicio_sesion);
        }
    }

    public function test_update_datos_formulario() {
        $get_data = new datos_update_insert();
        $datos_update = $get_data->get_update();
        $result = $this->fm->update_datos_formulario($datos_update['datos_post_formulario'], $datos_update['datos_formulario'], $datos_update['elemento_seccion'], $datos_update['id_censo'], $datos_update['id_file_comprobante'], $datos_update['nombe_file'], $datos_update['ruta_archivo'], $datos_update['datos_files'], $datos_update['detalle_censo']);
        $pr_inicio_sesion = $this->unit->run($result, 'is_array', '¿Es un array el retorno?');
        pr($pr_inicio_sesion);
        $pr_inicio_sesion = $this->unit->run($result['tp_msg'] == 'success', true, $result['mensaje']);
        pr($pr_inicio_sesion);
    }

    public function test_insert_datos_formulario() {
        $get_data = new datos_update_insert();
        $datos_insert = $get_data->get_insert();
        $result = $this->fm->insert_datos_formulario($datos_insert['datos_post_formulario'], $datos_insert['deficion_formulario'], $datos_insert['datos_files']);
        $pr_inicio_sesion = $this->unit->run($result, 'is_array', '¿Es un array el retorno?');
        pr($pr_inicio_sesion);
        $pr_inicio_sesion = $this->unit->run($result['tp_msg'] == 'success', true, $result['mensaje']);
        pr($pr_inicio_sesion);
    }

}

class datos_update_insert {

    public function get_update() {
        $tmp_campos_formulario = (array) json_decode('[{"id_campos_formulario":2,"tooltip":"","placeholder":"","display":true,"nueva_linea":false,"attributes_extra":"","regla_notificacion":"","excepciones_opciones":"522,523,524,525","campos_dependientes":"","id_formulario":2,"id_elemento_seccion":19,"lbl_formulario":"T\u00e9cnicos","nom_formulario":"pnmas_tecnicos","ruta_file_js":null,"id_campo":34,"lb_campo":"Rol que desempe\u00f1a:","nom_campo":"role_desempenia","icono":"user","id_tipo_campo":3,"nom_tipo_campo":"dropdown","id_tipo_dato":2,"nom_tipo_dato":"int","orden":1,"rules":"{\"field\":\"role_desempenia\",\"label\":\"Rol que desempe\\u00f1a:\",\"rules\":\"required\"}","id_callback_form":null,"ruta_js_form":null,"funcion_js_form":null,"tipo_evento_form":null,"id_callback":null,"ruta_js":null,"funcion_js":null,"tipo_evento":null,"id_catalogo":33,"nom_catalogo":"role_docente","editable":true,"is_precarga":true,"valor":"525","is_precarga_registro_sistema":false,"id_censo":3},{"id_campos_formulario":3,"tooltip":"","placeholder":"","display":true,"nueva_linea":false,"attributes_extra":"","regla_notificacion":"","excepciones_opciones":"513,514,515,516","campos_dependientes":"","id_formulario":2,"id_elemento_seccion":19,"lbl_formulario":"T\u00e9cnicos","nom_formulario":"pnmas_tecnicos","ruta_file_js":null,"id_campo":20,"lb_campo":"Instituci\u00f3n que avala:","nom_campo":"institucion_avala","icono":"","id_tipo_campo":4,"nom_tipo_campo":"dropdown_otro","id_tipo_dato":2,"nom_tipo_dato":"int","orden":2,"rules":"{\"field\":\"institucion_avala\",\"label\":\"Instituci\\u00f3n que avala:\",\"rules\":\"required\"}","id_callback_form":null,"ruta_js_form":null,"funcion_js_form":null,"tipo_evento_form":null,"id_callback":null,"ruta_js":null,"funcion_js":null,"tipo_evento":null,"id_catalogo":32,"nom_catalogo":"institucion_avala","editable":true,"is_precarga":true,"valor":"516","is_precarga_registro_sistema":false,"id_censo":3},{"id_campos_formulario":4,"tooltip":"","placeholder":"","display":true,"nueva_linea":false,"attributes_extra":"","regla_notificacion":"","excepciones_opciones":null,"campos_dependientes":"","id_formulario":2,"id_elemento_seccion":19,"lbl_formulario":"T\u00e9cnicos","nom_formulario":"pnmas_tecnicos","ruta_file_js":null,"id_campo":13,"lb_campo":"Duraci\u00f3n:","nom_campo":"duracion","icono":"hourglass-half ","id_tipo_campo":3,"nom_tipo_campo":"dropdown","id_tipo_dato":2,"nom_tipo_dato":"int","orden":3,"rules":"{\"field\":\"duracion\",\"label\":\"Duraci\\u00f3n:\",\"rules\":\"required\"}","id_callback_form":null,"ruta_js_form":null,"funcion_js_form":null,"tipo_evento_form":null,"id_callback":2,"ruta_js":"{\"duracion_1\":\"componentes\/duracion.js\"}","funcion_js":"{\"onchange\" : \"visualizar_campos(this);\"}","tipo_evento":"","id_catalogo":22,"nom_catalogo":"duracion","editable":true,"is_precarga":null,"valor":"36","is_precarga_registro_sistema":false,"id_censo":3},{"id_campos_formulario":5,"tooltip":"","placeholder":"","display":false,"nueva_linea":false,"attributes_extra":"","regla_notificacion":"","excepciones_opciones":null,"campos_dependientes":"","id_formulario":2,"id_elemento_seccion":19,"lbl_formulario":"T\u00e9cnicos","nom_formulario":"pnmas_tecnicos","ruta_file_js":null,"id_campo":19,"lb_campo":"Horas:","nom_campo":"horas","icono":"hourglass-half ","id_tipo_campo":2,"nom_tipo_campo":"numeric","id_tipo_dato":2,"nom_tipo_dato":"int","orden":4,"rules":"{\"field\":\"horas\",\"label\":\"Horas:\",\"rules\":\"callback_required_depends[duracion~254]\"}","id_callback_form":null,"ruta_js_form":null,"funcion_js_form":null,"tipo_evento_form":null,"id_callback":null,"ruta_js":null,"funcion_js":null,"tipo_evento":null,"id_catalogo":null,"nom_catalogo":null,"editable":null,"is_precarga":true,"valor":"NULL","is_precarga_registro_sistema":false,"id_censo":3},{"id_campos_formulario":6,"tooltip":"","placeholder":"","display":true,"nueva_linea":true,"attributes_extra":"","regla_notificacion":"","excepciones_opciones":null,"campos_dependientes":"","id_formulario":2,"id_elemento_seccion":19,"lbl_formulario":"T\u00e9cnicos","nom_formulario":"pnmas_tecnicos","ruta_file_js":null,"id_campo":14,"lb_campo":"Fecha de inicio:","nom_campo":"fecha_inicio","icono":"calendar-minus-o","id_tipo_campo":9,"nom_tipo_campo":"date","id_tipo_dato":4,"nom_tipo_dato":"date","orden":5,"rules":"{\"field\":\"fecha_inicio\",\"label\":\"Fecha de inicio:\",\"rules\":\"required|valida_fecha_inicio_menor_fecha_fin[fecha_termino~El campo {field} debe ser menor que fecha de termino.]\"}","id_callback_form":null,"ruta_js_form":null,"funcion_js_form":null,"tipo_evento_form":null,"id_callback":null,"ruta_js":null,"funcion_js":null,"tipo_evento":null,"id_catalogo":null,"nom_catalogo":null,"editable":null,"is_precarga":null,"valor":"09\/05\/2017","is_precarga_registro_sistema":false,"id_censo":3},{"id_campos_formulario":7,"tooltip":"","placeholder":"","display":true,"nueva_linea":false,"attributes_extra":"","regla_notificacion":"","excepciones_opciones":null,"campos_dependientes":"","id_formulario":2,"id_elemento_seccion":19,"lbl_formulario":"T\u00e9cnicos","nom_formulario":"pnmas_tecnicos","ruta_file_js":null,"id_campo":15,"lb_campo":"Fecha de t\u00e9rmino:","nom_campo":"fecha_termino","icono":"calendar-plus-o ","id_tipo_campo":9,"nom_tipo_campo":"date","id_tipo_dato":4,"nom_tipo_dato":"date","orden":6,"rules":"{\"field\":\"fecha_termino\",\"label\":\"Fecha de t\\u00e9rmino:\",\"rules\":\"required|ciclos_clinicos_fecha_maxima[01\\\/01\\\/2018]|valida_fecha_fin_mayor_inicio[fecha_inicio~El campo {field} debe ser mayor que fecha de inicio.]\"}","id_callback_form":null,"ruta_js_form":null,"funcion_js_form":null,"tipo_evento_form":null,"id_callback":null,"ruta_js":null,"funcion_js":null,"tipo_evento":null,"id_catalogo":null,"nom_catalogo":null,"editable":null,"is_precarga":null,"valor":"29\/12\/2017","is_precarga_registro_sistema":false,"id_censo":3},{"id_campos_formulario":8,"tooltip":"","placeholder":"","display":true,"nueva_linea":false,"attributes_extra":"","regla_notificacion":"","excepciones_opciones":"142","campos_dependientes":"","id_formulario":2,"id_elemento_seccion":19,"lbl_formulario":"T\u00e9cnicos","nom_formulario":"pnmas_tecnicos","ruta_file_js":null,"id_campo":39,"lb_campo":"Tipo de comprobante:","nom_campo":"tipo_comprobante","icono":"vcard","id_tipo_campo":3,"nom_tipo_campo":"dropdown","id_tipo_dato":2,"nom_tipo_dato":"int","orden":7,"rules":"{\"field\":\"tipo_comprobante\",\"label\":\"Tipo de comprobante:\",\"rules\":\"required\"}","id_callback_form":null,"ruta_js_form":null,"funcion_js_form":null,"tipo_evento_form":null,"id_callback":null,"ruta_js":null,"funcion_js":null,"tipo_evento":null,"id_catalogo":5,"nom_catalogo":"tipo_comprobante","editable":true,"is_precarga":true,"valor":"142","is_precarga_registro_sistema":false,"id_censo":3},{"id_campos_formulario":9,"tooltip":"","placeholder":"","display":true,"nueva_linea":false,"attributes_extra":"","regla_notificacion":"","excepciones_opciones":null,"campos_dependientes":"","id_formulario":2,"id_elemento_seccion":19,"lbl_formulario":"T\u00e9cnicos","nom_formulario":"pnmas_tecnicos","ruta_file_js":null,"id_campo":35,"lb_campo":"Sede:","nom_campo":"sede","icono":"","id_tipo_campo":3,"nom_tipo_campo":"dropdown","id_tipo_dato":3,"nom_tipo_dato":"text","orden":8,"rules":"{\"field\":\"sede\",\"label\":\"Sede:\",\"rules\":\"required\"}","id_callback_form":null,"ruta_js_form":null,"funcion_js_form":null,"tipo_evento_form":null,"id_callback":11,"ruta_js":"{\"localizador_sede_2\":\"rama_organica\/rama_organica.js\", \"localizador_sede_1\":\"componentes\/localizador_sede.js\"}","funcion_js":"{\"onclick\" : \"carga_localizador_sede(this);\", \"onchange\":\"get_inicializa({seleccion:\'radio\',agrupacion:true,anio:2017,tipo_unidad:true,nivel_atencion:1,columnas:[\'region\', \'delegacion\', \'cve_unidad\', \'nombre_unidad\'], div_resultado:\'#modal_cuerpo\'}, this)\", \"class\":\"localiza_sede\",\"data-target\":\"#my_modal\",\"data-toggle\":\"modal\"}","tipo_evento":"{}","id_catalogo":null,"nom_catalogo":null,"editable":null,"is_precarga":true,"valor":"37EA010000","is_precarga_registro_sistema":false,"id_censo":3}]');
        $datos_post_formulario = [];
        foreach ($tmp_campos_formulario as $value) {
            $datos_post_formulario[] = (array) $value;
        }
        $get_data['datos_post_formulario'] = (array) json_decode('{"id_elementos_seccion":"19","formulario":"2","censo_regstro":"Mw","role_desempenia":"525","institucion_avala_auxiliar":"","institucion_avala":"516","duracion":"254","horas":"34","fecha_inicio":"11\/05\/2017","fecha_termino":"20\/12\/2017","tipo_comprobante":"142","sede":"37UA390000","folio_comprobante":"ZBX-2017-XYZ","extension":"pdf","comprobante":"311091488_1504020548","id_file_comprobante":"MTU3"}');
        $get_data['datos_formulario'] = $datos_post_formulario;
        $get_data['elemento_seccion'] = '19';
        $get_data['id_censo'] = '3';
        $get_data['id_file_comprobante'] = '157';
        $get_data['nombe_file'] = null;
        $get_data['ruta_archivo'] = NULL;
        $get_data['datos_files'] = (array) json_decode('[]');
        $get_data['detalle_censo'] = (array) json_decode('{"id_censo":3,"is_carga_sistema":false,"folio":"ZBX-2017","id_file":157,"nombre_comprobante":"311091488_1504020548","ruta":"\/assets\/us\/uploads\/311091488\/","nombre_extencion":"pdf","id_validacion_registro":1,"nombre_validacion":"Registro usuario","formulario_registros":"{\"role_desempenia\":\"525\",\"institucion_avala\":\"516\",\"duracion\":\"36\",\"horas\":\"NULL\",\"fecha_inicio\":\"09\\\/05\\\/2017\",\"fecha_termino\":\"29\\\/12\\\/2017\",\"tipo_comprobante\":\"142\",\"sede\":\"37EA010000\",\"id_formulario\":2}"}');
        return $get_data;
    }

    public function get_insert() {
        $tmp_campos_formulario = (array) json_decode('[{"id_campos_formulario":2,"tooltip":"","placeholder":"","display":true,"nueva_linea":false,"attributes_extra":"","regla_notificacion":"","excepciones_opciones":"522,523,524,525","campos_dependientes":"","id_formulario":2,"id_elemento_seccion":19,"lbl_formulario":"T\u00e9cnicos","nom_formulario":"pnmas_tecnicos","ruta_file_js":null,"id_campo":34,"lb_campo":"Rol que desempe\u00f1a:","nom_campo":"role_desempenia","icono":"user","id_tipo_campo":3,"nom_tipo_campo":"dropdown","id_tipo_dato":2,"nom_tipo_dato":"int","orden":1,"rules":"{\"field\":\"role_desempenia\",\"label\":\"Rol que desempe\\u00f1a:\",\"rules\":\"required\"}","id_callback_form":null,"ruta_js_form":null,"funcion_js_form":null,"tipo_evento_form":null,"id_callback":null,"ruta_js":null,"funcion_js":null,"tipo_evento":null,"id_catalogo":33,"nom_catalogo":"role_docente","editable":true,"is_precarga":true},{"id_campos_formulario":3,"tooltip":"","placeholder":"","display":true,"nueva_linea":false,"attributes_extra":"","regla_notificacion":"","excepciones_opciones":"513,514,515,516","campos_dependientes":"","id_formulario":2,"id_elemento_seccion":19,"lbl_formulario":"T\u00e9cnicos","nom_formulario":"pnmas_tecnicos","ruta_file_js":null,"id_campo":20,"lb_campo":"Instituci\u00f3n que avala:","nom_campo":"institucion_avala","icono":"","id_tipo_campo":4,"nom_tipo_campo":"dropdown_otro","id_tipo_dato":2,"nom_tipo_dato":"int","orden":2,"rules":"{\"field\":\"institucion_avala\",\"label\":\"Instituci\\u00f3n que avala:\",\"rules\":\"required\"}","id_callback_form":null,"ruta_js_form":null,"funcion_js_form":null,"tipo_evento_form":null,"id_callback":null,"ruta_js":null,"funcion_js":null,"tipo_evento":null,"id_catalogo":32,"nom_catalogo":"institucion_avala","editable":true,"is_precarga":true},{"id_campos_formulario":4,"tooltip":"","placeholder":"","display":true,"nueva_linea":false,"attributes_extra":"","regla_notificacion":"","excepciones_opciones":null,"campos_dependientes":"","id_formulario":2,"id_elemento_seccion":19,"lbl_formulario":"T\u00e9cnicos","nom_formulario":"pnmas_tecnicos","ruta_file_js":null,"id_campo":13,"lb_campo":"Duraci\u00f3n:","nom_campo":"duracion","icono":"hourglass-half ","id_tipo_campo":3,"nom_tipo_campo":"dropdown","id_tipo_dato":2,"nom_tipo_dato":"int","orden":3,"rules":"{\"field\":\"duracion\",\"label\":\"Duraci\\u00f3n:\",\"rules\":\"required\"}","id_callback_form":null,"ruta_js_form":null,"funcion_js_form":null,"tipo_evento_form":null,"id_callback":2,"ruta_js":"{\"duracion_1\":\"componentes\/duracion.js\"}","funcion_js":"{\"onchange\" : \"visualizar_campos(this);\"}","tipo_evento":"","id_catalogo":22,"nom_catalogo":"duracion","editable":true,"is_precarga":null},{"id_campos_formulario":5,"tooltip":"","placeholder":"","display":false,"nueva_linea":false,"attributes_extra":"","regla_notificacion":"","excepciones_opciones":null,"campos_dependientes":"","id_formulario":2,"id_elemento_seccion":19,"lbl_formulario":"T\u00e9cnicos","nom_formulario":"pnmas_tecnicos","ruta_file_js":null,"id_campo":19,"lb_campo":"Horas:","nom_campo":"horas","icono":"hourglass-half ","id_tipo_campo":2,"nom_tipo_campo":"numeric","id_tipo_dato":2,"nom_tipo_dato":"int","orden":4,"rules":"{\"field\":\"horas\",\"label\":\"Horas:\",\"rules\":\"callback_required_depends[duracion~254]\"}","id_callback_form":null,"ruta_js_form":null,"funcion_js_form":null,"tipo_evento_form":null,"id_callback":null,"ruta_js":null,"funcion_js":null,"tipo_evento":null,"id_catalogo":null,"nom_catalogo":null,"editable":null,"is_precarga":true},{"id_campos_formulario":6,"tooltip":"","placeholder":"","display":true,"nueva_linea":true,"attributes_extra":"","regla_notificacion":"","excepciones_opciones":null,"campos_dependientes":"","id_formulario":2,"id_elemento_seccion":19,"lbl_formulario":"T\u00e9cnicos","nom_formulario":"pnmas_tecnicos","ruta_file_js":null,"id_campo":14,"lb_campo":"Fecha de inicio:","nom_campo":"fecha_inicio","icono":"calendar-minus-o","id_tipo_campo":9,"nom_tipo_campo":"date","id_tipo_dato":4,"nom_tipo_dato":"date","orden":5,"rules":"{\"field\":\"fecha_inicio\",\"label\":\"Fecha de inicio:\",\"rules\":\"required|valida_fecha_inicio_menor_fecha_fin[fecha_termino~El campo {field} debe ser menor que fecha de termino.]\"}","id_callback_form":null,"ruta_js_form":null,"funcion_js_form":null,"tipo_evento_form":null,"id_callback":null,"ruta_js":null,"funcion_js":null,"tipo_evento":null,"id_catalogo":null,"nom_catalogo":null,"editable":null,"is_precarga":null},{"id_campos_formulario":7,"tooltip":"","placeholder":"","display":true,"nueva_linea":false,"attributes_extra":"","regla_notificacion":"","excepciones_opciones":null,"campos_dependientes":"","id_formulario":2,"id_elemento_seccion":19,"lbl_formulario":"T\u00e9cnicos","nom_formulario":"pnmas_tecnicos","ruta_file_js":null,"id_campo":15,"lb_campo":"Fecha de t\u00e9rmino:","nom_campo":"fecha_termino","icono":"calendar-plus-o ","id_tipo_campo":9,"nom_tipo_campo":"date","id_tipo_dato":4,"nom_tipo_dato":"date","orden":6,"rules":"{\"field\":\"fecha_termino\",\"label\":\"Fecha de t\\u00e9rmino:\",\"rules\":\"required|ciclos_clinicos_fecha_maxima[01\\\/01\\\/2018]|valida_fecha_fin_mayor_inicio[fecha_inicio~El campo {field} debe ser mayor que fecha de inicio.]\"}","id_callback_form":null,"ruta_js_form":null,"funcion_js_form":null,"tipo_evento_form":null,"id_callback":null,"ruta_js":null,"funcion_js":null,"tipo_evento":null,"id_catalogo":null,"nom_catalogo":null,"editable":null,"is_precarga":null},{"id_campos_formulario":8,"tooltip":"","placeholder":"","display":true,"nueva_linea":false,"attributes_extra":"","regla_notificacion":"","excepciones_opciones":"142","campos_dependientes":"","id_formulario":2,"id_elemento_seccion":19,"lbl_formulario":"T\u00e9cnicos","nom_formulario":"pnmas_tecnicos","ruta_file_js":null,"id_campo":39,"lb_campo":"Tipo de comprobante:","nom_campo":"tipo_comprobante","icono":"vcard","id_tipo_campo":3,"nom_tipo_campo":"dropdown","id_tipo_dato":2,"nom_tipo_dato":"int","orden":7,"rules":"{\"field\":\"tipo_comprobante\",\"label\":\"Tipo de comprobante:\",\"rules\":\"required\"}","id_callback_form":null,"ruta_js_form":null,"funcion_js_form":null,"tipo_evento_form":null,"id_callback":null,"ruta_js":null,"funcion_js":null,"tipo_evento":null,"id_catalogo":5,"nom_catalogo":"tipo_comprobante","editable":true,"is_precarga":true},{"id_campos_formulario":9,"tooltip":"","placeholder":"","display":true,"nueva_linea":false,"attributes_extra":"","regla_notificacion":"","excepciones_opciones":null,"campos_dependientes":"","id_formulario":2,"id_elemento_seccion":19,"lbl_formulario":"T\u00e9cnicos","nom_formulario":"pnmas_tecnicos","ruta_file_js":null,"id_campo":35,"lb_campo":"Sede:","nom_campo":"sede","icono":"","id_tipo_campo":3,"nom_tipo_campo":"dropdown","id_tipo_dato":3,"nom_tipo_dato":"text","orden":8,"rules":"{\"field\":\"sede\",\"label\":\"Sede:\",\"rules\":\"required\"}","id_callback_form":null,"ruta_js_form":null,"funcion_js_form":null,"tipo_evento_form":null,"id_callback":11,"ruta_js":"{\"localizador_sede_2\":\"rama_organica\/rama_organica.js\", \"localizador_sede_1\":\"componentes\/localizador_sede.js\"}","funcion_js":"{\"onclick\" : \"carga_localizador_sede(this);\", \"onchange\":\"get_inicializa({seleccion:\'radio\',agrupacion:true,anio:2017,tipo_unidad:true,nivel_atencion:1,columnas:[\'region\', \'delegacion\', \'cve_unidad\', \'nombre_unidad\'], div_resultado:\'#modal_cuerpo\'}, this)\", \"class\":\"localiza_sede\",\"data-target\":\"#my_modal\",\"data-toggle\":\"modal\"}","tipo_evento":"{}","id_catalogo":null,"nom_catalogo":null,"editable":null,"is_precarga":true}]');
        $datos_post_formulario = [];
        foreach ($tmp_campos_formulario as $value) {
            $datos_post_formulario[] = (array) $value;
        }
        $get_data['deficion_formulario'] = $datos_post_formulario;
        $get_data['datos_post_formulario'] = (array) json_decode('{"id_elementos_seccion":"19","formulario":"2","censo_regstro":"","role_desempenia":"522","institucion_avala_auxiliar":"","institucion_avala":"515","duracion":"36","horas":"","fecha_inicio":"03\/03\/2014","fecha_termino":"20\/11\/2014","tipo_comprobante":"142","sede":"36EA010000","folio_comprobante":"PRUEBA-UNIT-XYZ","extension":"pdf","comprobante":"311091488_1504115528","id_docente":1,"id_validacion_registro":1,"is_carga_sistema":false,"ruta_file":".\/assets\/us\/uploads\/311091488\/"}');
        $get_data['datos_files'] = (array) json_decode('[]');
        return $get_data;
    }

}
