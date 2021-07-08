BEGIN;
SELECT setval('catalogo.elementos_catalogos_id_elemento_catalogo_seq', 3950);
----Agregar campo a docente
alter table censo.docente add column num_plaza varchar(20) null;

--Permisos para reporte de enfermeria y tecnicos *******************************
insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('REPOR_ENFTEC', 'Reporte docentes enfermería y técnicos', '/censo/reporte_enfermeria_tecnicos', true, null, 8, 'MENU');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('REPOR_ENFTEC', 'NORMATIVO',true);

insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('REPOR_GRID_ENFTEC', 'GridReporteDocentesEnfermeriaTecnicos', '/censo/datos_reporte_enfermeria_tecnicos', true, 'REPOR_ENFTEC', 8, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('REPOR_GRID_ENFTEC', 'NORMATIVO',true);
--*****************************************************************************

--*****************Formació docente  *********************************************************
--Crear campo horas para formacion docente 
insert into ui.campo (nombre,descripcion, id_tipo_campo,label,activo,id_tipo_dato) values ('form_edu_duracion_horas',null,3,'Horas:',true,2);
insert into ui.campo (nombre,descripcion, id_tipo_campo,label,activo,id_tipo_dato) values ('form_edu_cedula','Cédula profesional para formación docente, para los casos Maestría y ',2,'Cédula:',true,2);
insert into ui.campo (nombre,descripcion, id_tipo_campo,label,activo,id_tipo_dato) values ('form_edu_nom_acad',null,1,'Nombre académico:',true,3);


--crear catálogo de horas para formacion docente
insert into catalogo.catalogo (nombre, descripcion, label,tipo) values ('form_edu_dura_horas','Total de horas asociadas al formulario de formación docente', 'Horas', 'elementos_catalogos');

--crear elementos de catálogo para las horas en formacion docente
insert into catalogo.elementos_catalogos (id_elemento_catalogo,nombre, descripcion, id_catalogo, tipo,label,orden, nivel) values (3950,'form_edu_dura_horas_1', null, (select id_catalogo from catalogo.catalogo where nombre = 'form_edu_dura_horas'), 1, '1 - 10', 1,1);
insert into catalogo.elementos_catalogos (id_elemento_catalogo,nombre, descripcion, id_catalogo, tipo,label,orden, nivel) values (3951,'form_edu_dura_horas_2', null, (select id_catalogo from catalogo.catalogo where nombre = 'form_edu_dura_horas'), 1, '10 - 40', 2,1);


--*****************Experiencia docente actual *********************************************************

--Agrega tipo de curso enfermeria y tecnicos 
insert into catalogo.elementos_catalogos (id_elemento_catalogo,nombre, descripcion, id_catalogo,label,orden,tipo,nivel) values (3952,'tipo_curso_9', 'Enfermería', 15,'Enfermería',null,1,1);
insert into catalogo.elementos_catalogos (id_elemento_catalogo,nombre, descripcion, id_catalogo,label,orden,tipo,nivel) values (3953,'tipo_curso_10', 'Técnicos', 15,'Técnicos',null,1,1);

--Sub tipo de curso para enfermería
insert into catalogo.elementos_catalogos (id_elemento_catalogo,nombre, descripcion, id_catalogo,label,orden,tipo,nivel) values (3954,'tipo_curso_12', 'Servicio Social de Carreras Afines a la Salud', 15,'Servicio social de carreras afines a la salud',null,1,1);
insert into catalogo.elementos_catalogos (id_elemento_catalogo,nombre, descripcion, id_catalogo,label,orden,tipo,nivel) values (3955,'tipo_curso_11', 'Ciclos clínicos de carreras afines a la salud', 15,'Ciclos clínicos de carreras afines a la salud',null,1,1);

--Regla para controlar el campo subtipo de curso entre enfermeria y pregrado
insert into catalogo.reglas_dependencia_catalogos values ('TPC_STPC_PRIMA','Relación subtipo curso pregrado y enfermeria','Para controlar sub tipo de cursos de pregrado y enfermeria',15,15);

--Poblar regla para controlar el campo subtipo de curso entre enfermeria y pregrado
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC_PRIMA', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_2'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_7'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC_PRIMA', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_2'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC_PRIMA', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_12'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC_PRIMA', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_11'));

--Agregar a reglas de profesor, los profesores de enfermeria
insert into catalogo.detalle_dependencias_catalogos values('TPC_TPPROF', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_prof_1'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_TPPROF', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_prof_2'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_TPPROF', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_prof_3'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_TPPROF', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_prof_4'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_TPPROF', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_prof_5'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_TPPROF', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_prof_6'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_TPPROF', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_prof_7'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_TPPROF', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_prof_8'));

--Agregar a reglas de profesor, los profesores de tecnicos
insert into catalogo.detalle_dependencias_catalogos values('TPC_TPPROF', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_prof_1'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_TPPROF', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_prof_2'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_TPPROF', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_prof_3'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_TPPROF', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_prof_4'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_TPPROF', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_prof_5'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_TPPROF', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_prof_6'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_TPPROF', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_prof_7'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_TPPROF', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_prof_8'));


----Regla para controlar tecnicos
--insert into catalogo.reglas_dependencia_catalogos values ('TPC_CURTECNICOS','Relación de tipo curso técnicos con cursos tecnicos','Para controlar cursos de técnicos',15,61);


----Regla para controlar enfermeria
--insert into catalogo.reglas_dependencia_catalogos values ('TPC_CURENFERMERIA','Relación de tipo curso enfermeria con cursos enfermeria','Para controlar cursos de técnicos',15,61);




---Relaciona tecnicos a la regla de tipo de curso
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_12'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_13'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_14'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_15'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_16'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_17'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_18'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_19'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_20'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_21'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_22'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_23'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_24'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_25'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_26'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_27'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_28'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_29'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_30'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_31'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_32'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_36'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_10'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_37')); 


---Relaciona enfermeria a la regla de tipo de curso
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_1'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_2'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_3'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_4'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_5'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_6'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_7'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_8'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_9'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_10'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_11'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_33'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_34'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_35'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_38'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_CURSO', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_9'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_39'));


