let acSelect2FormatState = ( state ) => {
	let indicator = '(PRO)';

	if ( !state.id ) {
		return state.text;
	}

	if ( -1 !== state.text.indexOf( indicator ) ) {
		let label = state.text.replace( indicator, '' );
		return $( `<span>${label} <span  class="ac-type-pro-only">pro</span></span>` );
	}

	return state.text;
};

let type_select2 = function( column ) {

	let $column = column.$el;
	let $settings = $column.find( '.ac-setting-input_type' );

	$settings.select2( {
		width : '100%',
		templateResult : acSelect2FormatState,
		templateSelection : acSelect2FormatState
	} );

};

module.exports = type_select2;