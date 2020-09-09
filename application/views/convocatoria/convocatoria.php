<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
echo js('convocatoria/convocatoria.js');
?>
<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-sm-12">
                <h1 class="page-head-line">
                    Detalle de la convocatoria
                </h1>
            </div>


            <div class="btn-group btn-group-justified" role="group">
                <div class="btn-group" role="group">
                    <button class="btn btn-tpl" type="button" name="editar_convocatoria"  onclick="editar_convocatoria(<?php echo $convocatoria['id_convocatoria']; ?>)" data-toggle="modal" data-target="#my_modal">
                        <a class="btn-link">Editar</a>
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <a class="btn-link" href="<?php echo site_url('convocatoria/get_categorias/' . $convocatoria['id_convocatoria']); ?>">
                        <button class="btn btn-tpl" type="button" name="editar_categoria_convocatoria">
                            Editar categorías
                        </button>
                    </a>
                </div>
                <div class="btn-group" role="group">
                    <a class="btn-link" href="<?php echo site_url('convocatoria/get_elementos/' . $convocatoria['id_convocatoria']); ?>">
                        <button class="btn btn-tpl" type="button" name="editar_elementos_convocatoria">
                            Editar elementos
                        </button>
                    </a>
                </div>
                <div class="btn-group" role="group">
                    <a class="btn-link" href="<?php echo site_url('convocatoria/get_secciones/' . $convocatoria['id_convocatoria']); ?>">
                        <button class="btn btn-tpl" type="button" name="editar_elementos_convocatoria" >
                            Editar secciones
                        </button>
                    </a>
                </div>
            </div>

            <br>
            <br>
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Convocatoria</h3>
                </div>
                <div class="panel-body">
                    <?php include 'cabecera_censo.php'; ?>
                </div>
            </div>
            <br>

        </div>

    </div>
</div>