---Agregar los campos a los formularios de formacion docente 
INSERT INTO ui.campos_formulario (id_formulario,id_campo,id_catalogo,orden,rules,id_callback,tooltip,placeholder,display,activo,css,excepciones_opciones,nueva_linea,attributes_extra,reglas_catalogos,regla_notificacion,mostrar_datatable,campos_dependientes,is_precarga,clave_regla_dependencia_catalogo,label_personalizado,is_linea_completa,grupo_informacion_campo) VALUES (8,(select id_campo from ui.campo where nombre = 'form_edu_cedula'),NULL,7,'{"field":"form_edu_cedula","label":"Cédula:","rules":"required"}',NULL,'','',false,true,'',NULL,false,'',NULL,'',false,'{"campos":[],"elementos":{}}',false,NULL,NULL,false,1);
INSERT INTO ui.campos_formulario (id_formulario,id_campo,id_catalogo,orden,rules,id_callback,tooltip,placeholder,display,activo,css,excepciones_opciones,nueva_linea,attributes_extra,reglas_catalogos,regla_notificacion,mostrar_datatable,campos_dependientes,is_precarga,clave_regla_dependencia_catalogo,label_personalizado,is_linea_completa,grupo_informacion_campo) VALUES	(2,(select id_campo from ui.campo where nombre = 'form_edu_cedula'),NULL,7,'{"field":"form_edu_cedula","label":"Cédula:","rules":"required"}',NULL,'','',false,true,'',NULL,false,'',NULL,'',false,'{"campos":[],"elementos":{}}',false,NULL,NULL,false,1);
INSERT INTO ui.campos_formulario (id_formulario,id_campo,id_catalogo,orden,rules,id_callback,tooltip,placeholder,display,activo,css,excepciones_opciones,nueva_linea,attributes_extra,reglas_catalogos,regla_notificacion,mostrar_datatable,campos_dependientes,is_precarga,clave_regla_dependencia_catalogo,label_personalizado,is_linea_completa,grupo_informacion_campo) VALUES	 (7,(select id_campo from ui.campo where nombre = 'form_edu_cedula'),NULL,7,'{"field":"form_edu_cedula","label":"Cédula:","rules":"required"}',NULL,'','',false,true,'',NULL,false,'',NULL,'',false,'{"campos":[],"elementos":{}}',false,NULL,NULL,false,1);
INSERT INTO ui.campos_formulario (id_formulario,id_campo,id_catalogo,orden,rules,id_callback,tooltip,placeholder,display,activo,css,excepciones_opciones,nueva_linea,attributes_extra,reglas_catalogos,regla_notificacion,mostrar_datatable,campos_dependientes,is_precarga,clave_regla_dependencia_catalogo,label_personalizado,is_linea_completa,grupo_informacion_campo) VALUES	 (7,(select id_campo from ui.campo where nombre = 'form_edu_nom_acad'),NULL,6,'{"field":"form_edu_nom_acad","label":"Nombre académico","rules":"required"}',NULL,'','',false,true,'',NULL,false,'',NULL,'',NULL,'{"campos":[],"elementos":{}}',NULL,NULL,NULL,false,1);
INSERT INTO ui.campos_formulario (id_formulario,id_campo,id_catalogo,orden,rules,id_callback,tooltip,placeholder,display,activo,css,excepciones_opciones,nueva_linea,attributes_extra,reglas_catalogos,regla_notificacion,mostrar_datatable,campos_dependientes,is_precarga,clave_regla_dependencia_catalogo,label_personalizado,is_linea_completa,grupo_informacion_campo) VALUES	 (8,(select id_campo from ui.campo where nombre = 'form_edu_nom_acad'),NULL,6,'{"field":"form_edu_nom_acad","label":"Nombre académico","rules":"required"}',NULL,'','',false,true,'',NULL,false,'',NULL,'',NULL,'{"campos":[],"elementos":{}}',NULL,NULL,NULL,false,1);
INSERT INTO ui.campos_formulario (id_formulario,id_campo,id_catalogo,orden,rules,id_callback,tooltip,placeholder,display,activo,css,excepciones_opciones,nueva_linea,attributes_extra,reglas_catalogos,regla_notificacion,mostrar_datatable,campos_dependientes,is_precarga,clave_regla_dependencia_catalogo,label_personalizado,is_linea_completa,grupo_informacion_campo) VALUES	 (2,(select id_campo from ui.campo where nombre = 'form_edu_nom_acad'),NULL,6,'{"field":"form_edu_nom_acad","label":"Nombre académico","rules":"required"}',NULL,'','',false,true,'',NULL,false,'',NULL,'',NULL,'{"campos":[],"elementos":{}}',NULL,NULL,NULL,false,1);

