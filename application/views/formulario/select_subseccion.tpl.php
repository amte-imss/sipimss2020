<div class="col-md-3 div_nv_<?php echo $nivel; ?>">
	<div class="input-group input-group-sm">
	<?php

	echo $this->form_complete->create_element(
		array(
			'id' => 'nivel_' . $nivel,
			'name' => 'select_subseccion',
		    'type' => 'dropdown',
		    'first' => array('' => 'Selecciona opción'),
		    'options' => $opciones,
		    'value' => '',
		    'attributes' => array(
		    	'class' => 'form-control input-sm',
		    	'onchange' => 'get_subseccion_hijo(this)',
		    	'data-nivel' => $nivel
		    ),
		)
	);
	?>
	</div>
</div>
