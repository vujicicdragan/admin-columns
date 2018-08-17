/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js/admin-page-columns.js":
/*!**********************************!*\
  !*** ./js/admin-page-columns.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


__webpack_require__(/*! core-js/modules/es6.regexp.replace */ "./node_modules/core-js/modules/es6.regexp.replace.js");

__webpack_require__(/*! core-js/modules/es6.array.find */ "./node_modules/core-js/modules/es6.array.find.js");

/**
 * AC variables. Defined in DOM.
 * @param AC {Object}
 * @param AC.list_screen {String}
 * @param AC.layout {String}
 * @param AC.i81n {String}
 */
var AC;
/**
 * Temporary column name used for form elements.
 *
 * @type {number}
 */

var incremental_column_name = 0;
/**
 * DOM ready
 */

jQuery(document).ready(function ($) {
  if ($('#cpac').length === 0) {
    return false;
  }

  cpac_init($);
  cpac_submit_form($);
  cpac_reset_columns($);
  cpac_menu($);
  cpac_add_column($);
  cpac_sidebar_feedback($);
});

function ac_show_ajax_message(message, attr_class) {
  var msg = jQuery('<div class="ac-message hidden ' + attr_class + '"><p>' + message + '</p></div>');
  jQuery('.ac-boxes').before(msg);
  msg.slideDown();
}
/*
 * Submit Form
 *
 * @since 2.0.2
 */


function cpac_submit_form($) {
  var $save_buttons = $('.sidebox a.submit, .column-footer a.submit');
  $save_buttons.click(function () {
    var $button = $(this);
    var $container = $button.closest('.ac-admin').addClass('saving');
    var columns_data = $container.find('.ac-columns form').serialize();
    $save_buttons.attr('disabled', 'disabled'); // reset

    $container.find('.ac-message').remove(); // placed by restore button

    var xhr = $.post(ajaxurl, {
      action: 'ac_columns_save',
      data: columns_data,
      _ajax_nonce: AC._ajax_nonce,
      list_screen: AC.list_screen,
      layout: AC.layout,
      original_columns: AC.original_columns
    }, // JSON response
    function (response) {
      if (response) {
        if (response.success) {
          ac_show_ajax_message(response.data, 'updated');
          $container.addClass('stored');
        } // Error message
        else if (response.data) {
            ac_show_ajax_message(response.data.message, 'notice notice-warning');
          }
      } // No response
      else {}
    }, 'json'); // No JSON

    xhr.fail(function (error) {// We choose not to notify the user of errors, because the settings will have
      // been saved correctly despite of PHP notices/errors from plugin or themes.
    }); // Always

    xhr.always(function () {
      $save_buttons.removeAttr('disabled', 'disabled');
      $container.removeClass('saving');
    });
    $(document).trigger('cac_update', $container);
  });
}
/*
 * Add Column
 *
 * @since 2.0
 */


function cpac_add_column($) {
  $('.add_column').click(function (e) {
    e.preventDefault();
    var clone = $('#add-new-column-template').find('.ac-column').clone(); // increment clone id ( before adding to DOM, otherwise radio buttons will reset )

    clone.cpac_update_clone_id(); // Open

    clone.addClass('opened').find('.ac-column-body').slideDown(150, function () {
      $('html, body').animate({
        scrollTop: clone.offset().top - 58
      }, 300);
    }); // add to DOM

    $('.ac-columns form').append(clone); // TODO: better?

    clone.column_bind_toggle();
    clone.column_bind_remove();
    clone.column_bind_clone();
    clone.column_bind_events(); // hook for addons

    $(document).trigger('column_add', clone);
  });
}
/**
 * @since 2.2.1
 */


function cpac_sidebar_feedback($) {
  var sidebox = $('.sidebox#direct-feedback');
  sidebox.find('#feedback-choice a.no').click(function (e) {
    e.preventDefault();
    sidebox.find('#feedback-choice').slideUp();
    sidebox.find('#feedback-support').slideDown();
  });
  sidebox.find('#feedback-choice a.yes').click(function (e) {
    e.preventDefault();
    sidebox.find('#feedback-choice').slideUp();
    sidebox.find('#feedback-rate').slideDown();
  });
}

function cpac_init($) {
  var container = $('.ac-admin');
  var boxes = container.find('.ac-boxes'); // Written for PHP Export

  if (boxes.hasClass('disabled')) {
    boxes.find('.ac-column').each(function (i, col) {
      $(col).column_bind_toggle();
      $(col).find('input, select').prop('disabled', true);
    });
  } else {
    var _columns = boxes.find('.ac-columns'); // we start by binding the toggle and remove events.


    _columns.find('.ac-column').each(function (i, col) {
      $(col).column_bind_toggle();
      $(col).column_bind_remove();
      $(col).column_bind_clone();
      $(col).cpac_bind_indicator_events();
      $(col).column_onload();
    }); // ordering of columns


    _columns.cpac_bind_ordering();
  } // hook for addons


  $(document).trigger('cac_menu_change', columns); // deprecated

  $(document).trigger('cac_model_ready', container.data('type'));
}
/*
 * Menu
 *
 * @since 1.5
 */


function cpac_menu($) {
  $('#ac_list_screen').on('change', function () {
    $('.view-link').hide();
    $(this).parents('form').submit();
    $(this).prop('disabled', true).next('.spinner').css('display', 'inline-block');
  });
}
/*
 * Reset columns
 *
 * @since 3.0.3
 */


function cpac_reset_columns($) {
  var $container = $('.ac-admin');
  $('a[data-clear-columns]').on('click', function () {
    $container.find('.ac-column').each(function () {
      $(this).find('.remove-button').trigger('click');
    });
  });
}
/*
 * jQuery functions
 *
 * @since 2.0
 */