---Agregar los campos a los formulario actividad docente actual
INSERT INTO ui.campos_formulario (id_formulario,id_campo,id_catalogo,orden,rules,id_callback,tooltip,placeholder,display,activo,css,excepciones_opciones,nueva_linea,attributes_extra,reglas_catalogos,regla_notificacion,mostrar_datatable,campos_dependientes,is_precarga,clave_regla_dependencia_catalogo,label_personalizado,is_linea_completa,grupo_informacion_campo) VALUES	 (5,(select id_campo from ui.campo where nombre = 'form_edu_duracion_horas'),(select id_catalogo from catalogo.catalogo where nombre = 'form_edu_dura_horas'),14,'{"field":"form_edu_duracion_horas","label":"Horas:","rules":"required"}',NULL,'','',false,true,'',NULL,false,'',0,'',false,'{"campos":[],"elementos":{}}',false,NULL,NULL,false,1);

--Ordena y actualiza campos del formulario 5 actividad docente actual
update ui.campos_formulario set orden = 1 from ui.campo c where  ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'anio';
update ui.campos_formulario set orden = 2, reglas_catalogos = 2, excepciones_opciones = '3955,208,2001,3893,3894,3954', campos_dependientes = '{"campos":["curso","tipo_profesor","sub_tipo_curso_preg","form_edu_duracion_horas"],"elementos":{"curso":["208","1999","2001","2002","2003","2004","3952","3953"],"tipo_profesor":["208","1999","2000","2001","2002","2003","2004"],"sub_tipo_curso_preg":["2000","3952"],"form_edu_duracion_horas":["1999","3952","3953"]}}' from ui.campo c where  ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'tipo_curso';
update ui.campos_formulario set orden = 3, clave_regla_dependencia_catalogo = 'TPC_STPC_PRIMA', reglas_catalogos = 3, excepciones_opciones = '3893,3894', campos_dependientes = '{"campos":["tipo_promocion_preg","curso_aux"],"elementos":{"tipo_promocion_preg":["3893","3894"],"curso_aux":["3893","3894"]}}' from ui.campo c where  ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'sub_tipo_curso_preg';
update ui.campos_formulario set orden = 4, reglas_catalogos = 3, campos_dependientes = '{"campos":["form_aca_otro_curso"],"elementos":{"form_aca_otro_curso":["1796"]}}' from ui.campo c where  ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'curso';
update ui.campos_formulario set orden = 5 from ui.campo c where  ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'form_aca_otro_curso';
update ui.campos_formulario set orden = 6 from ui.campo c where  ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'tipo_promocion_preg';
update ui.campos_formulario set orden = 7 from ui.campo c where  ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'curso_aux';
update ui.campos_formulario set orden = 8 from ui.campo c where  ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'grado';
update ui.campos_formulario set orden = 9 from ui.campo c where  ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'sede_academica';
update ui.campos_formulario set orden = 10 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'tipo_profesor';
update ui.campos_formulario set orden = 11 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'curso_con_reconocimiento_inst_edu';
update ui.campos_formulario set orden = 12 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'form_aca_otra_institucion';
update ui.campos_formulario set orden = 13 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'curso_institucion_educativa';
update ui.campos_formulario set orden = 14 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'form_edu_duracion_horas';

