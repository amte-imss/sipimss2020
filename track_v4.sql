---Reemplazar vista
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
    sum(t.enfermeria_tecnicos) AS enfermeria_tecnicos,
    sum(t.educacion_continua) AS educacion_continua,
    sum(t.pregrado) AS pregrado,
    sum(t.educacion_distancia) AS educacion_distancia,
    sum(t.diplomado_educacion) AS diplomado_educacion,
    sum(t.especialidad_educacion) AS especialidad_educacion,
    sum(t.maestria_educacion) AS maestria_educacion,    
    sum(t.doctorado_educacion) AS doctorado_educacion,
    case when sum(t.diplomado_educacion) > 0 then 1 else 0 end diplomado_educacion_cumple,
    case when sum(t.especialidad_educacion) > 0 then 1 else 0 end especialidad_educacion_cumple,
    case when sum(t.maestria_educacion) > 0 then 1 else 0 end maestria_educacion_cumple,
    case when sum(t.doctorado_educacion) > 0 then 1 else 0 end doctorado_educacion_cumple,
    case when sum(t.diplomado_educacion) > 0 or sum(t.especialidad_educacion) > 0 or
    	sum(t.maestria_educacion) > 0 or sum(t.doctorado_educacion) > 0 
    	then 1 else 0 end cumplimiento
FROM censo.total_registros_censo t
GROUP BY t.id_docente;

--Permisos de modulo rol
insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('REPOR_FORDOC', 'Reporte formación docente', '/censo/reporte_formacion_docente', true, null, 7, 'MENU');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('REPOR_FORDOC', 'NORMATIVO',true);

insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('REPOR_FORDOC_GRID', 'Grid Reporte formación docentes', '/censo/datos_reporte_formacion_docente', true, 'REPOR_FORDOC', 7, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('REPOR_FORDOC_GRID', 'NORMATIVO',true);

