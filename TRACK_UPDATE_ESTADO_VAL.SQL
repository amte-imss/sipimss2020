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
	RETURN -1;--no encontro convocatoria alguna	
END;
$$;

--Permisos aviso de privacidad
insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('INI_AVISOPRIVA', 'Confirma aviso de privacidad', '/inicio/confirmar_aviso_privacidad', true, 'INDEX', 4, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('INI_AVISOPRIVA', 'DOCENTE',true);
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('INI_AVISOPRIVA', 'VALIDADOR1',true);
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('INI_AVISOPRIVA', 'VALIDADOR2',true);
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('INI_AVISOPRIVA', 'NORMATIVO',true);