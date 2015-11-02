$(document).ready(function() {
	if (window.location.hash == '#filters') {
		$('#filters-form').show();
	}

	$('#filters-button').click(function() {
		$('#filters-form').slideToggle();
			return false;
	});

	$('#select-all').change(function() {
		$('.select-item').prop('checked', $(this).is(':checked'));
	});

	$("#generator_settings_minFilterDominance").slider({
		formater: function(value) { return value + ' %'; },
		min: 0,
		max: 100,
		value: 90
	});

	$("#generator_settings_minThresholdDominance").slider({
		formater: function(value) { return value + ' %'; },
		min: 0,
		max: 100,
		value: 90
	});
});
