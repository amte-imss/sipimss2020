<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

/**
 * Description of Convocatoria_model
 *
 * @author chrigarc
 */
class Convocatoria_model extends MY_Model
{

    const REGION = 'REGION', N1 = 'N1';

    //put your code here
    function __construct()
    {
        parent::__construct();
    }

    public function get_convocatorias($filtros = [])
    {
        $convocatorias = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        if (isset($filtros['tipo_convocatoria']) && $filtros['tipo_convocatoria'] == 'N')
        {
            $select = array(
                'id_convocatoria', 'A.nombre', 'A.clave',
                'id_tipo_convocatoria', 'A.id_tipo_convocatoria',
                'A.fechas_inicio', 'A.fechas_fin', 'A.activa',
                'A.target', 'A.porcentaje'
            );
        } else
        {
            $select = array(
                'id_convocatoria', 'A.nombre', 'A.clave',
                'id_tipo_convocatoria', 'A.id_tipo_convocatoria',
                'A.fechas_inicio', 'A.fechas_fin', 'A.activa'
            );
        }
        $this->db->select($select);
        if (isset($filtros['id_convocatoria']))
        {
            $this->db->where('id_convocatoria', $filtros['id_convocatoria']);
        }
        if (isset($filtros['tipo_convocatoria']) && $filtros['tipo_convocatoria'] == 'N')
        {
            $convocatorias = $this->db->get('convocatoria.censo A')->result_array();
        } else
        {
            $convocatorias = $this->db->get('convocatoria.convocatorias A')->result_array();
        }

//        pr($this->db->last_query());
//        pr($convocatorias);
        $this->db->flush_cache();
        $this->db->reset_query();
        return $convocatorias;
    }

    public function insert($parametros = [])
    {
        $salida = array('status' => false, 'msg' => '');
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();

        $tipo = $this->get_tipo($parametros['tipo']);
        $fechas = transform_date($parametros);

        $insert = array(
            'nombre' => $parametros['nombre'],
            'clave' => $parametros['clave'],
            'id_tipo_convocatoria' => $tipo,
            'fechas_inicio' => $fechas[0],
            'fechas_fin' => $fechas[1],
            'target' => $parametros['segmento']
        );

        $this->db->insert('convocatoria.censo', $insert);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        } else
        {
            $this->db->trans_commit();
            $salida['status'] = true;
            $salida['id_convocatoria'] = $this->db->insert_id();
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    public function update($parametros = [])
    {
        $salida = array('status' => false, 'msg' => '');
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();

        $fechas = transform_date($parametros);

        $update = array(
            'nombre' => $parametros['nombre'],
            'clave' => $parametros['clave'],
            'fechas_inicio' => $fechas[0],
            'fechas_fin' => $fechas[1],
            'porcentaje' => $parametros['porcentaje'],
        );

        $this->db->where('id_convocatoria', $parametros['id_convocatoria']);
        $this->db->update('convocatoria.censo', $update);

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

    public function get_entidades($id_convocatoria = 0)
    {
        $entidades = [];
        $filtros['id_convocatoria'] = $id_convocatoria;
        $filtros['tipo_convocatoria'] = 'N';
        $convocatoria = $this->get_convocatorias($filtros)[0];
        $target = $convocatoria['target'];
        switch ($target)
        {
            case 'REGION':
                $entidades[$target] = $this->get_regiones($id_convocatoria);
                break;
            case 'DELEGACION':
                $entidades[$target] = $this->get_delegaciones($id_convocatoria);
                break;
            case 'UMAE':
                $entidades[$target] = $this->get_umae($id_convocatoria);
                break;
            default :
                $entidades['DELEGACION'] = $this->get_delegaciones($id_convocatoria);
                $entidades['UMAE'] = $this->get_umaes($id_convocatoria);
                break;
        }
//        pr($entidades);
        return $entidades;
    }

    private function get_regiones($id_convocatoria)
    {
        $regiones = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_region id_entidad', 'A.nombre', 'B.activo activa'
        );
        $this->db->select($select);
        $this->db->join("convocatoria.entidades B", "B.tipo = 'REGION' and A.id_region = B.id_entidad and B.id_convocatoria = {$id_convocatoria}", 'left');
        $this->db->order_by('A.nombre');
        $regiones = $this->db->get('catalogo.regiones A')->result_array();
        $this->db->flush_cache();
        $this->db->reset_query();
        return $regiones;
    }

    private function get_delegaciones($id_convocatoria)
    {
        $delegaciones = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_delegacion id_entidad', 'A.nombre', 'B.activo  activa'
        );
        $this->db->select($select);
        $this->db->join("convocatoria.entidades B", "B.tipo = 'DELEGACION' and B.id_entidad = A.id_delegacion and B.id_convocatoria = {$id_convocatoria}", 'left');
        $this->db->order_by('A.nombre');
        $delegaciones = $this->db->get('catalogo.delegaciones A')->result_array();
        $this->db->flush_cache();
        $this->db->reset_query();
        return $delegaciones;
    }

