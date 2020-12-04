

<?php 
//pr($datos_docentes_limbo);
$datatable= ['header'=>'','body'=>'','seleccionar'=>''];
$umaes_total = 0;
if(count($datos_docentes_limbo)>0){
    $datatable['seleccionar'] = $this->form_complete->create_element(
        array('id' => 'seleccionar' ,
            'type' => 'checkbox', 
            'attributes' => array(
                'class' => 'form-control  form-control input-sm selecciona_docentes_limbo',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => 'seleccionar',
                'onclick' => 'selecciona_todo_limbo(this);',
                //'checked' => (isset($umae_select[$ooad['clave_unidad']]))?'checked':''
                //'checked' => $row['activo']
                )
                )
            );
            foreach($datos_docentes_limbo as $key => $val){
                $check = $this->form_complete->create_element(
                    array('id' => 'docentes_datos' ,
                    'type' => 'checkbox', 'value'=>$val['id_usuario'],
                    'attributes' => array('name' => 'docentes_limbo[]',
                    'class' => 'form-control  form-control input-sm lista_docentes_limbo',
                    'data-toggle' => 'tooltip',
                    //'text' => $val['id_usuario'], 
                    'data-placement' => 'top',
                    'title' => 'seleccionar',
                    
                    //'checked' => (isset($umae_select[$ooad['clave_unidad']]))?'checked':''
                    //'checked' => $row['activo']
                )
            )
        );
        if(strlen(trim($val['umae']))>0){
            $umaes_total ++;
        }
        $datatable['body'] .= '<tr>';
        $datatable['body'] .= '<td>'.$val['matricula'].'</td>';
        $datatable['body'] .= '<td>'.$val['nombre_docente'].'</td>';
        $datatable['body'] .= '<td>'.$val['delegacion'].'</td>';
        $datatable['body'] .= '<td>'.$val['departamento'] .'</td>';
        $datatable['body'] .= '<td>'.$val['nom_unidad'].'('.$val['clave_unidad'].')'.'</td>';
        $datatable['body'] .= '<td>'.$val['umae'].'</td>';
        $datatable['body'] .= '<td>'.$check.'</td>';
        $datatable['body'] .= '</tr>';
    }

}

?> 

<h4>Docentes no asignados a validador: <?php echo count($datos_docentes_limbo);?> (UNIDADES: <?php echo count($datos_docentes_limbo) - $umaes_total;?>  - UMAES: <?php echo $umaes_total;?> )</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Matrícula</th>
            <th>Nombre</th>            
            <th>Delegación</th>
            <th>Adscripción</th>
            <th>Unidad</th>
            <th>UMAE</th>
            <th>seleccionar <?php echo $datatable['seleccionar'];?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        echo $datatable['body'];
        ?>
    </tbody>
</table>