(function ($) {
  /*
   * Column: bind toggle events
   *
   * For performance we bind all other events after the click event.
   *
   * @since 2.0
   */
  $.fn.column_bind_toggle = function () {
    var $column = $(this);
    var is_disabled = $column.closest('.ac-boxes').hasClass('disabled');
    $column.find('[data-toggle="column"]').click(function (e) {
      e.preventDefault();
      $column.toggleClass('opened').find('.ac-column-body').slideToggle(150);

      if (is_disabled) {
        return;
      }

      if (!$column.hasClass('events-binded')) {
        $column.column_bind_events();
      }

      $column.addClass('events-binded'); // hook for addons

      $(document).trigger('column_init', $column);
    }).css('cursor', 'pointer');
  };
  /*
   * Column: bind remove events
   *
   * @since 2.0
   */


  $.fn.column_bind_remove = function () {
    $(this).find('.remove-button').click(function (e) {
      $(this).closest('.ac-column').column_remove();
      e.preventDefault();
    });
  };
  /**
   * Column: bind clone events
   *
   * @since 2.3.4
   */


  $.fn.column_bind_clone = function () {
    $(this).find('.clone-button').click(function (e) {
      e.preventDefault();
      var $clone = $(this).closest('.ac-column').column_clone();

      if (typeof $clone !== 'undefined') {
        $clone.removeClass('loading').hide().slideDown();
      }
    });
  };

  $.fn.cpac_column_refresh = function () {
    var el = $(this);
    var select = el.find('[data-refresh="column"]');
    var column_name = $(this).attr('data-column-name');
    var opened = el.hasClass('opened'); // Allow plugins to hook into this event

    $(document).trigger('pre_column_refresh', el);
    var data = $(this).find(':input').serializeArray();
    var request_data = {
      action: 'ac_column_refresh',
      _ajax_nonce: AC._ajax_nonce,
      list_screen: AC.list_screen,
      layout: AC.layout,
      column_name: column_name,
      original_columns: AC.original_columns
    };
    $.each(request_data, function (name, value) {
      data.push({
        name: name,
        value: value
      });
    }); // Mark column as loading

    el.addClass('loading');
    select.prop('disabled', 1); // Fetch new form HTML

    var xhr = $.post(ajaxurl, data, function (response) {
      if (response) {
        // Replace current form by new form
        var newel = $('<div>' + response.data + '</div>').children();
        el.replaceWith(newel);
        el = newel; // Bind events

        el.column_bind_toggle();
        el.column_bind_remove();
        el.column_bind_clone();
        el.column_bind_events(); // Open settings

        if (opened) {
          el.addClass('opened').find('.ac-column-body').show();
        } // Allow plugins to hook into this event
        // TODO: change to column_refresh?


        $(document).trigger('column_change', el);
      } // Do nothing
      else {}
    }, 'json');
    xhr.fail(function (error) {
      var $msg = el.closest('.ac-admin').find('.ajax-message');
      $msg.addClass('error').find('p').html(AC.i18n.error);
      $msg.slideDown();
      el.slideUp(function () {
        el.remove();
      });
      console.log('responseText: ' + error.responseText);
    });
    xhr.always(function () {
      // Remove "loading" marking from column
      el.removeClass('loading');
      select.prop('disabled', false);
    });
  };

  $.fn.column_onload = function () {
    var column = $(this);
    /** When an label contains an icon or span, the displayed label can appear empty. In this case we show the "type" label. */

    var column_label = column.find('.column_label .toggle');

    if ($.trim(column_label.html()) && column_label.width() < 1) {
      column_label.html(column.find('.column_type .inner').html());
    }
  };
  /*
   * Form Events
   *
   * @since 2.0
   */


  $.fn.column_bind_events = function () {
    var column = $(this);
    var container = column.closest('.ac-admin ');
    column.column_onload(); // Current column type

    var default_value = column.find('select.ac-setting-input_type option:selected').val(); // Type selector

    column.find('select.ac-setting-input_type').change(function () {
      var option = $('optgroup', this).children(':selected');
      var type = option.val();
      var msg = column.find('.msg').hide();
      var $select = $(this);
      var current_original_columns = [];
      container.find('.ac-column[data-original=1]').each(function () {
        current_original_columns.push($(this).data('type'));
      });
      column.addClass('loading');
      $.ajax({
        url: ajaxurl,
        method: 'post',
        dataType: 'json',
        data: {
          action: 'ac_column_select',
          type: type,
          current_original_columns: current_original_columns,
          original_columns: AC.original_columns,
          list_screen: AC.list_screen,
          layout: AC.layout,
          _ajax_nonce: AC._ajax_nonce
        }
      }).done(function (response) {
        if (response) {
          if (response.success) {
            var el = column.closest('.ac-column'); // Replace current form by new form

            var newel = $('<div>' + response.data + '</div>').children();
            el.replaceWith(newel);
            el = newel; // Bind events

            el.column_bind_toggle();
            el.column_bind_remove();
            el.column_bind_clone();
            el.column_bind_events(); // Open settings

            el.addClass('opened').find('.ac-column-body').show();
            el.cpac_update_clone_id(); // Allow plugins to hook into this event

            $(document).trigger('column_change', el);
          } // Error message
          else if (response.data) {
              if ('message' === response.data.type) {
                msg.html(response.data.error).show(); // Set to default

                $select.find('option').removeAttr('selected');
                $select.find('option[value="' + default_value + '"]').attr('selected', 'selected');
              }
            }
        }
      }).always(function () {
        column.removeClass('loading');
      });
    });
    /** change label */

    column.find('.ac-column-setting--label input').bind('keyup change', function () {
      var value = $(this).val();
      $(this).closest('.ac-column').find('td.column_label .inner > a.toggle').html(value);
    });
    /** tooltip */

    column.find('.ac-column-body .col-label .label').hover(function () {
      $(this).parents('.col-label').find('div.tooltip').show();
    }, function () {
      $(this).parents('.col-label').find('div.tooltip').hide();
    });
    /**
     * Populates the main Label with the selected label from the dropdown,
     */

    column.find('select[data-label="update"]').change(function () {
      var $label = column.find('input.ac-setting-input_label');
      var field_label = $(this).find('option:selected').text(); // Set new label

      $label.val(field_label);
      $label.trigger('change');
    }); // refresh column and re-bind all events

    column.find('[data-refresh="column"]').change(function () {
      column.cpac_column_refresh();
    });
    $(document).trigger('init_settings', column);
  };

  $.fn.column_bind_settings = function () {
    var $column = $(this);
    $column.find('.ac-column-setting--image_size').cpac_column_setting_image_size();
    $column.find('.ac-column-setting--width').cpac_column_setting_width();
  };
  /*
   * Column: remove from DOM
   *
   * @since 2.0
   */


  $.fn.column_remove = function () {
    $(this).addClass('deleting').animate({
      opacity: 0,
      height: 0
    }, 350, function () {
      $(this).remove();
    });
  };
  /*
   * Column: clone
   *
   * @since 2.3.4
   */


  $.fn.column_clone = function () {
    var column = $(this);
    var columns = $(this).closest('ac-columns');

    if ('1' === column.attr('data-original')) {
      var message = AC.i18n.clone.replace('%s', '<strong>' + column.find('.column_label .toggle').text() + '</strong>');
      column.addClass('opened').find('.ac-column-body').slideDown(150);
      column.find('.ac-setting-input_type').next('.msg').html(message).show();
      return;
    }

    var clone = $(this).clone();
    clone.cpac_update_clone_id();
    $(this).after(clone); // rebind events

    clone.column_bind_toggle();
    clone.column_bind_remove();
    clone.column_bind_clone(); // rebind all other events

    clone.column_bind_events(); // reinitialize sortability

    columns.cpac_bind_ordering(); // hook for addons

    $(document).trigger('column_add', clone);
    return clone;
  };
  /*
   * Update clone ID
   *
   * @since 2.0
   */


  $.fn.cpac_update_clone_id = function () {
    var $el = $(this);
    var original_column_name = $el.attr('data-column-name');
    var temp_column_name = '_new_column_' + incremental_column_name; // update input names with clone ID

    var inputs = $el.find('input, select, label');
    $(inputs).each(function (i, v) {
      // name
      if ($(v).attr('name')) {
        $(v).attr('name', $(v).attr('name').replace('columns[' + original_column_name + ']', 'columns[' + temp_column_name + ']'));
      } // id


      if ($(v).attr('id')) {
        $(v).attr('id', $(v).attr('id').replace('-' + original_column_name + '-', '-' + temp_column_name + '-'));
      } // TODO for

    });
    $el.attr('data-column-name', temp_column_name); // increment

    incremental_column_name++;
  };
  /*
   * Bind events: triggered after column is init, changed or added
   *
   */


  $(document).bind('column_init column_change column_add', function (e, column) {
    var is_disabled = $(column).closest('.ac-boxes').hasClass('disabled');

    if (is_disabled) {
      return;
    }

    $(column).cpac_bind_column_addon_events();
    $(column).cpac_bind_indicator_events();
  });
  /*
   * Optional Radio Click events
   *
   */

  $.fn.cpac_bind_column_addon_events = function () {
    var column = $(this);
    var inputs = column.find('[data-trigger] label');
    inputs.on('click', function () {
      var id = $(this).closest('td.input').data('trigger');
      var state = $('input', this).val(); // Toggle indicator icon

      var label = column.find('[data-indicator-id="' + id + '"]').removeClass('on');

      if ('on' === state) {
        label.addClass('on');
      } // Toggle additional options


      var additional = column.find('[data-handle="' + id + '"]').addClass('hide');

      if ('on' === state) {
        additional.removeClass('hide');
      }
    }); // On load

    column.find('[data-trigger]').each(function () {
      var trigger = $(this).data('trigger'); // Hide additional column settings

      var additional = column.find('[data-handle="' + trigger + '"]').addClass('hide');

      if ('on' === $('input:checked', this).val()) {
        additional.removeClass('hide');
      }
    });
  };
  /*
   * Indicator Click Events
   *
   */


  $.fn.cpac_bind_indicator_events = function () {
    var $column = $(this);
    var $indicators = $column.find('.ac-column-header [data-indicator-toggle]');
    $indicators.each(function () {
      var $indicator = $(this);
      var setting = $(this).data('setting');
      var $setting = $column.find('.ac-column-setting[data-setting=' + setting + ']');
      var $input = $setting.find('.col-input:first .ac-setting-input:first input[type=radio]');
      $indicator.unbind('click').on('click', function (e) {
        e.preventDefault();
        $indicator.toggleClass('on');

        if ($(this).hasClass('on')) {
          $input.filter('[value=on]').prop('checked', true).trigger('click').trigger('change');
        } else {
          $input.filter('[value=off]').prop('checked', true).trigger('click').trigger('change');
        }
      });
      $input.on('change', function () {
        var value = $input.filter(':checked').val();

        if ('on' === value) {
          $indicator.addClass('on');
        } else {
          $indicator.removeClass('on');
        }
      });
    });
  };
  /*
   * Sortable
   *
   * @since 1.5
   */


  $.fn.cpac_bind_ordering = function () {
    $(this).each(function () {
      if ($(this).hasClass('ui-sortable')) {
        $(this).sortable('refresh');
      } else {
        $(this).sortable({
          items: '.ac-column',
          handle: '.column_sort'
        });
      }
    });
  }; // Settings fields: Image _size


  $.fn.cpac_column_setting_image_size = function () {
    function initState($setting, $select) {
      if ('cpac-custom' === $select.val()) {
        $setting.find('.ac-column-setting').show();
      } else {
        $setting.find('.ac-column-setting').hide();
      }
    }

    $(this).each(function () {
      var $setting = $(this);
      var $select = $(this).find('.ac-setting-input select');
      initState($setting, $select);
      $select.on('change', function () {
        initState($setting, $(this));
      });
    });
  };

  $(document).on('init_settings', function (e, column) {
    $(column).find('.ac-column-setting--image').cpac_column_setting_image_size();
    $(column).find('.ac-column-setting--images').cpac_column_setting_image_size();
  }); // Settings fields: Width

  $.fn.column_width_slider = function () {
    var column_width = $(this).find('.ac-setting-input-width');
    var input_width = column_width.find('.description input'),
        input_unit = column_width.find('.unit-select input'),
        unit = input_unit.filter(':checked').val(),
        width = input_width.val(),
        slider = column_width.find('.width-slider'),
        indicator = $(this).find('.ac-column-header .ac-column-heading-setting--width'); // width

    if ('%' === unit && width > 100) {
      width = 100;
    }

    input_width.val(width);
    slider.slider({
      range: 'min',
      min: 0,
      max: '%' === unit ? 100 : 500,
      value: width,
      slide: function slide(event, ui) {
        input_width.val(ui.value);
        indicator.trigger('update');
        input_width.trigger('validate');
      }
    });
  };

  $.fn.cpac_column_setting_width = function () {
    $(this).each(function () {
      var $column = $(this).parents('.ac-column');
      $column.column_width_slider(); // indicator

      var $width_indicator = $column.find('.ac-column-header .ac-column-heading-setting--width');
      $width_indicator.on('update', function () {
        var _width = $column.find('.ac-setting-input-width .description input').val();

        var _unit = $column.find('.ac-setting-input-width .description .unit').text();

        if (_width > 0) {
          $(this).text(_width + _unit);
        } else {
          $(this).text('');
        }
      }); // unit selector

      var width_unit_select = $column.find('.ac-setting-input-width .unit-select label');
      width_unit_select.on('click', function () {
        $column.find('span.unit').text($(this).find('input').val());
        $column.column_width_slider(); // re-init slider

        $width_indicator.trigger('update'); // update indicator
      }); // width_input

      var width_input = $column.find('.ac-setting-input-width .description input').on('keyup', function () {
        $column.column_width_slider(); // re-init slider

        $(this).trigger('validate'); // validate input

        $width_indicator.trigger('update'); // update indicator
      }) // width_input:validate
      .on('validate', function () {
        var _width = width_input.val();

        var _new_width = $.trim(_width);

        if (!$.isNumeric(_new_width)) {
          _new_width = _new_width.replace(/\D/g, '');
        }

        if (_new_width.length > 3) {
          _new_width = _new_width.substring(0, 3);
        }

        if (_new_width <= 0) {
          _new_width = '';
        }

        if (_new_width !== _width) {
          width_input.val(_new_width);
        }
      });
    });
  };

  $.fn.cpac_column_sub_setting_toggle = function (options) {
    var settings = $.extend({
      value_show: "on",
      subfield: '.ac-column-setting'
    }, options);

    function initState($setting, $input) {
      var value = $input.filter(':checked').val();
      var $subfields = $setting.find(settings.subfield);

      if (settings.value_show === value) {
        $subfields.show();
      } else {
        $subfields.hide();
      }
    }

    $(this).each(function () {
      var $setting = $(this);
      var $input = $(this).find('.ac-setting-input input[type="radio"]');
      initState($setting, $input);
      $input.on('change', function () {
        initState($setting, $input);
      });
    });
  };

  $.fn.cpac_column_setting_date = function () {
    $(this).each(function () {
      var $container = $(this); // Custom input

      var $radio_custom = $container.find('input.custom');
      var $input_custom = $container.find('.ac-setting-input-date__custom');
      var $input_value = $container.find('.ac-setting-input-date__value');
      var $example_custom = $container.find('.ac-setting-input-date__example');
      var $selected = $container.find('input[type=radio]:checked');
      var $help_msg = $container.find('.help-msg'); // Click Event

      $container.find('input[type=radio]').on('change', function () {
        var $input = $(this);
        var $input_container = $input.closest('label');
        var date_format = $input_container.find('code').text();
        var description = $input_container.find('.ac-setting-input-date__more').html();

        if (date_format) {
          $input_custom.val(date_format).trigger('change');
        }

        if ($input.hasClass('diff')) {
          $input_custom.val('');
          $example_custom.text('');
        }

        $input_custom.prop('disabled', true); // Custom input selected

        if ($input.hasClass('custom')) {
          $input.val($input_custom.val());
          $input_custom.prop('disabled', false);
          $help_msg.show();
        } // Show more description


        $help_msg.hide();

        if (description) {
          $help_msg.html(description).show();
        }

        $input_value.val($input.val());
      }); // Custom input

      $input_custom.on('change', function () {
        $example_custom.html('<span class="spinner is-active"></span>');
        $radio_custom.val($input_custom.val());
        var $custom_value = $(this).val();

        if (!$custom_value) {
          $example_custom.text('');
          return;
        }

        $.ajax({
          url: ajaxurl,
          method: 'post',
          data: {
            action: 'date_format',
            date: $custom_value
          }
        }).done(function (date) {
          $example_custom.text(date);
        });
        $input_value.val($custom_value);
      }); // Update date example box

      $selected.trigger('change'); // Select custom input as a default

      if (0 === $selected.length) {
        $radio_custom.trigger('click');
      }
    });
  }; // Settings fields: Pro


  $.fn.cpac_column_setting_pro = function () {
    $(this).each(function () {
      var $container = $(this);
      $container.find('input').on('click', function (e) {
        e.preventDefault();
        $container.find('[data-ac-open-modal]').trigger('click');
      });
    });
  };

  $(document).on('init_settings', function (e, column) {
    $(column).find('.ac-column-setting--width').cpac_column_setting_width();
    $(column).find('.ac-column-setting--date').cpac_column_setting_date();
    $(column).find('.ac-column-setting--pro').cpac_column_setting_pro(); // TODO: pro?

    $(column).find('.ac-column-setting--filter').cpac_column_sub_setting_toggle();
    $(column).find('.ac-column-setting--sort').cpac_column_sub_setting_toggle();
    $(column).find('.ac-column-setting--edit').cpac_column_sub_setting_toggle();
    $(".ac-setting-input_type").select2({
      width: '100%',
      templateResult: function templateResult(state) {
        if (!state.id) {
          return state.text;
        }

        state.text = state.text.replace('(PRO)', "<span  class=\"ac-type-pro-only\">pro</span>");
        return $("<span>".concat(state.text, " </span>"));
      },
      templateSelection: function templateSelection(data) {
        var $value = $("<div>".concat(data.text, "</div>"));
        $value.find('.ac-type-pro-only').remove();
        return $value.text();
      }
    });
  }); // AC Modal Events (todo move to separate logic)

  $().ready(function () {
    $(document).on('click', '[data-ac-open-modal]', function (e) {
      e.preventDefault();
      $($(this).data('ac-open-modal')).addClass('-active');
    });
    $('.ac-modal__dialog__close').on('click', function (e) {
      e.preventDefault();
      $(this).closest('.ac-modal').removeClass('-active');
    });
    $('.ac-modal').on('click', function () {
      $(this).removeClass('-active');
    }); // Prevent bubbling

    $('.ac-modal__dialog').on('click', function (e) {
      e.stopPropagation();
    });
    $(document).keyup(function (e) {
      if (e.keyCode === 27) {
        $('.ac-modal').removeClass('-active');
      }
    });
  });
})(jQuery);

