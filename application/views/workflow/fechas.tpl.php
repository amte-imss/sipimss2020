<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
?>
<?php

if(isset($linea_tiempo)){
    $ltf_inicio = transform_date($linea_tiempo['fechas_inicio']);
    $ltf_fin = transform_date($linea_tiempo['fechas_fin']);
  //  pr($ltf_inicio);
   // pr($ltf_fin);
}

//pr($workflow['labels_fechas']);
$fechas = str_replace('{', '', $labels_fechas);
$fechas = str_replace('}', '', $fechas);
$fechas = str_replace('"', '', $fechas);
$nombres_fechas = explode(',', $fechas);
$index = 0;
foreach ($nombres_fechas as $key => $value)
{
    ?>
    <br>
    <div class="row">
        <div class="form-group">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Fecha de inicio <?php echo $value; ?></span>
                    <?php
                    $val_ini = isset($linea_tiempo)? $ltf_inicio[$index]: '';
                    echo $this->form_complete->create_element(
                            array('id' => 'fecha_inicio_' . $key,
                                'type' => 'text',
                                'value' => $val_ini != '' ? get_date_formato($val_ini):$val_ini,
                                'attributes' => array(
                                    'required' => true,
                                    'class' => 'form-control fecha fecha_inicio',
                                    'data-toggle' => 'tooltip',
                                    'data-date-format' => "dd-mm-yyyy",
                                    'title' => 'Fecha de inicio ' . $value)
                            )
                    );
                    ?>
                </div>
                <div style="display:none;" id="div-requerido-fecha_inicio_<?php echo $key; ?>" class="alert alert-danger convocatoria-requerido" role="alert">Campo requerido</div>
                <div style="display:none;" id="div-fecha_inicio_<?php echo $key; ?>" class="alert alert-danger convocatoria-fecha" role="alert">Configuración de fechas incorrecta</div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Fecha de fin <?php echo $value; ?></span>
                    <?php
                    $val_fin = isset($linea_tiempo)? $ltf_fin[$index]: '';
                    echo $this->form_complete->create_element(
                            array('id' => 'fecha_fin_' . $key,
                                'type' => 'text',
                                'value' => $val_fin != '' ? get_date_formato($val_fin): $val_fin,
                                'attributes' => array(
                                    'required' => true,
                                    'class' => 'form-control fecha fecha_fin',
                                    'data-toggle' => 'tooltip',
                                    'data-date-format' => "dd-mm-yyyy",
                                    'title' => 'Fecha de fin ' . $value)
                            )
                    );
                    ?>
                </div>
                <div style="display:none;" id="div-requerido-fecha_fin_<?php echo $key; ?>" class="alert alert-danger convocatoria-requerido" role="alert">Campo requerido</div>
                <div style="display:none;" id="div-fecha_fin_<?php echo $key; ?>" class="alert alert-danger convocatoria-fecha" role="alert">Configuración de fechas incorrecta</div>
            </div>
        </div>
    </div>
    <?php
    $index++;
}
?>
