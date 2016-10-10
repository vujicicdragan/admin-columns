/*
 *	Fires when the dom is ready
 *
 */

/**
 * AC variables. Defined in DOM.
 * @param {Object} cpac
 */
var cpac;

/**
 * Translations. Defined in DOM.
 * @param {Object} cpac
 */
var cpac_i18n;

/**
 * DOM ready
 */
jQuery( document ).ready( function( $ ) {

	if ( $( '#cpac' ).length === 0 ) {
		return false;
	}

	cpac_init( $ );
	cpac_submit_form( $ );
	cpac_reset_columns( $ );
	cpac_menu( $ );
	cpac_add_column( $ );
	cpac_sidebar_feedback( $ );

} );

/*
 * Submit Form
 *
 * @since 2.0.2
 */
function cpac_submit_form( $ ) {

	var $save_buttons = $( '.sidebox a.submit, .column-footer a.submit' );

	$save_buttons.click( function() {

		var $button = $( this );
		var $container = $button.closest( '.columns-container' ).addClass( 'saving' );
		var columns_data = $container.find( '.cpac-columns form' ).serialize();
		var $msg = $container.find( '.ajax-message' );

		$save_buttons.attr( 'disabled', 'disabled' );

		// reset
		$container.find( '.ajax-message' ).hide().removeClass( 'error updated' );
		$( '.cpac_message' ).remove(); // placed by restore button

		var xhr = $.post( ajaxurl, {
				plugin_id : 'cpac',
				action : 'cpac_columns_update',
				data : columns_data,
				_ajax_nonce : cpac._ajax_nonce,
				list_screen : $container.data( 'type' )
			},

			// JSON response
			function( response ) {
				if ( response ) {
					if ( response.success ) {
						$msg.addClass( 'updated' ).find( 'p' ).html( response.data );
						$msg.slideDown();//.delay( 2000 ).slideUp();

						$container.addClass( 'stored' );
					}

					// Error message
					else if ( response.data ) {
						$msg.addClass( response.data.type ).find( 'p' ).html( response.data.message );
						$msg.slideDown();
					}
				}

				// No response
				else {
				}

			}, 'json' );

		// No JSON
		xhr.fail( function( error ) {
			// We choose not to notify the user of errors, because the settings will have
			// been saved correctly despite of PHP notices/errors from plugin or themes.
		} );

		// Always
		xhr.always( function() {
			$save_buttons.removeAttr( 'disabled', 'disabled' );
			$container.removeClass( 'saving' );
		} );

		$( document ).trigger( 'cac_update', $container );
	} );
}

/*
 * Add Column
 *
 * @since 2.0
 */
function cpac_add_column( $ ) {

	$( '.add_column' ).click( function( e ) {
		e.preventDefault();

		var clone = $( '#add-new-column-template' ).find( '.cpac-column' ).clone();

		// increment clone id ( before adding to DOM, otherwise radio buttons will reset )
		clone.cpac_update_clone_id( cpac.list_screen );

		// TODO: animation should go more fluently

		// Open
		clone.addClass( 'opened' ).find( '.column-form' ).slideDown( 150, function() {
			$( 'html, body' ).animate( { scrollTop : clone.offset().top - 58 }, 300 );
		} );

		// add to DOM
		$( '.cpac-columns form' ).append( clone );

		// refresh column
		clone.cpac_column_refresh();

		// hook for addons
		$( document ).trigger( 'column_add', clone );
	} );

}

/**
 * @since 2.2.1
 */
function cpac_sidebar_feedback( $ ) {
	var sidebox = $( '.sidebox#direct-feedback' );

	sidebox.find( '#feedback-choice a.no' ).click( function( e ) {
		e.preventDefault();

		sidebox.find( '#feedback-choice' ).slideUp();
		sidebox.find( '#feedback-support' ).slideDown();
	} );

	sidebox.find( '#feedback-choice a.yes' ).click( function( e ) {
		e.preventDefault();

		sidebox.find( '#feedback-choice' ).slideUp();
		sidebox.find( '#feedback-rate' ).slideDown();
	} );
}