/***/ }),

/***/ "./node_modules/core-js/modules/_a-function.js":
/*!*****************************************************!*\
  !*** ./node_modules/core-js/modules/_a-function.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function (it) {
  if (typeof it != 'function') throw TypeError(it + ' is not a function!');
  return it;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_add-to-unscopables.js":
/*!*************************************************************!*\
  !*** ./node_modules/core-js/modules/_add-to-unscopables.js ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 22.1.3.31 Array.prototype[@@unscopables]
var UNSCOPABLES = __webpack_require__(/*! ./_wks */ "./node_modules/core-js/modules/_wks.js")('unscopables');
var ArrayProto = Array.prototype;
if (ArrayProto[UNSCOPABLES] == undefined) __webpack_require__(/*! ./_hide */ "./node_modules/core-js/modules/_hide.js")(ArrayProto, UNSCOPABLES, {});
module.exports = function (key) {
  ArrayProto[UNSCOPABLES][key] = true;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_an-object.js":
/*!****************************************************!*\
  !*** ./node_modules/core-js/modules/_an-object.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(/*! ./_is-object */ "./node_modules/core-js/modules/_is-object.js");
module.exports = function (it) {
  if (!isObject(it)) throw TypeError(it + ' is not an object!');
  return it;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_array-methods.js":
/*!********************************************************!*\
  !*** ./node_modules/core-js/modules/_array-methods.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 0 -> Array#forEach
// 1 -> Array#map
// 2 -> Array#filter
// 3 -> Array#some
// 4 -> Array#every
// 5 -> Array#find
// 6 -> Array#findIndex
var ctx = __webpack_require__(/*! ./_ctx */ "./node_modules/core-js/modules/_ctx.js");
var IObject = __webpack_require__(/*! ./_iobject */ "./node_modules/core-js/modules/_iobject.js");
var toObject = __webpack_require__(/*! ./_to-object */ "./node_modules/core-js/modules/_to-object.js");
var toLength = __webpack_require__(/*! ./_to-length */ "./node_modules/core-js/modules/_to-length.js");
var asc = __webpack_require__(/*! ./_array-species-create */ "./node_modules/core-js/modules/_array-species-create.js");
module.exports = function (TYPE, $create) {
  var IS_MAP = TYPE == 1;
  var IS_FILTER = TYPE == 2;
  var IS_SOME = TYPE == 3;
  var IS_EVERY = TYPE == 4;
  var IS_FIND_INDEX = TYPE == 6;
  var NO_HOLES = TYPE == 5 || IS_FIND_INDEX;
  var create = $create || asc;
  return function ($this, callbackfn, that) {
    var O = toObject($this);
    var self = IObject(O);
    var f = ctx(callbackfn, that, 3);
    var length = toLength(self.length);
    var index = 0;
    var result = IS_MAP ? create($this, length) : IS_FILTER ? create($this, 0) : undefined;
    var val, res;
    for (;length > index; index++) if (NO_HOLES || index in self) {
      val = self[index];
      res = f(val, index, O);
      if (TYPE) {
        if (IS_MAP) result[index] = res;   // map
        else if (res) switch (TYPE) {
          case 3: return true;             // some
          case 5: return val;              // find
          case 6: return index;            // findIndex
          case 2: result.push(val);        // filter
        } else if (IS_EVERY) return false; // every
      }
    }
    return IS_FIND_INDEX ? -1 : IS_SOME || IS_EVERY ? IS_EVERY : result;
  };
};


