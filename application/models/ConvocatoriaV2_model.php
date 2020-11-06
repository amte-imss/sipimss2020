<?php

/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of ConvocatoriaV2_model
 *
 * @author chrigarc
 */
class ConvocatoriaV2_model extends MY_Model
{

    const UNIDADES = 'unidades', DELEGACIONES = 'delegaciones', N1 = 'N1', N2 = 'N2',
            MAX_NIVELES = 100;

    function __construct()
    {
        parent::__construct();
    }

    public function get_unidad($filtros)
    {
//        pr($filtros);
        if (isset($filtros['fechas']['inicio']))
        {
            $periodo = date('Y', strtotime($filtros['fechas']['inicio']));
        } else
        {
//            pr('no encontro la fecha');
            $periodo = date('Y');
        }
        $unidad = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_unidad_instituto id_unidad', 'A.clave_unidad', 'A.clave_presupuestal', 'A.nombre unidad',
            'B.id_delegacion', 'B.nombre delegacion',
            'C.id_region', 'C.nombre region',
            'D.id_linea_tiempo', 'D.activa'
        );
        $this->db->select($select);
        $this->db->join('catalogo.delegaciones B', 'B.id_delegacion = A.id_delegacion', 'inner');
        $this->db->join('catalogo.regiones C', 'C.id_region = B.id_region', 'inner');
        $this->db->join('workflow.unidades_censo D', 'D.id_unidad = A.id_unidad_instituto and D.activa', 'left');
        $this->db->join('workflow.lineas_tiempo E', 'D.id_linea_tiempo = E.id_linea_tiempo and $$2016/01/01$$ >= any ("E".fechas_inicio) and $$2016/01/01$$ <= any ("E".fechas_fin)', 'left');
        $this->db->where('A.anio', $periodo);
        if (isset($filtros['clave_unidad']))
        {
            $this->db->where('A.clave_unidad', $filtros['clave_unidad']);
            $unidad = $this->db->get('catalogo.unidades_instituto A')->result_array();
//            pr($this->db->last_query());
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $unidad;
    }

