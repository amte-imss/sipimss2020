<?php
$config['workflow.lineas_tiempo'] = array(
    'select' => array(
      'L.id_linea_tiempo','L.clave','L.nombre as "linea de tiempo"', 'L.fechas_inicio', 'L.fechas_fin', 'L.id_etapa_activa',
      'E.id_etapa','E.nombre etapa', 'I.clave_unidad','I.nombre unidad', 'I.grupo_tipo_unidad tipo', 'I.clave_presupuestal',
      'P.clave_departamental clave_asdcripcion', 'P.nombre as adscripcion', 'D.nombre as delegacion', 'R.nombre as region',
      ' M.matricula','M.nombre', 'M.apellido_p', 'M.apellido_m', 'M.email', 'M.curp'
    ),
    'join' => array(
        array(
            'table' => 'workflow.etapas E',
            'condition' => 'L.id_etapa_activa = E.id_etapa',
            'type' => 'left'
        ),
        array(
            'table' => 'workflow.unidades_censo C',
            'condition' => 'C.id_linea_tiempo = L.id_linea_tiempo',
            'type' => 'left'
        ),
        array(
            'table' => 'catalogo.unidades_instituto I',
            'condition' => 'I.id_unidad_instituto = C.id_unidad',
            'type' => 'left'
        ),
        array(
            'table' => 'catalogo.delegaciones D',
            'condition' => 'D.id_delegacion = I.id_delegacion',
            'type' => 'left'
        ),
        array(
            'table' => 'catalogo.regiones R',
            'condition' => 'R.id_region = I.id_region',
            'type' => 'left'
        ),
        array(
            'table' => 'catalogo.departamentos_instituto P',
            'condition' => 'P.clave_unidad = I.clave_unidad',
            'type' => 'left'
        ),
        array(
            'table' => 'censo.historico_datos_docente H',
            'condition' => 'H.id_departamento_instituto = P.id_departamento_instituto',
            'type' => 'left'
        ),
        array(
            'table' => 'censo.docente M',
            'condition' => 'M.id_docente = H.id_docente',
            'type' => 'left'
        ),
    ),
    'where' => array('H.actual' => 1)
);

$config['workflow.validadores_censo'] = array(
    'select' => array(
      'L.id_linea_tiempo','L.clave','L.nombre as "linea de tiempo"', 'L.fechas_inicio', 'L.fechas_fin', 'L.id_etapa_activa',
      'E.id_etapa','E.nombre etapa', 'UI.clave_unidad','UI.nombre unidad', 'UI.grupo_tipo_unidad tipo', 'UI.clave_presupuestal',
      'DI.clave_departamental clave_asdcripcion', 'DI.nombre as adscripcion', 'D.nombre as delegacion', 'R.nombre as region',
      'CD.matricula','CD.nombre', 'CD.apellido_p', 'CD.apellido_m', 'CD.email', 'CD.curp', 'VC.tipo'
    ),
    'join' => array(
        array(
            'table' => 'workflow.lineas_tiempo L',
            'condition' => 'VC.id_linea_tiempo = L.id_linea_tiempo',
            'type' => 'left'
        ),
        array(
            'table' => 'workflow.etapas E',
            'condition' => 'E.id_etapa = L.id_etapa_activa',
            'type' => 'left'
        ),
        array(
            'table' => 'workflow.unidades_censo UC',
            'condition' => 'UC.id_linea_tiempo = L.id_linea_tiempo',
            'type' => 'left'
        ),
        array(
            'table' => 'catalogo.unidades_instituto UI',
            'condition' => 'UI.id_unidad_instituto = UC.id_unidad',
            'type' => 'left'
        ),
        array(
            'table' => 'catalogo.delegaciones D',
            'condition' => 'D.id_delegacion = UI.id_delegacion',
            'type' => 'left'
        ),

        array(
            'table' => 'catalogo.regiones R',
            'condition' => 'R.id_region = UI.id_region',
            'type' => 'left'
        ),
        array(
            'table' => 'catalogo.departamentos_instituto DI',
            'condition' => 'DI.clave_unidad = UI.clave_unidad',
            'type' => 'left'
        ),
        array(
            'table' => 'censo.docente CD',
            'condition' => 'CD.id_usuario = VC.id_usuario',
            'type' => 'left'
        ),
    )
);