function cpac_init( $ ) {

	var container = $( '.columns-container' );
	var boxes = container.find( '.cpac-boxes' );

	// Written for PHP Export
	if ( boxes.hasClass( 'disabled' ) ) {
		boxes.find( '.cpac-column' ).each( function( i, col ) {
			$( col ).column_bind_toggle();
			$( col ).find( 'input, select' ).prop( 'disabled', true );
		} );
	}

	else {
		var columns = boxes.find( '.cpac-columns' );

		// we start by binding the toggle and remove events.
		columns.find( '.cpac-column' ).each( function( i, col ) {
			$( col ).column_bind_toggle();
			$( col ).column_bind_remove();
			$( col ).column_bind_clone();
			$( col ).cpac_bind_indicator_events();
		} );

		// ordering of columns
		columns.cpac_bind_ordering();
	}

	// hook for addons
	$( document ).trigger( 'cac_menu_change', columns ); // deprecated
	$( document ).trigger( 'cac_model_ready', container.data( 'type' ) );
}

/*
 * Menu
 *
 * @since 1.5
 */
function cpac_menu( $ ) {
	$( '#ac_list_screen' ).on( 'change', function() {
		$( this ).prop( 'disabled', true ).next( '.spinner' ).css( 'display', 'inline-block' );
		$( '.view-link' ).hide();
		window.location = $( this ).val();
	} );
}

/*
 * Reset columns
 *
 * @since NEWVERSION
 */
function cpac_reset_columns( $ ) {
	var $container = $( '.columns-container' );

	$( 'a[data-clear-columns]' ).on( 'click', function() {
		$container.find( '.cpac-column' ).each( function() {
			$( this ).find( '.remove-button' ).trigger( 'click' );
		} );
	} );
}

/*
 * jQuery functions
 *
 * @since 2.0
 */
