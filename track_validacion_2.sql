
---Track  actual 01/11/2020 -------------------------------------------------------------------------------------
insert into ui.validacion_registro (nombre, activo) values ('En proceso de validación N1', true);
update ui.validacion_registro set activo = false where id_validacion_registro in(4);
alter table ui.validacion_registro add column orden int DEFAULT 1;
update ui.validacion_registro set orden = 2 where id_validacion_registro = 8;
update ui.validacion_registro set orden = 3 where id_validacion_registro = 9;
update ui.validacion_registro set orden = 4 where id_validacion_registro = 3;
update ui.validacion_registro set orden = 5 where id_validacion_registro = 7;
update ui.validacion_registro set nombre = 'Validado' where id_validacion_registro = 3;
update ui.validacion_registro set nombre = 'Registro docente' where id_validacion_registro = 1;
alter table  "validacion"."validacionN1_seccion" rename column id_censo to id_docente;
ALTER TABLE "validacion"."validacionN1_seccion" drop CONSTRAINT "pk_validacion_censo_n1" ;
ALTER TABLE "validacion"."validacionN1_seccion" ADD CONSTRAINT "pk_valida_seccion_docente" FOREIGN KEY ("id_docente") REFERENCES "censo"."docente" ("id_docente");
alter table  "validacion"."validacionN1_seccion" add  id_validador integer not null;
ALTER TABLE "validacion"."validacionN1_seccion" ADD CONSTRAINT "pk_validacion_seccion_id_validador" FOREIGN KEY ("id_validador") REFERENCES "censo"."docente" ("id_docente");
alter table  "validacion"."validacionN1_seccion" add column id_seccion integer not null;
ALTER TABLE "validacion"."validacionN1_seccion" ADD CONSTRAINT "pk_validacion_seccion_id_seccion" FOREIGN KEY ("id_seccion") REFERENCES "catalogo"."secciones" ("id_seccion");

--Funcion que obtiene el estado de la validacion del docente
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
					SELECT into init_row count(id_ratificador) total from validacion.ratificador A where A.id_docente = id_docente_ and A.activo and A.id_convocatoria = aux_row_conv.id_convocatoria;
					if init_row.total is not null and init_row.total > 0 then 
						RETURN 7;-- Ratificado
					ELSE
						SELECT into init_row count(id_validacion_finaliza) total from validacion."validacionN1_finaliza" B where B.id_docente = id_docente_ and B.id_convocatoria = aux_row_conv.id_convocatoria and B.activo ;
						if init_row.total is not null and init_row.total > 0 then 
							RETURN 3; --Validado po N1
						ELSE
							SELECT into init_row count(id_validacion_seccion) total from validacion."validacionN1_seccion" C where C.id_docente = id_docente_ and C.id_convocatoria = aux_row_conv.id_convocatoria and C.activo ;
							if init_row.total is not null and  init_row.total > 0 then
								RETURN 9; --En proceso de validacion por N1
							ELSE
									SELECT into init_row count(id_fin_registro) total from validacion."fin_registro_censo" D where D.id_docente = id_docente_ and D.id_convocatoria = aux_row_conv.id_convocatoria and D.activo_edicion = false;
									if init_row.total is not null and init_row.total > 0 then
										RETURN 8; --Finaliza registro de docente
									ELSE
										RETURN 1; --Registro de usuario
									end if;
							end if;
						end if;
					end if;
				end if;
		END LOOP;
	else
		RETURN 0;
	end if;		
END;
$$;

