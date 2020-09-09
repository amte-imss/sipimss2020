<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Secciones_model extends MY_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    /**
     * @author LEAS
     * @Fecha 23/05/2017
     * @param type $id_elemento_seccion id del elemento sección a buscar
     * @param type $top_tree tope de la sima, o profundidad, es decir,
     * 0 = el elemento raíz; 1= el segundo elemento después del inicio,
     * 2=tercer elemento y así, es preciso señalar que dependera del tamaño
     * de la ramá lo que indica los niveles de profundidad
     */
    public function get_elemento_seccion_ramas($id_elemento_seccion, $top_tree = 0) {
        $resut = $this->get_arbol_secciones_atras_postgres($id_elemento_seccion); //Obtiene todo el recorrido de la ramá, desde el elemneto solicitado
        $text_rama = '';
        if (!empty($resut)) {//Si top es mayor a cero, quitar de rama nivel de inicio a fin
            $text_rama = $text_tmp = $resut[0]['arbol_secciones'];
            if ($top_tree > 0) {//Valida el nivel hasta el que deberia llegar el sistema
                $text_rama = '';
                $rompe_rama = explode('>', $text_tmp);
                $count = count($rompe_rama);
                $separador = '';
                for ($i = $top_tree; $i < $count; $i++) {
                    $text_rama .= $separador . $rompe_rama[$i];
                    $separador = ' > ';
                }
            }
        }
//        pr($text_rama);
        return $text_rama;
    }

    /**
     *
     * @Fecha 04/05/2017
     * @author LEAS
     * @param type $id_elemento_seccion id del elemento sección, recorre el árbol de
     * elementos desde la ramá solicitada hacia atrás, es decir, desde el elemento_seccion solicitado
     * hasta encontrar su padre, el padre de su padre y así hasta llegar al nivel cero (el elemento ya no tiene padre)
     */
    public function get_arbol_secciones_atras($id_elemento_seccion) {
        $select = "
                    WITH RECURSIVE path(nombre, path, parent, id_seccion, id_elemento_seccion, id_catalogo_elemento_padre,
                    label) AS
                    (
                        SELECT nombre, '/', NULL, id_seccion, id_elemento_seccion, id_catalogo_elemento_padre, label FROM
                        catalogo.elementos_seccion esinit WHERE esinit.id_elemento_seccion=" . $id_elemento_seccion
                . "  UNION
                            SELECT
                            esr.nombre,
                            parentpath.path || CASE parentpath.path WHEN '/' THEN '' ELSE '/' END || esr.nombre,
                            parentpath.path,
                            esr.id_seccion,
                            esr.id_elemento_seccion,
                            esr.id_catalogo_elemento_padre,
                            esr.label
                            FROM catalogo.elementos_seccion esr, path as parentpath
                            WHERE esr.id_elemento_seccion = parentpath.id_catalogo_elemento_padre
                    )
                    SELECT * FROM path;
                ";

        $query_result = $this->db->query($select)->result();
//        pr($this->db->last_query());
        return $query_result;
    }

    /**
     *
     * @Fecha 19/06/2017
     * @author LEAS
     * @param type $id_elemento_seccion id del elemento sección, recorre el árbol de
     * elementos desde la ramá solicitada hacia atrás, es decir, desde el elemento_seccion solicitado
     * hasta encontrar su padre, el padre de su padre y así hasta llegar al nivel cero (el elemento ya no tiene padre)
     */
    public function get_arbol_secciones_atras_postgres($id_elemento_seccion) {
        $select = "catalogo.get_arbol_secciones_pinta_padres(id_elemento_seccion) arbol_secciones";
        $this->db->select($select);
        $this->db->where('id_elemento_seccion', $id_elemento_seccion);
        $resultado = $this->db->get('catalogo.elementos_seccion');
//        pr($this->db->last_query());
        return $resultado->result_array();
    }

    /**
     *
     * @Fecha 15/05/2017
     * @author LEAS
     * @param type $id_seccion
     * @return  Description Regresa los datos referentes a una sección
     * @modificado CPMS 18/07/2017
     */
    public function get_seccion($id_seccion = null, $order_by = null) {
        if (!is_null($id_seccion)) {
            $this->db->where('id_seccion', $id_seccion);
        }
        if (!is_null($order_by)) {
            $this->db->order_by($order_by);
        }
        $query = $this->db->get('catalogo.secciones');
        return $query->result_array();
    }

    /**
     *
     * @Fecha 04/05/2017
     * @author LEAS
     * @param type $seccion en contexto
     * @return type Array Description Obtiene todos los "elementos_seccion" que se encuentran
     * relacionados con una sección
     */
    public function get_elemento_seccion($seccion) {
        /* select * from ui.formulario
          inner join ui.campos_formulario
          on ui.campos_formulario.id_formulario=ui.formulario.id_formulario
          where ui.formulario.id_formulario=1 */
        $select = array(
            'e.id_elemento_seccion', 'e.label',
        );
        $this->db->select($select); //Agrega select
        $this->db->where('e.id_seccion', $seccion);
        $this->db->where('e.id_catalogo_elemento_padre is null');
        $this->db->where('e.activo', TRUE);
        $this->db->order_by('e.label', 'asc');

        $resultado = $this->db->get('catalogo.elementos_seccion e');

//        pr($this->db->last_query());
        return $resultado->result_array();
    }

    /**
     *
     * @param type $elemento_seccion_padre Identificador de un "elemento_seccion"
     * de la entidad "catalogo.elementos_seccion"
     * @return type Array Elementos_seccion que son hijos de otro elemento
     */
    public function get_elemento_seccion_hijo($elemento_seccion_padre) {
        $select = array(
            'e.id_elemento_seccion', 'e.label', 'e.id_catalogo_elemento_padre'
        );
        $this->db->select($select); //Agrega select
        $this->db->join('catalogo.elementos_seccion cte', 'e.id_catalogo_elemento_padre = cte.id_elemento_seccion');
        $this->db->where('e.id_catalogo_elemento_padre ', $elemento_seccion_padre);
        $this->db->where('cte.activo', TRUE);
        $this->db->order_by('e.label', 'asc');
        $resultado = $this->db->get('catalogo.elementos_seccion e');
//        pr($this->db->last_query());
        return $resultado->result_array();
    }

    public function get_secciones_padres() {

        $resultado = array();
        $secc = '';
        $select = array(
            'id_seccion', 'label'
        );

        $this->db->select($select);
        //$this->db->where('id_seccion', $params['id_seccion']);
        //$this->db->where('id_catalogo_elemento_padre is null');

        $this->db->order_by('label', 'asc');

        $resultado = $this->db->get('catalogo.secciones');

        if ($resultado->num_rows() > 0) {

            $secciones = $resultado->result_array();
            foreach ($secciones as $index => $value) {
                $secc[$value['id_seccion']] = $value['label'];
            }
        }

//
        return $secc;
    }

    /**
     * @fecha 21/04/20717
     * @author LEAS
     * @param type $params parametros para obtener los datos requeridos del catalogo
     * con la siguiente estructura
     * array(
     *      select => array('id_elemento_catalogo', 'nombre',
      'id_catalogo', 'id_catalogo_elemento_padre'),
     *      where => array(ec.id_status_elemento => $valor, 'ec.id_catalogo'=>$valor,
     *                  'ec.id_elemento_catalogo' => $valor),
     *      order_by => array()
     * );
     * @return type
     */
    public function get_elementos_catalogos($params = NULL) {
//        $select = array(
//            'id_elemento_catalogo', 'nombre', 'label',
//            'id_catalogo', 'id_catalogo_elemento_padre',
//            'is_validado'
//        );
//        pr($params);
        if (!empty($params)) {
            $this->db->select($params['select']);
            //Agrega implicitamente si son activos los campos de formulario
            $params = array_merge($params, array('cf.activo' => true, 'f.activo' => TRUE, 'c.activo' => TRUE,
                'tc.activo' => TRUE,
                //Indica que obtenga los valores de catálogo que no es null
                'ec.id_status_elemento in (1,2)' => null
            ));
            if (isset($params['where']['ec.id_catalogo']) and ! is_numeric($params['where']['ec.id_catalogo'])) {
                return [];
            }
            foreach ($params['where'] as $key => $value) {
                if (!is_null($key) AND ! empty($key)) {
                    $this->db->where($key, $value);
                }
            }
            if (isset($params['order_by'])) {
                foreach ($params['order_by'] as $order) {
                    $this->db->order_by($order);
                }
            }
            $resultado = $this->db->get('catalogo.elementos_catalogos ec');
//            pr($this->db->last_query());
//            pr($resultado->result_array());
            return $resultado->result_array();
        }
        return null;
    }

    public function get_elementos_catalogos_por_reglas($params = NULL) {
        if (!empty($params)) {
            $this->db->select($params['select']);
            //Agrega implicitamente si son activos los campos de formulario
            $params = array_merge($params, array(
                //Indica que obtenga los valores de catálogo que no es null
                'e.id_status_elemento in (1,2)' => null
            ));
            if (isset($params['where']['r.id_catalogo_padre']) and ! is_numeric($params['where']['r.id_catalogo_padre'])) {
                return [];
            }
            foreach ($params['where'] as $key => $value) {
                if (!is_null($key) AND ! empty($key)) {
                    $this->db->where($key, $value);
                }
            }
            foreach ($params['where_or'] as $key => $value) {
                if (!is_null($key) AND ! empty($key) && !is_null($value) && $value != '') {
                    $this->db->or_where($key, $value);
                }
            }
            if (isset($params['order_by'])) {
                foreach ($params['order_by'] as $order) {
                    $this->db->order_by($order);
                }
            }else{
                $this->db->order_by('1 asc');
            }
            $this->db->join('catalogo.detalle_dependencias_catalogos d','e.id_elemento_catalogo = d.id_elemento_catalogo_hijo', 'left');
            $this->db->join('catalogo.reglas_dependencia_catalogos r','r.clave_regla_dependecia_catalogo = d.clave_regla_dependecia_catalogo', 'left');
            $resultado = $this->db->get('catalogo.elementos_catalogos e');
           // pr($this->db->last_query());
           // pr($resultado->result_array());
            return $resultado->result_array();
        }
        return null;
    }

    /**
     * @author LEAS
     * @author HILDA
     * @param type $params se define por la siguiente estructura en función de la estructura de la entidad de base de datos
     * ""
     *  * array(
     *      select => array(valores,..., n)
     *      where => array("llave_1"=>"valor_1", ..., "llave_n" => "valor_n"),
     *      order_by => array(valores,..., n)
     * );
     * @deprecated since version 0.1
     * @return type
     */
    public function get_elementos_catalogos_llave_valor($params = NULL) {
        $res_catalogos = $this->get_elementos_catalogos($params);
        $array_result = array();
        foreach ($res_catalogos as $value) {
            //pr($value);
            $array_result[$value['id_elemento_catalogo']] = $value['label'];
        }
        return $array_result;
    }

    /**
     *
     * @param type $params
     */
    public function get_elementos_catalogos_reglas($params = NULL) {
        $res_catalogos = $this->get_elementos_catalogos_por_reglas($params);
        $array_result = array();
        foreach ($res_catalogos as $value) {
            //pr($value);
            $array_result[$value['id_elemento_catalogo']] = $value['label'];
        }
        return $array_result;
    }

    /**
     * @author Clon de LEAS, modificacion HPTZ
     * @fecha 28/06/2017
     * @param type $id_elemento_seccion
     * @return type
     * @modificado CPMS solo revise que los parametros no fueran nulos
     */
    public function get_sub_secciones_c($id_seccion = null, $id_elemento_seccion = null, $arbol_completo = false) {
        $select = array(
            'ES.id_elemento_seccion',
            'catalogo.get_arbol_secciones_pinta_padres("ES"."id_elemento_seccion") AS label',
        );
//            "ES.label",
        $this->db->distinct();
        $this->db->select($select);


        if (!is_null($id_seccion)) {
            $this->db->where('S.id_seccion', $id_seccion);
        }

        if (!is_null($id_elemento_seccion)) {
            $this->db->where('ES.id_elemento_seccion', $id_elemento_seccion);
        }else if(!$arbol_completo)
        {
            $this->db->where('ES.id_catalogo_elemento_padre is null');
        }


        $this->db->join('catalogo.secciones S', 'S.id_seccion = ES.id_seccion');

        $this->db->order_by('label', 'asc');
//        $this->db->order_by('ES.id_elemento_seccion', 'asc');
        $resultado = $this->db->get('catalogo.elementos_seccion ES');

        $resultado = $resultado->result_array();
        // pr($this->db->last_query());
        //return $resultado->result_array();
        $data_estatus = array();

        foreach ($resultado as $row) {

            $data_estatus[$row['id_elemento_seccion']] = $row['label'];
        }

        return $data_estatus;
    }

    /**
     * @author CPMS
     * @date 17/Julio/2017
     * @param id de seccion
     * @param si queremos formularios activos o no
     * @return Devuelve el id de los formularios asociados a un elemento seccion
     */
    public function get_formularios_asociados($id_elem_seccion, $activo = true) {
        $this->db->where('id_elemento_seccion', $id_elem_seccion);
        $this->db->where('activo', $activo);

        $resultado = $this->db->get('ui.formulario');
        return $resultado->result_array();
    }

    /**
     * @author CPMS
     * @date 19/07/2017
     * @param arreglo con los datos para agregar un elemento seccion
     * @return boolean
     */
    public function create_elemento_seccion($values = array()) {
        $salida = false;

        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();

        $insert = $values;
        $id_seccion = $insert['id_seccion'];

        if (isset($insert['id_catalogo_elemento_padre'])) {
            $id_subseccion = $insert['id_catalogo_elemento_padre'];
            $insert['id_catalogo_elemento_padre'] = ($id_subseccion == '') ? null : (int) $id_subseccion;
        }

        if (isset($insert['descripcion'])) {
            $insert['descripcion'] = htmlentities($insert['descripcion']);
        }

        $insert['id_seccion'] = ($id_seccion == '') ? null : (int) $id_seccion;

        $this->db->insert('catalogo.elementos_seccion', $insert);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $salida = true;
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    /**
     * @author CPMS
     * @date 19/07/2017
     * @param arreglo con los datos para actualizar los datos de un elemento seccion
     * @return boolean
     */
    public function update_elemento_seccion($values = array()) {
        $salida = false;

        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();

        $update = $values;

        $id_seccion = $update['id_seccion'];
        $update['id_seccion'] = ($id_seccion == '') ? null : (int) $id_seccion;

        if (isset($update['id_catalogo_elemento_padre'])) {
            $id_subseccion = $update['id_catalogo_elemento_padre'];
            $update['id_catalogo_elemento_padre'] = ($id_subseccion == '') ? null : (int) $id_subseccion;
        }

        if (isset($update['descripcion'])) {
            $update['descripcion'] = $update['descripcion'];
        }

        $this->db->where('id_elemento_seccion', $values['id_elemento_seccion']);
        $this->db->update('catalogo.elementos_seccion', $update);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $salida = true;
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    /**
     * @author CPMS
     * @date 18/07/2017
     * @param id del elemento seccion, default es nulo
     * @return devuelve los datos de la tabla elementos_seccion en un arreglo,
     * si el parametro fue nulo devuelve la lista de todos los registros de esta tabla,
     * si no lo que devuelve sera un arreglo cuya localidad [0] contiene un arreglo con los
     * datos de ese elemento_seccion
     */
    public function get_info_elemento_seccion($id_elemento_seccion = null) {
        if (!is_null($id_elemento_seccion)) {
            $this->db->where('id_elemento_seccion', $id_elemento_seccion);
        }
        $query = $this->db->get('catalogo.elementos_seccion');
        // pr($this->db->last_query());
        return $query->result_array();
    }

    /**
     * @author CPMS
     * @date 24/07/2017
     * @param id_seccion
     * @param id_elemento_seccion
     * @return devuelve las etiquetas de los elementos de seccion que devuelva
     * la funcion get_sub_secciones_c
     */
    public function get_labels($id_seccion = null, $id_elemento_seccion = null) {
        $ramas = $this->get_sub_secciones_c($id_seccion, $id_elemento_seccion);
        $resultado = array();
        foreach ($ramas as $key => $value) {
            $label = $this->get_info_elemento_seccion($key);
            $resultado[$key] = $label[0]['label'];
        }
        return $resultado;
    }

    /**
     * @author CPMS
     * @date 18/09/2017
     * @param id_padre id del elemento de seccion padre
     * @param id_seccion id de la seccion a la que pertenecen
     * @return array con los elementos de secciones cuyo padre tenga el id pasado
     * como parametro
     */
    public function get_elementos_seccion($id_padre = null, $id_seccion = null, $order_by = null) {
        $this->db->flush_cache();
        $this->db->reset_query();

        $select = array('id_elemento_seccion', 'nombre', 'id_catalogo_elemento_padre', 'label', 'id_seccion');
        $this->db->select($select);
        if (!is_null($id_padre)) {
            $this->db->where('id_catalogo_elemento_padre', $id_padre);
        } else {
            $this->db->where('id_catalogo_elemento_padre is NULL');
        }

        if (!is_null($id_seccion)) {
            $this->db->where('id_seccion', $id_seccion);
        }

        if (!is_null($order_by)) {
            $this->db->order_by($order_by);
        }

        $query = $this->db->get('catalogo.elementos_seccion')->result_array();

        $resultado = array();
        foreach ($query as $key => $value) {
            $resultado[$value['id_elemento_seccion']] = $value['label'];
        }

        $this->db->flush_cache();
        $this->db->reset_query();
        return $resultado;
    }

}
