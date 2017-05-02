// Script to check or uncheck both of the Create Album Checkboxes in the Post submit
// and file upload forms.   
$(".create-album-checkbox").change(function () {
	$(".create-album-checkbox:input:checkbox").prop('checked', $(this).prop("checked"));
});