--Ordena y actualiza campos del formulario 2
update ui.campos_formulario set orden = 1 from ui.campo c where ui.campos_formulario.id_campos_formulario = 2 and ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 2 and c.nombre = 'anio';
update ui.campos_formulario set orden = 2, excepciones_opciones = '2145,1781,1784,1782,1783', campos_dependientes = '{"campos":["form_edu_cedula","form_edu_duracion_horas","form_edu_nom_acad"],"elementos":{"form_edu_cedula":["1783","1784"],"form_edu_duracion_horas":["1782","1781","2145"],"form_edu_nom_acad":["1783","1784"]}}', reglas_catalogos = 1  from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 2 and c.nombre = 'form_edu_nivel_academico';
update ui.campos_formulario set orden = 3 from ui.campo c where ui.campos_formulario.id_campos_formulario = 2 and ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 2 and c.nombre = 'curso_interno_externo';
update ui.campos_formulario set orden = 4 from ui.campo c where ui.campos_formulario.id_campos_formulario = 2 and ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 2 and c.nombre = 'form_aca_otro_curso';
update ui.campos_formulario set orden = 5 from ui.campo c where ui.campos_formulario.id_campos_formulario = 2 and ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 2 and c.nombre = 'form_edu_institucion_academica';
update ui.campos_formulario set orden = 6 from ui.campo c where ui.campos_formulario.id_campos_formulario = 2 and ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 2 and c.nombre = 'form_edu_nom_acad';
update ui.campos_formulario set orden = 7 from ui.campo c where ui.campos_formulario.id_campos_formulario = 2 and ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 2 and c.nombre = 'form_edu_cedula';

--Ordena y actualiza campos del formulario 7
update ui.campos_formulario set orden = 1 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 7 and c.nombre = 'anio';
update ui.campos_formulario set orden = 2, excepciones_opciones = '2146,1785,1787,1786', campos_dependientes = '{"campos":["form_edu_cedula","form_edu_duracion_horas","form_edu_nom_acad"],"elementos":{"form_edu_cedula":["1786","1787"],"form_edu_duracion_horas":["1785","2146"],"form_edu_nom_acad":["1786","1787"]}}', reglas_catalogos = 1  from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 7 and c.nombre = 'form_edu_nivel_academico';
update ui.campos_formulario set orden = 3 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 7 and c.nombre = 'curso_interno_externo';
update ui.campos_formulario set orden = 4 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 7 and c.nombre = 'form_aca_otro_curso';
update ui.campos_formulario set orden = 5 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 7 and c.nombre = 'form_edu_institucion_academica';
update ui.campos_formulario set orden = 6 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 7 and c.nombre = 'form_edu_nom_acad';
update ui.campos_formulario set orden = 7 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 7 and c.nombre = 'form_edu_cedula';

