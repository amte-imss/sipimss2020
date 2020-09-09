<?php
$extra = '';
if(!is_null($id_seccion))
{
	$extra.='?id_seccion='.$id_seccion;
}
if($extra == '')
{
	$extra.='?';
}else{
	$extra.='&';
}
if(!is_null($id_elemento_seccion))
{
	$extra.='id_elemento_seccion='.$id_elemento_seccion;
}
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <!--<h3 class="panel-title">Añadir</h3>-->
            </div>
            <div class="panel-body"><br>
              <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row" style="margin:5px;">
                      <a role="button" class="add_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" href="<?php echo base_url('/index.php/secciones/nuevo_elemento_seccion/'.$extra); ?>">
                        <span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span>
                        <span class="ui-button-text">Añadir Elementos de secciones</span>
                      </a>
                    </div>
                    <div class="row" style="margin:5px;">
                      <div class="table table-container-fluid panel">
				              <?php echo $output;?>
    		              </div>
                    </div>
                  </div>
              </div>
            </div>
          </div>
    </div>
</div>
