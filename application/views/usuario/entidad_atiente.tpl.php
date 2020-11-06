<?php
$html_ooad = '';
$html_umae = '';

foreach ($delegaciones_cat as $key_ooad => $ooad) { 
    $check = $this->form_complete->create_element(
        array('id' => 'ooad' . $ooad['clave_delegacional'],
            'type' => 'checkbox','value'=>$ooad['clave_delegacional'],
            'attributes' => array('name' => 'ooad[]',
                'class' => 'form-control  form-control input-sm ooadlist',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => 'activo',
                'checked' => (isset($ooad_select[$ooad['clave_delegacional']]))?'checked':''
            )
        )
    );
    $html_ooad .= '<tr>
        <td>'.$ooad['clave_delegacional'].'</td>
        <td>'.$ooad['nombre'].'</td>
        <td>'.$check .'</td>'       
    .'</tr>';
}
foreach ($umae_cat as $key_ooad => $ooad) { 
    $check = $this->form_complete->create_element(
        array('id' => 'umae'.$ooad['clave_unidad'] ,
            'type' => 'checkbox', 'value'=>$ooad['clave_unidad'],
            'attributes' => array('name' => 'umae[]',
                'class' => 'form-control  form-control input-sm umaelist',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => 'activo',
                'checked' => (isset($umae_select[$ooad['clave_unidad']]))?'checked':''
                //'checked' => $row['activo']
            )
        )
    );

    $html_umae .= '<tr>
        <td>'.$ooad['clave_unidad'].'</td>
        <td>'.$ooad['nombre'].'</td>
        <td>'.$check .'</td>'      
    .'</tr>';
}

?>

<input name="entidad_asignada" type="hidden" value="1">
<input name="entidad_asignada_valida" type="hidden" value="">


<div class="list-group" id="list_entidades_asignadas">
    <h3 ><b>Entidad designada</b></h3>
    <div class="list-group-item">
        <div class="panel-body" onmousedown="elemento(event);">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <table class="table table-striped">
                        <thead class="thead-light">
                        <tr>
                          <th>Clave</th>                                                                
                          <th>OOAD</th>                                                                
                          <th>Opción</th>                                                                
                        </tr>
                        </thead>
                        <tbody>
                            <?php echo $html_ooad; ?>
                        </tbody>
                    </table>

                </div>
                <div class="col-lg-6 col-md-6">
                    <table class="table table-striped">
                        <thead class="thead-light">
                        <tr>
                          <th>Clave</th>                                                                
                          <th>UMAE</th>                                                                
                          <th>Opción</th>                                                                
                        </tr>
                        </thead>
                        <tbody>
                            <?php echo $html_umae; ?>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</div>