--Ordena y actualiza campos del formulario 8
update ui.campos_formulario set orden = 1 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 8 and c.nombre = 'anio';
update ui.campos_formulario set orden = 2, excepciones_opciones = '2147,1791,1790,1788,1789', campos_dependientes = '{"campos":["form_edu_cedula","form_edu_duracion_horas","form_edu_nom_acad"],"elementos":{"form_edu_cedula":["1789","1790"],"form_edu_duracion_horas":["1788","1791","2147"],"form_edu_nom_acad":["1789","1790"]}}', reglas_catalogos = 1  from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 8 and c.nombre = 'form_edu_nivel_academico';
update ui.campos_formulario set orden = 3 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 8 and c.nombre = 'curso_interno_externo';
update ui.campos_formulario set orden = 4 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 8 and c.nombre = 'form_aca_otro_curso';
update ui.campos_formulario set orden = 5 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 8 and c.nombre = 'form_edu_institucion_academica';
update ui.campos_formulario set orden = 6 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 8 and c.nombre = 'form_edu_nom_acad';
update ui.campos_formulario set orden = 7 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 8 and c.nombre = 'form_edu_cedula';


SELECT setval('catalogo.elementos_catalogos_id_elemento_catalogo_seq', 3956);

--fUNCION PARA OBTENER DATOS DE PREGRADO
CREATE OR REPLACE FUNCTION censo.informacion_especifica_formulario(id_docente_f int,  id_censo_f int,  id_elemento_catalogo_f int) RETURNS varchar
    LANGUAGE plpgsql
    AS $$
	declare init_row RECORD;	
begin
	select into init_row  id_docente, id_censo , texto 
		from
			(select id_docente, id_censo , string_agg(texto, ', ') texto ,array_agg(DISTINCT id_elemento_catalogo) ids_elemento_seccion 
			, (SELECT count(y.x) FROM (SELECT unnest(array_agg(DISTINCT id_elemento_catalogo)) AS x) as y where y.x in(id_elemento_catalogo_f) ) total 
			from 
			(select "CC".id_docente, "CC".id_censo --, "CM".label, "CIF".valor--"CC".id_docente, "ES".id_elemento_seccion, "ES".label
					,"CM"."label" ||' '|| 
					(case when ("TC".nombre = 'dropdown' or "TC".nombre = 'dropdown_otro') 
						then (case when "CM".nombre = 'sede_academica' 
								then (select '('||ui.clave_unidad || ') '|| ui.nombre from catalogo.unidades_instituto ui where ui.anio = (select max(un.anio) from catalogo.unidades_instituto un ) and ui.clave_unidad = "CIF".valor) 
							  else "ELCAT".label end) 
					 else "CIF".valor end) texto 
					--,"CM".nombre campo, "CIF".valor valor_campo
					, "ELCAT".id_elemento_catalogo
			 FROM censo.censo "CC"
				 JOIN ui.validacion_registro "UVR" ON "UVR".id_validacion_registro = "CC".id_validacion_registro
				 LEFT JOIN censo.censo_info "CIF" ON "CC".id_censo = "CIF".id_censo
				 LEFT JOIN ui.campos_formulario "CF" ON "CIF".id_campos_formulario = "CF".id_campos_formulario
				 LEFT JOIN ui.formulario "F" ON "CF".id_formulario = "F".id_formulario
				 LEFT JOIN catalogo.elementos_seccion "ES" ON "F".id_elemento_seccion = "ES".id_elemento_seccion
				 LEFT JOIN catalogo.secciones "S" ON "S".id_seccion = "ES".id_seccion
				 LEFT JOIN ui.campo "CM" ON "CM".id_campo = "CF".id_campo
				 LEFT JOIN catalogo.catalogo "CT" ON "CT".id_catalogo = "CF".id_catalogo
				 LEFT JOIN ui.tipo_dato_campos "TDC" ON "TDC".id_tipo_dato = "CM".id_tipo_dato
				 LEFT JOIN ui.tipo_campo "TC" ON "TC".id_tipo_campo = "CM".id_tipo_campo
				 LEFT JOIN catalogo.elementos_catalogos "ELCAT" ON "ELCAT".id_catalogo = "CT".id_catalogo AND "ELCAT".id_elemento_catalogo::text = "CIF".valor
				 where "F".id_formulario = 5 and "CIF".valor != 'NULL' and id_docente = 2112
			--  	 GROUP BY "CC".id_docente, "CC".id_censo, "CF".orden, "CM".label
				 order by "CC".id_docente, "CC".id_censo, "CF".orden
			  ) as pregado_info
			  GROUP by id_docente, id_censo) as h 
		WHERE total >0;
		  
	  	if init_row.texto is not null then
			return init_row.texto;
		else
			return '';
		end if;
	END;
