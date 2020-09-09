<?php
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <br><br/>
            <div class="headerTitle" style="width:100%; max-width:95%; height: 80px; margin:auto; border-left: 4px solid darkgray; display: flex;
                  align-items: center; text-indent: 15px; font-size: 40px;">
              <p>Notificaciones</p>
            </div>
            <div class="panel-body"><br>
              <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12">
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