/***/ }),

/***/ "./node_modules/core-js/modules/_array-species-constructor.js":
/*!********************************************************************!*\
  !*** ./node_modules/core-js/modules/_array-species-constructor.js ***!
  \********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(/*! ./_is-object */ "./node_modules/core-js/modules/_is-object.js");
var isArray = __webpack_require__(/*! ./_is-array */ "./node_modules/core-js/modules/_is-array.js");
var SPECIES = __webpack_require__(/*! ./_wks */ "./node_modules/core-js/modules/_wks.js")('species');

module.exports = function (original) {
  var C;
  if (isArray(original)) {
    C = original.constructor;
    // cross-realm fallback
    if (typeof C == 'function' && (C === Array || isArray(C.prototype))) C = undefined;
    if (isObject(C)) {
      C = C[SPECIES];
      if (C === null) C = undefined;
    }
  } return C === undefined ? Array : C;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_array-species-create.js":
/*!***************************************************************!*\
  !*** ./node_modules/core-js/modules/_array-species-create.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 9.4.2.3 ArraySpeciesCreate(originalArray, length)
var speciesConstructor = __webpack_require__(/*! ./_array-species-constructor */ "./node_modules/core-js/modules/_array-species-constructor.js");

module.exports = function (original, length) {
  return new (speciesConstructor(original))(length);
};


