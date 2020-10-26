--crea un esquema
create schema validacion ;

-- crea convocatoria
update convocatoria.convocatorias set activa = false;
insert into convocatoria.convocatorias (nombre, clave, id_tipo_convocatoria, fechas_inicio, fechas_fin, activa) values ('CENSO2020_1','CENSO2020_1', 'N','{2020-10-19,2020-10-19,2020-10-19}','{2020-12-31,2020-12-31,2020-12-31}', true)
--permisos de modulo registro
insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('CENSO_FIN_REG_DOC', 'Finaliza Registro censo docente', '/ConvocatoriaV2/guardar_registro_finaliza_convocatoria_docente_censo', true, 'CENSO', 5, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('CENSO_FIN_REG_DOC', 'DOCENTE',true);

update ui.validacion_registro set activo = false where id_validacion_registro in(2,5,6);
insert into ui.validacion_registro (nombre, activo) values ('Ratificado', true);
insert into ui.validacion_registro (nombre, activo) values ('Finaliza registro censo', true);


--Tablas requeridas

CREATE TABLE "validacion"."ratificador" (
"id_ratificador" serial NOT NULL,
"ratificado" integer NOT NULL,
"comentario" text,
"id_ratificador_validador" integer,
"id_docente" integer,
"id_convocatoria" integer,
"fecha" timestamp without time zone DEFAULT now() NOT NULL,
"activo" boolean DEFAULT true,
PRIMARY KEY ("id_ratificador") 
);



CREATE TABLE "validacion"."validacionN1_finaliza" (
"id_validacion_finaliza" serial NOT NULL,
"id_validador" integer NOT NULL,
"id_docente" integer NOT NULL,
"id_convocatoria" integer NOT NULL,
"fecha_registro" timestamp without time zone DEFAULT now() NOT NULL,
"activo" boolean DEFAULT true,
PRIMARY KEY ("id_validacion_finaliza") 
);



CREATE TABLE "validacion"."validacionN1_seccion" (
"id_validacion_seccion" serial NOT NULL,
"id_censo" integer NOT NULL,
"comentario" text,
"id_convocatoria" integer null,
"fecha" timestamp without time zone DEFAULT now() NOT NULL,
"activo" boolean DEFAULT true,
PRIMARY KEY ("id_validacion_seccion") 
);



CREATE TABLE "validacion"."fin_registro_censo" (
"id_fin_registro" serial NOT NULL,
"id_docente" integer NOT NULL,
"id_convocatoria" integer NOT NULL,
"activo_edicion" boolean DEFAULT false,
"fecha_registro" timestamp without time zone DEFAULT now() NOT NULL,
PRIMARY KEY ("id_fin_registro") 
);



ALTER TABLE "validacion"."validacionN1_seccion" ADD CONSTRAINT "pk_validacion_censo_n1" FOREIGN KEY ("id_censo") REFERENCES "censo"."censo" ("id_censo");
ALTER TABLE "validacion"."fin_registro_censo" ADD CONSTRAINT "pk_docente_fin_registro" FOREIGN KEY ("id_docente") REFERENCES "censo"."docente" ("id_docente");
ALTER TABLE "validacion"."fin_registro_censo" ADD CONSTRAINT "pk_convocatoria_fin_registro" FOREIGN KEY ("id_convocatoria") REFERENCES "convocatoria"."convocatorias" ("id_convocatoria");
ALTER TABLE "validacion"."validacionN1_seccion" ADD CONSTRAINT "pk_validacion_censo_convocatoria" FOREIGN KEY ("id_convocatoria") REFERENCES "convocatoria"."convocatorias" ("id_convocatoria");
ALTER TABLE "validacion"."validacionN1_finaliza" ADD CONSTRAINT "pk_validacion_final_id_validador" FOREIGN KEY ("id_validador") REFERENCES "censo"."docente" ("id_docente");
ALTER TABLE "validacion"."validacionN1_finaliza" ADD CONSTRAINT "pk_validacion_final_validado" FOREIGN KEY ("id_docente") REFERENCES "censo"."docente" ("id_docente");
ALTER TABLE "validacion"."validacionN1_finaliza" ADD CONSTRAINT "pk_validacion_final_convocatoria" FOREIGN KEY ("id_convocatoria") REFERENCES "convocatoria"."convocatorias" ("id_convocatoria");
ALTER TABLE "validacion"."ratificador" ADD CONSTRAINT "pk_ratificador_validador" FOREIGN KEY ("id_ratificador_validador") REFERENCES "censo"."docente" ("id_docente");
ALTER TABLE "validacion"."ratificador" ADD CONSTRAINT "pk_ratificador_docente_ratificado" FOREIGN KEY ("id_docente") REFERENCES "censo"."docente" ("id_docente");
ALTER TABLE "validacion"."ratificador" ADD CONSTRAINT "pk_ratificador_convocatoria" FOREIGN KEY ("id_convocatoria") REFERENCES "convocatoria"."convocatorias" ("id_convocatoria");

