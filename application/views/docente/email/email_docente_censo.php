<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Sistema de Información de Docentes del Instituto Mexicano del Seguro Social</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0;" >
  <p>Estimado usuario, han registrado su cuenta en el Sistema de Información de Docentes del Instituto Mexicano del Seguro Social.</p>
  <p>Este sistema tiene como propósito, concentrar la información profesional actualizada y confiable del personal de salud con actividad docente en el IMSS, lo que permite realizar una mejor programación, toma de decisiones y evaluación.</p>
  <p>La liga para ingresar es <a href="http://aplicativosweb_die.imss.gob.mx/censo/">http://aplicativosweb_die.imss.gob.mx/censo/</a>.</p>
  <?php if(empty($username_alias)){ ?>
  <p>Para iniciar sesión utilice su matrícula y la siguiente contraseña: <?php echo isset($password) ? $password : ''; ?></p>  <?php echo isset($password) ? $password : ''; ?>
  <?php }else{ ?>
  <p>Para iniciar sesión utilice su matrícula o nombre de usuario(alias): <?php echo $username_alias; ?> y la siguiente contraseña: <?php echo isset($password) ? $password : ''; ?></p> 
  <?php } ?>
</body>
</html>
