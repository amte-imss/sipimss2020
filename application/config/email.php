<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['email'] = Array(
	'protocol' => 'smtp',
	'crypt' => 'tls',
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'username' => 'sied.ad.imss@gmail.com',
    'password' => 'Gy19059+',
    'mailtype'  => 'html',
    'charset'   => 'utf-8',
    'validate'  => false,
    'debug' => 2,
    'auth' => true,
    /*
    'host' => 'smtp.gmail.com',
    //'port' => 587,
    'port' => 465,
    'crypt' => 'tls',
    //'username' => "sied.ad.imss@gmail.com",
    //'password' => "s13d.4d.1mss",
    //'setFrom' => array('email'=>'sied.ad.imss@gmail.com', 'name'=>'SIPIMSS')
    'username' => "cenitluis.pumas@gmail.com",
    'password' => "L3@sc0m3P@l0m@s",*/
    'setFrom' => array('email'=>'genaro.sanchez@imss.gob.mx', 'name'=>'IMSS :: SISTEMA DE INFORMACION DE DOCENTES IMSS')
	//Correo IMSS
    // 'debug' => 1,
    //'host' => 'smtp.1and1.mx',
    //'port' => "587",
    //'crypt' => 'TLS',
    //'username' => "postmaster@kaliashop.me",
    //'password' => "Banana123.",
    //'setFrom' => array('email'=>'soporte.sipimss@gmail.com', 'name'=>'SIPIMSS')
    //'setFrom' => array('email'=>'soporte.sipimss@gmail.com', 'name'=>'SIPIMSS')
);
