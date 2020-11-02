<?php $aux_doc = encrypt_base64($id_docente);   
?>

<div class="col-md-12" id="div_error_<?php echo $aux_doc;?>" style='display:none'>
    <div id="alerta_<?php echo $aux_doc;?>"  class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <span id="msg_<?php echo $aux_doc;?>"></span>
    </div>
</div>
<div class="md-form text-center">   
    <a id="item_finaliza_ratificacion" href="#item-contacto" 
    class=" btn btn-success text-center" data-toggle="modal" 
    data-target="#admin-finalizaratificacion" 
    style="background-color:#008EAD !important;"
    >
        <i class="dashboard"></i>Terminar ratificación
    </a>
</div>

<?php $controlador_val = '/'.$this->uri->rsegment(1).'/ratificar_validacion'; ?>
        <div class="modal fade" id="admin-finalizaratificacion" tabindex="-1" role="dialog" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="padding:35px 50px;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4><span class="glyphicon glyphicon-lock"></span>Terminar ratificación</h4>
                    </div>
                    <div class="modal-body" style="padding:40px 50px;">
                        <p>Al dar clic en "Terminar ratificación" se dará por concluida la ratificación, no podrá modificar el veredicto.</p> 
                        <p>Por favor confirme la finalización.</p>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button class="btn btn-success" data-dismiss="modal" 
                            id="btn_validacion<?php echo encrypt_base64($id_docente); ?>" 
                            style=" background-color:#008EAD" 
                            data-path="<?php echo $controlador_val; ?>"
                            data-doc="<?php echo encrypt_base64($id_docente); ?>"
                            onclick="guarda_ratificacion(this);"
                            >
                            Terminar ratificación<span class=""></span>
                        </button>
                    <!-- <button type="submit" class="btn btn-primary" onclick="finalizar_censo(this)">Finalizar censo</button>-->
                    </div>
                </div>
            </div>
        </div>