    public function insert($parametros = [])
    {
        $salida = array('status' => false, 'msg' => '');
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();

        $fechas = transform_date($parametros);
        if(!$this->valida_fechas($fechas, true)){
            $salida['msg'] = 'Configuración de fechas incorrecta';
            return $salida;
        }
        // $conf_adi = array('porcentaje_muestra' => $parametros['porcentaje']);
        $conf_adi = '{}';
        $insert = array(
            'nombre' => $parametros['nombre'],
            'clave' => $parametros['clave'],
            'id_workflow' => $parametros['id_workflow'],
            'fechas_inicio' => $fechas[0],
            'fechas_fin' => $fechas[1],
            'configuraciones_adicionales' => json_encode($conf_adi)
        );

        $this->db->insert('workflow.lineas_tiempo', $insert);
        $salida['id_linea_tiempo'] = $this->db->insert_id();
        $this->db->reset_query();
        $insert = [];
        $insert['id_linea_tiempo'] = $salida['id_linea_tiempo'];
        foreach ($parametros['unidades'] as $value)
        {
            $insert['id_unidad'] = $value;
            $this->db->insert('workflow.unidades_censo', $insert);
            $this->db->reset_query();
        }

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        } else
        {
            $this->db->trans_commit();
            $salida['status'] = true;
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    private function valida_fechas($fechas = [], $validando_actual = false){
        $status = true;
        $actual=date_create(date('Y-m-d'));
        $fi = transform_date($fechas[0]);
        $ff = transform_date($fechas[1]);
        $of = [];
        for ($i=0; $i < count($fi); $i++)
        {
            $date2=date_create($fi[$i]);
            if($validando_actual)
            {
                if($date2<$actual)
                {
                    $status = false;
                }
            }
            $of[]=$date2;
            $date2=date_create($ff[$i]);
            if($validando_actual)
            {
                if($date2<$actual)
                {
                    $status = false;
                }
            }
            $of[]=$date2;
        }
        for ($i=0; $i < count($of) -1; $i++)
        {
            if($of[$i]>$of[$i+1])
            {
                $status = false;
            }
        }
        return $status;
    }

    public function update($parametros = [])
    {
        $salida = array('status' => false, 'msg' => 'Error al actualizar');
        $fechas = transform_date($parametros);
        // $conf_adi = array('porcentaje_muestra' => $parametros['porcentaje']);
        $conf_adi = '{}';
        $update = array(
            'nombre' => $parametros['nombre'],
            'clave' => $parametros['clave'],
            'fechas_inicio' => $fechas[0],
            'fechas_fin' => $fechas[1],
            'activa' => $parametros['activa_boolean'],
            'configuraciones_adicionales' => json_encode($conf_adi)
        );
        //pr($update);
        $this->db->where('id_linea_tiempo', $parametros['id_linea_tiempo']);
        $this->db->update('workflow.lineas_tiempo', $update);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        } else
        {
            $this->db->trans_commit();
            $salida['status'] = true;
            $salida['msg'] = 'Actualizado con éxito';
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    public function upsert_participantes($id_linea_tiempo = 0, $unidades = [])
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin(); //Definir inicio de transacción
        $this->db->where('id_linea_tiempo', $id_linea_tiempo);
        $this->db->set('activa', false);
        $this->db->update('workflow.unidades_censo');
        $this->db->reset_query();
        foreach ($unidades as $value)
        {
            $this->db->select('count(*) cantidad');
            $this->db->where('id_linea_tiempo', $id_linea_tiempo);
            $this->db->where('id_unidad', $value);
            $existe = $this->db->get('workflow.unidades_censo')->result_array()[0]['cantidad'] != 0;
            $this->db->reset_query();
            if ($existe)
            {
                $this->db->where('id_linea_tiempo', $id_linea_tiempo);
                $this->db->where('id_unidad', $value);
                $this->db->set('activa', true);
                $this->db->update('workflow.unidades_censo');
            } else
            {
                $this->db->flush_cache();
                $insert = array(
                    'id_linea_tiempo' => $id_linea_tiempo,
                    'id_unidad' => $value,
                    'activa' => true
                );
                $this->db->insert('workflow.unidades_censo', $insert);
            }
            $this->db->reset_query();
        }
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $resultado['result'] = FALSE;
            $resultado['msg'] = "Ocurrió un error durante el guardado, por favor intentelo de nuevo más tarde.";
        } else
        {
            $this->db->trans_commit();
            $resultado['msg'] = 'Unidades participantes almacenadas con éxito';
            $resultado['result'] = TRUE;
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $resultado;
    }

    public function get_participantes($id_linea_tiempo = 0, $filtros = [])
    {
        $agrupadas = true;
        if (isset($filtros['unidades_agrupadas']) && !$filtros['unidades_agrupadas'])
        {
            $agrupadas = false;
        }
        $salida['unidades'] = $this->get_unidades_participantes($id_linea_tiempo, $agrupadas);
        if (isset($filtros['delegaciones']) && $filtros['delegaciones'])
        {
            $salida['delegaciones'] = $this->get_delegaciones_participantes($id_linea_tiempo);
        }
        return $salida;
    }

    private function get_unidades_participantes($id_linea_tiempo, $agrupadas = true)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $participantes = [];
        $select = array(
            'A.id_linea_tiempo',
            'REG.id_region', 'REG.nombre region',
            'DEL.id_delegacion', 'DEL.nombre delegacion',
            'B.id_unidad_instituto', 'B.nombre unidad', 'B.clave_unidad', 'B.grupo_tipo_unidad',
            'D.id_usuario id_usuario_n1', 'E.matricula matricula_n1', 'concat("E".nombre,$$ $$,"E".apellido_p,$$ $$,"E".apellido_m) nombre_validador_N1',
            'DD.id_usuario id_usuario_n2', 'EE.matricula matricula_n2', 'concat("EE".nombre,$$ $$,"EE".apellido_p,$$ $$,"EE".apellido_m) nombre_validador_N2'
        );
        $this->db->select($select);
        $this->db->join('catalogo.unidades_instituto B', 'B.id_unidad_instituto = A.id_unidad and A.activa', 'inner');
        $this->db->join('catalogo.delegaciones DEL', 'DEL.id_delegacion = B.id_delegacion', 'inner');
        $this->db->join('catalogo.regiones REG', ' REG.id_region = DEL.id_region', 'inner');
        $this->db->join("workflow.validadores_unidades_censo C", "C.id_linea_tiempo = A.id_linea_tiempo and A.id_unidad = C.id_unidad_instituto and C.tipo = 'N1' and C.activa", 'left');
        $this->db->join("workflow.validadores_unidades_censo CC", "CC.id_linea_tiempo = A.id_linea_tiempo and A.id_unidad = CC.id_unidad_instituto and C.tipo = 'N2' and CC.activa", 'left');
        $this->db->join('sistema.usuarios D', 'D.id_usuario = C.id_usuario', 'left');
        $this->db->join('censo.docente E', ' E.id_usuario = D.id_usuario and E.activo', 'left');
        $this->db->join('sistema.usuarios DD', 'DD.id_usuario = CC.id_usuario', 'left');
        $this->db->join('censo.docente EE', ' EE.id_usuario = DD.id_usuario and EE.activo', 'left');
        $this->db->where('A.id_linea_tiempo', $id_linea_tiempo);
        $participantes = $this->db->get('workflow.unidades_censo A')->result_array();
        $this->db->flush_cache();
        $this->db->reset_query();
        if ($agrupadas)
        {
            return $this->filtra_unidades_umae($participantes);
        } else
        {
            return $participantes;
        }
    }

