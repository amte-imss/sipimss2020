<?php
echo js('usuario/usuario.js');
 //pr($usuario);
 //pr($grupos_usuario);
?>
<div id="page-inner">
  <div class="col-sm-12">
      <h1 class="page-head-line">
           Información general</h1>
    </div>
    <div class="col-sm-12">
        <div class=""> <br><br>

            <div class="">
                <!--form usuario completo-->

                <?php
                echo form_open('usuario/editar/' . $usuario['id_usuario'] . '/' . Usuario::BASICOS, array('id' => 'form_actualizar'));
                ?>
                <div id="area_datos_basicos" class="col-md-12">
                    <?php echo $datos_basicos; ?>
                </div>
                <?php echo form_close(); ?>

            </div>
        </div>

    </div>

    <?php if(isset($super) && $super === true) { ?>
        <div class="row">
                <div class="col-sm-12">
                    <br><br>
                    <div class="col-sm-12">
                        <h6 class="page-head-line">
                            Actividad del usuario</h6>
                    </div>
                    <!--div id="status_actividad_usuario"></div-->
                    <div class="col-md-4">
                        <?php
                        $opciones_actividad = array(
                            0 => 'Desactivado', 1 => 'Activo'
                        );
                        echo form_open('usuario/editar/' . $usuario['id_usuario'] . '/' . Usuario::STATUS_ACTIVIDAD, array('id' => 'form_actualizar_actividad'));

                        echo $this->form_complete->create_element(
                            array(
                                'id' => 'status_actividad',
                                'type' => 'dropdown',
                                'options' => $opciones_actividad,
                                'value' => $usuario['usuario_activo']? 1: 0,
                                'attributes' => array(
                                    'class' => 'form-control'
                                )
                            )
                        );
                        ?>
                    </div>
                    <div>
                        <button id="submit" name="submit" type="submit" class="btn btn-success"  style=" background-color:#008EAD">Guardar <span class=""></span></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
        </div>
    <?php } ?>
        <br><br>
        <div class="row">
            <div class="col-sm-12">
                <div id="status_actividad_usuario"></div>
            </div>
        </div>

    <?php
    $permitir_reapertura = false;
    foreach ($grupos_usuario as $key => $grupo_usuario) {
        if($grupo_usuario['id_rol']==LNiveles_acceso::Docente && $grupo_usuario['activo']==1){
            $permitir_reapertura = true;
        }
    } 
    if($permitir_reapertura) { ?>
        <div class="row">
                <div class="col-sm-12">
                    <br>
                    <div class="col-sm-12">
                        <h6 class="page-head-line">
                            Actividad registro</h6>
                    </div>
                    <!--div id="status_reapertura_registro"></div-->
                    <div class="col-md-4">Este bloque permite la reapertura de la edición y alta de datos del docente, una vez que el usuario marcó como finalizado su registro docente.
                        <?php
                        $opciones_reapertura = array(
                            0 => 'Cerrado', 1 => 'Abierto'
                        );
                        echo form_open('usuario/editar/' . $usuario['id_usuario'] . '/' . Usuario::STATUS_REAPERTURA, array('id' => 'form_reapertura'));

                        echo $this->form_complete->create_element(
                            array(
                                'id' => 'status_reapertura',
                                'type' => 'dropdown',
                                'options' => $opciones_reapertura,
                                'value' => $usuario['activo_edicion'] ? 1: 0,
                                'attributes' => array(
                                    'class' => 'form-control'
                                )
                            )
                        );
                        ?>
                    </div>
                    <div>
                        <button id="submit2" name="submit" type="submit" class="btn btn-success"  style=" background-color:#008EAD">Guardar <span class=""></span></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
        </div>
    <?php } ?>

    <div>
      <br><br>
        <div class="row">
            <div class="col-sm-12">
              <br><br>
              <div class="col-sm-12">
                  <h6 class="page-head-line">
                       Contraseña de usuario</h6>
                </div>


                    <div class="col-md-12">
                        <div class="" style="text-aligne:center; width: 650px; text-align: left;">
                            <!--form usuario completo-->
                            <?php
                            echo form_open('usuario/editar/' . $usuario['id_usuario'] . '/' . Usuario::PASSWORD, array('id' => 'form_actualizar_password'));
                            ?>
                            <div id="campo_password">
                                <?php echo $campo_password; ?>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>

            </div>
        </div>
    </div>

    <div>
        <div class="row">
          <div class="col-md-12">
              <h1 class="page-head-line">
                   Roles</h1>
            </div>
            <div class="col-sm-12">
                <div >

                    <div class="">
                        <?php
                            $tipo_ent = '';
                        if(isset($entidad_atiende) && !is_null($entidad_atiende)){
                            $tipo_ent = '/1';
                        }
                        echo form_open('usuario/editar/' . $usuario['id_usuario'] . '/' . Usuario::NIVELES_ACCESO . $tipo_ent, array('id' => 'form_usuario_niveles'));
                        ?>
                        <div  id="campo_niveles_acceso">
                            <?php echo $campo_niveles_acceso; ?>
                        </div>
                        <?php
                        echo form_close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
