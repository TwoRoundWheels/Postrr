//  Show play button for uploads on click or tap.
$(".upload-image-thumbnail").click(function(e) {
	$(".view").css("display", "none");
	$(".upload-image-thumbnail").find("img").css("opacity","1");
	$(this).find("a").css("display", "block");
	$(this).find("img").css("opacity",".5");
});

//  Show play button for uploads on hover for PC users
$(".upload-image-thumbnail").hover(function(e) {
	$(".view").css("display", "none");
	$(".upload-image-thumbnail").find("img").css("opacity","1");
	$(this).find("a").css("display", "block");
	$(this).find("img").css("opacity",".5");
});