$$;


drop VIEW censo.total_registros_censo_docente;

drop VIEW censo.total_registros_censo;

CREATE OR REPLACE VIEW censo.total_registros_censo AS 
SELECT "CC".id_docente,
        CASE
            WHEN "ES".id_elemento_seccion = 6 THEN count(DISTINCT "CC".id_censo)
            ELSE NULL::bigint
        END AS experiencia_docente_previa,
        CASE
            WHEN "ES".id_elemento_seccion = 5 THEN count(DISTINCT "CC".id_censo)
            ELSE NULL::bigint
        END AS experiencia_actual,
        CASE
            WHEN "ES".id_elemento_seccion = 2 THEN count(DISTINCT "CC".id_censo)
            ELSE NULL::bigint
        END AS formacion_educacion,
        CASE
            WHEN "ES".id_elemento_seccion = 7 THEN count(DISTINCT "CC".id_censo)
            ELSE NULL::bigint
        END AS formacion_investigacion_temas_educacion,
        CASE
            WHEN "ES".id_elemento_seccion = 9 THEN count(DISTINCT "CC".id_censo)
            ELSE NULL::bigint
        END AS formacion_investigacion_otros_campos,
        CASE
            WHEN "ES".id_elemento_seccion = 3 THEN count(DISTINCT "CC".id_censo)
            ELSE NULL::bigint
        END AS publicacion,
        CASE
            WHEN "ES".id_elemento_seccion = 4 THEN count(DISTINCT "CC".id_censo)
            ELSE NULL::bigint
        END AS material_recurso,
        CASE
            WHEN "ES".id_elemento_seccion = 5 THEN count(
            CASE
                WHEN "CM".id_campo = 40 THEN
                CASE
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo = 1999 THEN 1
                    ELSE NULL::integer
                END
                ELSE NULL::integer
            END)
            ELSE NULL::bigint
        END AS posgrado,
        CASE
            WHEN "ES".id_elemento_seccion = 5 THEN count(
            CASE
                WHEN "CM".id_campo = 40 THEN
                CASE
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo = 2004 THEN 1
                    ELSE NULL::integer
                END
                ELSE NULL::integer
            END)
            ELSE NULL::bigint
        END AS ciefd,
				CASE
            WHEN "ES".id_elemento_seccion = 5 THEN count(
            CASE
                WHEN "CM".id_campo in(169) THEN
                CASE
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo = 3894 THEN 1
                    ELSE NULL::integer
                END
                ELSE NULL::integer
            END)
            ELSE NULL::bigint
        END AS servicio_social,
				CASE
            WHEN "ES".id_elemento_seccion = 5 THEN count(
            CASE
                WHEN "CM".id_campo in(40) THEN
                CASE
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo = 3952 THEN 1
                    ELSE NULL::integer
                END
                ELSE NULL::integer
            END)
            ELSE NULL::bigint
        END AS enfermeria,
				CASE
            WHEN "ES".id_elemento_seccion = 5 THEN count(
            CASE
                WHEN "CM".id_campo in(40) THEN
                CASE
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo = 3953 THEN 1
                    ELSE NULL::integer
                END
                ELSE NULL::integer
            END)
            ELSE NULL::bigint
        END AS tecnicos,
        CASE
            WHEN "ES".id_elemento_seccion = 5 THEN string_agg(
            CASE
                WHEN "CM".id_campo in(169,40) THEN
                CASE
										--select * from catalogo.elementos_catalogos where id_elemento_catalogo in(3894,3893)
                    --WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo in(3894,3893) then (select texto from censo.informacion_pregrado ip where ip.id_docente = "CC".id_docente and "CC".id_censo = ip.id_censo) 
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo in(3894,3893) then (SELECT censo.informacion_especifica_formulario("CC".id_docente,"CC".id_censo,"ELCAT".id_elemento_catalogo)) 
					ELSE NULL::varchar
                END
                ELSE NULL::varchar
            end, '\n')
            ELSE NULL::varchar
        END AS datos_formulario,
		CASE
            WHEN "ES".id_elemento_seccion = 5 THEN string_agg(
            CASE
                WHEN "CM".id_campo in(169,40) THEN
                CASE
										--select * from catalogo.elementos_catalogos where id_elemento_catalogo in(3894,3893)
                    --WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo in(3894,3893) then (select texto from censo.informacion_pregrado ip where ip.id_docente = "CC".id_docente and "CC".id_censo = ip.id_censo) 
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo in(3952,3953) then (SELECT censo.informacion_especifica_formulario("CC".id_docente,"CC".id_censo,"ELCAT".id_elemento_catalogo)) 
					ELSE NULL::varchar
                END
                ELSE NULL::varchar
            end, '\n')
            ELSE NULL::varchar
        END AS datos_formulario_enf_tec,
				CASE
            WHEN "ES".id_elemento_seccion = 5 THEN count(
            CASE
                WHEN "CM".id_campo in(169) THEN
                CASE
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo = 3893 THEN 1
                    ELSE NULL::integer
                END
                ELSE NULL::integer
            END)
            ELSE NULL::bigint
        END AS internado_medico,
        CASE
            WHEN "ES".id_elemento_seccion = 5 THEN count(
            CASE
                WHEN "CM".id_campo in(40) THEN
                CASE
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo = 2001 THEN 1
                    ELSE NULL::integer
                END
                ELSE NULL::integer
            END)
            ELSE NULL::bigint
        END AS enfermeria_tecnicos,
        CASE
            WHEN "ES".id_elemento_seccion = 5 THEN count(
            CASE
                WHEN "CM".id_campo = 40 THEN
                CASE
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo = 2002 THEN 1
                    ELSE NULL::integer
                END
                ELSE NULL::integer
            END)
            ELSE NULL::bigint
        END AS educacion_continua,
        CASE
            WHEN "ES".id_elemento_seccion = 5 THEN count(
            CASE
                WHEN "CM".id_campo = 40 THEN
                CASE
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo = 2000 THEN 1
                    ELSE NULL::integer
                END
                ELSE NULL::integer
            END)
            ELSE NULL::bigint
        END AS pregrado,
        CASE
            WHEN "ES".id_elemento_seccion = 5 THEN count(
            CASE
                WHEN "CM".id_campo = 40 THEN
                CASE
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo = 2003 THEN 1
                    ELSE NULL::integer
                END
                ELSE NULL::integer
            END)
            ELSE NULL::bigint
        END AS educacion_distancia,
        CASE
            WHEN "ES".id_elemento_seccion = 2 THEN count(
            CASE
                WHEN "CM".id_campo = 86 THEN
                CASE
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo IN (2145,2146,2147) THEN 1
                    ELSE NULL::integer
                END
                ELSE NULL::integer
            END)
            ELSE NULL::bigint
        END AS curso_corto_educacion,
        CASE
            WHEN "ES".id_elemento_seccion = 2 THEN count(
            CASE
                WHEN "CM".id_campo = 86 THEN
                CASE
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo IN (1781,1785,1791) THEN 1
                    ELSE NULL::integer
                END
                ELSE NULL::integer
            END)
            ELSE NULL::bigint
        END AS diplomado_educacion,
        CASE
            WHEN "ES".id_elemento_seccion = 2 THEN count(
            CASE
                WHEN "CM".id_campo = 86 THEN
                CASE
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo IN (1782,1788) THEN 1
                    ELSE NULL::integer
                END
                ELSE NULL::integer
            END)
            ELSE NULL::bigint
        END AS especialidad_educacion,
        CASE
            WHEN "ES".id_elemento_seccion = 2 THEN count(
            CASE
                WHEN "CM".id_campo = 86 THEN
                CASE
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo IN (1783,1786,1789) THEN 1
                    ELSE NULL::integer
                END
                ELSE NULL::integer
            END)
            ELSE NULL::bigint
        END AS maestria_educacion,
        CASE
            WHEN "ES".id_elemento_seccion = 2 THEN count(
            CASE
                WHEN "CM".id_campo = 86 THEN
                CASE
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo IN (1784,1787,1790) THEN 1
                    ELSE NULL::integer
                END
                ELSE NULL::integer
            END)
            ELSE NULL::bigint
        END AS doctorado_educacion
   FROM censo.censo "CC"
     JOIN ui.validacion_registro "UVR" ON "UVR".id_validacion_registro = "CC".id_validacion_registro
     LEFT JOIN censo.censo_info "CIF" ON "CC".id_censo = "CIF".id_censo
     LEFT JOIN ui.campos_formulario "CF" ON "CIF".id_campos_formulario = "CF".id_campos_formulario
     LEFT JOIN ui.formulario "F" ON "CF".id_formulario = "F".id_formulario
     LEFT JOIN catalogo.elementos_seccion "ES" ON "F".id_elemento_seccion = "ES".id_elemento_seccion
     LEFT JOIN catalogo.secciones "S" ON "S".id_seccion = "ES".id_seccion
     LEFT JOIN ui.campo "CM" ON "CM".id_campo = "CF".id_campo
     LEFT JOIN catalogo.catalogo "CT" ON "CT".id_catalogo = "CF".id_catalogo
     LEFT JOIN ui.tipo_dato_campos "TDC" ON "TDC".id_tipo_dato = "CM".id_tipo_dato
     LEFT JOIN ui.tipo_campo "TC" ON "TC".id_tipo_campo = "CM".id_tipo_campo
     LEFT JOIN catalogo.elementos_catalogos "ELCAT" ON "ELCAT".id_catalogo = "CT".id_catalogo AND "ELCAT".id_elemento_catalogo::text = "CIF".valor
     --where id_docente = 2210
  GROUP BY "CC".id_docente, "ES".id_elemento_seccion, "ES".label;



