<?php
foreach($crud->css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($crud->js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <!-- <h3 class="panel-title">Añadir</h3> -->
            </div>
            <div class="panel-body"><br>
			  <?php
			  if(isset($status))
			  {
				 $tp = $status['result']?'success':'danger';
				 echo html_message($status['msg'], $tp);
			  }

			  ?>
              <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row" style="margin:5px;">
                      <a  href="javascript:history.back()" class="btn btn-primary">Regresar</a>
					  <a href="#formulario_copiar" class="btn btn-primary" data-toggle="modal" data-target="#formulario_copiar">Copiar de otro formulario existente</a>
                    </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row" style="margin:5px;">
                      <a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" href="<?php echo base_url('/index.php/formulario/nuevo_campo_formulario/'.$id_formulario); ?>">
                        <span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span>
                        <span class="ui-button-text">Añadir nuevo campo</span>
                      </a>
                    </div>
                  </div>

                  <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row" style="margin:5px;">
                      <div class="table table-container-fluid panel">
				              <?php echo $crud->output;?>
    		              </div>
                    </div>
                  </div>
              </div> <!--row-->
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="formulario_copiar" tabindex="3" role="dialog" aria-labelledby="formulario_copiar">
		<div class="modal-dialog modal-lg" id="formulario_modal_content" role="document">
			<div class="modal-content">
			    <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="formulario_modal_titulo">
						Copiar campos a partir de otro formulario
			        </h4>
			    </div>

			    <div class="col-md-12 col-sm-12 col-xs-12 " id='modal_error' style='display:none'>
			        <div id='formulario_mensaje_error_modal' class='alert alert-info'>
			            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			            <span id='formulario_mensaje_error_modal'></span>
			        </div>
			    </div>
			    <div class="modal-body" id="formulario_modal_cuerpo">
					<?php
					echo form_open(site_url('/formulario/campos_formulario/'.$id_formulario), array('id' => 'form_formulario_copiar', 'autocomplete'=>"off"));
					?>
					<div class="col-md-6" style="display: 1">
						<div class="row">
							<div class="col-md-4">
								<label for="copiar" class="control-label">
									<b class="rojo">*</b> Formulario origen:
								</label>
							</div>
							<div class="col-md-8">
								<div class="input-group">
									<span class="input-group-addon">
										<span class="fa fa-female"> </span>
									</span>
									<?php
									echo $this->form_complete->create_element(array(
										'id' => 'copiar',
										 'type' => 'dropdown',
										  'options' => $formularios_activos,
										  'first' => array('' => 'Seleccione una opción'),
										  'attributes' => array( 'class' => 'form-control')));
									?>
	                            </div>
							</div>
							<?php echo form_error_format('copiar'); ?>
						</div>
					</div>
					<br><br>
					<div class="form-group">
						<label class="control-label"></label>
						<button id="submit" name="submit" type="submit" class="btn btn-tpl">Registrar   <span class="glyphicon glyphicon-send"></span></button>
					</div>
					<br><br>
					<?php
					echo form_close();
					?>
			    </div>
			</div>
		</div>
</div>
