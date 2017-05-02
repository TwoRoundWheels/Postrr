/*  
 * Script for Dropzone options. this.on("success") attaches a hidden element when dropzone
 * successfully uploads. [] on the value of the name field increments name to be used as an 
 * array iterated through in a foreach line in the PostsController
 *
 * When a file is removed, the file is deleted from the database and server and the hidden element is
 * also removed.
*/

Dropzone.options.dropzoneForm = {
	acceptedFiles: ".wmv,.webm,.ogv,.oga,.ogg,image/jpeg,image/gif,image/png,image/bmp,image/tiff,video/asf,video/avi,video/divx,video/x-flv,video/quicktime,video/mpeg,video/mp4,video/ogg,video/x-matroska,audio/mpeg,audio/x-realaudio,audio/wav,audio/ogg,audio/midi,audio/wma,audio/x-matroska",
	init: function() {
		var count = 0;
		this.on("addedfile", function(file) {
			count += 1;
			if (!/^[a-zA-Z0-9\-\_ ,]*\.[a-zA-Z0-9,]*$/.test(file.name)) {
				alert("Filenames need to have only one \".\" in the name to be accepted.");
				this.removeFile(file);
			}
		});
		this.on("success", function(file, response) {
			var filename = response.filename;
			file.savedName = filename;
			$('<input>', {
				id: file.savedName,
				type: 'hidden',
				name: 'attached[]'
			}).appendTo('form').val(filename);
			$('#url').attr('disabled', 'disabled');
		});
		this.on("removedfile", function(file) {
			var savedName = file.savedName;
			count-=1;
			if (count === 0) {
				$('#url').removeAttr('disabled');
			}
			$.ajax({
				url: 'remove-attachment', 
				type: 'POST',
				data: {'savedName': savedName},
				success: function(response) {
					//  Escape characters with backslashes to allow savedName to be used as a jQuery selector
					savedName = savedName.replace( /(:|\.|\[|\]|,)/g, "\\$1" );
					$('#'+savedName).remove();
				} 
			});
		})
		this.on('error', function(file, errorMessage) {
			alert(errorMessage);
			this.removeFile(file);
		})
	}			
};
