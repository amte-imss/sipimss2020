--Funcion para actualizar unidades del censo
BEGIN;
CREATE OR REPLACE FUNCTION catalogo.actualizar_unidades_anio_actual(anio_referente int) RETURNS varchar
    LANGUAGE plpgsql
    AS $$
	declare init_row RECORD;	
begin

	if anio_referente is not null then--se genera la busqueda al año indicado y lo agrega al actual

		select into init_row  count(*) total from catalogo.unidades_instituto u WHERE u.anio = (date_part('year', CURRENT_DATE));
		if init_row.total is not null and init_row.total > 0 then
			RETURN 'ExistenUnidades con anio '|| (date_part('year', CURRENT_DATE));
		else 
			insert into catalogo.unidades_instituto (clave_unidad,  nombre,  id_delegacion,  clave_presupuestal,  fecha,  nivel_atencion,  id_tipo_unidad,  umae,  activa,  latitud,  longitud,  id_region,  grupo_tipo_unidad,  grupo_delegacion, direccion_fisica,  entidad_federativa,  anio,  unidad_principal,  nombre_unidad_principal,  clave_unidad_principal,  sede_academica) 
			(select clave_unidad,  nombre,  id_delegacion,  clave_presupuestal,  fecha,  nivel_atencion,  id_tipo_unidad,  umae,  activa,  latitud,  longitud,  id_region,  grupo_tipo_unidad,  grupo_delegacion, direccion_fisica,  entidad_federativa,  date_part('year', CURRENT_DATE),  unidad_principal,  nombre_unidad_principal,  clave_unidad_principal,  sede_academica
			from catalogo.unidades_instituto u where  activa and u.anio = anio_referente/*agregar el anio copia*/ order by u.id_unidad_instituto)
			;
			select into init_row  count(*) total from catalogo.unidades_instituto u WHERE u.anio = (date_part('year', CURRENT_DATE));
			RETURN  init_row.total || ' unidades agregadas para el anio ' || (select date_part('year', CURRENT_DATE)) ;
		end if;

	else -- busca el año actual menos 1 

		select into init_row  count(*) total from catalogo.unidades_instituto u WHERE u.anio = (date_part('year', CURRENT_DATE));
		if init_row.total is not null and init_row.total > 0 then
			RETURN 'ExistenUnidades con anio '|| (select date_part('year', CURRENT_DATE));
		else 
			insert into catalogo.unidades_instituto (clave_unidad,  nombre,  id_delegacion,  clave_presupuestal,  fecha,  nivel_atencion,  id_tipo_unidad,  umae,  activa,  latitud,  longitud,  id_region,  grupo_tipo_unidad,  grupo_delegacion, direccion_fisica,  entidad_federativa,  anio,  unidad_principal,  nombre_unidad_principal,  clave_unidad_principal,  sede_academica) 
				(select clave_unidad,  nombre,  id_delegacion,  clave_presupuestal,  fecha,  nivel_atencion,  id_tipo_unidad,  umae,  activa,  latitud,  longitud,  id_region,  grupo_tipo_unidad,  grupo_delegacion, direccion_fisica,  entidad_federativa,  date_part('year', CURRENT_DATE),  unidad_principal,  nombre_unidad_principal,  clave_unidad_principal,  sede_academica
				from catalogo.unidades_instituto u where activa and u.anio = (select MAX(anio) from catalogo.unidades_instituto up) /*agregar el anio copia*/ order by u.id_unidad_instituto)
			;
			select into init_row  count(*) total from catalogo.unidades_instituto u WHERE u.anio = (date_part('year', CURRENT_DATE));
			RETURN  init_row.total || ' unidades agregadas para el anio ' || (select date_part('year', CURRENT_DATE)) ;
		end if;
	
	end if;		
END;
$$;

select catalogo.actualizar_unidades_anio_actual(null) agrgada;


commit;