    private function get_umaes($id_convocatoria)
    {
        $umae = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        /*
          $select = array(
          'A.id_delegacion', 'A.nombre', 'B.activos'
          );
          $this->db->select($select);
          $this->db->join("convocatoria.entidades B","B.tipo = 'DELEGACION' and B.id_entidad = A.id_delegacion and B.id_convocatoria = {$id_convocatoria}", 'left');
          $this->db->order_by('A.nombre');
          $umae = $this->db->get('catalogo.delegaciones A')->result_array();
         * 
         */
        $this->db->flush_cache();
        $this->db->reset_query();
        return $umae;
    }

    public function get_validadores(&$filtros = [])
    {
        $validadores = [];
        $target = $filtros['tipo_entidad'];
        switch ($target)
        {
            case 'REGION':
                $validadores[$target] = $this->get_validadores_regiones($filtros);
                break;
            case 'DELEGACION':
                $validadores[$target] = $this->get_validadores_delegaciones($filtros);
                break;
            case 'UMAE':
                $validadores[$target] = $this->get_validadores_umae($filtros);
                break;
            default :
                $validadores['DELEGACION'] = $this->get_validadores_delegaciones($filtros);
                $validadores['UMAE'] = $this->get_validadores_umaes($filtros);
                break;
        }
        return $validadores;
    }

    private function get_validadores_regiones(&$filtros = [])
    {
        $v_regiones = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'C.id_docente', 'B.matricula', 'DD.nombre categoria', 'I.activo'
        );
        $this->db->select($select);
        $this->db->join('censo.docente B', 'B.id_docente = A.id_docente', 'inner');
        $this->db->join('censo.historico_datos_docente C', 'C.id_docente = B.id_docente', 'inner');
        $this->db->join('convocatoria.categorias D', ' D.id_categoria = C.id_categoria', 'inner');
        $this->db->join('catalogo.categorias DD', 'DD.id_categoria = D.id_categoria', 'inner');
        $this->db->join('catalogo.departamentos_instituto E ', ' E.id_departamento_instituto = C.id_departamento_instituto', 'inner');
        $this->db->join('catalogo.unidades_instituto F', 'F.id_unidad_instituto = E.id_unidad_instituto', 'inner');
        $this->db->join('catalogo.delegaciones G', 'G.id_delegacion = F.id_delegacion', 'inner');
        $this->db->join('catalogo.regiones H ', 'H.id_region = G.id_region', 'inner');
        $this->db->join('convocatoria.validadores_n1n2 I', "I.id_docente = B.id_docente and I.tipo = '{$filtros['tipo_entidad']}' and   D.id_convocatoria = {$filtros['id_convocatoria']}", 'left');
        $this->db->where('H.id_region', $filtros['id_entidad']);
        $this->db->where('I.validacion', $filtros['validacion']);
        $this->db->group_by(array('C.id_docente', 'B.matricula', 'DD.nombre', 'I.activo', 'C.actual'));
        $this->db->having('C.actual', 1);
        $v_regiones = $this->db->get('sistema.usuarios A')->result_array();
//        pr($this->db->last_query());
        $this->db->flush_cache();
        $this->db->reset_query();
        return $v_regiones;
    }

    private function get_validadores_delegaciones(&$filtros = [])
    {
        $v_delegaciones = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'C.id_docente', 'B.matricula', 'DD.nombre categoria', 'I.activo'
        );
        $this->db->select($select);
        $this->db->join('censo.docente B', 'B.id_docente = A.id_docente', 'inner');
        $this->db->join('censo.historico_datos_docente C', 'C.id_docente = B.id_docente', 'inner');
        $this->db->join('convocatoria.categorias D', ' D.id_categoria = C.id_categoria', 'inner');
        $this->db->join('catalogo.categorias DD', 'DD.id_categoria = D.id_categoria', 'inner');
        $this->db->join('catalogo.departamentos_instituto E ', ' E.id_departamento_instituto = C.id_departamento_instituto', 'inner');
        $this->db->join('catalogo.unidades_instituto F', 'F.id_unidad_instituto = E.id_unidad_instituto', 'inner');
        $this->db->join('catalogo.delegaciones G', 'G.id_delegacion = F.id_delegacion', 'inner');
        $this->db->join('convocatoria.validadores_n1n2 I', "I.id_docente = B.id_docente and I.tipo = '{$filtros['tipo_entidad']}' and   D.id_convocatoria = {$filtros['id_convocatoria']}", 'left');
        $this->db->where('H.id_delegacion', $filtros['id_entidad']);
        $this->db->where('I.validacion', $filtros['validacion']);
        $this->db->group_by(array('C.id_docente', 'B.matricula', 'DD.nombre', 'I.activo', 'C.actual'));
        $this->db->having('C.actual', 1);
        $v_delegaciones = $this->db->get('sistema.usuarios A')->result_array();
