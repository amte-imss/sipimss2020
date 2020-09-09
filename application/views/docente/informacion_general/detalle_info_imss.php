<?php
$string_value = get_elementos_lenguaje(array(En_catalogo_textos::INFORMACION_GENERAL));
?>
<div class="row">
  <div class="col-md-6">
    <div class="row">
      <div class="col-md-4">
        <label for="" class="control-label">
          <?php echo $string_value['lbl_clave_delegacional']; ?>
        </label>
      </div>
      <div class="col-md-8">
        <label for="" class="control-label">
          <?php echo (isset($delegacion)) ? $delegacion : ''; ?>
        </label>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="row">
            <div class="col-md-4">
                <label for="" class="control-label">
                    <?php echo $string_value['lbl_region']; ?>
                </label>
            </div>
            <div class="col-md-8">
                <label for="" class="control-label">
                    <?php echo (isset($region)) ? $region : ''; ?>
                </label>
            </div>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-4">
                <label for="" class="control-label">
                    <?php echo $string_value['lbl_clave_departamental']; ?>
                </label>
            </div>
            <div class="col-md-8">
                <label for="" class="control-label">
                    <?php echo (isset($departamento)) ? $departamento : ''; ?>
                </label>
            </div>
        </div>
    </div>
    <?php if ((isset($nivel_atencion))) { ?>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-4">
                    <label for="" class="control-label">
                        <?php echo $string_value['lbl_nivel_atencion']; ?>
                    </label>
                </div>
                <div class="col-md-8">
                    <label for="" class="control-label">
                        <?php echo $string_value['r_nivel_atencion_' . $nivel_atencion]; ?>
                    </label>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<br>
<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-4">
                <label for="" class="control-label">
                    <?php echo $string_value['lbl_clave_categoria']; ?>
                </label>
            </div>
            <div class="col-md-8">
                <label for="" class="control-label">
                    <?php echo (isset($categoria)) ? $categoria : ''; ?>
                </label>
            </div>
        </div>
    </div>
</div>

<br>

<div class="row">
    <div class="col-md-12 text-center">

        <a class="btn btn-tpl" data-toggle="modal" data-target="#my_modal"
           onclick="funcion_ver_datos_siap(this);"
           data-url="<?php echo '/' . $this->uri->rsegment(1); ?>">
               <?php echo $string_value['btn_actualiza_datos_imss'] ?>
        </a>
    </div>
</div>
