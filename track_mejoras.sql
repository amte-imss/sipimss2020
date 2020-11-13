
---Track  actual 01/11/2020 -------------------------------------------------------------------------------------

update ui.validacion_registro set activo = false where id_validacion_registro = 7;

insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('CENSO_HAB_EDICION', 'Habilita nuevamente edición', '/censo/habilita_edicion', true, 'CENSO', 10, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('CENSO_HAB_EDICION', 'VALIDADOR1',true);