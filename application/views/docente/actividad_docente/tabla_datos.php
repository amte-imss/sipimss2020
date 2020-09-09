<div class="row">
    <div class="container">
        <h1>Filtro<small>(<i class="fa fa-filter"></i>)</small></h1>
        <div class="row">
            <div class="col-md-11">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Data table</h3>
                        <div class="pull-right">
                            <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
                                <i class="fa fa-filter"></i>
                            </span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Buscar" />
                    </div>
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Kilgore</td>
                                <td>Trout</td>
                                <td>kilgore</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Bob</td>
                                <td>Loblaw</td>
                                <td>boblahblah</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Holden</td>
                                <td>Caulfield</td>
                                <td>penceyreject</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url("assets/js/template_sipimss/tabla_funcion.js"); ?>"></script>









<!--
<script>
//custom select box

$(function(){
    $('select.styled').customSelect();
});

</script> -->
