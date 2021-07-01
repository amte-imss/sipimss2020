BEGIN;
SELECT setval('catalogo.elementos_catalogos_id_elemento_catalogo_seq', 3893);
--crear catálogo de tipo promocion
insert into catalogo.catalogo (nombre, descripcion, label,tipo) values ('tipo_promocion','Tipo de prooción para pregrado en los sub tipos de cursos', 'Tipo de promocón', 'elementos_catalogos');


---Tipos de cursos nuevos
insert into catalogo.elementos_catalogos (id_elemento_catalogo,nombre, descripcion, id_catalogo,label,orden,tipo,nivel) values (3893,'tipo_curso_7', 'Internado Médico', 15,'Internado Médico',null,1,1);
insert into catalogo.elementos_catalogos (id_elemento_catalogo,nombre, descripcion, id_catalogo,label,orden,tipo,nivel) values (3894,'tipo_curso_8', 'Servicio Social', 15,'Servicio Social',null,1,1);

---Cursos faltantes para servicio social
insert into catalogo.elementos_catalogos (id_elemento_catalogo, nombre, descripcion, id_catalogo,label,orden,tipo,nivel) values (3895,'curso_curso_140', 'Servicio social medicina', 61,'Servicio social medicina',null,1,1);
insert into catalogo.elementos_catalogos (id_elemento_catalogo, nombre, descripcion, id_catalogo,label,orden,tipo,nivel) values (3896,'curso_curso_141', 'Servicio social de carreras afines', 61,'Servicio social de carreras afines',null,1,1);
insert into catalogo.elementos_catalogos (id_elemento_catalogo, nombre, descripcion, id_catalogo,label,orden,tipo,nivel) values (3897,'curso_curso_142', 'QFB', 61,'QFB',null,1,1);
insert into catalogo.elementos_catalogos (id_elemento_catalogo, nombre, descripcion, id_catalogo,label,orden,tipo,nivel) values (3898,'curso_curso_143', 'QBP', 61,'QBP',null,1,1);
insert into catalogo.elementos_catalogos (id_elemento_catalogo, nombre, descripcion, id_catalogo,label,orden,tipo,nivel) values (3899,'curso_curso_144', 'Psicología Clínica', 61,'Psicología Clínica', null,1,1);
insert into catalogo.elementos_catalogos (id_elemento_catalogo, nombre, descripcion, id_catalogo,label,orden,tipo,nivel) values (3900,'curso_curso_145', 'Fisiatría', 61,'Fisiatría',null,1,1);
insert into catalogo.elementos_catalogos (id_elemento_catalogo, nombre, descripcion, id_catalogo,label,orden,tipo,nivel) values (3901,'curso_curso_146', 'Terapia física y rehabilitación', 61,'Terapia física y rehabilitación',null,1,1);

--Crea elemetos de promocion
insert into catalogo.elementos_catalogos (id_elemento_catalogo,nombre, descripcion, id_catalogo, tipo,label,orden, nivel) values (3902,'tipo_promocion_a', null, (select id_catalogo from catalogo.catalogo where nombre = 'tipo_promocion'), 1, 'PROMOCIÓN A', 1,1);
insert into catalogo.elementos_catalogos (id_elemento_catalogo,nombre, descripcion, id_catalogo, tipo,label,orden, nivel) values (3903,'tipo_promocion_b', null, (select id_catalogo from catalogo.catalogo where nombre = 'tipo_promocion'), 1, 'PROMOCIÓN B', 1,1);



---Cursos faltantes para Internado Médico
insert into catalogo.elementos_catalogos (id_elemento_catalogo, nombre, descripcion, id_catalogo,label,orden,tipo,nivel) values (3904,'curso_curso_147', 'URGENCIAS', 61,'URGENCIAS',null,1,1);