/***/ }),

/***/ "./node_modules/core-js/modules/_cof.js":
/*!**********************************************!*\
  !*** ./node_modules/core-js/modules/_cof.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var toString = {}.toString;

module.exports = function (it) {
  return toString.call(it).slice(8, -1);
};


/***/ }),

/***/ "./node_modules/core-js/modules/_core.js":
/*!***********************************************!*\
  !*** ./node_modules/core-js/modules/_core.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var core = module.exports = { version: '2.5.7' };
if (typeof __e == 'number') __e = core; // eslint-disable-line no-undef


/***/ }),

/***/ "./node_modules/core-js/modules/_ctx.js":
/*!**********************************************!*\
  !*** ./node_modules/core-js/modules/_ctx.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// optional / simple context binding
var aFunction = __webpack_require__(/*! ./_a-function */ "./node_modules/core-js/modules/_a-function.js");
module.exports = function (fn, that, length) {
  aFunction(fn);
  if (that === undefined) return fn;
  switch (length) {
    case 1: return function (a) {
      return fn.call(that, a);
    };
    case 2: return function (a, b) {
      return fn.call(that, a, b);
    };
    case 3: return function (a, b, c) {
      return fn.call(that, a, b, c);
    };
  }
  return function (/* ...args */) {
    return fn.apply(that, arguments);
  };
};


