<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$total_ooad = $total_umae = 0;
$html_ooad = $html_umae = '';

if ( !is_null($result_delegacional) ){
    foreach ($result_delegacional as $key_ooad => $ooad) { 
        $html_ooad .= '<tr>
            <td>'.$ooad['nombre'].'</td>
            <td>'.$ooad['total'].'</td>
        </tr>';
        $total_ooad += $ooad['total'];
    }
} else {
    echo '<tr>
        <td colspan="2">No existen registros.</td>
    </tr>';
}

if ( !is_null($result_umae) ){
    foreach ($result_umae as $key_umae => $umae) { 
        $html_umae .= '<tr>
            <td>'.$umae['nombre_unidad_principal'].'</td>
            <td>'.$umae['total'].'</td>
        </tr>';
        $total_umae += $umae['total'];
    }
} else {
    echo '<tr>
        <td colspan="2">No existen registros.</td>
    </tr>';
}
?>

<div class="list-group">
    <div class="list-group-item">
        <div class="panel-body" onmousedown="elemento(event);">
            <div class="row">
                <div class="col-lg-4 col-md-4"><h3><?php echo $total_ooad + $total_umae; ?> docentes registrados</h3></div>
                <div class="col-lg-4 col-md-4"><h3><?php echo $total_ooad; ?> docentes registrados en OOAD</h3></div>
                <div class="col-lg-4 col-md-4"><h3><?php echo $total_umae; ?> docentes registrados en UMAE</h3></div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>OOAD</th>
                                <th># de registros</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $html_ooad; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6 col-md-6">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>UMAE</th>
                                <th># de registros</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $html_umae; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>