SELECT setval('catalogo.elementos_catalogos_id_elemento_catalogo_seq', 3905);
--Se crea la regla para control de sub tipo de curso
insert into catalogo.reglas_dependencia_catalogos values ('TPC_STPC','Relación subtipo curso pregrado','Para controlar cursos de pregrado',15,61);

---Relacion de reglas de dependencias Servicio Social
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_40'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_44'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_46'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_48'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_49'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_50'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_53'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_55'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_57'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_59'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_60'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_61'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_62'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_90'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_140'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_141'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_142'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_143'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_144'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_145'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_8'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_146'));
;

---Relacion de reglas de dependencias Internado medico
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_7'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_75'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_7'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_92'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_7'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_103'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_7'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_104'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_7'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_126'));
insert into catalogo.detalle_dependencias_catalogos values('TPC_STPC', (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'tipo_curso_7'), (select id_elemento_catalogo id_elemento_catalogo_padre from catalogo.elementos_catalogos ec where nombre = 'curso_curso_147'));



---Cambia regla de catalogo del campo tipo de curso para el formulario 5
update ui.campos_formulario set excepciones_opciones = '208,3893,3894', reglas_catalogos = 2 where id_campos_formulario = 18;


--Agrega campos que se van a utilizar **************
insert into ui.campo (nombre,descripcion, id_tipo_campo,label,activo,id_tipo_dato) values 
('sub_tipo_curso_preg',null,3,'Sub tipo curso:',true,2),
('curso_aux','Este curso se crea como apoyo al campo curso, para poder controlar la información de pre-grado principalmente (servicio social e internado medico)',3,'Curso:',true,2),
('tipo_promocion_preg','Promoción que aplica para MIP o Servicio social',3,'Tipo de promoción:',true,2)
;
---Configurar relacion campo formulario ************
--curso auxiliar
INSERT INTO ui.campos_formulario
(id_formulario,id_campo,id_catalogo,orden,rules,id_callback,tooltip,placeholder,display,activo,css,excepciones_opciones,nueva_linea,attributes_extra,reglas_catalogos,regla_notificacion,mostrar_datatable,campos_dependientes,is_precarga,clave_regla_dependencia_catalogo,label_personalizado,is_linea_completa,grupo_informacion_campo) VALUES
(5,(select id_campo from ui.campo where nombre in('curso_aux')),(select id_catalogo from catalogo.catalogo where nombre = 'corso_curso'),7,'{"field":"curso_aux","label":"Curso:","rules":"required"}',NULL,'','',false,true,'',NULL,false,'',3,'',false,'{"campos":[],"elementos":{}}',false,'TPC_STPC',NULL,false,1);

--Subtipo de curso
INSERT INTO ui.campos_formulario
(id_formulario,id_campo,id_catalogo,orden,rules,id_callback,tooltip,placeholder,display,activo,css,excepciones_opciones,nueva_linea,attributes_extra,reglas_catalogos,regla_notificacion,mostrar_datatable,campos_dependientes,is_precarga,clave_regla_dependencia_catalogo,label_personalizado,is_linea_completa,grupo_informacion_campo) VALUES
(5,(select id_campo from ui.campo where nombre in('sub_tipo_curso_preg')),(select id_catalogo from catalogo.catalogo where nombre = 'tipo_curso'),5,'{"field":"sub_tipo_curso_preg","label":"Sub tipo curso:","rules":"required"}',NULL,'','',false,true,'','3893,3894',false,'',1,'',false,'{"campos":["tipo_promocion_preg","curso_aux"],"elementos":{"tipo_promocion_preg":["3893","3894"],"curso_aux":["3893","3894"]}}',false,NULL,NULL,false,1);

