
insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('CAMBIO_VALN1', 'Cambio de validador N1', '/censo/registros_validador', true, 'CENSO', 15, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('CAMBIO_VALN1', 'NORMATIVO',true);
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('CAMBIO_VALN1', 'SUPERADMIN',true);

insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('CAMBIO_VALN1_LIST', 'Cambio de validador N1 listado', '/censo/validadores_nivel1', true, 'CENSO', 16, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('CAMBIO_VALN1_LIST', 'NORMATIVO',true);
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('CAMBIO_VALN1_LIST', 'SUPERADMIN',true);

insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('CAMBIO_VALN1_GUARDA', 'Guardar cambio de validador N1', '/censo/guarda_cambio_validador', true, 'CENSO', 17, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('CAMBIO_VALN1_GUARDA', 'NORMATIVO',true);
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('CAMBIO_VALN1_GUARDA', 'SUPERADMIN',true);
