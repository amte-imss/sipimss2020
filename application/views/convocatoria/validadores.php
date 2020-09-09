<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
//pr($validadores);
echo js('convocatoria/validadores.js');
echo form_open('convocatoria/get_validadores/', array('id' => 'form_convocatoria'));
?>
<div class="row">
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-addon">Matricula</span>                                    
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'matricula',
                        'type' => 'text',
                        'attributes' => array(
                            'class' => 'form-control',
                            'data-toggle' => 'tooltip',
                            'title' => 'Matricula')
                    )
            );
            ?> 
        </div>        
    </div>    
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <input type="submit" value="Buscar" class="btn btn-primary">
        </div> 
    </div>
</div>
<?php echo form_close(); ?>
<br>
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Matricula</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($validadores as $row)
                {
                    ?>
                    <tr>
                        <td><?php echo $row['matricula']; ?></td>
                        <td><?php echo $row['categoria']; ?></td>
                        <td><?php
                            echo form_open("convocatoria/get_validadores/{$filtros['id_convocatoria']}/{$filtros['tipo_entidad']}/{$filtros['id_entidad']}/{$filtros['validacion']}", array('id' => 'form_'.$row['id_docente']));
                            //, {$filtros['id_convocatoria']}, '{$filtros['tipo_entidad']}', {$filtros['id_entidad']}, '{$filtros['validacion']}'
                            $atributos = array(
                                'class' => 'form-control',
                                'data-toggle' => 'tooltip',
                                'onchange' => "upsert_validador('{$row['id_docente']}')");
                            if ($row['activo'])
                            {
                                $atributos['checked'] = true;
                            }
                            echo $this->form_complete->create_element(
                                    array('id' => 'activo' . $row['id_docente'],
                                        'type' => 'checkbox',
                                        'attributes' => $atributos
                                    )
                            );
                            ?>
                            <input type="hidden" name="id_docente" value="<?php echo $row['id_docente'];?>">
                            <?php
                            echo form_close();
                            ?>
                        </td>                    
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
