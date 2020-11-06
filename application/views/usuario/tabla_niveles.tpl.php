<?php

if (isset($status) && $status)
{
    echo html_message('Usuario actualizado con éxito', 'success');
} else if (isset($status))
{
    echo html_message('Falla al actualizar usuario', 'danger');
    

}
?>
<script>
                        var name_validador2 = '';
                    </script>
<input type="hidden" name="niveles" value="1">
<table class="table table-bordered">
    <thead>
    <th>Nombre</th>
    <th>Activo</th>
</thead>
<tbody>
    <?php
    foreach ($grupos_usuario as $row)
    {
        ?>
        <tr>
            <td><?php echo $row['nombre']; ?></td>
            <td>
                <?php
               $attr = array('name' => 'activo' . $row['id_rol'],
               'class' => 'form-control  form-control input-sm',
               'data-toggle' => 'tooltip',
               'data-placement' => 'top',
               'title' => 'activo',
               'checked' => $row['activo']
            );

                if(!is_null($entidad_atiende) && $row['id_rol'] == LNiveles_acceso::Validador2){
                    $attr['onclick'] = 'selecciona_entidad(this)';
                    ?>
                    <script>
                         name_validador2 = "<?php echo 'activo' . $row['id_rol']?>"; 
                    </script>
                    <?php
                }
                echo $this->form_complete->create_element(
                        array('id' => 'activo' . $row['id_rol'],
                            'type' => 'checkbox',
                            'attributes' =>$attr
                        )
                );
                ?>
            </td>
        </tr>
<?php } ?>
</tbody>
</table>

<?php 
if (isset($status) && !$status)
{
    echo html_message($msg, 'danger');
}
 ?>
<?php echo $entidad_atiende; ?>

<div class="col-md-12">
  <div class="col-md-5">

  </div>
  <div class="col-md-2">
      <?php
      echo $this->form_complete->create_element(array(
          'id' => 'btn_submit',
          'type' => 'submit',
          'value' => 'Guardar',
          'attributes' => array(
              'class' => 'btn btn-tpl',
          ),
      ));
      ?>
  </div>

</div>

<script>
    $(document).ready(function () {
        if(document.getElementById(name_validador2).checked){
            $("#list_entidades_asignadas").css("display", "block");
        }else{
            $("#list_entidades_asignadas").css("display", "none");
        }
        
    });
    function selecciona_entidad(element){
        var c = element.checked;
        if(c){
            $("#list_entidades_asignadas").css("display", "block");
            //ooadlist
            //umaelist
        }else{
            $("#list_entidades_asignadas").css("display", "none");
        }
    }
</script>