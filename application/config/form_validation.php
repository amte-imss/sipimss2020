<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config = array(
    'comprobante_actividad' => array(
        array(
            'field' => 'comprobante',
            'label' => 'cargar comprobante',
            'rules' => 'required'
        ),
        array(
            'field' => 'folio_comprobante',
            'label' => 'folio del comprobante',
//            'rules' => 'is_folio_comprobante_unico'
            'rules' => ''
        ),
//       array(
//            'field' => 'userfile',
//            'label' => 'archivo',
//            'rules' => 'callback_file_check'
//        ),
    ),
    'datos_siap' => array(
        array(
            'field' => 'clave_delegacional',
            'label' => 'clave delegacional',
            'rules' => 'required'
        ),
    ),
    'datos_generales' => array(
        array(
            'field' => 'apellido_p',
            'label' => 'apellido paterno',
            'rules' => 'required|alpha_accent_space_dot_quot'
        ),
        array(
            'field' => 'apellido_m',
            'label' => 'apellido materno',
            'rules' => 'required|alpha_accent_space_dot_quot'
        ),
        array(
            'field' => 'nombre',
            'label' => 'nombre',
            'rules' => 'required|alpha_accent_space_dot_quot'
        ),
        array(
            'field' => 'email',
            'label' => 'correo electrónico',
            'rules' => 'required|valida_correo_electronico'
        ),
        array(
            'field' => 'email_personal',
            'label' => 'correo electrónico',
            'rules' => 'valida_correo_electronico'
        ),
        array(
            'field' => 'telefono_particular',
            'label' => 'teléfono',
            'rules' => 'numeric'
        ),
        array(
            'field' => 'telefono_laboral',
            'label' => 'teléfono',
            'rules' => 'numeric'
        ),
        array(
            'field' => 'ext_tel_laboral',
            'label' => 'extensión',
            'rules' => 'numeric'
        ),
        array(
            'field' => 'fase_carrera_docente',
            'label' => 'Fase de carrera docente',
            'rules' => 'required'
        ),
    ),
);

$config['form_status_actividad_usuario'] = array(
    array(
        'field' => 'status_actividad',
        'label' => 'Actividad del usuario',
        'rules' => 'required|integer'
    )
);


$config["login"] = array(
    array(
        'field' => 'usuario',
        'label' => 'Usuario',
        'rules' => 'required',
        'errors' => array(
            'required' => 'El campo %s es obligatorio, favor de ingresarlo.',
        ),
    ),
    array(
        'field' => 'password',
        'label' => 'Contraseña',
        'rules' => 'required',
        'errors' => array(
            'required' => 'El campo %s es obligatorio, favor de ingresarlo.',
        ),
    ),
    /*array(
        'field' => 'captcha',
        'label' => 'Imagen de seguridad',
        'rules' => 'required|check_captcha',
        'errors' => array(
            'required' => 'El campo %s es obligatorio, favor de ingresarlo.',
            'check_captcha' => "El texto no coincide con la imagen, favor de verificarlo."
        ),
    ),*/
);

$config['form_user_update_password'] = array(
    array(
        'field' => 'pass',
        'label' => 'Contraseña',
        'rules' => 'required|min_length[8]'
    ),
    array(
        'field' => 'pass_confirm',
        'label' => 'Confirmar contraseña',
        'rules' => 'required|min_length[8]' //|callback_valid_pass
    ),
);

$config['form_actualizar'] = array(
    array(
        'field' => 'email',
        'label' => 'Correo electrónico',
        'rules' => 'trim|required|valida_correo_electronico' //|callback_valid_pass
    ),
);

$config['form_niveles_acceso_usuario'] = array(
    array(
        'field' => 'niveles',
        'label' => 'niveles',
        'rules' => 'required'
    )
);

