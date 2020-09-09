<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Clase para identificar información del empleado con base en la CURP
 * @version 	: 1.0.0
 * @author      : Guagnelli Mike
 **/
class Curp {

    private $curp;
    private $genero;
    private $fecha_nac;
    private $edad;

    public function __construct($param=null) {
    	$this->CI =& get_instance();
//        pr($param);
        if(!is_null($param)){
            $this->curp = $param['curp'];
            $this->_explodeCURP();
        }
    }

    /**
    *  @modificada Christian Garcia, Estaba muy mal el calculo de la edad y el calculo del año de nacimiento
    **/
    function _explodeCURP(){
        if(strlen($this->curp) < 10){
            return false;
        }
        $day = substr($this->curp,8,2);
        $month = substr($this->curp,6,2);
        $year = substr($this->curp,4,2);
        // pr(substr(date('Y'), 2, 2));
        if(substr(date('Y'), 2, 2)>=$year)
        {
            $year = '20'.$year;
        }else
        {
            $year = '19'.$year;
        }
        // pr($year);
        //echo date("Y");
        $f = "{$year}/{$month}/{$day}";
        // pr($f);
        $this->fecha_nac = strtotime($f);
        // pr($this->fecha_nac);
        $date1=date_create(date('Y-m-d'));
        $date2=date_create($f);
        // pr($date1);
        // pr($date2);
        $this->edad = date_diff($date1, $date2)->y;
        $this->genero = substr($this->curp,10,1);
    }

    public function setCURP($curp){
        $this->curp = $curp;
        if(!$this->_explodeCURP()){
            return false;
        }
        return true;
    }

    public function getGenero(){
        if(is_null($this->genero)){
            return false;
        }
        $genero = array("H"=>"Masculino","M"=>"Femenino");
        return $genero[strtoupper($this->genero)];
    }

    public function getEdad(){
        return $this->edad;
    }

    public function getFechaNac(){
        return $this->fecha_nac;
    }
}
