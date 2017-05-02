//Script to send X-CSRF token with all AJAX requests.
$.ajaxSetup({
	headers: {
	'X-CSRF-Token': $('meta[name="_token"]').attr('content')
	}
});
