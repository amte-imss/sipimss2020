BEGIN;
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
				 where "F".id_formulario = 5 and "CIF".valor != 'NULL' and id_docente = id_docente_f --and "CC".id_censo = id_censo_f
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
commit;