    private function filtra_unidades_umae($participantes)
    {
        $unidades = [];
        $umae = [];
        $tipos_umae = array('UMAE', 'CUMAE');
        foreach ($participantes as $row)
        {
            if (in_array($row['grupo_tipo_unidad'], $tipos_umae))
            {
                $umae[] = $row;
            } else
            {
                $unidades[] = $row;
            }
        }
        return array('umae' => $umae, 'delegacional' => $unidades);
    }

    private function get_delegaciones_participantes($id_linea_tiempo)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $participantes = [];
        $select = array(
            'A.id_linea_tiempo',
            'REG.id_region', 'REG.nombre region',
            'DEL.id_delegacion', 'DEL.nombre delegacion',
            'max("D".id_usuario) id_usuario', 'max(concat("E".nombre,$$ $$,"E".apellido_p,$$ $$,"E".apellido_m)) nombre_validador',
            'max("H".id_categoria) id_categoria', 'max("I".nombre) categoria', 'max("I".clave_categoria) clave_categoria'
        );
        $this->db->select($select);
        $this->db->join('catalogo.unidades_instituto B', 'B.id_unidad_instituto = A.id_unidad and A.activa', 'inner');
        $this->db->join('catalogo.delegaciones DEL', 'DEL.id_delegacion = B.id_delegacion', 'inner');
        $this->db->join('catalogo.regiones REG', ' REG.id_region = DEL.id_region', 'inner');
        $this->db->join("workflow.validadores_unidades_censo C", "C.id_linea_tiempo = A.id_linea_tiempo and A.id_unidad = C.id_unidad_instituto and C.tipo = 'N2' and C.activa", 'left');
        $this->db->join('sistema.usuarios D', 'D.id_usuario = C.id_usuario', 'left');
        $this->db->join('censo.docente E', ' E.id_usuario = D.id_usuario and E.activo', 'left');
        $this->db->join('censo.historico_datos_docente H', 'H.id_docente = E.id_docente', 'left');
        $this->db->join('catalogo.categorias I', 'I.id_categoria = H.id_categoria', 'left');
        $this->db->where('A.id_linea_tiempo', $id_linea_tiempo);
        $this->db->where('B.grupo_tipo_unidad !=', 'UMAE');
        $this->db->where('B.grupo_tipo_unidad !=', 'CUMAE');
        $this->db->group_by(array(
            'A.id_linea_tiempo',
            'REG.id_region', 'REG.nombre',
            'DEL.id_delegacion', 'DEL.nombre',
        ));
        $participantes = $this->db->get('workflow.unidades_censo A')->result_array();
//        pr($this->db->last_query());
        $this->db->flush_cache();
        $this->db->reset_query();
        return $participantes;
    }

    public function get_secciones($id_linea_tiempo = 0, $tipo = 'N1')
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_seccion', 'A.label'
        );
        $this->db->select($select);
        $this->db->where('A.activo', true);
        $this->db->order_by('A.orden');

        $tmp['secciones'] = $this->db->get('catalogo.secciones A')->result_array();
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_elemento_seccion', ' A.id_seccion',
            'A.id_catalogo_elemento_padre',
            'A.label', 'B.activa'
        );
        $this->db->select($select);
        $this->db->join('workflow.secciones_censo B', "B.id_subseccion = A.id_elemento_seccion and B.validacion = '{$tipo}' and B.id_linea_tiempo = {$id_linea_tiempo}", 'left');
        $this->db->where('A.activo', true);
        $tmp['subsecciones'] = $this->db->get('catalogo.elementos_seccion A')->result_array();
