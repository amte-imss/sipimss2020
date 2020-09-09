<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
//pr($config);
$h = (isset($height)) ? $height : '500px';
$w = (isset($width)) ? $width : '100%';
$element = (isset($id_form)) ? $id_form . $config['data_index'] : 'form_rama_organica' . $config['data_index'];
?>
<script type="text/javascript">

    var rama_datos = <?php echo json_encode($datos); ?>;
    var rama_campos = <?php echo json_encode($campos); ?>;
<?php
//pr($datos);
if (isset($config['config']['configuraciones']['seleccion']) && $config['config']['configuraciones']['seleccion'] == 'radio')
{
    ?>

        rama_campos.push({name: 'clave_unidad', title: 'Seleccionado', align: "center", itemTemplate: function (value, item) {
                return $("<input>").attr("type", "radio")
                        .attr("checked", item.Checked)
                        .attr('data-index', <?php echo $config['data_index'] ?>)
                        .attr('data-cve', value)
                        .on("change", function () {
                            item.Checked = $(this).is(":checked");
                            localizador_sede_check(this);
                        })
                        .attr('name', 'sede');
            }});

    <?php
} else if (isset($config['config']['configuraciones']['seleccion']) && $config['config']['configuraciones']['seleccion'] == 'checkbox')
{
    ?>
        $('#localizador_sede_button_select_all_<?php echo $config['data_index'] ; ?>').css('display', 'initial');
        rama_campos.push({name: 'clave_unidad', title: 'Seleccionado', align: "center", itemTemplate: function (value, item) {
                var tmp_bool = false;
                if (localizadores_sede[<?php echo $config['data_index'] ?>].value[value] !== 'undefined' && localizadores_sede[<?php echo $config['data_index'] ?>].value[value]) {
                    tmp_bool = true;
                }
                return $('<input class="localizador_sede_field_checkbox_<?php echo $config['data_index']; ?>">').attr("type", "checkbox")
                        .attr("checked", item.Checked || tmp_bool)
                        .attr('data-index', <?php echo $config['data_index'] ?>)
                        .attr('data-cve', value)
                        .on("change", function () {
                            item.Checked = $(this).is(":checked");
                            localizador_sede_check(this);
    <?php
    if (isset($config['config']['configuraciones']['funcion_auxiliar']))
    {
        echo $config['config']['configuraciones']['funcion_auxiliar'];
        ?>(this);<?php
    }
    ?>

                        });
            }});
    <?php
}
?>

    $(function () {

        $("#<?php echo $element; ?>").jsGrid("search");

        $("#<?php echo $element; ?>").jsGrid({
            height: "<?php echo $h; ?>",
            width: "<?php echo $w; ?>",
            pageButtonCount: 3,
            pagerFormat: "Paginas: {first} {prev} {pages} {next} {last}    {pageIndex} de {pageCount}",
            pagePrevText: "Anterior",
            pageNextText: "Siguiente",
            pageFirstText: "Primero",
            pageLastText: "Último",
            noDataContent: "No se encontraron datos",
            sorting: true,
            paging: true,
            data: rama_datos,
            fields: rama_campos
        });

        $('#localizador_sede_button_select_all_<?php echo $config['data_index']; ?>').on('click', function(){
            localizador_sede_select_all($(this));
        });
        $("#localizador_sede_pager_<?php echo $config['data_index']; ?>").on("change", function() {
            var page = parseInt($(this).val(), 10);
             $("#<?php echo $element; ?>").jsGrid("option", "pageSize", page);
        });
    });
</script>
