jQuery(function($){
	
	/* загрузчик изображений */
	$('.upload_image_button').click(function( event ){
 
		event.preventDefault();
 
		const button = $(this);
 
		const customUploader = wp.media({
			title: 'Choose image',
			library : {
				uploadedTo : wp.media.view.settings.post.id, 
				type : 'image'
			},
			button: {
				text: 'Select image' 
			},
			multiple: false
		});
 
		customUploader.on('select', function() {
 
			const image = customUploader.state().get('selection').first().toJSON();
 
			button.parent().prev().attr( 'src', image.url );
			button.prev().val( image.id );
 
		});
 
		customUploader.open();
	});

	/* удаление изображения */
	$('.remove_image_button').click(function( event){
 
		event.preventDefault(); 

			const src = $(this).parent().prev().data('src');

			$(this).parent().prev().attr('src', src);
			$(this).prev().prev().val('');
	});

	/* очистка кастомных полей */
	$('.clear_custom_fields').click(function( event){
 
		event.preventDefault();
 
		
			const srcImg = $("#custom_img_block").children('img').data('src');

			$("#custom_img_block").children('img').attr('src', srcImg);
			$("#custom_img_block").children('.add_img_btn').children('#_uploader_custom').val('');

			$("#_select").children('option[selected="selected"]').removeAttr('selected');
			$("#_select").children('option[value="one"]').attr("selected","selected");

			$("._creating_date_field").children('#_creating_date').attr("value","");

	});

	/* сохранение при нажатии на кастомную кнопку */
	$('#button_save').click(function( event){
 
		event.preventDefault();
 
		
			$('#publish').click();

	});
});