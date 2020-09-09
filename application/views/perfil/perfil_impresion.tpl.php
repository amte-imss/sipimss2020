<header role="banner">
	<div class="container_16">
			<hgroup>
				<h1><?php echo $nombre_docente; ?></h1>
				<!-- <h2>Docente IMSS</h2> -->
			</hgroup>
			<figure>
				<img src="<?php echo $elementos_seccion['ruta_imagen_perfil']; ?>" alt="<?php echo $nombre_docente; ?>" title="<?php echo $nombre_docente; ?>">
			</figure>
	</div>
</header>
<section role="main" class="container_16 clearfix">
	<div class="grid_16">
		<!-- A propos -->
		<div class="grid_8 loisirs">
			<h3>Información Personal</h3>
			<?php
            if ($informacion_general) {
                echo $informacion_general;
            }
            ?>
		</div>
		<!-- Compétences -->
		<div class="grid_8 competences">
			<h3>Información IMSS</h3>
			<?php
            if ($informacion_imss) {
                echo $informacion_imss;
            }
            ?>
		</div>
	</div>
	<!-- Expériences -->
	<div class="grid_16 experiences">
		<!--<h3>Experiencia</h3>
		<ul>
			<li>
				<h4><strong>Formación docente</strong> Formación del personal de salud > Formacion académica</h4>
				<p>Lorem Ipsum</p>
			</li>
			<li>
				<h4><strong>Becas</strong> Becas laborales</h4>
				<p>Lorem Ipsum</p>
			</li>
			<li>
				<h4><strong>Material Educativo</strong> Antologías o manuales relativos a la educación</h4>
				<p>Lorem Ipsum</p>
			</li>
		</ul> -->
		<?php
        if ($main_content) {
            echo $main_content;
        }
        ?>
	</div>
	<!-- Contact -->
	<!-- <div class="grid_16 contact">
		<h3>Contacto</h3>
		<ul>
			<li class="lieu">Tula, Hidalgo </li>
			<li class="phone">55 55 55 55 55</li>
			<li class="mail"><a href="diana_angeles@gmail.com">diana_angeles@gmail.com</a></li>
		</ul>
	</div> -->
</section>