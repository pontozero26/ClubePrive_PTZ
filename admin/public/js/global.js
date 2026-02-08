$(document).ready(function () {
	$("input[data-alert-text]").each(function () {
		const alertText = $(this).attr("data-alert-text");
		const alertType = $(this).attr("data-alert-type");

		console.log(alertText);

		if (alertText && alertType) {
			console.log("aa");
			$(this).wrap(`<div class="input-alert ${alertType}"></div>`);
			$('<div class="alert-text">' + alertText + "</div>").insertAfter($(this));
		}
	});

	$("input[data-alert-text]").on("focus", function () {
		var $input = $(this);
		var $wrapper = $input.parent(".input-alert");

		if ($wrapper.length) {
			$wrapper.before($input);
			$wrapper.remove();
		}

		$input.removeAttr("data-alert-type data-alert-text");
		$input.focus();
	});
});
