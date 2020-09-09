<!--<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" ></script>-->
<link href="<?php // echo base_url('assets/css/template_sipimss/jquery-ui-1.7.2.custom.css'); ?>" rel="stylesheet" />
<!-- Modal -->
<style>
    .ui-datepicker {z-index: 1151 !important;}
</style>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Editar convocatoria de censo</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Nivel geográfico:</span>
                                <select class="form-control">
                                    <option>Seleccionar</option>
                                    <option>Región</option>
                                    <option>Delegación</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Región:</span>
                                <select class="form-control">
                                    <option>Seleccionar</option>
                                    <option>Seleccionar</option>
                                    <option>Noroccidente</option>
                                    <option>Centro Surestev
                                    <option>Noreste</option>
                                    <option>Centro</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Nombre</span>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha de inicio registro</span>
                                <input type="date" class="form-control fecha">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha de fin registro</span>
                                <input type="date" class="form-control fecha">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha Inicio validación N1</span>
                                <input type="date" class="form-control fecha">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha fin validación N1</span>
                                <input type="date" class="form-control fecha">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha inicio validación N2</span>
                                <input type="date" class="form-control fecha">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha fin validación N2</span>
                                <input type="date" class="form-control fecha">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal2" tabindex="3" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Editar convocatoria de evaluación curricular</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Nivel geográfico:</span>
                                <select class="form-control">
                                    <option>Seleccionar</option>
                                    <option>Región</option>
                                    <option>Delegación</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Región:</span>
                                <select class="form-control">
                                    <option>Seleccionar</option>
                                    <option>Seleccionar</option>
                                    <option>Noroccidente</option>
                                    <option>Centro Surestev
                                    <option>Noreste</option>
                                    <option>Centro</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Nombre</span>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha de inicio solicitud</span>
                                <input type="date"  class="form-control fecha">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha de fin solicitud</span>
                                <input type="date"  class="form-control fecha">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha inicio validación</span>
                                <input type="date" class="form-control fecha">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha fin validación</span>
                                <input type="date" class="form-control fecha">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha inicio evaluación</span>
                                <input type="date" class="form-control fecha">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha fin evaluación</span>
                                <input type="date" class="form-control fecha">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3">
    <div class="modal-dialog" role="document">
        <div class="modal-content  modal-lg">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Nueva convocatoria</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Nivel geográfico:</span>
                                <select class="form-control">
                                    <option>Seleccionar</option>
                                    <option>Región</option>
                                    <option>Delegación</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Región:</span>
                                <select class="form-control">
                                    <option>Seleccionar</option>
                                    <option>Seleccionar</option>
                                    <option>Noroccidente</option>
                                    <option>Centro Surestev
                                    <option>Noreste</option>
                                    <option>Centro</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Nombre</span>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha de inicio registro</span>
                                <input type="date" class="form-control fecha">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha de fin registro</span>
                                <input type="date" class="form-control fecha">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha Inicio validación N1</span>
                                <input type="date" class="form-control fecha">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha fin validación N1</span>
                                <input type="date" class="form-control fecha">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha inicio validación N2</span>
                                <input type="date" class="form-control fecha">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Fecha fin validación N2</span>
                                <input type="date" class="form-control fecha">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/third-party//vis-4.19.1/dist/vis.js"></script>
