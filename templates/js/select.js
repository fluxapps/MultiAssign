$(document).ready(function () {
	$('#select_mem').click(function () {
		$('.role_2').each(function () {
			if ($(this).prop('checked')) {
				$(this).prop('checked', false);
			} else {
				$(this).prop('checked', true);
			}
		});
	});
	$('#select_tut').click(function () {
		$('.role_3').each(function () {
			if ($(this).prop('checked')) {
				$(this).prop('checked', false);
			} else {
				$(this).prop('checked', true);
			}
		});
	});
	$('#select_adm').click(function () {
		$('.role_1').each(function () {
			if ($(this).prop('checked')) {
				$(this).prop('checked', false);
			} else {
				$(this).prop('checked', true);
			}
		});
	});
});

