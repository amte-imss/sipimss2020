<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$controlador = $this->uri->rsegment(1);
$item_data = [];
//pr($campos_agrupados);
$pinta_modo_intercalado = false;//Cambia o alterna el orden de las columnas
/* $value contiene las sigiente estructura
                            [lb_campo] => Formación profesional del profesor
                            [respuesta_valor] => Carrera técnica
                            [nueva_linea] => 1
                            [nom_tipo_campo] => dropdown
                        */
$custom_cursos = '' ;                       
foreach ($campos_agrupados as $key_grupo => $value_agrupados) {
    $item_data[$key_grupo] = ['header'=>'', 'body'=>'', 'container'=>''];
    $header = 0;
    $body = 0;
    $br = '<br>';
    foreach ($value_agrupados as $key_c => $value) {
        if ($value['respuesta_valor'] != 'NULL') {//Submit lo considerará el
            
            switch ($value['nom_tipo_campo']) {
                
                case 'checkbox':
                    if($value['respuesta_valor'] == 1){   
                                             
                        $item_data[$key_grupo]['body'] .= '<label>'.$value['lb_campo'].': </label>' ;                        
                      
                    }
                    //pr($item_data[$key_grupo]['body']);
                break;
                case 'custom':
                    if((strlen($value['respuesta_valor']))>0 ){
                        $decode  = json_decode($value['respuesta_valor'], true);
                        if(!empty($decode) ){
                            $datos_tabla = '';
                            foreach ($decode as $key_cust => $value_custom){

                                    $datos_tabla .= '<tr>
                                        <td>'. $value_custom['nombre_curso'] .'</td>' .
                                        '<td>'. $value_custom['anio'] .'</td>'.
                                    '</tr>';                                                
                            }                                
                            $custom_cursos .= '<p> <label class="bold-label">'.$value['lb_campo'].'</label></p>' .
                            '<table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre curso</th>
                                        <th>Años</th>
                                    </tr>
                                </thead>
                                <tbody>' .
                                $datos_tabla .
                               '</tbody>
                            </table>'                                                
                            ;
                        }
                    }
                    
                break;
                default :
                if($header==0){
                    $item_data[$key_grupo]['header'] = '<div class="panel-heading">'. $value['lb_campo'] . '&nbsp;<text class="l_'.$key_c.'">' . $value['respuesta_valor']. '</text></div>';
                    $header = 1;
                }else{
                    if (preg_match("/Número de años/", $value['lb_campo']) == 1) {
                        $item_data[$key_grupo]['body'] .=  ' &nbsp;' . $value['lb_campo'].' &nbsp;' . $value['respuesta_valor'] .$br ;

                    }else{
                        $item_data[$key_grupo]['body'] .= '<label>'.$value['lb_campo'].': </label>&nbsp;<text class="l_'.$key_c.'">' . $value['respuesta_valor'] .'</text>' . $br ;
                    }
                    $br = '<br>';
                   
                }
                
            }
        }
    }
    $body_div = '';
    if(!empty($item_data[$key_grupo]['body'])){
        $body_div = '<div class="panel-body">' .
        $item_data[$key_grupo]['body'] .
        '</div>';
    }
    $item_data[$key_grupo]['container'] = '<div class="panel panel-default" id="div_'. $id_censo . '_' . $key_grupo.'" >'.
    $item_data[$key_grupo]['header'] .
    $body_div .
    '</div>';
}
?>


     
<?php
            $item_grupo = [];
            $indicador = 1;
            $item_grupo = ['columna_1'=>'','columna_2'=>''];
            if($pinta_modo_intercalado){
                foreach ($campos_agrupados as $key_grupo => $value_agrupados) {
                    if ($indicador%2==0){//par
                        $item_grupo['columna_1'] .= $item_data[$key_grupo]['container'];
                    }else{//impar
                        $item_grupo['columna_2'] .= $item_data[$key_grupo]['container'];
                    }
                    $indicador++;           
                }
                
            }else{
                
                
                $total_grupos = count($campos_agrupados)/2;
                //pr(count($campos_agrupados));
                //pr(count($campos_agrupados)/2);
                foreach ($campos_agrupados as $key_grupo => $value_agrupados) {
                    if ($total_grupos >= $indicador){//par
                        $item_grupo['columna_1'] .= $item_data[$key_grupo]['container'];
                    }else{//impar
                        $item_grupo['columna_2'] .= $item_data[$key_grupo]['container'];
                        
                    }
                    $indicador++;
                }
            }
            ?>
    <div class="panel-group">
       <div class="row">
           <div class="col-md-6">
               <?php echo $item_grupo['columna_1'];?>
            </div >
            <div class="col-md-6">
                <?php echo $item_grupo['columna_2'];?>
            </div >
       </div >
    </div>

    <?php echo $custom_cursos; ?>