/***/ }),

/***/ "./node_modules/core-js/modules/_defined.js":
/*!**************************************************!*\
  !*** ./node_modules/core-js/modules/_defined.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// 7.2.1 RequireObjectCoercible(argument)
module.exports = function (it) {
  if (it == undefined) throw TypeError("Can't call method on  " + it);
  return it;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_descriptors.js":
/*!******************************************************!*\
  !*** ./node_modules/core-js/modules/_descriptors.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// Thank's IE8 for his funny defineProperty
module.exports = !__webpack_require__(/*! ./_fails */ "./node_modules/core-js/modules/_fails.js")(function () {
  return Object.defineProperty({}, 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),

/***/ "./node_modules/core-js/modules/_dom-create.js":
/*!*****************************************************!*\
  !*** ./node_modules/core-js/modules/_dom-create.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(/*! ./_is-object */ "./node_modules/core-js/modules/_is-object.js");
var document = __webpack_require__(/*! ./_global */ "./node_modules/core-js/modules/_global.js").document;
// typeof document.createElement is 'object' in old IE
var is = isObject(document) && isObject(document.createElement);
module.exports = function (it) {
  return is ? document.createElement(it) : {};
};


/***/ }),

/***/ "./node_modules/core-js/modules/_export.js":
/*!*************************************************!*\
  !*** ./node_modules/core-js/modules/_export.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var global = __webpack_require__(/*! ./_global */ "./node_modules/core-js/modules/_global.js");
var core = __webpack_require__(/*! ./_core */ "./node_modules/core-js/modules/_core.js");
var hide = __webpack_require__(/*! ./_hide */ "./node_modules/core-js/modules/_hide.js");
var redefine = __webpack_require__(/*! ./_redefine */ "./node_modules/core-js/modules/_redefine.js");
var ctx = __webpack_require__(/*! ./_ctx */ "./node_modules/core-js/modules/_ctx.js");
var PROTOTYPE = 'prototype';

var $export = function (type, name, source) {
  var IS_FORCED = type & $export.F;
  var IS_GLOBAL = type & $export.G;
  var IS_STATIC = type & $export.S;
  var IS_PROTO = type & $export.P;
  var IS_BIND = type & $export.B;
  var target = IS_GLOBAL ? global : IS_STATIC ? global[name] || (global[name] = {}) : (global[name] || {})[PROTOTYPE];
  var exports = IS_GLOBAL ? core : core[name] || (core[name] = {});
  var expProto = exports[PROTOTYPE] || (exports[PROTOTYPE] = {});
  var key, own, out, exp;
  if (IS_GLOBAL) source = name;
  for (key in source) {
    // contains in native
    own = !IS_FORCED && target && target[key] !== undefined;
    // export native or passed
    out = (own ? target : source)[key];
    // bind timers to global for call from export context
    exp = IS_BIND && own ? ctx(out, global) : IS_PROTO && typeof out == 'function' ? ctx(Function.call, out) : out;
    // extend global
    if (target) redefine(target, key, out, type & $export.U);
    // export
    if (exports[key] != out) hide(exports, key, exp);
    if (IS_PROTO && expProto[key] != out) expProto[key] = out;
  }
};
global.core = core;
// type bitmap
$export.F = 1;   // forced
$export.G = 2;   // global
$export.S = 4;   // static
$export.P = 8;   // proto
$export.B = 16;  // bind
$export.W = 32;  // wrap
$export.U = 64;  // safe
$export.R = 128; // real proto method for `library`
module.exports = $export;


/***/ }),

/***/ "./node_modules/core-js/modules/_fails.js":
/*!************************************************!*\
  !*** ./node_modules/core-js/modules/_fails.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function (exec) {
  try {
    return !!exec();
  } catch (e) {
    return true;
  }
};


/***/ }),

/***/ "./node_modules/core-js/modules/_fix-re-wks.js":
/*!*****************************************************!*\
  !*** ./node_modules/core-js/modules/_fix-re-wks.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var hide = __webpack_require__(/*! ./_hide */ "./node_modules/core-js/modules/_hide.js");
var redefine = __webpack_require__(/*! ./_redefine */ "./node_modules/core-js/modules/_redefine.js");
var fails = __webpack_require__(/*! ./_fails */ "./node_modules/core-js/modules/_fails.js");
var defined = __webpack_require__(/*! ./_defined */ "./node_modules/core-js/modules/_defined.js");
var wks = __webpack_require__(/*! ./_wks */ "./node_modules/core-js/modules/_wks.js");

module.exports = function (KEY, length, exec) {
  var SYMBOL = wks(KEY);
  var fns = exec(defined, SYMBOL, ''[KEY]);
  var strfn = fns[0];
  var rxfn = fns[1];
  if (fails(function () {
    var O = {};
    O[SYMBOL] = function () { return 7; };
    return ''[KEY](O) != 7;
  })) {
    redefine(String.prototype, KEY, strfn);
    hide(RegExp.prototype, SYMBOL, length == 2
      // 21.2.5.8 RegExp.prototype[@@replace](string, replaceValue)
      // 21.2.5.11 RegExp.prototype[@@split](string, limit)
      ? function (string, arg) { return rxfn.call(string, this, arg); }
      // 21.2.5.6 RegExp.prototype[@@match](string)
      // 21.2.5.9 RegExp.prototype[@@search](string)
      : function (string) { return rxfn.call(string, this); }
    );
  }
};


/***/ }),

/***/ "./node_modules/core-js/modules/_global.js":
/*!*************************************************!*\
  !*** ./node_modules/core-js/modules/_global.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// https://github.com/zloirock/core-js/issues/86#issuecomment-115759028
var global = module.exports = typeof window != 'undefined' && window.Math == Math
  ? window : typeof self != 'undefined' && self.Math == Math ? self
  // eslint-disable-next-line no-new-func
  : Function('return this')();
if (typeof __g == 'number') __g = global; // eslint-disable-line no-undef


/***/ }),