//        pr($this->db->last_query());
        $secciones = $this->procesa_secciones($tmp);
        $this->db->flush_cache();
        $this->db->reset_query();
//        pr($secciones);
        return $secciones;
    }

    public function upsert_secciones($parametros = [])
    {
        $salida = array('status' => false, 'msg' => '');
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();
        $arbol = json_decode($parametros['arbol'], true);
//        pr($arbol);


        $this->recorre_arbol($arbol, $parametros);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        } else
        {
            $salida['status'] = true;
            $this->db->trans_commit();
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    private function recorre_arbol(&$arbol, &$params = [])
    {
        foreach ($arbol as $rama)
        {

            if (startsWith($rama['id'], 'subseccion'))
            {
//                pr($rama['id']);
                $this->upsert_seccion_aux($rama, $params);
            }
            if (count($rama['children']) > 0)
            {
                $this->recorre_arbol($rama['children'], $params);
            }
        }
    }

    private function upsert_seccion_aux($item = [], &$parametros = [])
    {

        $id_subseccion = str_replace('subseccion', '', $item['id']);
        $activa = $item['state']['selected'] == 1 || $item['state']['selected'];
        $this->db->select('count(*) cantidad');
        $this->db->start_cache();
        $this->db->where('id_subseccion', $id_subseccion);
        $this->db->where('id_linea_tiempo', $parametros['id_linea_tiempo']);
        $this->db->where('validacion', $parametros['tipo']);
        $this->db->stop_cache();
        $existe = $this->db->get('workflow.secciones_censo')->result_array()[0]['cantidad'] != 0;
//        pr($this->db->last_query());
        if ($existe)
        {
            $this->db->set('activa', $activa);
            $this->db->update('workflow.secciones_censo');
//            pr($this->db->last_query());
        } else
        {
            $insert = array(
                'id_subseccion' => $id_subseccion,
                'id_linea_tiempo' => $parametros['id_linea_tiempo'],
                'validacion' => $parametros['tipo'],
                'activa' => $activa
            );
            $this->db->insert('workflow.secciones_censo', $insert);
        }
        $this->db->flush_cache();
        $this->db->reset_query();
    }

    private function procesa_secciones($arreglo)
    {
        $secciones = [];
        //pr($arreglo);
        foreach ($arreglo['secciones'] as $seccion)
        {
            $secciones['seccion' . $seccion['id_seccion']] = array(
                'id' => 'seccion' . $seccion['id_seccion'],
                'parent' => '#',
                'text' => $seccion['label'],
            );
        }

        foreach ($arreglo['subsecciones'] as $subseccion)
        {
            if (empty($subseccion['id_catalogo_elemento_padre']))
            {
                $parent = 'seccion' . $subseccion['id_seccion'];
            } else
            {
                $parent = 'subseccion' . $subseccion['id_catalogo_elemento_padre'];
            }
            $secciones['subseccion' . $subseccion['id_elemento_seccion']] = array(
                'id' => 'subseccion' . $subseccion['id_elemento_seccion'],
                'parent' => $parent,
                'text' => $subseccion['label'],
                'activa' => $subseccion['activa'],
                'state' => array(
                    'selected' => $subseccion['activa'],
                )
            );
        }

        for ($i = 0; $i < ConvocatoriaV2_model::MAX_NIVELES; $i++)
        {
            foreach ($secciones as $key => $value)
            {
                if ($value['parent'] != '#' && !isset($secciones[$value['parent']]))
                {
                    //  pr('quitando ' . $key);
                    unset($secciones[$key]);
                }
            }
        }
        //pr($secciones);
        $secciones_procesadas = [];
        foreach ($secciones as $key => $value)
        {
            $secciones_procesadas[] = $value;
        }
        return $secciones_procesadas;
    }

    public function get_validadores(&$filtros = [])
    {
        $validacion = (isset($filtros['validacion']) && $filtros['validacion'] == ConvocatoriaV2_model::N2 ? ConvocatoriaV2_model::N2 : ConvocatoriaV2_model::N1);
        $validadores = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_usuario', 'B.id_docente', 'B.nombre', 'B.apellido_p', 'B.apellido_m',
            'C.id_categoria', 'CAT.nombre categoria', 'UNIDADES.id_unidad_instituto',
            'UNIDADES.nombre unidad', 'UNIDADES.clave_unidad', 'UNIDADES.id_delegacion',
        );
        $this->db->select($select);
        $this->db->join('censo.docente B','B.id_usuario = A.id_usuario', 'inner');
        $this->db->join('censo.historico_datos_docente C','C.id_docente = B.id_docente and C.actual = 1', 'inner');
        $this->db->join('catalogo.departamentos_instituto D','D.id_departamento_instituto = C.id_departamento_instituto', 'inner');
        $this->db->join('catalogo.unidades_instituto UNIDADES','UNIDADES.clave_unidad = D.clave_unidad and UNIDADES.anio = date_part($$year$$, current_date) ', 'inner');
        $this->db->join('catalogo.categorias CAT','CAT.id_categoria = C.id_categoria', 'inner');
        switch ($validacion)
        {
            case ConvocatoriaV2_model::N2:

                break;
            case ConvocatoriaV2_model::N1:
                $this->db->where('("UNIDADES".id_unidad_instituto, "CAT".id_categoria) in (select SQ.id_unidad_instituto, SQ.id_categoria from sistema.categorias_convocatoria SQ where SQ.activa and SQ.id_unidad_instituto = "UNIDADES".id_unidad_instituto and SQ.validacion = $$N1$$) or ("A".id_usuario) in (select SQ1.id_usuario from workflow.validadores_unidades_censo SQ1 where SQ1.id_linea_tiempo = 1 and SQ1.activa)', null, false);
                break;
        }
        $validadores = $this->db->get('sistema.usuarios A')->result_array();
        $this->db->flush_cache();
        $this->db->reset_query();
        return $validadores;
    }

    /**
    * Función que crea el registro para finalizar una convocatoria
    * @author Cheko
    * @param type $datos datos de la peticion para guardar
    *
    */
    public function registro_finaliza_convocatoria($datos){
        $this->db->flush_cache();
        $this->db->reset_query();
        $status = array('success' => false, 'message' => 'No se agrego correctamente el registro', 'data'=>[]);
        try
        {
            $this->db->where('id_docente', $datos['id_docente']);
            $this->db->where('id_linea_tiempo', $datos['id_linea_tiempo']);
            $registros = $this->db->get('workflow.convocatoria_finalizada')->result_array();
            // pr($registros);
            if(empty($registros))
            {
                $this->db->reset_query();
                $this->db->insert('workflow.convocatoria_finalizada', $datos);
                $status['success'] = true;
                $status['message'] = 'Agregado con éxito el registro de finalizar convocatoria';
            }else
            {
                $status['message'] = 'Registro ya existente en la base de datos';
            }
        }catch(Exception $ex)
        {

        }
        return $status;
    }

