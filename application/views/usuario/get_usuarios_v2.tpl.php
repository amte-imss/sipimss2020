<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.css'); ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid-theme.min.css'); ?>" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.js"></script>
<?php echo js('usuario/lista.js'); ?>
<div id="page-inner">
  <div class="col-md-12" id="div_error" style='display:none'>
      <div id="alerta"  class="alert alert-danger" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
          <span id="msg"></span>
      </div>
  </div>
    <div class="col-sm-12">
        <h1 class="page-head-line">
            Lista de usuarios
        </h1>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <div>
                <div class="pager-panel">
                    <label>Cantidad por pagina:
                        <select id="pager">
                            <option selected>5</option>
                            <option>10</option>
                            <option>20</option>
                            <option>50</option>
                            <option>100</option>
                            <option>200</option>                            
                        </select>
                    </label>
                </div>
                <div class="">
                    <div id="lista_usuarios">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_entrar_como" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false" data-keyboard="false">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button  type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Entrar como...</h4>
      </div>
      <div class="modal-body">
        <p>¿Confirma que desea entrar como el usuario seleccionado?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
     <form id="form_entrar_como" method="post">
        <input type="submit" class="btn btn-primary" value="Continuar">
     </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal_eliminar_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false" data-keyboard="false">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button id="close_delete" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Eliminar usuario...</h4>
      </div>
      <div class="modal-body">
        <p>Al seleccionar continuar, eliminará todo registro relacionado con el usuario </p>
        <p>¿Confirma que desea eliminar el usuario? </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
     <form id="form_eliminar_usuario" method="post">

        <input id="usuario_expuesto" type="hidden"  value="">
        <input type="button" class="btn btn-primary" onclick="elimina_usuario_registro(this);" value="Continuar">
     </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->