//        pr($this->db->last_query());
        $this->db->flush_cache();
        $this->db->reset_query();
        return $v_delegaciones;
    }

    private function get_validadores_umaes(&$filtros = [])
    {
        $v_umaes = [];
        return $v_umaes;
    }

    public function get_categorias($id_convocatoria = 0)
    {
        $categorias = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_categoria', 'A.nombre', 'A.clave_categoria', 'B.validacion',
            'B.validacion'
        );
        $this->db->select($select);
        $this->db->join('convocatoria.categorias B', 'B.id_categoria = A.id_categoria', 'join');
        $this->db->where('B.id_convocatoria', $id_convocatoria);
        $this->db->where('B.activa', true);
        $this->db->order_by('A.nombre');
        $categorias = $this->db->get('catalogo.categorias A')->result_array();
        $this->db->flush_cache();
        $this->db->reset_query();
        return $categorias;
    }

    public function get_secciones($id_convocatoria = 0, $tipo = 'N1')
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
        $this->db->join('convocatoria.secciones B', "B.id_subseccion = A.id_elemento_seccion and B.validacion = '{$tipo}' and B.id_convocatoria = {$id_convocatoria}", 'left');
        $this->db->where('A.activo', true);
        $tmp['subsecciones'] = $this->db->get('catalogo.elementos_seccion A')->result_array();
//        pr($this->db->last_query());
        $secciones = $this->procesa_secciones($tmp);
        $this->db->flush_cache();
        $this->db->reset_query();