--permisos de validacion
insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('CENSO_VAL_SECC_G', 'Guarda validacion seccion', '/censo/guarda_val_seccion', true, 'CENSO', 6, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('CENSO_VAL_SECC_G', 'VALIDADOR1',true);

alter table "validacion"."validacionN1_seccion" add column elementos_censo text not null;

insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('CENSO_TER_VALI', 'Terminar validacion n1', '/censo/terminar_validacion', true, 'CENSO', 7, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('CENSO_TER_VALI', 'VALIDADOR1',true);
insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('CENSO_TER_RATI', 'Ratificar', '/censo/ratificar_validacion', true, 'CENSO', 8, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('CENSO_TER_RATI', 'VALIDADOR2',true);

delete from catalogo.unidades_instituto where id_unidad_instituto=27483;
---Convocatoria

--Bandera finaliza docente u otro
alter table validacion.fin_registro_censo add column es_finaliza_docente boolean default true;

--Notificaciones
alter table  ui.notificaciones_estaticas add clave_rol varchar(10) default 'DOCENTE';
ALTER TABLE "ui"."notificaciones_estaticas" ADD CONSTRAINT "pk_notificacion_rol" FOREIGN KEY ("clave_rol") REFERENCES "sistema"."roles" ("clave_rol");
alter table "ui"."notificaciones_estaticas" drop CONSTRAINT notificaciones_estaticas_pkey;
alter table "ui"."notificaciones_estaticas" add  PRIMARY key (clave, clave_rol);
-- Agrega normativo que finaliza registro docente
alter table "ui"."notificaciones_estaticas" ADD column id_normativo int null;
ALTER TABLE "ui"."notificaciones_estaticas" ADD CONSTRAINT "pk_normativo_finaliza_registro" FOREIGN KEY ("id_normativo") REFERENCES "censo"."docente" ("id_docente");
alter table  ui.notificaciones_estaticas add activa boolean default true;

INSERT into ui.notificaciones_estaticas (clave, nombre, descripcion, fecha_inicio, fecha_fin, clave_rol) 
values 
('CENSO2020_1', 'Registro docente', 'No olvides que el registro de tu informació termina en {dias_restantes_convocatoria} días','2020-10-19', '2020-11-13', 'DOCENTE'),
('CENSO2020_1', 'Finalizar registro censo docente', 'El registro del censo docente termina en {dias_restantes_convocatoria} días, <br>por lo que deberá asegurarse de finalizar el registro','2020-10-19', '2020-11-13', 'NORMATIVO')
;


update convocatoria.convocatorias set fechas_inicio = '{2020-10-19,2020-11-14,2020-11-14}', fechas_fin = '{2020-11-13,2020-12-05,2020-12-05}' where nombre = 'CENSO2020_1';
alter table convocatoria.convocatorias add column is_confirmado_cierre_registro_censo boolean default false;

insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('CENSO_FIN_REGDOC_GEN', 'Finaliza Registro censo docente general', '/convocatoriaV2/finaliza_convocatoria_docente_censo_general', true, 'CENSO', 7, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('CENSO_FIN_REGDOC_GEN', 'NORMATIVO',true);


create or replace view censo.elementos_catalogo_censo as
select c.id_docente, ci.id_censo_info, ci.id_censo, ci.valor, f.id_formulario, f.nombre nombre_formulario, f.label label_formulario,
ci.id_campos_formulario, cc.id_catalogo, cc.nombre nombre_catalogo, cc.label label_catalogo,
ec.id_elemento_catalogo, ec.nombre elemento_catalogo_nombre, ec.label elemento_catalogo_label
from  censo.censo_info ci
join censo.censo c on c.id_censo = ci.id_censo 
join ui.campos_formulario cf on cf.id_campos_formulario = ci.id_campos_formulario
join ui.formulario f on f.id_formulario = cf.id_formulario and c.id_formulario = f.id_formulario
join catalogo.catalogo cc on cc.id_catalogo = cf.id_catalogo
join catalogo.elementos_catalogos ec on (ec.id_catalogo = cc.id_catalogo) and (ci.valor != 'NULL' and ec.id_elemento_catalogo = ci.valor::int)
;

CREATE TABLE "sistema"."usuario_ooad" (
"id_usuario" integer NOT NULL,
"ooad" varchar(2) NOT NULL,
PRIMARY KEY ("id_usuario", "ooad") 
);


CREATE TABLE "sistema"."usuario_umae" (
"id_usuario" integer NOT NULL,
"umae" varchar(20) NOT NULL,
PRIMARY KEY ("id_usuario", "umae") 
);

update catalogo.unidades_instituto set sede_academica=true where clave_unidad = '23HA020000';

update validacion.fin_registro_censo set activo_edicion = true where activo_edicion = false;