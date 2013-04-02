$(function()
{
	var config = {
		toolbar:
		[
			['Source', '-', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', '-', 'Image', 'Flash'],
			['UIColor']
		],
		language : languageCode
	};

	// Initialize the editor.
	// Callback function can be passed and executed after full instance creation.
	$('.jquery_ckeditor').ckeditor(config);
	var editor = $('.jquery_ckeditor').ckeditorGet();

	CKFinder.setupCKEditor(editor, '/lib/ckfinder/');
});