$config['form_registro_siap'] = array(
    array(
        'field' => 'matricula',
        'label' => 'Matrícula',
        'rules' => 'required|max_length[18]|alpha_dash'
    ),
    array(
        'field' => 'delegacion',
        'label' => 'Delegación',
        'rules' => 'required' //|callback_valid_pass
    ),
    array(
        'field' => 'email',
        'label' => 'Correo electrónico',
        'rules' => 'trim|required|valida_correo_electronico' //|callback_valid_pass
    ),
    array(
        'field' => 'password',
        'label' => 'Contraseña',
        'rules' => 'required' //|callback_valid_pass
    ),
    array(
        'field' => 'repass',
        'label' => 'Confirmación contraseña',
        'rules' => 'required|matches[password]'
    ),
    array(
        'field' => 'grupo',
        'label' => 'Niveles de Atencion',
        'rules' => 'required'
    )
);

$config['form_registro_no_siap'] = array(
    array(
        'field' => 'matricula1',
        'label' => 'Matrícula',
        'rules' => 'required|max_length[18]|alpha_dash'
    ),
    array(
        'field' => 'clave_departamental1',
        'label' => 'Clave departamental',
        'rules' => 'required' //|callback_valid_pass
    ),
    array(
        'field' => 'categoria1',
        'label' => 'Clave categoría',
        'rules' => 'required' //|callback_valid_pass
    ),
    array(
        'field' => 'email1',
        'label' => 'Correo electrónico',
        'rules' => 'trim|required|valida_correo_electronico' //|callback_valid_pass
    ),
    array(
        'field' => 'password1',
        'label' => 'Contraseña',
        'rules' => 'required' //|callback_valid_pass
    ),
    array(
        'field' => 'repass1',
        'label' => 'Confirmación contraseña',
        'rules' => 'required|matches[password1]'
    ),
    array(
        'field' => 'grupo1',
        'label' => 'Niveles de Atencion',
        'rules' => 'required'
    )
);

$config['form_registro_no_imss'] = array(
    array(
        'field' => 'matricula2',
        'label' => 'Nombre de usuario',
        'rules' => 'required|max_length[18]|alpha_dash'
    ),
    array(
        'field' => 'email2',
        'label' => 'Correo electrónico',
        'rules' => 'trim|required|valida_correo_electronico' //|callback_valid_pass
    ),
    array(
        'field' => 'password2',
        'label' => 'Contraseña',
        'rules' => 'required' //|callback_valid_pass
    ),
    array(
        'field' => 'repass2',
        'label' => 'Confirmación contraseña',
        'rules' => 'required|matches[password2]'
    ),
    array(
        'field' => 'grupo2',
        'label' => 'Niveles de Atencion',
        'rules' => 'required'
    )
);



$config['form_registro_usuario'] = array(
    array(
        'field' => 'reg_usuario',
        'label' => 'Matrícula',
        'rules' => 'required|max_length[18]|alpha_dash'
    ),
    array(
        'field' => 'id_delegacion',
        'label' => 'Delegación',
        'rules' => 'required' //|callback_valid_pass
    ),
    array(
        'field' => 'reg_email',
        'label' => 'Correo electrónico',
        'rules' => 'trim|required|valida_correo_electronico' //|callback_valid_pass
    ),
    array(
        'field' => 'reg_password',
        'label' => 'Contraseña',
        'rules' => 'required' //|callback_valid_pass
    ),
    array(
        'field' => 'reg_repassword',
        'label' => 'Confirmación contraseña',
        'rules' => 'required|matches[reg_password]'
    ),
    array(
        'field' => 'reg_captcha',
        'label' => 'Captcha',
        'rules' => 'required|check_captcha'
    )
);

$config['nueva_convocatoria_censo'] = array(
    array(
        'field' => 'nombre',
        'label' => 'Nombre',
        'rules' => 'required|max_length[100]'
    ),
    array(
        'field' => 'clave',
        'label' => 'Clave',
        'rules' => 'required|max_length[15]'
    ),
    array(
        'field' => 'fecha_inicio_0',
        'label' => 'Fecha inicio de registro',
        'rules' => 'required'
    ),
    array(
        'field' => 'fecha_fin_0',
        'label' => 'Fecha fin registro',
        'rules' => 'required'
    ),
    /*array(
        'field' => 'unidades[]',
        'label' => 'Participantes',
        'rules' => 'required'
    )*/
);

