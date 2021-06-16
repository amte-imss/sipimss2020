<?php echo css("jsgrid-1.5.3/jsgrid.min.css"); ?>
<?php echo css("jsgrid-1.5.3/jsgrid-theme.min.css"); ?>
<?php echo js("js_export_grid/jsgrid-1.5.3/jsgrid.js"); ?>

<?php echo js("js_export_grid/export/canvas-datagrid.js"); ?>
<?php echo js("js_export_grid/export/Blob.js"); ?>
<?php echo js("js_export_grid/export/FileSaver.js"); ?>
<?php echo js("js_export_grid/export/xlsx.full.min.js"); ?>
<?php echo js("js_export_grid/complemento_jsgrid.js"); 

function get_html($data_docentes){
    $html = '';
    if ( !is_null($data_docentes) ){
        //pr($data_docentes);
        foreach ($data_docentes as $key => $data_docente) {
            $html .= '<tr>
                <td>'.$key.'</td>
                <td>'.$data_docente['total'].'</td>
                <td>'.$data_docente['curso_corto_educacion_cumple'].'</td>
                <td>'.$data_docente['diplomado_educacion_cumple'].'</td>
                <td>'.$data_docente['especialidad_educacion_cumple'].'</td>
                <td>'.$data_docente['maestria_educacion_cumple'].'</td>
                <td>'.$data_docente['doctorado_educacion_cumple'].'</td>
                <td>'.$data_docente['cumplen'].'</td>
                <td>'.(($data_docente['total']>0) ? number_format($data_docente['curso_corto_educacion_cumple']/$data_docente['total']*100, 2) : 0).' %</td>
                <td>'.(($data_docente['total']>0) ? number_format($data_docente['diplomado_educacion_cumple']/$data_docente['total']*100, 2) : 0).' %</td>
                <td>'.(($data_docente['total']>0) ? number_format($data_docente['especialidad_educacion_cumple']/$data_docente['total']*100, 2) : 0).' %</td>
                <td>'.(($data_docente['total']>0) ? number_format($data_docente['maestria_educacion_cumple']/$data_docente['total']*100, 2) : 0).' %</td>
                <td>'.(($data_docente['total']>0) ? number_format($data_docente['doctorado_educacion_cumple']/$data_docente['total']*100, 2) : 0).' %</td>
                <td>'.(($data_docente['total']>0) ? number_format($data_docente['cumplen']/$data_docente['total']*100, 2) : 0).' %</td>
            </tr>';
        }
    } else {
        echo '<tr>
            <td colspan="2">No existen registros.</td>
        </tr>';
    }
    return $html;
}

?>
<div id="main_content_totals" class="list-group">
    <div class="list-group-item">
        <div class="panel-body">
            <h2>Reporte de formación docente</h2>
            <div class="col-md-12 col-sm-12 text-right">
                <button id="ver_detalle" type="button" class="btn btn-tpl" onclick="funcion_ver_detalle(this);">Ver detalle</button>               
            </div>
            <div class="row" id="main_content_total">
                <div class="col-lg-12 col-md-12">
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>OOAD</th>
                                <th>Número de docentes registrados</th>
                                <th>Número de docentes que cumplen con Curso corto</th>
                                <th>Número de docentes que cumplen con Diplomado</th>                                
                                <th>Número de docentes que cumplen con Especialidad</th>
                                <th>Número de docentes que cumplen con Maestría</th>
                                <th>Número de docentes que cumplen con Doctorado</th>
                                <th>Número de docentes que cumplen</th>
                                <th>% acumulado de capacitación docente con Curso corto</th>
                                <th>% acumulado de capacitación docente con Diplomado</th>
                                <th>% acumulado de capacitación docente con Especialidad</th>
                                <th>% acumulado de capacitación docente con Maestría</th>
                                <th>% acumulado de capacitación docente con Doctorado</th>
                                <th>% acumulado de docentes que cumplen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo get_html($data_docentes['D']); ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-12 col-md-12">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>UMAE</th>
                                <th>Número de docentes registrados</th>
                                <th>Número de docentes que cumplen con Curso corto</th>
                                <th>Número de docentes que cumplen con Diplomado</th>
                                <th>Número de docentes que cumplen con Especialidad</th>
                                <th>Número de docentes que cumplen con Maestría</th>
                                <th>Número de docentes que cumplen con Doctorado</th>
                                <th>Número de docentes que cumplen</th>
                                <th>% acumulado de capacitación docente con Curso corto</th>
                                <th>% acumulado de capacitación docente con Diplomado</th>
                                <th>% acumulado de capacitación docente con Especialidad</th>
                                <th>% acumulado de capacitación docente con Maestría</th>
                                <th>% acumulado de capacitación docente con Doctorado</th>
                                <th>% acumulado de docentes que cumplen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo get_html($data_docentes['U']); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <div id="main_content_detail" style="display:none;">
                <div id="page-inner">
                    <h2>Detallado</h2>
                    <div class="col-md-12 col-sm-12">
                            <a href="#" type="button" class="btn btn-theme animated flipInY visible pull-right" aria-expanded="false" onclick="exportar_reporte(this);" data-namegrid="jsGrid2">
                                <span>Exportar</span>
                            </a>                
                        </div><br>
                    <div id="jsGrid2"></div>
                <div>
            <div>
        </div>
    </div>