/**
    * Función que crea el registro para finalizar una convocatoria
    * @author Cheko
    * @param type $datos datos de la peticion para guardar
    *
    */
    public function registro_finaliza_convocatoria_registro_censo_docente($datos){
        $this->db->flush_cache();
        $this->db->reset_query();
        $status = array('success' => false, 'message' => 'No se agrego correctamente el registro', 'data'=>[]);
        try
        {
            $this->db->where('id_docente', $datos['id_docente']);
            $this->db->where('id_convocatoria', $datos['id_convocatoria']);
            $registros = $this->db->get('validacion.fin_registro_censo')->result_array();
            // pr($registros);
            if(empty($registros))
            {
                $this->db->reset_query();
                $this->db->insert('validacion.fin_registro_censo', $datos);
            }else
            {
                $this->db->reset_query();
                
                $this->db->where('id_docente', $datos['id_docente']);
                $this->db->where('id_convocatoria', $datos['id_convocatoria']);
                $activo = array('activo_edicion'=>false);
                $this->db->update('validacion.fin_registro_censo', $datos);                
                
            }
            $status['success'] = true;
            $status['message'] = 'Agregado con éxito el registro de finalizar convocatoria';
        }catch(Exception $ex)
        {

        }
        return $status;
    }
    public function registro_finaliza_convocatoria_registro_censo_docente_general($datos){
        $this->db->flush_cache();
        $this->db->reset_query();
        $status = array('success' => false, 'message' => 'No se agrego correctamente el registro', 'data'=>[]);
        try
        {   if(isset($datos['docentes'])){
            $docentes = $datos['docentes'];
            unset($datos['docentes']);
            //pr($datos);exit();
            //pr($docentes);exit();
            $resp = [];
            foreach($docentes as $key => $value){
                
                $this->db->where('id_docente', $value['id_docente']);
                $this->db->where('id_convocatoria', $datos['id_convocatoria']);
                $registros = $this->db->get('validacion.fin_registro_censo')->result_array();
                // pr($registros);
                    if(empty($registros))
                    {
                        $datos['es_finaliza_docente'] = false;
                        $datos['id_docente'] = $value['id_docente'];
                        //pr($datos);
                        $this->db->reset_query();
                        $this->db->insert('validacion.fin_registro_censo', $datos);
                    }else{
                        $this->db->reset_query();
                        $datos['es_finaliza_docente'] = true;
                        
                        $this->db->where('id_docente', $value['id_docente']);
                        $this->db->where('id_convocatoria', $datos['id_convocatoria']);
                        $activo = array('activo_edicion'=>false);
                        $this->db->update('validacion.fin_registro_censo', $datos);                
                        
                    }
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        $status['tp_msg'] = 'danger';
                        $status['mensaje'] = 'Ocurrio un error al guardar la ratificación. Por favor intentelo nuevamente';
                    break;
                } else {
                    //    $this->db->trans_commit();
                    //  $datos['respuesta']['mensaje'] = 'La finalización se guardo correctamente';
                    $status['tp_msg'] = 'success';
                    
                }
                if($status['tp_msg'] == 'success'){
                    //Guarda convocatoria
                    $conv['is_confirmado_cierre_registro_censo'] = true;
                        $this->db->reset_query();
                        $this->db->where('id_convocatoria', $datos['id_convocatoria']);
                        $this->db->update('convocatoria.convocatorias', $conv);                
                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            $status['tp_msg'] = 'danger';
                            $status['mensaje'] = 'Ocurrio un error al guardar la ratificación. Por favor intentelo nuevamente';
                        }else{
                            $status['tp_msg'] = 'success';
                            $status['mensaje'] = 'La finalización se guardo correctamente';
                        }
                    }
                }
            }
           
        }catch(Exception $ex)
        {
            $status['tp_msg'] = 'danger';
            $status['mensaje'] = 'Ocurrio un error al guardar la ratificación. Por favor intentelo nuevamente';
        }
         
        return $status;
    }

    public function upsert_validacion_secciones(&$datos){
        if(!empty($datos['data'])){
            $this->db->flush_cache();
            $this->db->reset_query();
            try
            {
            $this->db->where('id_docente', $datos['data']['id_docente']);
            $this->db->where('id_convocatoria', $datos['data']['id_convocatoria']);
            $this->db->where('id_validador', $datos['data']['id_validador']);
            $this->db->where('id_seccion', $datos['data']['id_seccion']);
            $this->db->where('activo', true);
            $registros = $this->db->get('validacion.validacionN1_seccion')->result_array();
            if(empty($registros))
            {
                $this->db->reset_query();
                $this->db->insert('validacion.validacionN1_seccion', $datos['data']);
                
            }else{
                $this->db->reset_query();
                
                $this->db->where('id_validador', $datos['data']['id_validador']);
                $this->db->where('id_docente', $datos['data']['id_docente']);
                $this->db->where('id_convocatoria', $datos['data']['id_convocatoria']);
                $this->db->where('id_seccion', $datos['data']['id_seccion']);
                $this->db->update('validacion.validacionN1_seccion', $datos['data']);                
                
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $datos['respuesta']['tp_msg'] = 'danger';
                $datos['respuesta']['mensaje'] = 'Ocurrio un error al guardar la validación. Por favor intentelo nuevamente';
            } else {
                $this->db->trans_commit();
                $datos['respuesta']['mensaje'] = 'La validación de la sección se guardo correctamente';
                $datos['respuesta']['tp_msg'] = 'success';
                
            }
        }catch(Exception $ex)
        {
            $datos['respuesta']['tp_msg'] = 'danger';
            $datos['respuesta']['mensaje'] = 'Ocurrio un error al guardar la validación. Por favor intentelo nuevamente';

        }
        }
    }


    public function upsert_finaliza_validacion(&$datos){
        if(!empty($datos['data'])){
            $this->db->flush_cache();
            $this->db->reset_query();
            try
            {
            $this->db->where('id_docente', $datos['data']['id_docente']);
            $this->db->where('id_convocatoria', $datos['data']['id_convocatoria']);
            $this->db->where('id_validador', $datos['data']['id_validador']);
            $this->db->where('activo', true);
            
            $registros = $this->db->get('validacion.validacionN1_finaliza')->result_array();
            if(empty($registros))
            {
                $this->db->reset_query();
                $this->db->insert('validacion.validacionN1_finaliza', $datos['data']);
                
            }else{
                $this->db->reset_query();
                
                $this->db->where('id_validador', $datos['data']['id_validador']);
                $this->db->where('id_docente', $datos['data']['id_docente']);
                $this->db->where('id_convocatoria', $datos['data']['id_convocatoria']);
                $this->db->update('validacion.validacionN1_finaliza', $datos['data']);                
                
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $datos['respuesta']['tp_msg'] = 'danger';
                $datos['respuesta']['mensaje'] = 'Ocurrio un error al guardar la validación. Por favor intentelo nuevamente';
            } else {
                $this->db->trans_commit();
                $datos['respuesta']['mensaje'] = 'La validación ha finalizado correctamente';
                $datos['respuesta']['tp_msg'] = 'success';
                
            }
        }catch(Exception $ex)
        {
            $datos['respuesta']['tp_msg'] = 'danger';
            $datos['respuesta']['mensaje'] = 'Ocurrio un error al guardar la validación. Por favor intentelo nuevamente';

        }
        }
    }

    public function upsert_finaliza_ratificacion(&$datos){
        if(!empty($datos['data'])){
            $this->db->flush_cache();
            $this->db->reset_query();
            try
            {
            $this->db->where('id_docente', $datos['data']['id_docente']);
            $this->db->where('id_convocatoria', $datos['data']['id_convocatoria']);
            $this->db->where('id_ratificador_validador', $datos['data']['id_ratificador_validador']);
            $this->db->where('activo', true);
            $registros = $this->db->get('validacion.ratificador')->result_array();
            if(empty($registros))
            {
                $this->db->reset_query();
                $this->db->insert('validacion.ratificador', $datos['data']);
                
            }else{
                $this->db->reset_query();
                
                $this->db->where('id_ratificador_validador', $datos['data']['id_ratificador_validador']);
                $this->db->where('id_docente', $datos['data']['id_docente']);
                $this->db->where('id_convocatoria', $datos['data']['id_convocatoria']);
                $this->db->update('validacion.ratificador', $datos['data']);                
                
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $datos['respuesta']['tp_msg'] = 'danger';
                $datos['respuesta']['mensaje'] = 'Ocurrio un error al guardar la ratificación. Por favor intentelo nuevamente';
            } else {
                $this->db->trans_commit();
                $datos['respuesta']['mensaje'] = 'La ratificación se guardo correctamente';
                $datos['respuesta']['tp_msg'] = 'success';
                
            }
        }catch(Exception $ex)
        {
            $datos['respuesta']['tp_msg'] = 'danger';
            $datos['respuesta']['mensaje'] = 'Ocurrio un error al guardar la ratificación. Por favor intentelo nuevamente';

        }
        }
    }

    public function get_validaciones_seccion($id_docente, $id_convocatoria, $id_seccion = null , $id_validador = null){
        $this->db->where('id_docente', $id_docente);
        $this->db->where('id_convocatoria', $id_convocatoria);
        if(!is_null($id_validador)){
            $this->db->where('id_validador', $id_validador);
        }   
        if(!is_null($id_seccion)){
            $this->db->where('id_seccion', $id_seccion);
        }
        $this->db->where('activo', true);
        $registros = $this->db->get('validacion.validacionN1_seccion')->result_array();
        return $registros;
    }

    public function get_ratificacion($id_docente, $id_convocatoria, $id_validador = null){
        $this->db->where('id_docente', $id_docente);
        $this->db->where('id_convocatoria', $id_convocatoria);
        $this->db->where('activo', true);
        if(!is_null($id_validador)){
            $this->db->where('id_validador', $id_validador);
        }   
        
        $registros = $this->db->get('validacion.ratificador')->result_array();
        return $registros;
    }
    

    public function estado_validacion_censo($id_docente){
        
        $this->db->flush_cache();
        $this->db->reset_query();

    }

    public function validar_cerrar_convocatoria($id_convocatoria){
        $convocatoria = $this->sesion->get_info_convocatoria_censo($id_convocatoria);
        $fechas_inicio = transform_date($convocatoria['fechas_inicio']);
        $fechas_fin = transform_date($convocatoria['fechas_fin']);
        $date1=date_create(date('Y-m-d'));
        $date2=date_create($fechas_fin[0]);
        $dias = date_diff($date1, $date2)->days;
        if($date1 > $date2){
            $dias = $dias * -1;                
        }
        //Si es fecha mayor al cierre de convocatoria y no se ha cerrado
        if($dias<0 && !$convocatoria['is_confirmado_cierre_registro_censo']){//aparece boton de finaliza cierre de censo            
            return true;
        }
        return false;
    }

    public function docentes($rol){
        $this->db->select('d.id_docente');
        $this->db->join('sistema.usuarios u', 'u.id_usuario = d.id_usuario');
        $this->db->join('sistema.usuario_rol ur', 'ur.id_usuario = u.id_usuario');
        $this->db->where('u.activo', true);
        $this->db->where('ur.clave_rol', $rol);           
        
        $registros = $this->db->get('censo.docente d')->result_array();
        return $registros;
    }


}