<link href="<?php echo base_url('assets/third-party//vis-4.19.1/dist/vis.css'); ?>" rel="stylesheet" />
<?php
echo js('convocatoria/index.js');
?>
<div class="list-group" style="padding-left:110px;">
    <div class="list-group-item">
        <div class="panel-body">

            <div class="row">
                <h2>Administrador de convocatorias</h2>
                <div class="row">
                    <button id="boton_nuevo1" style="float: right;" class="btn btn-primary" data-toggle="modal" data-target="#myModal3">Nueva</button>
                </div>
                <br>
                <div class="panel panel-default">
                    <div class="panel-heading" data-toggle="collapse" data-target="#demo">Timeline</div>
                    <div class="panel-body" id="demo" class="collapse">
                        <div id="visualization1" ></div>
                    </div>
                </div>


                <br>
                <?php
                echo form_open('convocatoria/index/', array('id' => 'form_convocatoria'));
                ?>
                <div class="form-group">
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">Tipo de convocatoria:</span>
                            <select class="form-control" onchange="tipo_convocatoria(this)">
                                <option value="">Seleccionar</option>
                                <option value="1">Censo</option>
                                <option value="2">Evaluación curricular docente</option>
                            </select>
                        </div>
                    </div>
                    <div id="nivel_geografico" class="col-md-4" style="display:none">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">Nivel geográfico:</span>
                            <select class="form-control" onchange="nivel_geografico(this)">
                                <option value="">Seleccionar</option>
                                <option value="1">Región</option>
                                <option value="2">Delegación</option>
                            </select>
                        </div>
                    </div>
                    <div id="regiones" class="col-md-4" style="display:none">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">Región:</span>
                            <select class="form-control">
                                <option>Seleccionar</option>
                                <option>Noroccidente</option>
                                <option>Centro Surestev
                                <option>Noreste</option>
                                <option>Centro</option>
                            </select>
                        </div>
                    </div>
                    <div id="delegaciones" class="col-md-4" style="display:none">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">Delegación:</span>
                            <select class="form-control">
                                <option>Seleccionar</option>
                                <option>SIN DELEGACION</option>
                                <option>AGUASCALIENTES</option>
                                <option>BAJA CALIFORNIA</option>
                                <option>BAJA CALIFORNIA SUR</option>
                                <option>CAMPECHE</option>
                                <option>COAHUILA</option>
                                <option>COLIMA</option>
                                <option>CHIAPAS</option>
                                <option>CHIHUAHUA</option>
                                <option>OFICINAS CENTRALES</option>
                                <option>DURANGO</option>
                                <option>GUANAJUATO</option>
                                <option>GUERRERO</option>
                                <option>HIDALGO</option>
                                <option>JALISCO</option>
                                <option>EDO MEX OTE</option>
                                <option>EDO MEX PTE</option>
                                <option>MICHOACAN</option>
                                <option>MORELOS</option>
                                <option>NAYARIT</option>
                                <option>NUEVO LEON</option>
                                <option>OAXACA</option>
                                <option>PUEBLA</option>
                                <option>QUERETARO</option>
                                <option>QUINTANA ROO</option>
                                <option>SAN LUIS POTOSI</option>
                                <option>SINALOA</option>
                                <option>SONORA</option>
                                <option>TABASCO</option>
                                <option>TAMAULIPAS</option>
                                <option>TLAXCALA</option>
                                <option>VERACRUZ NORTE</option>
                                <option>VERACRUZ SUR</option>
                                <option>YUCATAN</option>
                                <option>ZACATECAS</option>
                                <option>D F 1 NORTE</option>
                                <option>D F 2 NORTE</option>
                                <option>D F 3 SUR</option>
                                <option>D F 4 SUR</option>
                                <option>MANDO</option>
                            </select>
                        </div>
                    </div>
                </div>

                
            </div>
            <br />
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">Año:</span>
                        <select class="form-control">
                            <option>Seleccionar</option>
                            <option>2016</option>
                            <option>2017</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon"></span>
                        <select class="form-control">
                            <option>Seleccionar</option>
                            <option>Activos</option>
                            <option>Inactivos</option>
                        </select>
                    </div>
                </div>
            </div>

            <?php echo form_close(); ?>
            
            <br>
            <br>
            <div id="visualization2"style="display:none"></div>
            <br>
            <div class="row" id="tabla1" style="display:none">
                <table class="table table-striped">
                    <thead>
                    <th>Nombre</th>
                    <th>Nivel geográfico</th>
                    <th>Región/Delegación</th>
                    <th>Fecha de inicio registro</th>
                    <th>Fecha de fin registro</th>
                    <th>Fecha Inicio validación N1</th> 
                    <th>Fecha fin validación N1</th>
                    <th>Fecha inicio validación N2</th>
                    <th>Fecha fin validación N2</th>
                    <th>Activa</th>
                    <th>Acciones</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Convocatoria N</td>
                            <td>Región</td>
                            <td>Centro Sureste</td>
                            <td>25/05/2017</td>
                            <td>31/05/2017</td>
                            <td>1/06/2017</td>
                            <td>14/06/2017</td>
                            <td>15/06/2017</td>
                            <td>25/06/2017</td>
                            <td><input type="checkbox"></td>
                            <td><a data-toggle="modal" data-target="#myModal">editar</a> | <a>eliminar</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="row" id="tabla2" style="display:none">
                <table class="" >
                    <thead>
                        <th>Nombre</th>
                    <th>Nivel geográfico</th>
                    <th>Región/Delegación</th>
                    <th>Fecha de inicio solicitud</th>
                    <th>Fecha de fin solicitud</th>
                    <th>Fecha inicio validación</th> 
                    <th>Fecha fin validación</th>
                    <th>Fecha inicio evaluación</th>
                    <th>Fecha fin evaluación</th>
                    <th>Activa</th>
                    <th>Acciones</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Convocatoria N</td>
                            <td>Región</td>
                            <td>Centro Sureste</td>
                            <td>25/05/2017</td>
                            <td>31/05/2017</td>
                            <td>1/06/2017</td>
                            <td>14/06/2017</td>
                            <td>15/06/2017</td>
                            <td>25/06/2017</td>
                            <td><input type="checkbox"></td>
                            <td><a data-toggle="modal" data-target="#myModal2">editar</a> | <a>eliminar</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="pagi" class="row" style="float: right; display:none;">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li>
                            <a href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li>
                            <a href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

