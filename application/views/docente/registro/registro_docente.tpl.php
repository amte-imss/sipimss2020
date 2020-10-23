<?php
$controlador = '/' . $this->uri->rsegment(1).'/registro_docente';
?>
<div id="main_content" class="">
            <div id="page-inner">
                    <h4><span class="glyphicon glyphicon-lock"></span>Registro de usuario</h4>
                        <?php
                        if(isset($registro_valido))
                        {
                            $tipo = $registro_valido['result']?'success':'danger';
                            echo html_message($registro_valido['msg'], $tipo);
                        }
                        ?>
                        <div class="col-lg-3 col-md-2"></div>
                        <div id="area_registro" class="form col-lg-6 col-md-8 ">
                            <?php echo form_open($controlador, array('id' => 'registro_form', 'autocomplete' => 'off')); ?>
                            <div class="sign-in-htm">
                                <div class="form-group">
                                    <label for="user" class="pull-left"><span class="glyphicon glyphicon-user"></span> Matrícula:</label>
                                    <?php
                                    echo $this->form_complete->create_element(array('id' => 'reg_usuario',
                                    'type' => 'text',
                                    'value' => isset($post['reg_usuario'])?$post['reg_usuario']:'',
                                    'attributes' => array(
                                        'class' => 'form-control',
                                        'required'=>true,
                                        'placeholder' =>$texts['user']
                                    )));
                                    echo form_error_format('reg_usuario');
                                    ?>
                                </div>                                
                                <div class="form-group">
                                    <label for="delegacion" class="pull-left"><span class="glyphicon glyphicon-user"></span> OOAD:</label>
                                    <?php
                                    echo $this->form_complete->create_element(array('id' => 'id_delegacion',
                                    'type' => 'dropdown',
                                    'first' => array('' => 'Seleccione una opción'),
                                    'options' => $delegaciones,
                                    'value' => isset($post['id_delegacion'])?$post['id_delegacion']:'',
                                    'attributes' => array(
                                        'class' => 'form-control',
                                        'required'=>true,
                                        'placeholder' =>$texts['user']
                                    )));
                                    echo form_error_format('delegacion');
                                    ?>
                                </div>
                                <!--div class="form-group">
                                    <label for="username_alias" class="pull-left"><span class="glyphicon glyphicon-user"></span> Nombre de usuario(alias):</label>
                                    <?php
                                    echo $this->form_complete->create_element(array('id' => 'username_alias',
                                    'type' => 'hidden',
                                    'value' => isset($post['username_alias'])?$post['username_alias']:'',
                                    'attributes' => array(
                                        'class' => 'form-control',
                                        'required'=>true,
                                        'placeholder' =>$texts['username_alias']
                                    )));
                                    echo form_error_format('username_alias');
                                    ?>
                                </div-->
                                <div class="form-group">
                                    <label for="delegacion" class="pull-left"><span class="glyphicon glyphicon-user"></span> Correo electrónico:</label>
                                    <?php
                                    echo $this->form_complete->create_element(array('id' => 'reg_email',
                                    'type' => 'email',
                                    'value' => isset($post['reg_email'])?$post['reg_email']:'',
                                    'attributes' => array(
                                        'class' => 'form-control',
                                        'required'=>true,
                                        'placeholder' =>$texts['email']
                                    )));
                                    echo form_error_format('reg_email');
                                    ?>
                                </div>
                                <div class="form-group">
                                    <label for="reg_password" class="pull-left"><span class="glyphicon glyphicon-eye-open"></span> Contraseña:</label>
                                    <?php
                                    echo $this->form_complete->create_element(array('id' => 'reg_password',
                                    'type' => 'password',
                                    'value' => isset($post['reg_password'])?$post['reg_password']:'',
                                    'attributes' => array(
                                        'class' => 'input form-control',
                                        'required'=>true,
                                        'placeholder' =>$texts['passwd']
                                    )));
                                    echo form_error_format('reg_password');
                                    ?>
                                </div>
                                <div class="form-group">
                                    <label for="reg_repassword" class="pull-left"><span class="glyphicon glyphicon-eye-open"></span> Confirma contraseña:</label>
                                    <?php
                                    echo $this->form_complete->create_element(array('id' => 'reg_repassword',
                                    'type' => 'password',
                                    'value' => isset($post['reg_repassword'])?$post['reg_repassword']:'',
                                    'attributes' => array(
                                        'class' => 'input form-control',
                                        'required'=>true,
                                        'placeholder' =>$texts['passwd']
                                    )));
                                    echo form_error_format('reg_repassword');
                                    ?>
                                </div>                                
                                <br>
                                <div class="">
                                    <input type="submit" class="btn btn-success btn-block" value="Iniciar sesión">
                                </div>

                            </div>
                            <?php echo form_close(); ?>
                        </div>
                        <div class="col-lg-3 col-md-2"></div>
                    </div>
</div>


       