$config['editar_convocatoria_censo'] = array(
    array(
        'field' => 'nombre',
        'label' => 'Nombre',
        'rules' => 'required|max_length[100]'
    ),
    array(
        'field' => 'clave',
        'label' => 'Clave',
        'rules' => 'required|max_length[15]'
    ),
    array(
        'field' => 'fecha_inicio_0',
        'label' => 'Fecha inicio de registro',
        'rules' => 'required'
    ),
    array(
        'field' => 'fecha_fin_0',
        'label' => 'Fecha fin registro',
        'rules' => 'required'
    ),
    array(
        'field' => 'activo',
        'label' => 'Estado',
        'rules' => 'required'
    )
);

$config['elemento_seccion'] = array(
        array(
            'field' => 'nombre',
            'label' => 'nombre',
            'rules' => 'required|not_space'
        ),
        array(
            'field' => 'id_seccion',
            'label' => 'id_seccion',
            'rules' => 'required'
        ),
        array(
            'field' => 'activo',
            'label' => 'activo',
            'rules' => 'required'
        ),
        array(
            'field' => 'label',
            'label' => 'label',
            'rules' => 'required'
        )
    );

$config['campos_formulario'] = array(
        array(
            'field' => 'id_campo',
            'label' => 'Campo',
            'rules' => 'required'
        ),
        array(
            'field' => 'orden',
            'label' => 'orden',
            'rules' => 'required|integer'
        ),
        array(
            'field' => 'display',
            'label' => 'display',
            'rules' => 'required'
        ),
        array(
            'field' => 'activo',
            'label' => 'activo',
            'rules' => 'required'
        ),
        array(
            'field' => 'nueva_linea',
            'label' => 'Nueva línea',
            'rules' => 'required'
        )
    );

$config['formulario'] = array(
        array(
            'field' => 'nombre',
            'label' => 'nombre',
            'rules' => 'required|not_space'
        ),
        array(
            'field' => 'label',
            'label' => 'etiqueta',
            'rules' => 'required'
        ),
        array(
            'field' => 'id_elemento_seccion',
            'label' => 'subsección',
            'rules' => 'required'
        ),
        array(
            'field' => 'activo',
            'label' => 'activo',
            'rules' => 'required'
        )
    );

$config['nueva_unidad'] = array(
    array(
        'field' => 'clave_unidad',
        'label' => 'Clave unidad',
        'rules' => 'required'
    ),
    array(
        'field' => 'nombre',
        'label' => 'Nombre de la unidad',
        'rules' => 'required'
    ),
    array(
        'field' => 'id_delegacion',
        'label' => 'Delegación',
        'rules' => 'required|numeric'
    ),
    array(
        'field' => 'clave_presupuestal',
        'label' => 'Clave presupuestal',
        'rules' => 'required'
    ),
);

$config['update_catalogo_categorias'] = array(
    array(
        'field' => 'categoria',
        'label' => 'Categoria',
        'rules' => 'required'
    ),
    array(
        'field' => 'clave_categoria',
        'label' => 'Clave categoria',
        'rules' => 'required|max_length[12]'
    ),
    array(
        'field' => 'id_grupo_categoria',
        'label' => 'Grupo categoria',
        'rules' => 'required|is_numeric'
    ),
);

$config['insert_catalogo_categorias'] = array(
    array(
        'field' => 'id_categoria',
        'label' => 'ID',
        'rules' => 'required'
    ),
    array(
        'field' => 'categoria',
        'label' => 'Categoria',
        'rules' => 'required'
    ),
    array(
        'field' => 'clave_categoria',
        'label' => 'Clave categoria',
        'rules' => 'required|max_length[12]'
    ),
    array(
        'field' => 'id_grupo_categoria',
        'label' => 'Grupo categoria',
        'rules' => 'required|is_numeric'
    ),
);

