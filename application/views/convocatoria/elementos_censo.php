<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
echo js('convocatoria/elementos_censo.js');
?>
<div id="page-inner">
  <div class="col-sm-12">
      <h1 class="page-head-line">
      Región
      </h1>
    </div>
    <div class="col-md-12">
        <a href="<?php echo site_url('convocatoria/get_convocatorias/' . $convocatoria['id_convocatoria']); ?>"><span class="glyphicon glyphicon-triangle-left"></span>Regresar</a>
    </div>
        <div class="">
            <div class="">
                <?php echo form_open('convocatoria/get_elementos/' . $convocatoria['id_convocatoria'], array('id' => 'form_convocatoria')); ?>
                <?php
                foreach ($elementos as $key => $value)
                {
                    ?>
                    <div class="col-md-12">
                        <br>
                      <div class="col-md-11">

                      </div>
                      <input type="submit" class="btn btn-tpl" value="Guardar">
                    </div>
                    <div class="col-sm-12">
                          <br>
                          <br>
                      <div class="table-responsive">
                        <table class="sorting table table-striped table-bordered table-hover dataTable">
                          <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Acciones</th>
                                <th>Activo/Inactivo</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            foreach ($value as $row)
                            {
                                ?>
                                <tr>
                                    <td><?php echo $row['nombre']; ?></td>
                                    <td>
                                        <?php
                                        if ($row['activa'])
                                        {
                                            ?>
                                            <a onclick="get_validadores(<?php echo $convocatoria['id_convocatoria']; ?>, '<?php echo $key; ?>', <?php echo $row['id_entidad']; ?>, 'N1')" href="#" data-toggle="modal" data-target="#my_modal">Asignar validadores N1</a> |
                                            <a onclick="get_validadores(<?php echo $convocatoria['id_convocatoria']; ?>, '<?php echo $key; ?>', <?php echo $row['id_entidad']; ?>, 'N2')" href="#" data-toggle="modal" data-target="#my_modal">Asignar validadores N2</a>
                                            <?php
                                        }

                                        ?>
                                      </td>
                                      <td>
                                        <?php
                                        $atributos = array(
                                            'class' => 'form-control',
                                            'data-toggle' => 'tooltip');
                                        if($row['activa']){
                                            $atributos['checked'] = true;
                                        }


                                        echo $this->form_complete->create_element(
                                                array('id' => 'entidad_' . $key . '_' . $row['id_entidad'],
                                                    'type' => 'checkbox',
                                                    'attributes' => $atributos

                                                )
                                        );

                                         ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                          </tbody>
                        </table>
                    <?php
                }
                ?>

                    </div>
                      </div>


                <?php echo form_close(); ?>
            </div>

            <div class="col-md-12">
                <br><br>
                <div class="panel panel-success">
                  <div class="panel-heading">
                     <h3 class="panel-title">Convocatoria</h3>
                  </div>
                  <div class="panel-body">
                    <?php include 'cabecera_censo.php'; ?>
                  </div>
                </div>
            </div>
        </div>


</div>
