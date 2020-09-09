<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 * Oh!   pero que pasa, no creí que fuera posible pero ahora alguien más sabe lo
 * que te fumaste ese día y creo que fue de la buena
 */
//pr($config);
?>
<div>
    <!--<p>Localizador de sede</p>-->
    <input id="localizador_sede_config_<?php echo $config['data_index']; ?>" type="hidden" data-index="<?php echo $config['data_index']; ?>" name="config" value="<?php echo base64_encode(json_encode($config)); ?>">
    <div class="form-group">
        <div class="col col-md-12 col-lg-12 col-sm-12">
            <div class="col-md-4">
                <div class="input-group input-group">
                    <span class="input-group-addon">Localizar por:</span>
                    <?php
                    echo $this->form_complete->create_element(
                            array('id' => 'localizador_sede_id_servicio_' . $config['data_index'],
                                'type' => 'dropdown',
                                'first' => array('' => 'Seleccione...'),
                                'options' => $servicios,
                                'attributes' => array(
                                    'class' => 'form-control',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Nivel de atención',
                                    'data-index' => $config['data_index'],
                                    'onchange' => 'localizador_sede_servicio(this)')
                            )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col col-md-12 col-lg-12 col-sm-12">
            <?php
            if (!isset($config['configuraciones']['mostrar_nivel_atencion']) || $config['configuraciones']['mostrar_nivel_atencion'] == 1)
            {
                ?>
                <div class="col-md-4" style="display:none;">
                    <div class="input-group input-group">
                        <span class="input-group-addon">Nivel:</span>
                        <?php
                        echo $this->form_complete->create_element(
                                array('id' => 'localizador_sede_id_nivel_' . $config['data_index'],
                                    'type' => 'dropdown',
                                    'first' => array('' => 'Seleccione...'),
                                    'options' => $niveles,
                                    'attributes' => array(
                                        'class' => 'form-control',
                                        'data-toggle' => 'tooltip',
                                        'title' => 'Nivel de atención',
                                        'data-index' => $config['data_index'],
                                        'onchange' => 'localizador_sede_nivel(this)')
                                )
                        );
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>

            <?php
            if (!isset($config['configuraciones']['mostrar_tipo_unidad']) || $config['configuraciones']['mostrar_tipo_unidad'] == 1)
            {
                ?>
                <div class="col-md-4" style="display:none;">
                    <div class="input-group input-group">
                        <span class="input-group-addon">Tipo unidad:</span>
                        <?php
                        echo $this->form_complete->create_element(
                                array('id' => 'localizador_sede_id_tipo_unidad_' . $config['data_index'],
                                    'type' => 'dropdown',
                                    'first' => array('' => 'Seleccione...'),
                                    'attributes' => array(
                                        'class' => 'form-control',
                                        'data-toggle' => 'tooltip',
                                        'title' => 'Tipo de unidad',
                                        'data-index' => $config['data_index'],
                                        'onchange' => 'localizador_sede_tipo_unidad(this)')
                                )
                        );
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>

            <div class="col-md-4" style="display:none;">
                <div class="input-group input-group">
                    <span class="input-group-addon">UMAE:</span>
                    <?php
                    echo $this->form_complete->create_element(
                            array('id' => 'localizador_sede_cve_umae_' . $config['data_index'],
                                'type' => 'dropdown',
                                'first' => array('' => 'Seleccione...'),
                                'options' => $umaes,
                                'attributes' => array(
                                    'class' => 'form-control',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'UMAE',
                                    'data-index' => $config['data_index'],
                                    'onchange' => 'localizador_sede_umae(this)')
                            )
                    );
                    ?>
                </div>
            </div>

            <div class="col-md-4" style="display:none;">
                <div class="input-group input-group">
                    <span class="input-group-addon">UNIDAD NORMATIVA:</span>
                    <?php
                    echo $this->form_complete->create_element(
                            array('id' => 'localizador_sede_cve_unidad_normativa_' . $config['data_index'],
                                'type' => 'dropdown',
                                'first' => array('' => 'Seleccione...'),
                                'options' => $unidades_normativas,
                                'attributes' => array(
                                    'class' => 'form-control',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'UNIDAD NORMATIVA',
                                    'data-index' => $config['data_index'],
                                    'onchange' => 'localizador_sede_unidad_normativa(this)')
                            )
                    );
                    ?>
                </div>
            </div>

            <div class="col-md-4" style="display:none;">
                <div class="input-group input-group">
                    <span class="input-group-addon">COORDINACIÓN:</span>
                    <?php
                    echo $this->form_complete->create_element(
                            array('id' => 'localizador_sede_cve_coordinacion_' . $config['data_index'],
                                'type' => 'dropdown',
                                'first' => array('' => 'Seleccione...'),
                                'attributes' => array(
                                    'class' => 'form-control',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'COORDINACIÓN',
                                    'data-index' => $config['data_index'],
                                    'onchange' => 'localizador_sede_coordinacion(this)')
                            )
                    );
                    ?>
                </div>
            </div>

            <div class="col-md-4" style="display:none;">
                <div class="input-group input-group">
                    <span class="input-group-addon">Delegación:</span>
                    <?php
                    echo $this->form_complete->create_element(
                            array('id' => 'localizador_sede_id_delegacion_' . $config['data_index'],
                                'type' => 'dropdown',
                                'first' => array('' => 'Seleccione...'),
                                'options' => $delegaciones,
                                'attributes' => array(
                                    'class' => 'form-control',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Delegaciones',
                                    'data-index' => $config['data_index'],
                                    'onchange' => 'localizador_sede_delegacion(this)')
                            )
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
    <br>
    <label>Cantidad por pagina:
        <select id="localizador_sede_pager_<?php echo $config['data_index']; ?>">
            <option>5</option>
            <option>10</option>
            <option selected>25</option>
            <option>50</option>
            <option>100</option>
            <option>200</option>
        </select>
    </label>
    <button style="display:none;" type="button" name="button" id="localizador_sede_button_select_all_<?php echo $config['data_index']; ?>" data-index="<?php echo $config['data_index'];?>">Seleccionar todos</button>
    <div id="localizador_sede_table_<?php echo $config['data_index']; ?>">

    </div>
</div>
