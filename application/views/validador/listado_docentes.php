

<?php 
$datatable= ['header'=>'','body'=>'','seleccionar'=>''];
if(count($datos_docentes)>0){
    $datatable['seleccionar'] = $this->form_complete->create_element(
        array('id' => 'seleccionar' ,
            'type' => 'checkbox', 
            'attributes' => array(
                'class' => 'form-control  form-control input-sm selecciona_docentes',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => 'seleccionar',
                'onclick' => 'selecciona_todo(this);',
                //'checked' => (isset($umae_select[$ooad['clave_unidad']]))?'checked':''
                //'checked' => $row['activo']
            )
        )
    );
    foreach($datos_docentes as $key => $val){
        $check = $this->form_complete->create_element(
            array('id' => 'docentes_datos' ,
                'type' => 'checkbox', 'value'=>$val['id_usuario'],
                'attributes' => array('name' => 'docentes[]',
                    'class' => 'form-control  form-control input-sm lista_docentes',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'title' => 'seleccionar',
                    
                    //'checked' => (isset($umae_select[$ooad['clave_unidad']]))?'checked':''
                    //'checked' => $row['activo']
                )
            )
        );
        $datatable['body'] .= '<tr>';
        $datatable['body'] .= '<td>'.$val['matricula'].'</td>';
        $datatable['body'] .= '<td>'.$val['nombre_docente'].'</td>';
        $datatable['body'] .= '<td>'.$val['delegacion'].'</td>';
        $datatable['body'] .= '<td>'.$val['nom_unidad'].'('.$val['clave_unidad'].')'.'</td>';
        $datatable['body'] .= '<td>'.$check.'</td>';
        $datatable['body'] .= '</tr>';
    }

}
?> 

<h4>Docentes registrados</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Matrícula</th>
            <th>Nombre</th>            
            <th>Delegación</th>
            <th>Unidad</th>
            <th>seleccionar <?php echo $datatable['seleccionar'];?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        echo $datatable['body'];
        ?>
    </tbody>
</table>