CREATE OR REPLACE VIEW censo.total_registros_censo_docente
AS SELECT t.id_docente,
    sum(t.experiencia_docente_previa) AS experiencia_docente_previa,
    sum(t.experiencia_actual) AS experiencia_actual,
    sum(t.formacion_educacion) AS formacion_educacion,
    sum(t.formacion_investigacion_temas_educacion) AS formacion_investigacion_temas_educacion,
    sum(t.formacion_investigacion_otros_campos) AS formacion_investigacion_otros_campos,
    sum(t.publicacion) AS publicacion,
    sum(t.material_recurso) AS material_recurso,
    sum(t.posgrado) AS posgrado,
    sum(t.ciefd) AS ciefd,
	sum(t.servicio_social) AS servicio_social,
	sum(t.internado_medico) AS internado_medico,
    sum(t.enfermeria_tecnicos) AS enfermeria_tecnicos,
	sum(t.enfermeria) AS enfermeria,
	sum(t.tecnicos) AS tecnicos,
    sum(t.educacion_continua) AS educacion_continua,
    sum(t.pregrado) AS pregrado,
    sum(t.educacion_distancia) AS educacion_distancia,
    sum(t.curso_corto_educacion) AS curso_corto_educacion,
    sum(t.diplomado_educacion) AS diplomado_educacion,
    sum(t.especialidad_educacion) AS especialidad_educacion,
    sum(t.maestria_educacion) AS maestria_educacion,
    sum(t.doctorado_educacion) AS doctorado_educacion,
	  string_agg(t.datos_formulario, '') informacion_pregrado,
	  string_agg(t.datos_formulario_enf_tec, '') informacion_enf_tec,
        CASE
            WHEN sum(t.curso_corto_educacion) > 0::numeric THEN 1
            ELSE 0
        END AS curso_corto_educacion_cumple,
    	CASE
            WHEN sum(t.diplomado_educacion) > 0::numeric THEN 1
            ELSE 0
        END AS diplomado_educacion_cumple,
        CASE
            WHEN sum(t.especialidad_educacion) > 0::numeric THEN 1
            ELSE 0
        END AS especialidad_educacion_cumple,
        CASE
            WHEN sum(t.maestria_educacion) > 0::numeric THEN 1
            ELSE 0
        END AS maestria_educacion_cumple,
        CASE
            WHEN sum(t.doctorado_educacion) > 0::numeric THEN 1
            ELSE 0
        END AS doctorado_educacion_cumple,
        CASE
            WHEN sum(t.curso_corto_educacion) > 0::numeric OR sum(t.diplomado_educacion) > 0::numeric OR sum(t.especialidad_educacion) > 0::numeric OR sum(t.maestria_educacion) > 0::numeric OR sum(t.doctorado_educacion) > 0::numeric THEN 1
            ELSE 0
        END AS cumplimiento
   FROM censo.total_registros_censo t
  GROUP BY t.id_docente;






commit;