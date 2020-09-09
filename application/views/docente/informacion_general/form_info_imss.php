<?php
$string_value = get_elementos_lenguaje(array(En_catalogo_textos::INFORMACION_GENERAL));
?>
<form id="form_datos_imss" method="post" accept-charset="utf-8">

    <br>
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="row">
                <div class="col-md-4 text-center">
                    <label for="paterno" class="control-label">
                        <b class="rojo">*</b>
                        <?php echo $string_value['lbl_clave_delegacional']; ?> 
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 text-center">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <span class="fa fa-male"> </span>
                        </span>
                        <?php
                        echo $this->form_complete->create_element(
                                array('id' => 'clave_delegacional',
                                    'type' => 'dropdown',
                                    'first' => array('' => 'Selecciona opción'),
                                    'options' => $delegaciones,
                                    'firts' => array('' => 'seleccione delegacion'),
                                    'value' => (isset($clave_delegacional)) ? $clave_delegacional : '',
                                    'attributes' => array(
                                        'class' => 'form-control  form-control',
                                        'title' => $string_value['lbl_clave_delegacional'],
                                    ),
                                )
                        );
                        ?>
                    </div>
                </div>
            </div>
            <?php echo form_error_format('clave_delegacional'); ?>
        </div>
    </div>
</form>
