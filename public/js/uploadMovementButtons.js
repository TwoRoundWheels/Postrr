// These functions are used to move the display order of the uploads up or down and update the database
// accordingly via an AJAX request.

// Move attachment up in display order.
$(".up-arrow").click(function(e) {
	e.preventDefault();
	id = $(this).parent().parent().attr('id');
	div = $(this).parent().parent();
	if (id != 0) {
		postId = $('#post').val();
		$.ajax({
			url: '../move-up',
			type: 'POST',
			data: {'id': id, 'postId': postId},
			success: function(response) {
				// Create variable with value of id minus 1.  jQuery didn't like math inside selectors.
				idMinusOne = id - 1;
				// Assign a temporary value to selected id to keep from creating 2 id's of the same value.
				$('#' + id).attr("id", "tempValue"); 
				$('#' + idMinusOne).attr("id", id);
				$('#tempValue').attr("id", idMinusOne).fadeOut(300);
				$('#' + idMinusOne).insertBefore("#" + id).fadeIn(300);
				},
			error: function() {
				alert("Hmmm... It looks like something went wrong.");
			}	
		});
	}
});

//  Move attachment down in display order. 
$(".down-arrow").click(function(e) {
	e.preventDefault();
	id = $(this).parent().parent().attr('id');
	numberOfAttachments = $('#count').val() - 1;
	if (id < numberOfAttachments) {
		postId = $('#post').val();
		$.ajax({
			url: '../move-down',
			type: 'POST',
			data: {'id': id, 'postId': postId, 'max': numberOfAttachments},
			success: function(response) {		
				// Create variable with value of id plus 1.
				idPlusOne = parseInt(id) + 1;
				// Assign a temporary value to selected id to keep from creating 2 id's of the same value.
				$('#' + id).attr("id", "tempValue");
				$('#' + idPlusOne).attr("id", id);
				$('#tempValue').attr("id", idPlusOne).fadeOut(300);
				$('#' + idPlusOne).insertAfter("#" + id).fadeIn(300);
			},
			error: function() {
				alert("Hmmmm... It looks like something went wrong.");
			}
		});
	}
});