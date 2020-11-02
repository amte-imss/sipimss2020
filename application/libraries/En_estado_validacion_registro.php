<?php

/**
 * Clase que contiene la gestion de catalogos
 * @version 	: 1.0.0
 * @author      : LEAS
 * */
class En_estado_validacion_registro {

    /**
     * __default = 1,
     *       REGISTRO_USUARIO = 1,//El docente continua registrando informacion 
     *       REGISTRO_SISTEMA = 2,//No aplica
     *       VALIDADO_N1 = 3,//El validador N1 aprueba informacion
     *       VALIDADO_N2 = 4, //No aplica actualmente
     *       EVALUADO = 5,//No aplica actualmente
     *       NO_VALIDO_EVALUACION = 6,//No aplica actualmente
     *       RATIFICADO = 7,//El validador N2 ratifica la validacion de la validacion por N1
     *       FINALIZA_REGISTRO_CONVOCATORIA = 8,//Finaliza el docente su registro de informacion del censo
     *       PROCESO_VALIDACION_N1 = 9//El validador N1 sigue revisando la informacion del docente 
     */
    const
            __default = 1,
            /**
             * El docente continua registrando informacion
             */
            REGISTRO_USUARIO = 1, 
            REGISTRO_SISTEMA = 2,//No aplica
            /**
             * El validador N1 aprueba informacion
             */
            VALIDADO_N1 = 3,
            VALIDADO_N2 = 4,
            EVALUADO = 5,
            /**
             * No aplica actualmente
             */
            NO_VALIDO_EVALUACION = 6,
            /**
             * El validador N2 ratifica la validacion de la validacion por N1
             */
            RATIFICADO = 7,
            /**
             * Finaliza el docente su registro de informacion del censo
             */
            FINALIZA_REGISTRO_CONVOCATORIA = 8,
            /**
             * El validador N1 sigue revisando la informacion del docente
             */
            PROCESO_VALIDACION_N1 = 9 

    ;
}
