
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