(function( $ ) {

	/*
	 * Column: bind toggle events
	 *
	 * For performance we bind all other events after the click event.
	 *
	 * @since 2.0
	 */
	$.fn.column_bind_toggle = function() {

		var column = $( this );
		var is_disabled = column.closest( 'cpac-boxes' ).hasClass( 'disabled' );

		column.find( 'td.column_type a, td.column_edit, td.column_label a.toggle, td.column_label .edit-button, td.column_label .close-button, tr.column_action .close-button' ).click( function( e ) {
			e.preventDefault();

			column.toggleClass( 'opened' ).find( '.column-form' ).slideToggle( 150 );

			if ( is_disabled ) {
				return;
			}

			if ( !column.hasClass( 'events-binded' ) ) {
				column.column_bind_events();
			}

			column.addClass( 'events-binded' );

			// hook for addons
			$( document ).trigger( 'column_init', column );
		} );
	};

	/*
	 * Column: bind remove events
	 *
	 * @since 2.0
	 */
	$.fn.column_bind_remove = function() {
		$( this ).find( '.remove-button' ).click( function( e ) {
			$( this ).closest( '.cpac-column' ).column_remove();

			e.preventDefault();
		} );
	};

	/**
	 * Column: bind clone events
	 *
	 * @since 2.3.4
	 */
	$.fn.column_bind_clone = function() {
		$( this ).find( '.clone-button' ).click( function( e ) {
			e.preventDefault();

			var column = $( this ).closest( '.cpac-column' );
			var clone = column.column_clone();

			if ( typeof clone !== 'undefined' ) {
				clone.removeClass( 'loading' ).hide().slideDown();
			}
		} );
	};

	$.fn.cpac_column_refresh = function() {

		var el = $( this );
		var select = el.find( '.column-type select' );
		var $container = $( this ).closest( '.columns-container' );
		var column_name = $( this ).find( 'input.column-name' ).val();

		// Mark column as loading
		el.addClass( 'loading' );
		select.prop( 'disabled', 1 );

		// Fetch new form HTML
		var xhr = $.post( ajaxurl, {
			plugin_id : 'cpac',
			action : 'cpac_column_refresh',
			_ajax_nonce : cpac._ajax_nonce,
			column : column_name,
			formdata : $( this ).parents( 'form' ).serialize(),
			list_screen : $container.data( 'type' )
		}, function( response ) {

			if ( response ) {
				// Replace current form by new form
				var newel = $( '<div>' + response.data + '</div>' ).children();
				el.replaceWith( newel );
				el = newel;

				// Bind events
				el.column_bind_toggle();
				el.column_bind_remove();
				el.column_bind_clone();
				el.column_bind_events();

				// Open settings
				el.addClass( 'opened' ).find( '.column-form' ).show();

				// Allow plugins to hook into this event
				$( document ).trigger( 'column_change', el );
			}

			// Do nothing
			else {

			}
		}, 'json' );

		xhr.fail( function( error ) {
			var $msg = el.closest( '.columns-container' ).find( '.ajax-message' );

			$msg.addClass( 'error' ).find( 'p' ).html( cpac_i18n.error );
			$msg.slideDown();

			el.slideUp( function() { el.remove() } );

			console.log( 'responseText: ' + error.responseText );
		} );

		xhr.always( function() {
			// Remove "loading" marking from column
			el.removeClass( 'loading' );
			select.prop( 'disabled', false );
		} );
	};

	/*
	 * Form Events
	 *
	 * @since 2.0
	 */
	$.fn.column_bind_events = function() {

		var column = $( this );
		var container = column.closest( '.columns-container ' );
		var list_screen = container.attr( 'data-type' );

		// Current column type
		var default_value = column.find( '.column-type select option:selected' ).val();

		column.find( '.column-type select' ).change( function() {
			var option = $( 'optgroup', this ).children( ':selected' );
			var type = option.val();
			var msg = $( this ).next( '.msg' ).hide();
			var $select = $( this );

			var original_columns = [];
			container.find( '.cpac-column[data-original=1]' ).each( function() {
				original_columns.push( $( this ).data( 'type' ) );
			} );

			column.addClass( 'loading' );

			$.ajax( {
				url : ajaxurl,
				method : 'post',
				dataType : 'json',
				data : {
					plugin_id : 'cpac',
					action : 'cpac_column_select',
					original_columns : original_columns,
					_ajax_nonce : cpac._ajax_nonce,
					type : type,
					list_screen : list_screen
				}
			} )
				.done( function( response ) {
					if ( response ) {

						if ( response.success ) {
							var el = column.closest( '.cpac-column' );

							// Replace current form by new form
							var newel = $( '<div>' + response.data + '</div>' ).children();
							el.replaceWith( newel );
							el = newel;

							// Bind events
							el.column_bind_toggle();
							el.column_bind_remove();
							el.column_bind_clone();
							el.column_bind_events();

							// Open settings
							el.addClass( 'opened' ).find( '.column-form' ).show();

							// trigger refresh
							if ( el.find( '*[data-refresh=1]' ).length > 0 ) {
								el.cpac_column_refresh();
							}

							// Allow plugins to hook into this event
							$( document ).trigger( 'column_change', el );
						}

						// Error message
						else if ( response.data ) {
							if ( 'message' === response.data.type ) {
								msg.html( response.data.error ).show();
								// Set to default
								$select.find( 'option' ).removeAttr( 'selected' );
								$select.find( 'option[value="' + default_value + '"]' ).attr( 'selected', 'selected' );
							}
						}
					}
				} )

				.always( function() {
					column.removeClass( 'loading' );
				} );

		} );

		/** change label */
		column.find( '.column-form .column-label .input input' ).bind( 'keyup change', function() {
			var value = $( this ).val();
			$( this ).closest( '.cpac-column' ).find( 'td.column_label .inner > a.toggle' ).text( value );
		} );

		/** width */

		// slider
		column.column_width_slider();

		// indicator
		var width_indicator = column.find( '.column-meta span.width' );
		width_indicator.on( 'update', function() {
			var _width = column.find( 'input.width' ).val();
			var _unit = column.find( 'input.unit' ).filter( ':checked' ).val();
			if ( _width > 0 ) {
				$( this ).text( _width + _unit );
			} else {
				$( this ).text( '' );
			}
		} );

		// unit selector
		var width_unit_select = column.find( '.column-width .unit-select label' );
		width_unit_select.on( 'click', function() {

			column.find( 'span.unit' ).text( $( this ).find( 'input' ).val() );
			column.column_width_slider(); // re-init slider
			width_indicator.trigger( 'update' ); // update indicator
		} );

		// width_input
		var width_input = column.find( 'input.width' )
			.on( 'keyup', function() {
				column.column_width_slider(); // re-init slider
				$( this ).trigger( 'validate' ); // validate input
				width_indicator.trigger( 'update' ); // update indicator
			} )

			// width_input:validate
			.on( 'validate', function() {
				var _width = width_input.val();
				var _new_width = $.trim( _width );

				if ( !$.isNumeric( _new_width ) ) {
					_new_width = _new_width.replace( /\D/g, '' );
				}
				if ( _new_width.length > 3 ) {
					_new_width = _new_width.substring( 0, 3 );
				}
				if ( _new_width <= 0 ) {
					_new_width = '';
				}
				if ( _new_width !== _width ) {
					width_input.val( _new_width );
				}
			} );

		/** display custom image size */
		column.find( '.column-image_size select' ).change( function() {
			var custom_image_size = $( this ).closest( '.cpac-column' ).find( '.column-image_size_w, .column-image_size_h' ).addClass( 'hide' );
			if ( 'cpac-custom' === this.value ) {
				custom_image_size.removeClass( 'hide' );
			}
		} );

		/**    tooltip */
		column.find( '.column-form .label label .label, .column-form .label .info' ).hover( function() {
			$( this ).parents( '.label' ).find( 'p.description' ).show();
		}, function() {
			$( this ).parents( '.label' ).find( 'p.description' ).hide();
		} );

		// refresh column and re-bind all events
		column.find( '[data-refresh="1"] select' ).change( function() {
			column.cpac_column_refresh();
		} );
	};

	/*
	 * Column: remove from DOM
	 *
	 * @since 2.0
	 */
	$.fn.column_remove = function() {
		$( this ).addClass( 'deleting' ).animate( { opacity : 0, height : 0 }, 350, function() {
			$( this ).remove();
		} );
	};

	/*
	 * Column: remove from DOM
	 *
	 * @since 2.0
	 */
	$.fn.column_width_slider = function() {

		var column_width = $( this ).find( '.column-width' );

		var input_width = column_width.find( 'input.width' ),
			input_unit = column_width.find( 'input.unit' ),
			unit = input_unit.filter( ':checked' ).val(),
			width = input_width.val(),
			slider = column_width.find( '.width-slider' ),
			indicator = $( this ).find( '.column-meta span.width' );

		// width
		if ( '%' == unit && width > 100 ) {
			width = 100;
		}

		input_width.val( width );

		slider.slider( {
			range : 'min',
			min : 0,
			max : '%' == unit ? 100 : 500,
			value : width,
			slide : function( event, ui ) {

				input_width.val( ui.value );
				indicator.trigger( 'update' );
				input_width.trigger( 'validate' );
			}
		} );
	};

	/*
	 * Column: clone
	 *
	 * @since 2.3.4
	 */
	$.fn.column_clone = function() {

		var container = $( this ).closest( '.columns-container' );
		var column = $( this );
		var columns = $( this ).closest( 'cpac-columns' );

		if ( typeof column.attr( 'data-clone' ) === 'undefined' ) {
			var message = cpac_i18n.clone.replace( '%s', '<strong>' + column.find( '.column_label .toggle' ).text() + '</strong>' );

			column.addClass( 'opened' ).find( '.column-form' ).slideDown( 150 );
			column.find( '.msg' ).html( message ).show();

			return;
		}

		var clone = $( this ).clone();

		clone.cpac_update_clone_id( container.attr( 'data-type' ) );
		$( this ).after( clone );

		// rebind events
		clone.column_bind_toggle();
		clone.column_bind_remove();
		clone.column_bind_clone();

		// rebind all other events
		clone.column_bind_events();

		// reinitialize sortability
		columns.cpac_bind_ordering();

		// hook for addons
		$( document ).trigger( 'column_add', clone );

		return clone;
	};

	/*
	 * Update clone ID
	 *
	 * @since 2.0
	 */
	$.fn.cpac_update_clone_id = function( list_screen ) {

		var el = $( this );

		var type = el.attr( 'data-type' );
		var all_columns = $( '.columns-container[data-type="' + list_screen + '"]' ).find( '.cpac-columns' );
		var columns = $( all_columns ).find( '*[data-type="' + type + '"]' ).not( el );

		// get clone ID
		var ids = $.map( columns, function( e ) {
			if ( $( e ).attr( 'data-clone' ) ) {
				return parseInt( $( e ).attr( 'data-clone' ), 10 );
			}
			return 0;
		} );

		ids.sort();
		var max_id = Math.max.apply( null, ids ) + 1;
		for ( var id = 0; id <= max_id; id++ ) {
			if ( -1 === $.inArray( id, ids ) ) {
				break;
			}
		}

		// only increment when needed
		//if ( 0 === id )
		//	return;

		// get original clone ID
		var clone_id = el.attr( 'data-clone' );
		var clone_suffix = '';

		if ( clone_id ) {
			clone_suffix = '-' + clone_id;
		}

		// set clone ID
		el.attr( 'data-clone', id );
		el.find( 'input.clone' ).val( id );
		el.find( 'input.column-name' ).val( type + '-' + id );

		// update input names with clone ID
		var inputs = el.find( 'input, select, label' );
		$( inputs ).each( function( i, v ) {

			var new_name = type + '-' + id;

			// name
			if ( $( v ).attr( 'name' ) ) {
				// brackets prevent the replacement of storage model key when column name is similar to storage name, e.g. column comment and model wp-comments
				$( v ).attr( 'name', $( v ).attr( 'name' ).replace( '[' + type + clone_suffix + ']', '[' + new_name + ']' ) );
			}

			// for
			if ( $( v ).attr( 'for' ) ) {
				$( v ).attr( 'for', $( v ).attr( 'for' ).replace( type + clone_suffix + '-', new_name + '-' ) );
			}

			// id
			if ( $( v ).attr( 'id' ) ) {
				$( v ).attr( 'id', $( v ).attr( 'id' ).replace( type + clone_suffix + '-', new_name + '-' ) );
			}
		} );
	};

	/*
	 * Bind events: triggered after column is init, changed or added
	 *
	 */
	$( document ).bind( 'column_init column_change column_add', function( e, column ) {

		var is_disabled = $( column ).closest( '.cpac-boxes' ).hasClass( 'disabled' );

		if ( is_disabled ) {
			return;
		}

		$( column ).cpac_bind_column_addon_events();
		$( column ).cpac_bind_indicator_events();
	} );

	/*
	 * Optional Radio Click events
	 *
	 */
	$.fn.cpac_bind_column_addon_events = function() {

		var column = $( this );
		var inputs = column.find( '[data-trigger] label' );

		inputs.on( 'click', function() {

			var id = $( this ).closest( 'td.input' ).data( 'trigger' );
			var state = $( 'input', this ).val();

			// Toggle indicator icon
			var label = column.find( '[data-indicator-id="' + id + '"]' ).removeClass( 'on' );
			if ( 'on' == state ) {
				label.addClass( 'on' );
			}

			// Toggle additional options
			var additional = column.find( '[data-handle="' + id + '"]' ).addClass( 'hide' );
			if ( 'on' == state ) {
				additional.removeClass( 'hide' );
			}
		} );

		// Toggle additional column settings
		column.find( '[data-trigger]' ).each( function() {
			var additional = column.find( '[data-handle="' + $( this ).data( 'trigger' ) + '"]' ).addClass( 'hide' );
			if ( 'on' == $( 'input:checked', this ).val() ) {
				additional.removeClass( 'hide' );
			}
		} );
	};

	/*
	 * Indicator Click Events
	 *
	 */
	$.fn.cpac_bind_indicator_events = function() {

		var column = $( this );
		var indicator = column.find( '[data-indicator-id]' );

		indicator.unbind( 'click' ).click( function() {

			var id = $( this ).data( 'indicator-id' );
			var radio = column.find( '[data-trigger="' + id + '"] input' );

			if ( $( this ).hasClass( 'on' ) ) {
				$( this ).removeClass( 'on' ).addClass( 'off' );
				radio.filter( '[value=off]' ).prop( 'checked', true ).trigger( 'click' );
			}
			else {
				$( this ).removeClass( 'off' ).addClass( 'on' );
				radio.filter( '[value=on]' ).prop( 'checked', true ).trigger( 'click' );
			}
		} );
	};

	/*
	 * Sortable
	 *
	 * @since 1.5
	 */
	$.fn.cpac_bind_ordering = function() {
		$( this ).each( function() {
			if ( $( this ).hasClass( 'ui-sortable' ) ) {
				$( this ).sortable( 'refresh' );
			}
			else {
				$( this ).sortable( {
					items : '.cpac-column'
				} );
			}
		} );
	};

}( jQuery ));