BEGIN;

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
                WHEN "CM".id_campo = 40 THEN
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
    sum(t.enfermeria_tecnicos) AS enfermeria_tecnicos,
    sum(t.educacion_continua) AS educacion_continua,
    sum(t.pregrado) AS pregrado,
    sum(t.educacion_distancia) AS educacion_distancia,
    sum(t.curso_corto_educacion) AS curso_corto_educacion,
    sum(t.diplomado_educacion) AS diplomado_educacion,
    sum(t.especialidad_educacion) AS especialidad_educacion,
    sum(t.maestria_educacion) AS maestria_educacion,
    sum(t.doctorado_educacion) AS doctorado_educacion,
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



UPDATE catalogo.elementos_catalogos
SET nombre='curso_corto_ea', descripcion='Curso corto en educación y afines', id_catalogo=59, tipo='1', 
id_catalogo_elemento_padre=NULL, activo=true, "label"='Curso corto en educación y afines', orden=NULL, nivel=1, is_validado=true
WHERE id_elemento_catalogo=2145;

UPDATE catalogo.elementos_catalogos
SET nombre='curso_corto_itehs', descripcion='Curso corto en investigación en temas relacionados con la educación y las humanidades en salud', id_catalogo=59, tipo='1', 
id_catalogo_elemento_padre=NULL, activo=true, "label"='Curso corto en investigación en temas relacionados con la educación y las humanidades en salud', orden=NULL, nivel=1, is_validado=true
WHERE id_elemento_catalogo=2146;

UPDATE catalogo.elementos_catalogos
SET nombre='curso_corto_cdrs', descripcion='Curso corto en investigación en otros campos disciplinarios relacionados con la salud', id_catalogo=59, tipo='1', 
id_catalogo_elemento_padre=NULL, activo=true, "label"='Curso corto en investigación en otros campos disciplinarios relacionados con la salud', orden=NULL, nivel=1, is_validado=true
WHERE id_elemento_catalogo=2147;

update "ui"."campos_formulario" set excepciones_opciones='1781,1784,1782,1783,2145' where id_campos_formulario=3;

update "ui"."campos_formulario" set excepciones_opciones='1785,1787,1786,2146' where id_campos_formulario=34;

update "ui"."campos_formulario" set excepciones_opciones='1791,1790,1788,1789,2147' where id_campos_formulario=40;


COMMIT;