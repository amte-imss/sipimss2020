<?php
echo form_open_multipart('registro/index/cargar');
?>
<div id="page-inner">
    <div class="">
        <div class="row">
            <div class="col col-sm-12">
                <h1 class="page-head-line">
                    <br>
                    Precarga DIE
                </h1>
            </div>
            <div class="panel-default">
                <div class="form-inline" role="form" id="informacion_general">
                	<br>
                  <?php
                    if(isset($mensaje))
                    { ?>
                      <p style="text-indent: 130px;">  <?php echo $mensaje;?> </p>
                  <?php
                    }
                  ?>

                  <p id="mensajeRegistro" style="text-indent: 130px;">Seleccione una seccion para obtener los formulario</p>
                  <p id="mensajeFormulario" style="text-indent: 130px;"></p>
                	<div class="row">
                      <div class="col-md-1"></div>
                    	<div class="col-md-11">
                    		<div class="row">
                    			<div class="col-md-1">
                    				<label for="paterno" class="righthoralign control-label">
                    					<b class="rojo">*</b>Seccion:
                    				</label>
                    			</div>
                    			<div class="col-md-2">
                    				<div class="input-group">
                    					<span class="input-group-addon">
                    						<span class="fa fa-male"> </span>
                    					</span>
                              <select id="seccion" name="id_seccion" class="form-control" onchange="muestraFormularios(this)">
                                <option value="">Seleccionar</option>
                                <?php
                                    foreach ($secciones as $key => $value) { ?>
                                      <option value="<?php echo $value['id_seccion'];?>"><?php echo $value['label']; ?></option>
                                  <?php  }
                                ?>
                              </select>
                    				</div>
                    			</div>
                          <div class="col-md-1">
                    				<label for="paterno" class="righthoralign control-label">
                    					<b class="rojo">*</b>Formulario:
                    				</label>
                    			</div>
                          <div class="col-md-8" id="formularioRegistro">
                            <div class="row">
                              <div class="input-group">
                      					<span class="input-group-addon">
                      						<span class="fa fa-male"> </span>
                      					</span>
                                <select disabled="true" id="formulario" name="id_formulario" class="form-control" onchange="muestraBotonExportar(this)">
                                  <option value="">Seleccionar</option>
                                </select>
                      				</div>
                              <a disabled="true" id="descargarEstrutura" style="display:none" href="" class="btn btn-primary"></a>
                            </div>
                    			</div>
                    		</div>
                        <br>
                        <div class="row">
                          <div class="col-md-4">
                            <p>¿El formulario que cargará esta incompleto o completo?</p>
                            <input disabled="true" type="radio" id="rCompleto" name="completo" value="completo" checked="checked"> Completo<br>
                            <input disabled="true" type="radio" id="rIncompleto" name="completo" value="incompleto"> Incompleto
                          </div>
                          <div class="col-md-8">
                            <p>Seleccione el archivo para la carga de datos</p>
                            <?php
                            echo $this->form_complete->create_element(array('id' => 'fformulario',
                            'type' => 'file',
                            'attributes' => array(
                                'disabled' => true,
                                'name' => 'fformulario',
                                'required'=>true
                            )));
                            ?>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <button id="submit" name="submit" type="submit" class="btn btn-tpl">Subir datos   <span class="glyphicon glyphicon-send"></span></button>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<?php echo js('registro/registro.js'); ?>
