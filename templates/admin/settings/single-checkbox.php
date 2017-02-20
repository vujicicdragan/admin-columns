$defaults = array(
'name'          => '',
'label'         => '',
'instructions'  => '',
'default_value' => false,
);

$args = (object) wp_parse_args( $args, $defaults );

$current_value = $this->is_empty_options() ? $args->default_value : $this->get_option( $args->name );
?>
<p>
	<label for="<?php echo $args->name; ?>">
		<input name="<?php $this->attr_name( $args->name ); ?>" id="<?php echo $args->name; ?>" type="checkbox" value="1" <?php checked( $current_value, '1' ); ?>>
		<?php echo $args->label; ?>
	</label>
	<?php if ( $args->instructions ) : ?>
		<a class="cpac-pointer" rel="pointer-<?php echo $args->name; ?>" data-pos="right">
			<?php _e( 'Instructions', 'codepress-admin-columns' ); ?>
		</a>
	<?php endif; ?>
</p>
<?php if ( $args->instructions ) : ?>
	<div id="pointer-<?php echo $args->name; ?>" style="display:none;">
		<h3><?php _e( 'Notice', 'codepress-admin-columns' ); ?></h3>
		<?php echo $args->instructions; ?>
	</div>
	<?php
endif;