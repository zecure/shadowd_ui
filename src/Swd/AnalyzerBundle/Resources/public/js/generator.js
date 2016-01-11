$(document).ready(function() {
	$("#generator_settings_minFilterDominance").slider({
		formater: function(value) { return value + ' %'; },
		min: 0,
		max: 100
	});

	$("#generator_settings_minThresholdDominance").slider({
		formater: function(value) { return value + ' %'; },
		min: 0,
		max: 100
	});

	$("#generator_settings_predefined").change(function () {
		switch ($(this).val()) {
			case '1': // low security
				$("#generator_settings_enableWhitelist").prop('checked', true);
				$("#generator_settings_minFilterDominance").slider('setValue', 100).val(100);
				$("#generator_settings_maxLengthVariance").val('0');
				$("#generator_settings_unifyWhitelistArrays").prop('checked', true);
				$("#generator_settings_unifyWhitelistCallers").prop('checked', true);
				$("#generator_settings_enableBlacklist").prop('checked', true);
				$("#generator_settings_minThresholdDominance").slider('setValue', 90).val(90);
				$("#generator_settings_unifyBlacklistArrays").prop('checked', true);
				$("#generator_settings_unifyBlacklistCallers").prop('checked', false);
				$("#generator_settings_enableIntegrity").prop('checked', true);

				break;
			case '2': // moderate security
				$("#generator_settings_enableWhitelist").prop('checked', true);
				$("#generator_settings_minFilterDominance").slider('setValue', 99).val(99);
				$("#generator_settings_maxLengthVariance").val('1');
				$("#generator_settings_unifyWhitelistArrays").prop('checked', true);
				$("#generator_settings_unifyWhitelistCallers").prop('checked', false);
				$("#generator_settings_enableBlacklist").prop('checked', true);
				$("#generator_settings_minThresholdDominance").slider('setValue', 90).val(90);
				$("#generator_settings_unifyBlacklistArrays").prop('checked', true);
				$("#generator_settings_unifyBlacklistCallers").prop('checked', false);
				$("#generator_settings_enableIntegrity").prop('checked', true);

				break;
			case '3': // high security
				$("#generator_settings_enableWhitelist").prop('checked', true);
				$("#generator_settings_minFilterDominance").slider('setValue', 90).val(90);
				$("#generator_settings_maxLengthVariance").val('5');
				$("#generator_settings_unifyWhitelistArrays").prop('checked', false);
				$("#generator_settings_unifyWhitelistCallers").prop('checked', false);
				$("#generator_settings_enableBlacklist").prop('checked', true);
				$("#generator_settings_minThresholdDominance").slider('setValue', 90).val(90);
				$("#generator_settings_unifyBlacklistArrays").prop('checked', false);
				$("#generator_settings_unifyBlacklistCallers").prop('checked', false);
				$("#generator_settings_enableIntegrity").prop('checked', true);

				break;
		} 
	}).change();

	$("#generator_settings_minUniqueVisitors, #generator_settings_enableWhitelist,"
	+ "#generator_settings_minFilterDominance, #generator_settings_maxLengthVariance,"
	+ "#generator_settings_unifyWhitelistArrays, #generator_settings_unifyWhitelistCallers,"
	+ "#generator_settings_enableBlacklist, #generator_settings_minThresholdDominance,"
	+ "#generator_settings_unifyBlacklistArrays, #generator_settings_unifyBlacklistCallers,"
	+ "#generator_settings_enableIntegrity").change(function () {
		$("#generator_settings_predefined").val(4); // custom
	});
});