/***/ "./node_modules/core-js/modules/_has.js":
/*!**********************************************!*\
  !*** ./node_modules/core-js/modules/_has.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var hasOwnProperty = {}.hasOwnProperty;
module.exports = function (it, key) {
  return hasOwnProperty.call(it, key);
};


/***/ }),

/***/ "./node_modules/core-js/modules/_hide.js":
/*!***********************************************!*\
  !*** ./node_modules/core-js/modules/_hide.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var dP = __webpack_require__(/*! ./_object-dp */ "./node_modules/core-js/modules/_object-dp.js");
var createDesc = __webpack_require__(/*! ./_property-desc */ "./node_modules/core-js/modules/_property-desc.js");
module.exports = __webpack_require__(/*! ./_descriptors */ "./node_modules/core-js/modules/_descriptors.js") ? function (object, key, value) {
  return dP.f(object, key, createDesc(1, value));
} : function (object, key, value) {
  object[key] = value;
  return object;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_ie8-dom-define.js":
/*!*********************************************************!*\
  !*** ./node_modules/core-js/modules/_ie8-dom-define.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = !__webpack_require__(/*! ./_descriptors */ "./node_modules/core-js/modules/_descriptors.js") && !__webpack_require__(/*! ./_fails */ "./node_modules/core-js/modules/_fails.js")(function () {
  return Object.defineProperty(__webpack_require__(/*! ./_dom-create */ "./node_modules/core-js/modules/_dom-create.js")('div'), 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),

/***/ "./node_modules/core-js/modules/_iobject.js":
/*!**************************************************!*\
  !*** ./node_modules/core-js/modules/_iobject.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// fallback for non-array-like ES3 and non-enumerable old V8 strings
var cof = __webpack_require__(/*! ./_cof */ "./node_modules/core-js/modules/_cof.js");
// eslint-disable-next-line no-prototype-builtins
module.exports = Object('z').propertyIsEnumerable(0) ? Object : function (it) {
  return cof(it) == 'String' ? it.split('') : Object(it);
};


/***/ }),

/***/ "./node_modules/core-js/modules/_is-array.js":
/*!***************************************************!*\
  !*** ./node_modules/core-js/modules/_is-array.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 7.2.2 IsArray(argument)
var cof = __webpack_require__(/*! ./_cof */ "./node_modules/core-js/modules/_cof.js");
module.exports = Array.isArray || function isArray(arg) {
  return cof(arg) == 'Array';
};


/***/ }),

/***/ "./node_modules/core-js/modules/_is-object.js":
/*!****************************************************!*\
  !*** ./node_modules/core-js/modules/_is-object.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function (it) {
  return typeof it === 'object' ? it !== null : typeof it === 'function';
};


/***/ }),

/***/ "./node_modules/core-js/modules/_library.js":
/*!**************************************************!*\
  !*** ./node_modules/core-js/modules/_library.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = false;


/***/ }),

/***/ "./node_modules/core-js/modules/_object-dp.js":
/*!****************************************************!*\
  !*** ./node_modules/core-js/modules/_object-dp.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var anObject = __webpack_require__(/*! ./_an-object */ "./node_modules/core-js/modules/_an-object.js");
var IE8_DOM_DEFINE = __webpack_require__(/*! ./_ie8-dom-define */ "./node_modules/core-js/modules/_ie8-dom-define.js");
var toPrimitive = __webpack_require__(/*! ./_to-primitive */ "./node_modules/core-js/modules/_to-primitive.js");
var dP = Object.defineProperty;

exports.f = __webpack_require__(/*! ./_descriptors */ "./node_modules/core-js/modules/_descriptors.js") ? Object.defineProperty : function defineProperty(O, P, Attributes) {
  anObject(O);
  P = toPrimitive(P, true);
  anObject(Attributes);
  if (IE8_DOM_DEFINE) try {
    return dP(O, P, Attributes);
  } catch (e) { /* empty */ }
  if ('get' in Attributes || 'set' in Attributes) throw TypeError('Accessors not supported!');
  if ('value' in Attributes) O[P] = Attributes.value;
  return O;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_property-desc.js":
/*!********************************************************!*\
  !*** ./node_modules/core-js/modules/_property-desc.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function (bitmap, value) {
  return {
    enumerable: !(bitmap & 1),
    configurable: !(bitmap & 2),
    writable: !(bitmap & 4),
    value: value
  };
};


/***/ }),

/***/ "./node_modules/core-js/modules/_redefine.js":
/*!***************************************************!*\
  !*** ./node_modules/core-js/modules/_redefine.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var global = __webpack_require__(/*! ./_global */ "./node_modules/core-js/modules/_global.js");
var hide = __webpack_require__(/*! ./_hide */ "./node_modules/core-js/modules/_hide.js");
var has = __webpack_require__(/*! ./_has */ "./node_modules/core-js/modules/_has.js");
var SRC = __webpack_require__(/*! ./_uid */ "./node_modules/core-js/modules/_uid.js")('src');
var TO_STRING = 'toString';
var $toString = Function[TO_STRING];
var TPL = ('' + $toString).split(TO_STRING);

__webpack_require__(/*! ./_core */ "./node_modules/core-js/modules/_core.js").inspectSource = function (it) {
  return $toString.call(it);
};

(module.exports = function (O, key, val, safe) {
  var isFunction = typeof val == 'function';
  if (isFunction) has(val, 'name') || hide(val, 'name', key);
  if (O[key] === val) return;
  if (isFunction) has(val, SRC) || hide(val, SRC, O[key] ? '' + O[key] : TPL.join(String(key)));
  if (O === global) {
    O[key] = val;
  } else if (!safe) {
    delete O[key];
    hide(O, key, val);
  } else if (O[key]) {
    O[key] = val;
  } else {
    hide(O, key, val);
  }
// add fake Function#toString for correct work wrapped methods / constructors with methods like LoDash isNative
})(Function.prototype, TO_STRING, function toString() {
  return typeof this == 'function' && this[SRC] || $toString.call(this);
});


/***/ }),

