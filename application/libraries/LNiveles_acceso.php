<?php

/**
 * Clase que contiene los identificadores de los roles
 * @version 	: 1.0.0
 * @author      : JZDP
 * */ 
class LNiveles_acceso {

    
    
    const
            __default = '',
            Super = 'SUPERADMIN',
            Admin = 'ADMIN',
            //Nivel_central = '',
            Normativo = 'NORMATIVO',
            Docente = 'DOCENTE',
            Mesa = 'MESA_AYUDA',
            Validador1 = 'VALIDADOR1',
            Validador2 = 'VALIDADOR2'
    ;

    function nivel_acceso_valido($niveles_acceso, $niveles){
        foreach ($niveles as $key => $nivel) {
            if(in_array($nivel['id_grupo'],$niveles_acceso)){
                return true;
            }
        }
        return false;
    }
}
