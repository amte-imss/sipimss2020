<?php

/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
echo js('convocatoria/editar.js');
echo form_open('convocatoria/get_convocatorias/'.$convocatoria['id_convocatoria'].'/'.Convocatoria::UPDATE, array('id' => 'form_convocatoria'));
$fechas_inicio = transform_date($convocatoria['fechas_inicio']);
$fechas_fin = transform_date($convocatoria['fechas_fin']);
//pr($fechas_inicio);
//pr($fechas_fin);
?>
<style>
    
</style>
<div class="row">
    <div class="form-group">
        <div class="col-md-6">
            <div class="input-group input-group-sm">

                <span class="input-group-addon">Nombre</span>

                  <?php
                  echo $this->form_complete->create_element(
                          array('id' => 'nombre',
                              'type' => 'text',
                              'value' => $convocatoria['nombre'],
                              'attributes' => array(
                                  'class' => 'form-control',
                                  'data-toggle' => 'tooltip',
                                  'title' => 'Nombre de la convocatoria')
                          )
                  );
                  ?>



            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-sm">
                <span class="input-group-addon">Clave</span>
                <?php
                echo $this->form_complete->create_element(
                        array('id' => 'clave',
                            'type' => 'text',
                            'value' => $convocatoria['clave'],
                            'attributes' => array(
                                'class' => 'form-control',
                                'data-toggle' => 'tooltip',
                                'title' => 'Clave de la convocatoria')
                        )
                );
                ?>
            </div>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="form-group">
        <div class="col-md-6">
            <div class="input-group input-group-sm">
                <span class="input-group-addon">Porcentaje de muestra</span>
                <?php
                echo $this->form_complete->create_element(
                        array('id' => 'porcentaje',
                            'type' => 'text',
                            'value' => $convocatoria['porcentaje'],
                            'attributes' => array(
                                'class' => 'form-control',
                                'data-toggle' => 'tooltip',
                                'title' => 'Porcentaje de muestra')
                        )
                );
                ?>
            </div>
        </div>
    </div>
</div>
<?php
$nombres_fechas = array(0 => 'registro', 1 => 'validación N1', 2 => 'validación N2');
foreach ($nombres_fechas as $key => $value)
{
    ?>
    <br>
    <div class="row">
        <div class="form-group">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Fecha de inicio <?php echo $value; ?></span>
                    <?php //pr(date_format(date_create($fechas_fin[$key]), 'd/m/Y'));
                    echo $this->form_complete->create_element(
                            array('id' => 'fecha_inicio_'.$key,
                                'type' => 'text',
                                'value' => date_format(date_create($fechas_fin[$key]), 'd/m/Y'),
                                'attributes' => array(
                                    'class' => 'form-control fecha',
                                    'data-toggle' => 'tooltip',
                                    'data-date-format'=>"dd/mm/yyyy",
                                    'title' => 'Fecha de inicio '.$value)
                            )
                    );
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Fecha de fin <?php echo $value; ?></span>
                    <?php
                    echo $this->form_complete->create_element(
                            array('id' => 'fecha_fin_'.$key,
                                'type' => 'text',
                                'value' => date_format(date_create($fechas_fin[$key]), 'd/m/Y'),
                                'attributes' => array(
                                    'class' => 'form-control fecha',
                                    'data-toggle' => 'tooltip',
                                    'data-date-format'=>"dd/mm/yyyy",
                                    'title' => 'Fecha de fin '.$value)
                            )
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<div class="row modal-footer">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-tpl">Guardar</button>
</div>

<?php echo form_close(); ?>
