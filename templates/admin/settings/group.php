<tr class="<?php echo $this->id; ?>">
	<th scope="row">
		<h2><?php echo esc_html( $this->title ); ?></h2>

		<?php if ( $this->description ) : ?>
			<p><?php echo $this->description; ?></p>
		<?php endif; ?>
	</th>
	<td class="padding-22">
		<?php echo $this->settings; ?>
	</td>
</tr>