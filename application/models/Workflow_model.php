<?php

/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Linea_tiempo_model
 *
 * @author chrigarc
 */
class Workflow_model extends MY_Model implements ICron
{

    //put your code here
    function __construct()
    {
        parent::__construct();
    }

    public function get_lineas_tiempo($filtros = [])
    {
        $lt = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_linea_tiempo', 'A.nombre', 'A.clave',
            'B.id_workflow', 'B.nombre tipo', 'A.fechas_inicio', 'A.fechas_fin',
            'A.id_etapa_activa', 'C.nombre etapa', 'A.activa', 'A.configuraciones_adicionales'
        );

        $this->db->select($select);
        $this->db->join('workflow.workflows B ', ' B.id_workflow = A.id_workflow', 'inner');
        $this->db->join('workflow.etapas C ', ' C.id_etapa = A.id_etapa_activa and C.activa', 'left');
        //$this->db->where('A.activa', true);
        $this->db->where('B.activo', true);
        if (isset($filtros['where']))
        {
            $this->db->where($filtros['where']);
        }
        if(isset($filtros['id_linea_tiempo'])){
            $this->db->where('id_linea_tiempo', $filtros['id_linea_tiempo']);
        }

        $lt = $this->db->get('workflow.lineas_tiempo A')->result_array();
        $this->db->flush_cache();
        $this->db->reset_query();
//        pr($lt);
        return $lt;
    }

    public function get_tipos_lineas_tiempo($filtros){
        $lt = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'B.id_workflow id', 'B.nombre content'
        );

        $this->db->select($select);
        $this->db->join('workflow.workflows B ', ' B.id_workflow = A.id_workflow', 'inner');
        //$this->db->where('A.activa', true);
        $this->db->where('B.activo', true);
        if (isset($filtros['where']))
        {
            $this->db->where($filtros['where']);
        }
        $this->db->group_by(array('B.id_workflow', 'B.nombre'));
        $lt = $this->db->get('workflow.lineas_tiempo A')->result_array();
        $this->db->flush_cache();
        $this->db->reset_query();