$config['catalogo_unidades_instituto'] = array(
    array(
        'field' => 'clave_unidad',
        'label' => 'Clave unidad',
        'rules' => 'required'
    ),
    array(
        'field' => 'nombre',
        'label' => 'Nombre unidad',
        'rules' => 'required'
    ),
    array(
        'field' => 'id_delegacion',
        'label' => 'Delegación',
        'rules' => 'required|is_numeric'
    ),
    array(
        'field' => 'clave_presupuestal',
        'label' => 'Clave presupuestal',
        'rules' => 'required'
    ),
    array(
        'field' => 'nivel_atencion',
        'label' => 'Nivel de atención',
        'rules' => 'required|is_numeric'
    ),
    array(
        'field' => 'id_tipo_unidad',
        'label' => 'Tipo de unidad',
        'rules' => 'required|is_numeric'
    ),
    array(
        'field' => 'umae',
        'label' => 'Es UMAE',
        'rules' => 'required|is_numeric'
    ),
    array(
        'field' => 'activa',
        'label' => 'Activa',
        'rules' => 'required|is_numeric'
    ),
    array(
        'field' => 'id_region',
        'label' => 'Región',
        'rules' => 'required|is_numeric'
    ),
    array(
        'field' => 'anio',
        'label' => 'Año',
        'rules' => 'required|is_numeric'
    ),
);

$config['insert_catalogo_departamento'] = array(
    array(
        'field' => 'clave_unidad',
        'label' => 'Clave unidad',
        'rules' => 'required'
    ),
    array(
        'field' => 'departamento',
        'label' => 'Nombre de la adscripción',
        'rules' => 'required'
    ),
    array(
        'field' => 'clave_departamental',
        'label' => 'Clave adscripción',
        'rules' => 'required'
    ),
);

$config['update_catalogo_departamento'] = array(
    array(
        'field' => 'id_departamento_instituto',
        'label' => 'ID',
        'rules' => 'required|is_numeric'
    ),
    array(
        'field' => 'clave_unidad',
        'label' => 'Clave unidad',
        'rules' => 'required'
    ),
    array(
        'field' => 'departamento',
        'label' => 'Nombre de la adscripción',
        'rules' => 'required'
    ),
    array(
        'field' => 'clave_departamental',
        'label' => 'Clave adscripción',
        'rules' => 'required'
    ),
);


$config['catalogo_reglas_dependencia'] = array(
    array(
        'field' => 'clave_regla_dependecia_catalogo',
        'label' => 'Clave',
        'rules' => 'required'
    ),
    array(
        'field' => 'nombre',
        'label' => 'Nombre',
        'rules' => 'required'
    ),
    array(
        'field' => 'id_catalogo_padre',
        'label' => 'Catálogo padre',
        'rules' => 'required|is_numeric'
    ),
    array(
        'field' => 'id_catalogo_hijo',
        'label' => 'Catálolgo hijo',
        'rules' => 'required|is_numeric'
    )
);

$config['catalogo_detalle_dependencias_catalogos'] = array(
    array(
        'field' => 'clave_regla_dependecia_catalogo',
        'label' => 'Clave',
        'rules' => 'required'
    ),
    array(
        'field' => 'id_elemento_catalogo_padre',
        'label' => 'Elemento padre',
        'rules' => 'required|is_numeric'
    ),
    array(
        'field' => 'id_elemento_catalogo_hijo',
        'label' => 'Elemento hijo',
        'rules' => 'required|is_numeric'
    )
);

// VALIDACIONES
/*
             * isset
             * valid_email
             * valid_url
             * min_length
             * max_length
             * exact_length
             * alpha
             * alpha_numeric
             * alpha_numeric_spaces
             * alpha_dash
             * numeric
             * is_numeric
             * integer
             * regex_match
             * matches
             * differs
             * is_unique
             * is_natural
             * is_natural_no_zero
             * decimal
             * less_than
             * less_than_equal_to
             * greater_than
             * greater_than_equal_to
             * in_list
             * validate_date_dd_mm_yyyy
             * validate_date
             * form_validation_match_date  la fecha debe ser mayor que ()
             *
             *
             *
             */


//custom validation

/*

alpha_accent_space_dot_quot
 *
alpha_numeric_accent_slash
 *
alpha_numeric_accent_space_dot_parent
 *
alpha_numeric_accent_space_dot_double_quot

*/

/*
*password_strong
*
*
*
*
*/
