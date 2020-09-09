<?php
echo js('formularios/editar.js');
?>

<div id='editar_formulario'>

    <div ng-class="panelClass" class="row">
        <div class="col col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Editar formulario</h3>
                </div>

                <div class="panel-body"><br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row" style="margin:5px;">
                                <a href="javascript:history.back()" class="btn btn-primary">Regresar</a>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div id="form_mensaje"></div>
                        </div>
                    </div> <!-- row -->

                    <div class="row">
                        <?php
                        echo form_open('formulario/editar_formulario', array('id' => 'form_editar'));
                        ?>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row" style="margin:5px;">
                                <div class="row">
                                    <div id="id-input">
                                        <?php
                                        echo $this->form_complete->create_element(
                                                array(
                                                    'id' => 'id_formulario',
                                                    'name' => 'id_formulario',
                                                    'type' => 'hidden',
                                                    'value' => $id_formulario
                                                )
                                        );
                                        ?>
                                    </div>
                                </div>
                                <table class="table table-container-fluid panel">
                                    <tr>
                                        <td>Nombre*</td>
                                        <td>
                                            <?php
                                            echo $this->form_complete->create_element(
                                                    array(
                                                        'id' => 'nombre',
                                                        'type' => 'text',
                                                        'value' => $datos['nombre'],
                                                        'attributes' => array(
                                                            'class' => 'form-control',
                                                            'maxlength' => '128'
                                                        )
                                                    )
                                            );
                                            echo form_error_format('nombre');
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Etiqueta*</td>
                                        <td>
                                            <?php
                                            echo $this->form_complete->create_element(
                                                    array(
                                                        'id' => 'label',
                                                        'type' => 'text',
                                                        'value' => $datos['label'],
                                                        'attributes' => array(
                                                            'class' => 'form-control',
                                                            'maxlength' => '128'
                                                        )
                                                    )
                                            );
                                            echo form_error_format('label');
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Subsección*</td>
                                        <td>
                                            <?php
                                            echo $this->form_complete->create_element(
                                                    array(
                                                        'id' => 'id_elemento_seccion',
                                                        'type' => 'dropdown',
                                                        'options' => $subsecciones,
                                                        'value' => $datos['id_elemento_seccion'],
                                                        'attributes' => array(
                                                            'class' => 'form-control'
                                                        )
                                                    )
                                            );
                                            echo form_error_format('id_elemento_seccion');
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Descripción:</td>
                                        <td>
                                            <?php
                                            echo $this->form_complete->create_element(
                                                    array(
                                                        'id' => 'descripcion',
                                                        'type' => 'textarea',
                                                        'value' => $datos['descripcion'],
                                                        'attributes' => array(
                                                            'class' => 'form-control',
                                                            'rows' => '4'
                                                        )
                                                    )
                                            );
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CSS:</td>
                                        <td>
                                            <?php
                                            echo $this->form_complete->create_element(
                                                    array(
                                                        'id' => 'css',
                                                        'type' => 'textarea',
                                                        'value' => $datos['css'],
                                                        'attributes' => array(
                                                            'class' => 'form-control',
                                                            'rows' => '4'
                                                        )
                                                    )
                                            );
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Rutas(s) achivo(s) JS:</td>
                                        <td>
                                            <?php
                                            echo $this->form_complete->create_element(
                                                    array(
                                                        'id' => 'ruta_file_js',
                                                        'type' => 'textarea',
                                                        'value' => $datos['ruta_file_js'],
                                                        'attributes' => array(
                                                            'class' => 'form-control',
                                                            'rows' => '4'
                                                        )
                                                    )
                                            );
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Activo*:</td>
                                        <td>
                                            <?php
                                            echo $this->form_complete->create_element(
                                                    array(
                                                        'id' => 'activo',
                                                        'type' => 'dropdown',
                                                        'options' => array(
                                                            1 => "si",
                                                            0 => "no"
                                                        ),
                                                        'value' => $datos['activo']
                                                    )
                                            );
                                            echo form_error_format('activo');
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div> <!--col-->
                        <br>
                        <div class="row">
                            <div class="col-lg-offset-3 col-lg-3 col-md-offset-3 col-md-3 col-sm-offset-3 col-sm-3">
                                <center>
                                    <input type="submit" class="ui-input-button" value="Guardar">
                                </center>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <center>
                                    <button onclick="regresar()" class="ui-input-button">Cancelar</button>
                                </center>
                            </div>
                        </div>
                        <?php
                        echo form_close();
                        ?>
                    </div> <!--row-->

                </div> <!-- panel body -->
            </div>
        </div>
    </div>