</div>



<script>
(function() {
    /*var clients = [{
        Primenumber: [" 2", " 3", " 5", " 7"],
        Name: "Otto Clay",
    }, {
        Primenumber: [" 1", " 3", " 5", " 9"],
        Name: "Otto Clay2",
    }]*/
    var docentes = <?php echo json_encode(($data_docentes['js'])); ?>;

    $("#jsGrid2").jsGrid({
        width: "100%",
        height: "600px",

        inserting: false,
        editing: false,
        sorting: true,
        paging: true,
        filtering: false,
        
        data: docentes,

        fields: [{ name: "matricula", title: "Matrícula", type: "text" },
            { name: "nombre_docente", title: "Nombre del docente", type: "text" },
            { name: "curp", title: "CURP", type: "text" },
            { name: "email", title: "Correo electrónico", type: "text" },
            { name: "delegacion", title: "Delegación", type: "text" },
            { name: "clave_unidad", title: "Clave unidad", type: "text" },
            { name: "nom_unidad", title: "Unidad", type: "text" },
            { name: "umae", title: "UMAE", type: "text" },
            { name: "clave_departamental", title: "Clave departamental", type: "text" },
            { name: "departamento", title: "Departamento", type: "text" },
            { name: "clave_categoria", title: "Clave categoría", type: "text" },
            { name: "categoria", title: "Categoría", type: "text" },
            { name: "curso_corto_educacion", title: "# de cursos cortos en educación", type: "text" },
            { name: "diplomado_educacion", title: "# de diplomados en educación", type: "text" },
            { name: "especialidad_educacion", title: "# de especialidades en educación", type: "text" },
            { name: "maestria_educacion", title: "# de maestrías en educación", type: "text" },
            { name: "doctorado_educacion", title: "# de doctorados en educación", type: "text" },
            { name: "curso_corto_educacion_cumple", title: "Tiene curso corto", type: "checkbox", sorting: false },
            { name: "diplomado_educacion_cumple", title: "Tiene diplomado", type: "checkbox", sorting: false },
            { name: "especialidad_educacion_cumple", title: "Tiene especialidad", type: "checkbox", sorting: false },
            { name: "maestria_educacion_cumple", title: "Tiene maestría", type: "checkbox", sorting: false },
            { name: "doctorado_educacion_cumple", title: "Tiene doctorado", type: "checkbox", sorting: false },
            { name: "cumplimiento", title: "Cumplimiento de formación", type: "checkbox", sorting: false },
            //{ type: "control" }
        ]
    });
}());
function funcion_ver_detalle(){
    $("#main_content_total").fadeToggle();
    $("#main_content_detail").fadeToggle();
}

function exportar_reporte(element) {
    var namegrid = $(element).data('namegrid');
    var headers = remove_headers(obtener_cabeceras(), cabeceras_no_exportar());
    export_xlsx_grid(namegrid, headers, 'docentes', 'docentes');
}

function obtener_cabeceras() {
    var arr_header = {
        matricula: 'Matrícula',
        nombre_docente: "Nombre del docente",
        curp: "CURP",
        email: "Correo electrónico",
        delegacion: "Delegación",
        clave_unidad: "Clave unidad",
        nom_unidad: "Unidad",
        umae: "UMAE",
        clave_departamental: "Clave departamental",
        departamento: "Departamento",
        clave_categoria: "Clave categoría",
        categoria: "Categoría",
        curso_corto_educacion: "# de cursos cortos en educación",
        diplomado_educacion: "# de diplomados en educación",
        especialidad_educacion: "# de especialidades en educación",
        maestria_educacion: "# de maestrías en educación",
        doctorado_educacion: "# de doctorados en educación",
        curso_corto_educacion_cumple: "Tiene curso corto",
        diplomado_educacion_cumple: "Tiene diplomado",
        especialidad_educacion_cumple: "Tiene especialidad",
        maestria_educacion_cumple: "Tiene maestría",
        doctorado_educacion_cumple: "Tiene doctorado",
        cumplimiento: "Cumplimiento de formación"
    }

    return arr_header;
}

function cabeceras_no_exportar() {
    var arr_header = {
        acciones: 'Acciones',
    }
    return arr_header;
}
</script>