//        pr($secciones);
        return $secciones;
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

        foreach ($secciones as $key => $value)
        {            
            if ($value['parent'] != '#' && !isset($secciones[$value['parent']]))
            {                
                unset($secciones[$key]);
            }
        }

        $secciones_procesadas = [];
        foreach ($secciones as $key => $value){
            $secciones_procesadas[] = $value;
        }        
        return $secciones_procesadas;
    }

    public function upsert_entidades($parametros = [])
    {
        $salida = array('status' => false, 'msg' => '');
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();
        $entidades = $parametros['entidades'];
        foreach ($entidades as $key => $value)
        {
            foreach ($value as $row)
            {
                $this->db->flush_cache();
                $this->db->reset_query();
                $this->db->select('count(*) cantidad');
                $this->db->start_cache();
                $this->db->where('id_convocatoria', $parametros['id_convocatoria']);
                $this->db->where('id_entidad', $row['id_entidad']);
                $this->db->where('tipo', $key);
                $this->db->stop_cache();
                $existe = $this->db->get('convocatoria.entidades')->result_array()[0]['cantidad'] != 0;
                if ($existe)
                {
                    $this->db->set('activo', isset($parametros['entidad_' . $key . '_' . $row['id_entidad']]));
                    $this->db->update('convocatoria.entidades');
                } else
                {
                    $this->db->flush_cache();
                    $insert = array(
                        'id_convocatoria' => $parametros['id_convocatoria'],
                        'id_entidad' => $row['id_entidad'],
                        'tipo' => $key,
                        'activo' => isset($parametros['entidad_' . $key . '_' . $row['id_entidad']])
                    );
                    $this->db->insert('convocatoria.entidades', $insert);
                }
            }
        }
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

    public function upsert_validadores($parametros = [])
    {
        $salida = array('status' => false, 'msg' => '');
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();
        $this->db->select('count(*) cantidad');
        $this->db->start_cache();
        $this->db->where('id_docente', $parametros['id_docente']);
        $this->db->where('id_convocatoria', $parametros['id_convocatoria']);
        $this->db->where('validacion', $parametros['validacion']);
        $this->db->where('id_entidad', $parametros['id_entidad']);
        $this->db->where('tipo', $parametros['tipo_entidad']);
        $this->db->stop_cache();
        $existe = $this->db->get('convocatoria.validadores_n1n2')->result_array()[0]['cantidad'] != 0;
        if ($existe)
        {
            $this->db->set('activo', $parametros['activo']);
            $this->db->update('convocatoria.validadores_n1n2');
        } else
        {
            $this->db->flush_cache();
            $insert = array(
                'id_docente' => $parametros['id_docente'],
                'id_convocatoria' => $parametros['id_convocatoria'],
                'validacion' => $parametros['validacion'],
                'id_entidad' => $parametros['id_entidad'],
                'tipo' => $parametros['tipo_entidad'],
                'activo' => $parametros['activo']
            );
            $this->db->insert('convocatoria.validadores_n1n2', $insert);
        }
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

    public function upsert_categorias($parametros = [])
    {
        $salida = array('status' => false, 'msg' => '');
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();
        $this->db->select('count(*) cantidad');
        $this->db->start_cache();
        $this->db->where('id_categoria', $parametros['id_categoria']);
        $this->db->where('id_convocatoria', $parametros['id_convocatoria']);
        $this->db->where('validacion', $parametros['tipo']);
        $this->db->stop_cache();
        $existe = $this->db->get('convocatoria.categorias')->result_array()[0]['cantidad'] != 0;
        if ($existe)
        {
            $this->db->set('activa', $parametros['activa']);
            $this->db->update('convocatoria.categorias');
        } else
        {
            $this->db->flush_cache();
            $insert = array(
                'id_categoria' => $parametros['id_categoria'],
                'id_convocatoria' => $parametros['id_convocatoria'],
                'validacion' => $parametros['tipo'],
                'activa' => true
            );
            $this->db->insert('convocatoria.categorias', $insert);
        }
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

    private function upsert_seccion_aux($item = [], &$parametros = [])
    {

        $id_subseccion = str_replace('subseccion', '', $item['id']);
        $activa = $item['state']['selected'] == 1;
        $this->db->select('count(*) cantidad');
        $this->db->start_cache();
        $this->db->where('id_subseccion', $id_subseccion);
        $this->db->where('id_convocatoria', $parametros['id_convocatoria']);
        $this->db->where('validacion', $parametros['tipo']);
        $this->db->stop_cache();
        $existe = $this->db->get('convocatoria.secciones')->result_array()[0]['cantidad'] != 0;
        if ($existe)
        {
            $this->db->set('activa', $activa);
            $this->db->update('convocatoria.secciones');
        } else
        {
            $this->db->flush_cache();
            $insert = array(
                'id_subseccion' => $id_subseccion,
                'id_convocatoria' => $parametros['id_convocatoria'],
                'validacion' => $parametros['tipo'],
                'activa' => $activa
            );
            $this->db->insert('convocatoria.secciones', $insert);
        }
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

    private function get_tipo($n_tipo = 1)
    {
        $salida = 'N';
        switch ($n_tipo)
        {
            case 2: $salida = 'ECD';
                break;
            default : $salida = 'N';
                break;
        }
        return $salida;
    }

    public function get_segmentos()
    {
        $segmentos = array('REGION' => 'Región',
            'DELEGACION' => 'Delegación',
            'UMAE' => 'UMAE',
            'TODAS' => 'UMAE y delegaciones');
        return $segmentos;
    }

}
