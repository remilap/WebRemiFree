
$(function() {

	$("#form_modchapter").validate( {
		rules: {
			chapter_name: {
				required: true,
				alphanumeric: true,
				rangelength:[4, 20]
			}
		},
		messages: {
			chapter_name: {
				required: "Please enter the chapter name",
				alphanumeric: "Only letters and digits are accepted",
				rangelength: "The length should be in the range {0}-{1}"
			}
		}
	});

});
