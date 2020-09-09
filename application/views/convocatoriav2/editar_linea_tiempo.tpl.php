<style>
    .datepicker {z-index: 1151 !important;}
</style>

<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
$configuraciones_adicionales = json_decode($linea_tiempo['configuraciones_adicionales'], true);
$porcentaje = isset($configuraciones_adicionales['porcentaje_muestra'])?$configuraciones_adicionales['porcentaje_muestra']:'';
echo js('convocatoriav2/editar.js');
echo form_open('#', array('id' => 'form_workflow'));
?>
<?php
    if(isset($status))
    {
        $t_status = $status['status'] ? 'success':'danger';
        echo html_message($status['msg'], $t_status);
    }
?>
<input type="hidden" id="id_linea_tiempo" name="id_linea_tiempo" value="<?php echo $linea_tiempo['id_linea_tiempo']; ?>">
<div class="row">
    <div class="form-group">
        <div class="col-md-6">
            <div class="input-group" >
                <span class="input-group-addon">Nombre</span>
                <?php
                echo $this->form_complete->create_element(
                        array('id' => 'nombre',
                            'type' => 'text',
                            'value' => $linea_tiempo['nombre'],
                            'attributes' => array(
                                'required' => true,
                                'class' => 'form-control',
                                'data-toggle' => 'tooltip',
                                'title' => 'Nombre de la convocatoria')
                        )
                );
                ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-addon">Clave</span>
                <?php
                echo $this->form_complete->create_element(
                        array('id' => 'clave',
                            'type' => 'text',
                            'value' => $linea_tiempo['clave'],
                            'attributes' => array(
                                'required' => true,
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

<?php echo $fechas; ?>

<br>

<div class="row">
    <div class="form-group">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-addon">Estado</span>
                <?php
                echo $this->form_complete->create_element(
                        array('id' => 'activo',
                            'type' => 'dropdown',
                            'first' => array('' => 'Seleccione...'),
                            'options' => array(
                                1 => 'Activa',
                                2 => 'Inactiva'
                            ),
                            'value' => $linea_tiempo['activa']? 1:2,
                            'attributes' => array(
                                'required' => true,
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
<!-- <div class="row">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-addon">Porcentaje de muestra</span>
            <?php
            // echo $this->form_complete->create_element(
            //     array('id' => 'porcentaje',
            //     'type' => 'text',
            //     'value' => $porcentaje,
            //     'attributes' => array(
            //         'class' => 'form-control',
            //         'data-toggle' => 'tooltip',
            //         'title' => 'Porcentaje de muestra')
            //     )
            // );
            ?>
        </div>
    </div>
</div> -->
<div class="row">
    <input type="submit" class="btn btn-primary" value="Guardar">
</div>

<?php echo form_close(); ?>