//        pr($lt);
        return $lt;
    }

    public function get_workflows($filtros = [])
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $workflows = [];
        if (isset($filtros['select']))
        {
            $select = $filtros['select'];
        } else
        {
            $select = array(
                'A.id_workflow', 'A.nombre', 'A.labels_fechas',
                'B.clave_modulo id_controlador_new', 'B.url controlador_new',
                'C.clave_modulo id_controlador_update', 'C.url controlador_update'
            );
        }
        $this->db->select($select);
        $this->db->join('sistema.modulos B', 'B.clave_modulo = A.clave_controlador_insert', 'inner');
        $this->db->join('sistema.modulos C', 'C.clave_modulo = A.clave_controlador_update', 'inner');
        $this->db->where('A.activo', true);
        if (isset($filtros['id_workflow']))
        {
            $this->db->where('id_workflow', $filtros['id_workflow']);
        }
        $workflows = $this->db->get('workflow.workflows A')->result_array();
        $this->db->flush_cache();
        $this->db->reset_query();
        return $workflows;
    }

    public function get_workflows_administracion(&$filtros = [])
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.clave_modulo id_modulo', 'A.label', 'B.url'
        );
        $this->db->select($select);
        $this->db->join('sistema.modulos B', 'A.clave_modulo = B.clave_modulo', 'inner');
        $this->db->where('A.activo', true);
        $this->db->where('B.activo', true);
        if (isset($filtros['id_workflow']))
        {
            $this->db->where('A.id_workflow', $filtros['id_workflow']);
        }
        $this->db->order_by('A.orden');
        $modulos = $this->db->get('workflow.modulos_administracion A')->result_array();
        $this->db->flush_cache();
        $this->db->reset_query();
        return $modulos;
    }

    public function update($parametros = [])
    {
        $salida = array('status' => false, 'msg' => '');
        $fechas = transform_date($parametros);
        $update = array(
            'nombre' => $parametros['nombre'],
            'clave' => $parametros['clave'],
            'fechas_inicio' => $fechas[0],
            'fechas_fin' => $fechas[1],
            'activa' => $parametros['activa_boolean']
        );
        // pr($update);
        $this->db->where('id_linea_tiempo', $parametros['id_linea_tiempo']);
        $this->db->update('workflow.lineas_tiempo', $update);
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

    public function insert($parametros = [])
    {
        $salida = array('status' => false, 'msg' => '');
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();

        $fechas = transform_date($parametros);

        $insert = array(
            'nombre' => $parametros['nombre'],
            'clave' => $parametros['clave'],
            'id_workflow' => $parametros['id_workflow'],
            'fechas_inicio' => $fechas[0],
            'fechas_fin' => $fechas[1],
        );

        $this->db->insert('workflow.lineas_tiempo', $insert);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        } else
        {
            $this->db->trans_commit();
            $salida['status'] = true;
            $salida['id_linea_tiempo'] = $this->db->insert_id();
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    public function get_etapa_activa($linea_tiempo, $update_action = false)
    {
        $fecha_actual = date("Y-m-d");
        $fecha_actual_index = -1;
        $fechas = transform_date($linea_tiempo['fechas_inicio']);
        for ($i = 0; $i < count($fechas); $i++)
        {
            if ($fecha_actual == $fechas[$i])
            {
                $fecha_actual_index = $i;
            }
        }
        $this->db->flush_cache();
        $this->db->reset_query();

        if ($fecha_actual_index > 0)
        {
            $this->db->select('A.id_etapa');
            $this->db->where('A.id_workflow', $linea_tiempo['id_workflow']);
            $this->db->where('A.activa', true);
            $this->db->limit(1, $fecha_actual_index);
            $etapa = $this->db->get('workflow.etapas A');
            if ($etapa)
            {
                $etapa = $etapa[0]['id_etapa'];
                $etapa['accesos'] = $this->get_etapa_accesos($etapa);
                $etapa['notificaciones'] = $this->get_etapa_notificaciones($etapa);
                if ($update_action)
                {
                    $this->db->flush_cache();
                    $this->db->reset_query();
                    $this->db->where('id_linea_tiempo', $linea_tiempo['id_linea_tiempo']);
                    $this->db->set('id_etapa_actual', $etapa);
                    $this->db->update('workflow.lineas_tiempo');
                }
                if ($linea_tiempo['id_etapa_actual'] == null || ($linea_tiempo['id_etapa_actual'] != null && $linea_tiempo['id_etapa_actual'] != $etapa))
                {
                    /* Aquí se lanza la actualizacion de usuarios, pendiente */
                }
            }
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $etapa;
    }

    private function get_etapa_accesos($etapa)
    {
        $accesos = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.nivel_acceso', 'id_modulo'
        );
        $this->db->select($select);
        $this->db->where('A.id_etapa', $etapa);
        $this->db->where('A.activa', true);
        $accesos = $this->db->get('workflow.etapas_accesos A')->result_array();
        $this->db->flush_cache();
        $this->db->reset_query();
        return $accesos;
    }

    private function get_etapa_notificaciones($etapa)
    {
        $notificaciones = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'clave_notificacion', 'time_lapse'
        );
        $this->db->select($select);
        $this->db->where('A.activa', true);
        $this->db->where('A.id_etapa', $etapa);
        $notificaciones = $this->db->get('workflow.etapas_notificaciones A')->result_array();
        $this->db->flush_cache();
        $this->db->reset_query();
        return $notificaciones;
    }

    public function crontab()
    {
        $filtros['where'] = array(
            "current_date = ANY(fechas_inicio)"
        );
        $lineas = $this->get_lineas_tiempo($filtros);
        $etapas_activas = [];
        foreach ($lineas as $row)
        {
            $etapas_activas[] = $this->get_etapa_activa($row, true);
        }
        $this->load->model('Notificacion_model', 'notificacion');
        //$this->load->model('Modulo_model', 'modulo');
        foreach ($etapas_activas as $row)
        {
            $this->notificacion->lanza_notificaciones($row['notificaciones']);
        }
    }

    /**
     * Funcion que busca si hay un registro de la linea del tiempo
     * @author Cheko
     * @param type $nombre_tabla Nombre de la tabla para obtener los registros
     * @param type $id de la linea del tiempo
     *
     */
    public function obtener_registro_linea_tiempo($nombre_tabla, $id)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->select('*');
        $this->db->where('id_linea_tiempo',$id);
        $query = $this->db->get($nombre_tabla);
        $arrayDatos = $query->result_array();
        if(count($arrayDatos) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Funcion que elimina una linea del tiempo con
     * sus dependencias
     * @author Cheko
     * @param type $id el ide para eliminar la linea del tiempo
     *
     */
    public function eliminar_linea_tiempo($id)
    {
        $status = array('success' => false, 'message' => 'No se pudo borrar la lps registros de la linea del tiempo', 'data'=>[]);
        $this->db->flush_cache();
        $this->db->reset_query();

        try
        {
            //Borrar de validadores_censo
            $this->db->where('id_linea_tiempo', $id);
            $resultBorrarVCenso = $this->db->delete('workflow.validadores_censo');

            //Borrar de unidades_censo
            $this->db->where('id_linea_tiempo', $id);
            $resultBorrarUCenso = $this->db->delete('workflow.unidades_censo');

            //Borrar de secciones_censo
            $this->db->where('id_linea_tiempo', $id);
            $resultBorrarSCenso = $this->db->delete('workflow.secciones_censo');

            //Borrar de lineas_tiempo
            $this->db->where('id_linea_tiempo', $id);
            $resultBorrarLineasTiempo = $this->db->delete('workflow.lineas_tiempo');

            $status['success'] = true;
            $status['message'] = 'Eliminado con éxito';
            $status['data'] = array('deleteVCenso'=>$resultBorrarVCenso, 'deleteUCenso' => $resultBorrarUCenso, 'deleteSCenso' => $resultBorrarSCenso,
            'deleteLineasTiempo' => $resultBorrarLineasTiempo);

        }catch(Exception $ex)
        {

        }
        $this->db->reset_query();
        return $status;
    }

    public function get_modulos_etapas($filtros = [])
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'LT.id_linea_tiempo',  'LT.clave',
            'W.id_workflow', 'E.id_etapa', 'E.nombre',
            'EA.clave_rol', 'EA.clave_modulo'
        );
        $this->db->select($select);
        $this->db->join('workflow.workflows W','W.id_workflow = LT.id_workflow', 'inner');
        $this->db->join('workflow.etapas E','E.id_workflow = W.id_workflow', 'inner');
        $this->db->join('workflow.etapas_accesos EA ',' EA.id_etapa = E.id_etapa', 'inner');
        $this->db->where_in('EA.clave_rol', $filtros['roles']);
        if(isset($filtros['clave_modulo']))
        {
            $this->db->where('EA.clave_modulo', $filtros['clave_modulo']);
        }
        if(isset($filtros['modulos']))
        {
            $this->db->where_in('EA.clave_modulo', $filtros['modulos']);
        }
        $this->db->where('EA.activa', true);
        $this->db->where('LT.activa', true);
        $this->db->where('LT.id_linea_tiempo', $filtros['id_linea_tiempo']);
        if(isset($filtros['id_etapa']))
        {
            $this->db->where('E.id_etapa', $filtros['id_etapa']);
        }
        $modulos = $this->db->get('workflow.lineas_tiempo LT')->result_array();
        $this->db->reset_query();
        return $modulos;
    }

    public function procesa_lineas_tiempo(){
        $this->db->flush_cache();
        $this->db->reset_query();
        $lt = $this->get_lineas_tiempo_actuales();
        $this->libera_lineas_tiempo($lt);
        // pr($lt);
        $now = new DateTime("today");
        // pr($now);
        foreach ($lt as $row)
        {
            $fechas_i = explode(',',$row['fechas_inicio']);
            $fechas_f = explode(',',$row['fechas_fin']);
            $index = -1;
            for($i=0;$i<count($fechas_i) && $index==-1 ;$i++)
            {
                // pr($fechas_i[$i].' - '.$fechas_f[$i]);
                $fi =  new DateTime($fechas_i[$i]);
                $ff =  new DateTime($fechas_f[$i]);
                // pr($ff);
                // pr($ff==$now?'true':'false');
                // pr(($fi<=$now)?'true':'false');
                // pr(($ff>=$now)?'true':'false');
                if($fi<=$now && $ff>=$now){
                    $index = $i;
                }
            }
            $this->db->reset_query();
            // pr($index);
            if($index>-1){
                $id_etapa = $this->localiza_etapa($index, $row['etapas']);
                $this->db->set('id_etapa_activa', $id_etapa);
                $row['id_etapa_activa'] = $id_etapa;
                $this->ejecuta_actividades_linea_tiempo($row);
            }else
            {
                $this->db->set('id_etapa_activa', null);
            }
            $this->db->where('id_linea_tiempo', $row['id_linea_tiempo']);
            $this->db->update('workflow.lineas_tiempo LT');
        }
        $this->db->reset_query();
        return $lt;
    }

    private function localiza_etapa($index, $etapas){
        $etapas = json_decode($etapas, true);
        $id_etapa = $etapas[$index]['id_etapa'];
        return $id_etapa;
    }

    private function get_lineas_tiempo_actuales(){
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'LT.id_linea_tiempo', 'LT.id_workflow', 'array_to_string("LT".fechas_fin, $$,$$) fechas_fin',
            'array_to_string("LT".fechas_inicio, $$,$$) fechas_inicio',
            'concat($$[$$,array_to_string(array_agg(concat($${"orden": $$, "E".orden, $$, "id_etapa": $$, "E".id_etapa, $$}$$)), $$,$$),$$]$$) etapas'
        );
        $this->db->select($select);
        $this->db->join('workflow.etapas E','E.id_workflow = LT.id_workflow', 'inner');
        $this->db->where('LT.activa', true);
        $this->db->where('current_date >= (select unnest("LT".fechas_inicio) afi order by afi asc limit 1) and current_date <= (select unnest("LT".fechas_fin) afi order by afi desc limit 1)', null, false);
        $this->db->group_by('1,2,3');
        $lt = $this->db->get('workflow.lineas_tiempo LT')->result_array();
        $this->db->flush_cache();
        $this->db->reset_query();
        // pr($this->db->last_query());
        return $lt;
    }

    private function libera_lineas_tiempo($lineas_actuales = [])
    {
        $this->db->reset_query();
        $actuales = [];
        foreach ($lineas_actuales as $row)
        {
            $actuales[]= $row['id_linea_tiempo'];
            $this->db->set('id_etapa_activa', null);
            $this->db->where_not_in('id_linea_tiempo', $actuales);
            $this->db->update('workflow.lineas_tiempo LT');
        }
    }

    private function ejecuta_actividades_linea_tiempo($linea_tiempo = [])
    {

    }

}
