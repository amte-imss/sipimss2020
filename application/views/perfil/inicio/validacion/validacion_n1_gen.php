<?php $aux_doc = encrypt_base64($id_docente); ?>
<?php echo form_open('', array('id' => 'form_validacion_'. $aux_doc)); ?>
    <input id="docente" name="docente" type="hidden" value="<?php echo encrypt_base64($id_docente); ?>"> 
<?php echo form_close()?>
<?php ?>
<div class="col-md-12">
    <h1 class="page-head-line"></h1>
</div>
<div class="col-md-12" id="div_error_<?php echo $aux_doc;?>" style='display:none'>
    <div id="alerta_<?php echo $aux_doc;?>"  class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <span id="msg_<?php echo $aux_doc;?>"></span>
    </div>
</div>
<div class="md-form text-center">   
    <a id="item_finaliza_validacion" href="#item-contacto" 
    class=" btn btn-success text-center" data-toggle="modal" 
    data-target="#admin-finalizavalidacion" 
    style="background-color:#008EAD !important;"
    >
        <i class="dashboard"></i>Terminar validación
    </a>
</div>

<?php $controlador_val = '/'.$this->uri->rsegment(1).'/terminar_validacion'; ?>
        <div class="modal fade" id="admin-finalizavalidacion" tabindex="-1" role="dialog" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="padding:35px 50px;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4><span class="glyphicon glyphicon-lock"></span>Finalizar validación</h4>
                    </div>
                    <div class="modal-body" style="padding:40px 50px;">
                        <p>Al dar clic en "Terminar validación" se dará por concluida la validación, no permitirá editar los registros.</p> 
                        <p>Por favor confirme la finalización.</p>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button class="btn btn-success" data-dismiss="modal" 
                            id="btn_validacion<?php echo encrypt_base64($id_docente); ?>" 
                            style=" background-color:#008EAD" 
                            data-path="<?php echo $controlador_val; ?>"
                            data-doc="<?php echo encrypt_base64($id_docente); ?>"
                            onclick="guarda_validacion(this);"
                            >
                            Guardar<span class=""></span>
                        </button>
                    <!-- <button type="submit" class="btn btn-primary" onclick="finalizar_censo(this)">Finalizar censo</button>-->
                    </div>
                </div>
            </div>
        </div>