/***/ "./node_modules/core-js/modules/_shared.js":
/*!*************************************************!*\
  !*** ./node_modules/core-js/modules/_shared.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var core = __webpack_require__(/*! ./_core */ "./node_modules/core-js/modules/_core.js");
var global = __webpack_require__(/*! ./_global */ "./node_modules/core-js/modules/_global.js");
var SHARED = '__core-js_shared__';
var store = global[SHARED] || (global[SHARED] = {});

(module.exports = function (key, value) {
  return store[key] || (store[key] = value !== undefined ? value : {});
})('versions', []).push({
  version: core.version,
  mode: __webpack_require__(/*! ./_library */ "./node_modules/core-js/modules/_library.js") ? 'pure' : 'global',
  copyright: '© 2018 Denis Pushkarev (zloirock.ru)'
});


/***/ }),

/***/ "./node_modules/core-js/modules/_to-integer.js":
/*!*****************************************************!*\
  !*** ./node_modules/core-js/modules/_to-integer.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// 7.1.4 ToInteger
var ceil = Math.ceil;
var floor = Math.floor;
module.exports = function (it) {
  return isNaN(it = +it) ? 0 : (it > 0 ? floor : ceil)(it);
};


/***/ }),

/***/ "./node_modules/core-js/modules/_to-length.js":
/*!****************************************************!*\
  !*** ./node_modules/core-js/modules/_to-length.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 7.1.15 ToLength
var toInteger = __webpack_require__(/*! ./_to-integer */ "./node_modules/core-js/modules/_to-integer.js");
var min = Math.min;
module.exports = function (it) {
  return it > 0 ? min(toInteger(it), 0x1fffffffffffff) : 0; // pow(2, 53) - 1 == 9007199254740991
};


/***/ }),

/***/ "./node_modules/core-js/modules/_to-object.js":
/*!****************************************************!*\
  !*** ./node_modules/core-js/modules/_to-object.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 7.1.13 ToObject(argument)
var defined = __webpack_require__(/*! ./_defined */ "./node_modules/core-js/modules/_defined.js");
module.exports = function (it) {
  return Object(defined(it));
};


/***/ }),

/***/ "./node_modules/core-js/modules/_to-primitive.js":
/*!*******************************************************!*\
  !*** ./node_modules/core-js/modules/_to-primitive.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 7.1.1 ToPrimitive(input [, PreferredType])
var isObject = __webpack_require__(/*! ./_is-object */ "./node_modules/core-js/modules/_is-object.js");
// instead of the ES6 spec version, we didn't implement @@toPrimitive case
// and the second argument - flag - preferred type is a string
module.exports = function (it, S) {
  if (!isObject(it)) return it;
  var fn, val;
  if (S && typeof (fn = it.toString) == 'function' && !isObject(val = fn.call(it))) return val;
  if (typeof (fn = it.valueOf) == 'function' && !isObject(val = fn.call(it))) return val;
  if (!S && typeof (fn = it.toString) == 'function' && !isObject(val = fn.call(it))) return val;
  throw TypeError("Can't convert object to primitive value");
};


/***/ }),

/***/ "./node_modules/core-js/modules/_uid.js":
/*!**********************************************!*\
  !*** ./node_modules/core-js/modules/_uid.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var id = 0;
var px = Math.random();
module.exports = function (key) {
  return 'Symbol('.concat(key === undefined ? '' : key, ')_', (++id + px).toString(36));
};


/***/ }),

/***/ "./node_modules/core-js/modules/_wks.js":
/*!**********************************************!*\
  !*** ./node_modules/core-js/modules/_wks.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var store = __webpack_require__(/*! ./_shared */ "./node_modules/core-js/modules/_shared.js")('wks');
var uid = __webpack_require__(/*! ./_uid */ "./node_modules/core-js/modules/_uid.js");
var Symbol = __webpack_require__(/*! ./_global */ "./node_modules/core-js/modules/_global.js").Symbol;
var USE_SYMBOL = typeof Symbol == 'function';

var $exports = module.exports = function (name) {
  return store[name] || (store[name] =
    USE_SYMBOL && Symbol[name] || (USE_SYMBOL ? Symbol : uid)('Symbol.' + name));
};

$exports.store = store;


/***/ }),

/***/ "./node_modules/core-js/modules/es6.array.find.js":
/*!********************************************************!*\
  !*** ./node_modules/core-js/modules/es6.array.find.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

// 22.1.3.8 Array.prototype.find(predicate, thisArg = undefined)
var $export = __webpack_require__(/*! ./_export */ "./node_modules/core-js/modules/_export.js");
var $find = __webpack_require__(/*! ./_array-methods */ "./node_modules/core-js/modules/_array-methods.js")(5);
var KEY = 'find';
var forced = true;
// Shouldn't skip holes
if (KEY in []) Array(1)[KEY](function () { forced = false; });
$export($export.P + $export.F * forced, 'Array', {
  find: function find(callbackfn /* , that = undefined */) {
    return $find(this, callbackfn, arguments.length > 1 ? arguments[1] : undefined);
  }
});
__webpack_require__(/*! ./_add-to-unscopables */ "./node_modules/core-js/modules/_add-to-unscopables.js")(KEY);


/***/ }),

/***/ "./node_modules/core-js/modules/es6.regexp.replace.js":
/*!************************************************************!*\
  !*** ./node_modules/core-js/modules/es6.regexp.replace.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// @@replace logic
__webpack_require__(/*! ./_fix-re-wks */ "./node_modules/core-js/modules/_fix-re-wks.js")('replace', 2, function (defined, REPLACE, $replace) {
  // 21.1.3.14 String.prototype.replace(searchValue, replaceValue)
  return [function replace(searchValue, replaceValue) {
    'use strict';
    var O = defined(this);
    var fn = searchValue == undefined ? undefined : searchValue[REPLACE];
    return fn !== undefined
      ? fn.call(searchValue, O, replaceValue)
      : $replace.call(String(O), searchValue, replaceValue);
  }, $replace];
});


/***/ }),

/***/ 0:
/*!****************************************!*\
  !*** multi ./js/admin-page-columns.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./js/admin-page-columns.js */"./js/admin-page-columns.js");


/***/ })

/******/ });
//# sourceMappingURL=admin-page-columns.js.map