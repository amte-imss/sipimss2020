
---Track  actual 01/11/2020 -------------------------------------------------------------------------------------


insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('CENSO_HAB_EDIC_GEN', 'Habilita edición general', '/censo/habilita_edicion_general', true, 'CENSO', 11, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('CENSO_HAB_EDIC_GEN', 'NORMATIVO',true);

