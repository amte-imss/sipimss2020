    <li>
        <a id="item-contacto" href="#item-contacto" class="tablero-menu-item"
        data-toggle="modal" data-target="#admin-finalizacensogeneral" style="text-decoration: none; background-color: #006a62 !important; color: #fff;">
        <i class="dashboard"></i>De clic aquí para finalizar el registro del censo</a>
    </li>
    <div class="modal fade" id="admin-finalizacensogeneral" tabindex="-1" role="dialog" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding:35px 50px;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4><span class="glyphicon glyphicon-lock"></span>Finalizar registro del censo</h4>
                </div>
                <div class="modal-body" style="padding:40px 50px;">
                    <p>Al dar clic en "Finalizará el registro del censo" se dará por concluido el registro docente, no le permitirá agregar / editar más registros.</p> 
                    <p>Por favor confirme la finalización.</p>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <a href="<?php echo site_url('convocatoriaV2/finaliza_convocatoria_docente_censo_general/'); ?>" class="btn btn-primary">
                        Finalizar registro
                    </a>
                    <!-- <button type="submit" class="btn btn-primary" onclick="finalizar_censo(this)">Finalizar censo</button>-->
                </div>
            </div>
        </div>
    </div>
