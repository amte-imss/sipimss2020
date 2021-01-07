
---Track  actual 01/11/2020 -------------------------------------------------------------------------------------

update ui.validacion_registro set activo = false where id_validacion_registro = 7;

insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('CENSO_HAB_EDICION', 'Habilita nuevamente edición', '/censo/habilita_edicion', true, 'CENSO', 10, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('CENSO_HAB_EDICION', 'VALIDADOR1',true);

insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('ELIMINA_USUARIO', 'Eliminar usuario', '/usuario/eliminar', true, null, 1, 'ACCION');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('ELIMINA_USUARIO', 'NORMATIVO',true);


insert into sistema.modulos (clave_modulo, nombre, url, activo, modulo_padre_clave, orden, clave_configurador_modulo) values ('GEST_CONV_CRUD', 'Gestión convocatoría crud', '/convocatoriav2/convocatoria_crud', true, null, 1, 'MENU');
insert into sistema.roles_modulos (clave_modulo, clave_rol, activo) values ('GEST_CONV_CRUD', 'NORMATIVO',true);

update sistema.control_registro_usuarios set id_usuario_registra = (select id_usuario from sistema.usuarios where username='98351897')
where id_usuario_registra in (select id_usuario from sistema.usuarios where username='99374249')
;
