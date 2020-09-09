

<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

echo js('date/locales/bootstrap-datepicker.es.js', array( "charset" => "UTF-8")) ;
echo js('docente/campo_date.js');


echo form_open('convocatoria/nueva/', array('id' => 'form_convocatoria'));


?>


<div class="row">
    <div class="form-group">
        <div class="col-md-6">
            <div class="input-group input-group-sm">
                <span class="input-group-addon">Tipo de convocatoria</span>
                <select class="form-control" name="tipo">
                    <option>Seleccionar</option>
                    <option value="1">Censo</option>
                    <option value="2">Evaluación curricular docente</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-sm">
                <span class="input-group-addon">Segmento</span>
                <?php
                echo $this->form_complete->create_element(
                        array('id' => 'segmento',
                            'type' => 'dropdown',
                            'first' => array('' => 'Seleccione...'),
                            'options' => $segmentos,
                            'attributes' => array(
                                'class' => 'form-control',
                                'data-toggle' => 'tooltip',
                                'title' => '¿A cual segmento va dirigida la convocatoria?')
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
            <div class="input-group" >
                <span class="input-group-addon">Nombre</span>
                <?php
                echo $this->form_complete->create_element(
                        array('id' => 'nombre',
                            'type' => 'text',
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
            <div class="input-group">
                <span class="input-group-addon">Clave</span>
                <?php
                echo $this->form_complete->create_element(
                        array('id' => 'clave',
                            'type' => 'text',
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
                    <?php
                    echo $this->form_complete->create_element(
                            array('id' => 'fecha_inicio_'.$key,
                                'type' => 'date',
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
                                   'type' => 'date',
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


<div class="modal-footer">
      <button type="submit" class="btn btn-tpl">Guardar cambios</button>
    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
</div>


<?php echo form_close(); ?>
