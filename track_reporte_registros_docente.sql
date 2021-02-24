--Permisos de modulo rol
insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('REPOR_DOC1', 'Reporte docentes', '/censo/reporte_general', true, null, 6, 'MENU');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('REPOR_DOC1', 'VALIDADOR2',true);

insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('REPOR_GRID_DOC1', 'GridReporteDocentes', '/censo/datos_reporte', true, 'REPOR_DOC1', 6, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('REPOR_GRID_DOC1', 'VALIDADOR2',true);


--Se crea vista para simplificar registros y consulta. y optimizar consulta
create or replace view censo.total_registros_censo as
select "CC"."id_docente",--"ES".id_elemento_seccion, "ES".label, count(distinct "CC".id_censo),
		case when "ES".id_elemento_seccion = 6 then count(distinct "CC".id_censo) else null end experiencia_docente_previa,
		case when "ES".id_elemento_seccion = 5 then count(distinct "CC".id_censo) else null end experiencia_actual,
		case when "ES".id_elemento_seccion = 2 then count(distinct "CC".id_censo) else null end formacion_educacion,
		case when "ES".id_elemento_seccion = 7 then count(distinct "CC".id_censo) else null end formacion_investigacion_temas_educacion,
		case when "ES".id_elemento_seccion = 9 then count(distinct "CC".id_censo) else null end formacion_investigacion_otros_campos,
		case when "ES".id_elemento_seccion = 3 then count(distinct "CC".id_censo) else null end publicacion,
		case when "ES".id_elemento_seccion = 4 then count(distinct "CC".id_censo) else null end material_recurso,
		/*case when "ES".id_elemento_seccion = 5 then array_to_string(array_agg(case when "CM"."id_campo" = 40 then
		case WHEN ("ELCAT"."id_elemento_catalogo" IS NOT NULL) THEN "ELCAT"."label" ELSE "CIF"."valor" end else null end),',') else null end tipo_curso,*/
		case when "ES".id_elemento_seccion = 5 then count(case when "CM"."id_campo" = 40 then case WHEN ("ELCAT"."id_elemento_catalogo" IS NOT null and "ELCAT"."id_elemento_catalogo" = '1999') THEN 1 end else null end) else null end posgrado,
		case when "ES".id_elemento_seccion = 5 then count(case when "CM"."id_campo" = 40 then case WHEN ("ELCAT"."id_elemento_catalogo" IS NOT null and "ELCAT"."id_elemento_catalogo" = '2004') THEN 1 end else null end) else null end ciefd,
		case when "ES".id_elemento_seccion = 5 then count(case when "CM"."id_campo" = 40 then case WHEN ("ELCAT"."id_elemento_catalogo" IS NOT null and "ELCAT"."id_elemento_catalogo" = '2001') THEN 1 end else null end) else null end enfermeria_tecnicos,
		case when "ES".id_elemento_seccion = 5 then count(case when "CM"."id_campo" = 40 then case WHEN ("ELCAT"."id_elemento_catalogo" IS NOT null and "ELCAT"."id_elemento_catalogo" = '2002') THEN 1 end else null end) else null end educacion_continua,
		case when "ES".id_elemento_seccion = 5 then count(case when "CM"."id_campo" = 40 then case WHEN ("ELCAT"."id_elemento_catalogo" IS NOT null and "ELCAT"."id_elemento_catalogo" = '2000') THEN 1 end else null end) else null end pregrado,
		case when "ES".id_elemento_seccion = 5 then count(case when "CM"."id_campo" = 40 then case WHEN ("ELCAT"."id_elemento_catalogo" IS NOT null and "ELCAT"."id_elemento_catalogo" = '2003') THEN 1 end else null end) else null end educacion_distancia,
		/*case when "ES".id_elemento_seccion = 2 then array_to_string(array_agg(case when "CM"."id_campo" = 86 then
		case WHEN ("ELCAT"."id_elemento_catalogo" IS NOT NULL) THEN CONCAT("ELCAT"."id_elemento_catalogo",':',"ELCAT"."label") ELSE "CIF"."valor" end else null end),',') else null end tipo_curso,*/
		case when "ES".id_elemento_seccion = 2 then count(case when "CM"."id_campo" = 86 then case WHEN ("ELCAT"."id_elemento_catalogo" IS NOT null and "ELCAT"."id_elemento_catalogo" = '1781') THEN 1 end else null end) else null end diplomado_educacion,
		case when "ES".id_elemento_seccion = 2 then count(case when "CM"."id_campo" = 86 then case WHEN ("ELCAT"."id_elemento_catalogo" IS NOT null and "ELCAT"."id_elemento_catalogo" = '1782') THEN 1 end else null end) else null end especialidad_educacion,
		case when "ES".id_elemento_seccion = 2 then count(case when "CM"."id_campo" = 86 then case WHEN ("ELCAT"."id_elemento_catalogo" IS NOT null and "ELCAT"."id_elemento_catalogo" = '1783') THEN 1 end else null end) else null end maestria_educacion,
		case when "ES".id_elemento_seccion = 2 then count(case when "CM"."id_campo" = 86 then case WHEN ("ELCAT"."id_elemento_catalogo" IS NOT null and "ELCAT"."id_elemento_catalogo" = '1784') THEN 1 end else null end) else null end doctorado_educacion
		FROM "censo"."censo" "CC"
		JOIN "ui"."validacion_registro" "UVR" ON "UVR"."id_validacion_registro" = "CC"."id_validacion_registro"
		LEFT JOIN "censo"."censo_info" "CIF" ON "CC"."id_censo" = "CIF"."id_censo"
		LEFT JOIN "ui"."campos_formulario" "CF" ON "CIF"."id_campos_formulario" = "CF"."id_campos_formulario"
		LEFT JOIN "ui"."formulario" "F" ON "CF"."id_formulario" = "F"."id_formulario"
		LEFT JOIN "catalogo"."elementos_seccion" "ES" ON "F"."id_elemento_seccion" = "ES"."id_elemento_seccion"
		LEFT JOIN "catalogo"."secciones" "S" ON "S"."id_seccion" = "ES"."id_seccion"
		LEFT JOIN "ui"."campo" "CM" ON "CM"."id_campo" = "CF"."id_campo"
		LEFT JOIN "catalogo"."catalogo" "CT" ON "CT"."id_catalogo" = "CF"."id_catalogo"
		LEFT JOIN "ui"."tipo_dato_campos" "TDC" ON "TDC"."id_tipo_dato" = "CM"."id_tipo_dato"
		LEFT JOIN "ui"."tipo_campo" "TC" ON "TC"."id_tipo_campo" = "CM"."id_tipo_campo"
		LEFT JOIN "catalogo"."elementos_catalogos" "ELCAT" ON "ELCAT"."id_catalogo" = "CT"."id_catalogo" and CAST("ELCAT"."id_elemento_catalogo" as text) = "CIF"."valor"
		group by "CC"."id_docente","ES".id_elemento_seccion, "ES".label
;

-- Agrupacion de los registros del  censo por docente
create or replace view censo.total_registros_censo_docente as
select t.id_docente, sum(experiencia_docente_previa) experiencia_docente_previa, sum(experiencia_actual) experiencia_actual,
		sum(formacion_educacion) formacion_educacion, sum(formacion_investigacion_temas_educacion) formacion_investigacion_temas_educacion,
		sum(formacion_investigacion_otros_campos) formacion_investigacion_otros_campos,
		sum(publicacion) publicacion, sum(material_recurso) material_recurso,
		--, array_to_string(array_agg(t.tipo_curso),',')
		sum(posgrado) posgrado, sum(ciefd) ciefd, sum(enfermeria_tecnicos) enfermeria_tecnicos, sum(educacion_continua) educacion_continua,
		sum(pregrado) pregrado, sum(educacion_distancia) educacion_distancia, sum(diplomado_educacion) diplomado_educacion,
		sum(especialidad_educacion) especialidad_educacion, sum(maestria_educacion) maestria_educacion, sum(doctorado_educacion) doctorado_educacion
	from censo.total_registros_censo as t
group by t.id_docente;




CREATE OR REPLACE FUNCTION censo.estado_validacion_docente(id_docente_ bigint) RETURNS integer
    LANGUAGE plpgsql
    AS $$
	declare init_row RECORD;
	declare aux_row_conv RECORD;
  declare id_validacion_rat integer;
begin

	if id_docente_ is not null then--
		FOR aux_row_conv IN 
			select id_convocatoria, nombre, clave, id_tipo_convocatoria from convocatoria.convocatorias where activa
		LOOP
			if aux_row_conv.id_convocatoria is not null then
				--SELECT into init_row count(id_ratificador) total from validacion.ratificador A where A.id_docente = id_docente_ and A.activo and A.id_convocatoria = aux_row_conv.id_convocatoria;
				--if init_row.total is not null and init_row.total > 0 then 
					--RETURN 7;-- Ratificado
				--ELSE
					SELECT into init_row count(id_validacion_finaliza) total from validacion."validacionN1_finaliza" B where B.id_docente = id_docente_ and B.id_convocatoria = aux_row_conv.id_convocatoria and B.activo ;
					if init_row.total is not null and init_row.total > 0 then 
						RETURN 3; --Validado po N1
					ELSE						
						SELECT into init_row count(id_validacion_seccion) total from validacion."validacionN1_seccion" C where C.id_docente = id_docente_ and C.id_convocatoria = aux_row_conv.id_convocatoria and C.activo ;
						if init_row.total is not null and  init_row.total > 0 then
							RETURN 9; --En proceso de validacion por N1
						ELSE
							--*****Alerta Si modifica esta logica, modificar tambien la funcion "complemento censo.estado_validacion_docente_complemento"
							RETURN (select censo.estado_validacion_docente_complemento(id_docente_)); --Finaliza registro de docente
						end if;
					end if;
				--end if;
			else
				RETURN -1;
			end if;
		END LOOP;
	else
		RETURN 0;
	end if;		
END;
$$;

CREATE OR REPLACE FUNCTION censo.estado_validacion_docente_complemento(id_docente_ bigint) RETURNS integer
    LANGUAGE plpgsql
    AS $$
	declare init_row RECORD;
	declare aux_row_conv RECORD; 
begin

	if id_docente_ is not null then--
		FOR aux_row_conv IN 
			select id_convocatoria, nombre, clave, id_tipo_convocatoria from convocatoria.convocatorias where activa
		LOOP
			if aux_row_conv.id_convocatoria is not null then					
				SELECT into init_row count(id_fin_registro) total from validacion."fin_registro_censo" D where D.id_docente = id_docente_ and D.id_convocatoria = aux_row_conv.id_convocatoria and D.activo_edicion = false;
				if init_row.total is not null and init_row.total > 0 then
					RETURN 8; --Finaliza registro de docente
				ELSE
					RETURN 1; --Registro de usuario
				end if;
			else
				RETURN -1;
			end if;
		END LOOP;
	else
		RETURN 0;
	end if;		
END;
$$;

insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('REPOR_GRID_DOC1', 'NORMATIVO',true);
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('REPOR_DOC1', 'NORMATIVO',true);
insert into sistema.inicio_rol_modulo (id_inicio, clave_modulo, clave_rol, orden) values ('validador2_perfil', 'REPOR_DOC1', 'VALIDADOR2', 1);




