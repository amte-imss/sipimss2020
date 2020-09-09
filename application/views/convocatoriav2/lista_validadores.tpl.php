<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
echo form_open('convocatoriav2/get_validadores/' . $linea_tiempo['id_linea_tiempo'] . '/' . $tipo . '/' . $entidad . '/' . $validacion, array('id' => 'form_convocatoria'));
?>
<div class="row">
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-addon">Matricula</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'matricula',
                        'type' => 'text',
                        'attributes' => array(
                            'class' => 'form-control',
                            'data-toggle' => 'tooltip',
                            'title' => 'Matricula')
                    )
            );
            ?>
        </div>
    </div>
    <div class="col-md-4">
        <a href="<?php echo $exportarValidadores;?>" style="float: right;"><button class="btn btn-tpl" >Exportar Validadores</button></a>
        <div class="input-group input-group-sm">
            <input type="submit" value="Agregar" class="btn btn-primary">
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<?php echo form_open('convocatoriav2/get_validadores/' . $linea_tiempo['id_linea_tiempo'] . '/' . $tipo . '/' . $entidad . '/' . $validacion, array('id' => 'form_validador')); ?>
<input type="hidden" id="id_linea_tiempo" value="<?php echo $linea_tiempo['id_linea_tiempo']; ?>">
<input type="hidden" id="tipo" value="<?php echo $tipo; ?>">
<input type="hidden" id="id_entidad" value="<?php echo $entidad; ?>">
<input type="hidden" id="validacion" value="<?php echo $validacion; ?>">
<input type="hidden" id="matricula">
<?php echo form_close(); ?>
<br>
<div class="row">
    <div class="col-md-12">
        <div id="lista_validadores"></div>
    </div>
</div>

<script type="text/javascript">
    var lista_validadores_disponibles = [];
    lista_validadores_disponibles = [
        {id_usuario: 100, matricula:'14342', nombre_validador: 'Val prueba', clave_categoria:'cve_categoria', categoria:'cat'}
    ];
    render_validadores(lista_validadores_disponibles);
</script>
