jQuery(function(){
	jQuery("#upload_image").on("click",function(e1){
		e1.preventDefault();
			var images=wp.media({
			title: "Upload images",
			multiple: false
		}).open().on("select",function(e){
			// console.log("helloe world");
			var uploadimage=images.state().get("selection").first();
			console.log(uploadimage.toJSON());
			var upload_img=uploadimage.toJSON().url;
			console.log(upload_img);
			jQuery('#disp').val(upload_img);
			// jQuery("#lbl").html("<span class='dashicons dashicons-format-image'></span><label>Image</label>");
			//jQuery("#show-image").html("<img src='"+upload_img+"' style='height:100px; width:150px;border: 2px solid black;'/>");
			jQuery("#btn").html("<button id='remove-btn' class='button button-primary button-large'>Remove Image</button>");
			jQuery('#show-image').attr("src",upload_img);
			// document.getElementById('remove-btn').style.display = 'none';
			$("remove-btn").click(function(){
				$("#show-image").hide();
			});
		});
	});
});