--tipo de promocion
INSERT INTO ui.campos_formulario
(id_formulario,id_campo,id_catalogo,orden,rules,id_callback,tooltip,placeholder,display,activo,css,excepciones_opciones,nueva_linea,attributes_extra,reglas_catalogos,regla_notificacion,mostrar_datatable,campos_dependientes,is_precarga,clave_regla_dependencia_catalogo,label_personalizado,is_linea_completa,grupo_informacion_campo) VALUES
(5,(select id_campo from ui.campo where nombre in('tipo_promocion_preg')),(select id_catalogo from catalogo.catalogo where nombre = 'tipo_promocion'),6,'{"field":"tipo_promocion_preg","label":"Tipo de promoci\u00f3n:","rules":"required"}',NULL,'','',false,true,'',NULL,false,'',NULL,'',false,'{"campos":[],"elementos":{}}',false,NULL,NULL,false,1);


---Orden de los campos en el formulario
update ui.campos_formulario set orden = 1 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'anio';
update ui.campos_formulario set orden = 2, campos_dependientes = '{"campos":["curso","tipo_profesor","sub_tipo_curso_preg"],"elementos":{"curso":["208","1999","2001","2002","2003","2004"],"tipo_profesor":["208","1999","2000","2001","2002","2003","2004"],"sub_tipo_curso_preg":["2000"]}}' from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'tipo_curso';
update ui.campos_formulario set orden = 3, display = false from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'curso';
update ui.campos_formulario set orden = 4 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'form_aca_otro_curso';
update ui.campos_formulario set orden = 5 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'sub_tipo_curso_preg';
update ui.campos_formulario set orden = 6 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'tipo_promocion_preg';
update ui.campos_formulario set orden = 7 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'curso_aux';
update ui.campos_formulario set orden = 8 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'grado';
update ui.campos_formulario set orden = 9 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'sede_academica';
update ui.campos_formulario set orden = 10 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'tipo_profesor';
update ui.campos_formulario set orden = 11 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'curso_con_reconocimiento_inst_edu';
update ui.campos_formulario set orden = 12 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'form_aca_otra_institucion';
update ui.campos_formulario set orden = 13 from ui.campo c where ui.campos_formulario.id_campo = c.id_campo and ui.campos_formulario.id_formulario = 5 and c.nombre = 'curso_institucion_educativa';


--Permisos, reporte de pregrado *************************
insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('REPOR_PREG', 'Reporte docentes pregrado', '/censo/reporte_pregrado', true, null, 7, 'MENU');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('REPOR_PREG', 'NORMATIVO',true);

insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('REPOR_GRID_PREG', 'GridReporteDocentesPregrado', '/censo/datos_reporte_pregrado', true, 'REPOR_PREG', 7, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('REPOR_GRID_PREG', 'NORMATIVO',true);


--fUNCION PARA OBTENER DATOS DE PREGRADO
CREATE OR REPLACE FUNCTION censo.informacion_pregrado_formulario(id_docente_f int,  id_censo_f int) RETURNS varchar
    LANGUAGE plpgsql
    AS $$
	declare init_row RECORD;	
begin
	select into init_row  id_docente, id_censo , string_agg(texto, ', ') texto from 
		(select "CC".id_docente, "CC".id_censo --, "CM".label, "CIF".valor--"CC".id_docente, "ES".id_elemento_seccion, "ES".label
		,"CM"."label" ||' '|| 
 		(case when ("TC".nombre = 'dropdown' or "TC".nombre = 'dropdown_otro') 
 			then (case when "CM".nombre = 'sede_academica' 
 					then (select '('||ui.clave_unidad || ') '|| ui.nombre from catalogo.unidades_instituto ui where ui.anio = (select max(un.anio) from catalogo.unidades_instituto un ) and ui.clave_unidad = "CIF".valor) 
 				  else "ELCAT".label end) 
 		 else "CIF".valor end) texto 
		 FROM censo.censo "CC"
		     --JOIN ui.validacion_registro "UVR" ON "UVR".id_validacion_registro = "CC".id_validacion_registro
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
		     where "F".id_formulario = 5 and "CIF".valor != 'NULL' and "CC".id_docente = id_docente_f and "CC".id_censo = id_censo_f
		--  	 GROUP BY "CC".id_docente, "CC".id_censo, "CF".orden, "CM".label
		     order by "CC".id_docente, "CC".id_censo, "CF".orden
		  ) as pregado_info
  --where id_docente = 2210
  		GROUP by id_docente, id_censo ;
	  	if init_row.texto is not null then
			return init_row.texto;
		else
			return '';
		end if;
	END;
$$;

CREATE INDEX docente_identificador_historico ON censo.historico_datos_docente (id_docente);
CREATE INDEX censo_identificador_censo ON censo.censo (id_docente);
CREATE INDEX censo_info_identificador_info ON censo.censo_info (id_censo);

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
                WHEN "CM".id_campo in(select id_campo from ui.campo where nombre in('sub_tipo_curso_preg')) THEN
                CASE
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo = 3894 THEN 1
                    ELSE NULL::integer
                END
                ELSE NULL::integer
            END)
            ELSE NULL::bigint
        END AS servicio_social,
        CASE
            WHEN "ES".id_elemento_seccion = 5 THEN string_agg(
            CASE
                WHEN "CM".id_campo in(select id_campo from ui.campo where nombre in('sub_tipo_curso_preg')) THEN
                CASE
                    --WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo in(3894,3893) then (select texto from censo.informacion_pregrado ip where ip.id_docente = "CC".id_docente and "CC".id_censo = ip.id_censo) 
                    WHEN "ELCAT".id_elemento_catalogo IS NOT NULL AND "ELCAT".id_elemento_catalogo in(3894,3893) then (SELECT censo.informacion_pregrado_formulario("CC".id_docente,"CC".id_censo)) 
					ELSE NULL::varchar
                END
                ELSE NULL::varchar
            end, '\n')
            ELSE NULL::varchar
        END AS datos_formulario,
				CASE
            WHEN "ES".id_elemento_seccion = 5 THEN count(
            CASE
                WHEN "CM".id_campo in(select id_campo from ui.campo where nombre in('sub_tipo_curso_preg')) THEN
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
    sum(t.educacion_continua) AS educacion_continua,
    sum(t.pregrado) AS pregrado,
    sum(t.educacion_distancia) AS educacion_distancia,
    sum(t.curso_corto_educacion) AS curso_corto_educacion,
    sum(t.diplomado_educacion) AS diplomado_educacion,
    sum(t.especialidad_educacion) AS especialidad_educacion,
    sum(t.maestria_educacion) AS maestria_educacion,
    sum(t.doctorado_educacion) AS doctorado_educacion,
	  string_agg(t.datos_formulario, '') informacion_pregrado,
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


---Transporta informacion de pregrado
CREATE OR REPLACE VIEW censo.informacion_pregrado AS 
select id_docente, id_censo , string_agg(texto, ', ') texto from 
(select "CC".id_docente, "CC".id_censo --, "CM".label, "CIF".valor--"CC".id_docente, "ES".id_elemento_seccion, "ES".label
		,"CM"."label" ||' '|| 
 		(case when ("TC".nombre = 'dropdown' or "TC".nombre = 'dropdown_otro') 
 			then (case when "CM".nombre = 'sede_academica' 
 					then (select '('||ui.clave_unidad || ') '|| ui.nombre from catalogo.unidades_instituto ui where ui.anio = (select max(un.anio) from catalogo.unidades_instituto un ) and ui.clave_unidad = "CIF".valor) 
 				  else "ELCAT".label end) 
 		 else "CIF".valor end) texto 
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
     where "F".id_formulario = 5 and "CIF".valor != 'NULL' --and id_docente = 2210
--  	 GROUP BY "CC".id_docente, "CC".id_censo, "CF".orden, "CM".label
     order by "CC".id_docente, "CC".id_censo, "CF".orden
  ) as pregado_info
  --where id_docente = 2210
  GROUP by id_docente, id_censo ;




commit;




