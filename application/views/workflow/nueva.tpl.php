<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
echo js('workflow/modal_nueva.js');
echo form_open('#', array('id' => 'form_workflow'));
?>
<div id="page-inner">
    <div style="display:none;" id="div-error-general" class="alert alert-danger convocatoria-error" role="alert"></div>
    <div class="row">
        <div class="form-group">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Tipo:</span>
                    <?php
                    echo $this->form_complete->create_element(
                            array('id' => 'id_workflow',
                                'type' => 'dropdown',
                                'first' => array('' => 'Seleccione...'),
                                'options' => $tipos_lineas,
                                'attributes' => array(
                                    'required' => true,
                                    'class' => 'form-control',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Tipo',
                                    'onchange' => 'get_contenido(this)')
                            )
                    );
                    ?>
                </div>
                <div style="display:none;" id="div-requerido-id_workflow" class="alert alert-danger convocatoria-requerido" role="alert">Campo requerido</div>
                <div style="display:none;" id="div-error-id_workflow" class="alert alert-danger convocatoria-error" role="alert"></div>
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
                                    'required' => true,
                                    'class' => 'form-control',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Nombre de la convocatoria')
                            )
                    );
                    ?>
                </div>
                <div style="display:none;" id="div-requerido-nombre" class="alert alert-danger convocatoria-requerido" role="alert">Campo requerido</div>
                <div style="display:none;" id="div-error-nombre" class="alert alert-danger convocatoria-error" role="alert"></div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Clave</span>
                    <?php
                    echo $this->form_complete->create_element(
                            array('id' => 'clave',
                                'type' => 'text',
                                'attributes' => array(
                                    'required' => true,
                                    'class' => 'form-control',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Clave de la convocatoria')
                            )
                    );
                    ?>
                </div>
                <div style="display:none;" id="div-requerido-clave" class="alert alert-danger convocatoria-requerido" role="alert">Campo requerido</div>
                <div style="display:none;" id="div-error-clave" class="alert alert-danger convocatoria-error" role="alert"></div>
            </div>
        </div>
    </div>
    <div id="workflow_contenido"></div>

    <br>
    <div class="row">
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#cancelModal">Cancelar</button>
        <button id="workflow_boton_save" type="button" class="btn btn-tpl" style="display: none;" data-toggle="modal" data-target="#confirmModal">Guardar cambios</button>
    </div>
    <?php echo form_close(); ?>
    <!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Desea continuar?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" onclick="workflow_submit()" class="btn btn-primary">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
     <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Desea cancelar la nueva linea de tiempo?
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary">Cancelar</button>
                    <a href="<?php echo site_url('workflow'); ?>" class="btn btn-primary">Aceptar</a>
                </div>
            </div>
        </div>
    </div>
</div>
