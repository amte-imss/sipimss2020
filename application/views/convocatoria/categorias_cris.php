<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
echo js('convocatoria/categorias.js');
?>

  <div id="page-inner">
    <div class="col-sm-12">
        <h1 class="page-head-line">
          Editar categorías
        </h1>
      </div>

      <div class="col-md-3">
        <div class="col-sm-1">
        </div>
          <a href="<?php echo site_url('convocatoria/get_convocatorias/'.$convocatoria['id_convocatoria']);?>"><span class="glyphicon glyphicon-triangle-left"></span>Regresar</a>
      </div>


        <div>
          <br>
            <?php
            echo form_open('convocatoria/get_categorias/'.$convocatoria['id_convocatoria']);
            ?>

            <div class="row">

                <div class="col-md-4">

                    <div class="input-group">
                        <span class="input-group-addon">Categoría</span>
                        <input type="hidden" id="id_categoria" name="id_categoria" value="">
                        <?php
                        echo $this->form_complete->create_element(
                                array('id' => 'categoria_texto',
                                    'type' => 'text',
                                    'attributes' => array(
                                        'class' => 'form-control',
                                        'data-toggle' => 'tooltip',
                                        'title' => 'Porcentaje de muestra')
                                )
                        );
                        ?>
                    </div>
                    <ul class="ul-autocomplete" id="categoria_autocomplete" style="display:none;"></ul>
                </div>

                <div class="col-md-4">
                  <br><br>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">Tipo de validación</span>
                        <select class="form-control" name="tipo">
                            <option>Seleccionar</option>
                            <option value="N1">N1</option>
                            <option value="N2">N2</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <input type="submit" value="Agregar" class="btn btn-tpl">
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
            <br>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="sorting table table-striped table-bordered table-hover dataTable">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($categorias as $row)
                            {
                                ?>
                                <tr>
                                    <td><?php echo $row['id_categoria']; ?></td>
                                    <td><?php echo $row['nombre']; ?></td>
                                    <td><?php echo $row['validacion']; ?></td>
                                    <td><a href="<?php echo site_url('convocatoria/get_categorias/' . $convocatoria['id_convocatoria'] . '/' . $row['id_categoria'].'/'.$row['validacion']); ?>">Quitar</a></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
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
