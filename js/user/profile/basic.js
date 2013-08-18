// string, for use of sending
var original = '';
var range = '';
var scale = '';
var source = '';

function resize(json) {
	//console.log(json);
	source = "/images/avatar/" + json.filename;
	$("#avatar-img")
		.attr("src", source)
		.imgAreaSelect({
			handles: true,
			aspectRatio: "1:1",
			onSelectEnd: preview
		});

	$("#preview").attr("src", source);

	$("#avatar-selection").show();
	$("#avatar-confirm").show();
}

function preview(img, selection) {
	var scaleX = 100 / (selection.width || 1);
	var scaleY = 100 / (selection.height || 1);

	var naturalWidth = $("#avatar-img")[0].naturalWidth;
	var naturalHeight = $("#avatar-img")[0].naturalHeight;
	
	var width = 350;
	var height = 350 / naturalWidth * naturalHeight;

	$('#preview').css({
		width: Math.round(scaleX * width) + 'px',
		height: Math.round(scaleY * height) + 'px',
		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
	});

	original = JSON.stringify({'width': naturalWidth, 'height': naturalHeight});
	range = JSON.stringify(selection);
	scale = JSON.stringify({"scaleX": scaleX, "scaleY": scaleY});
}

$('<div><img id="preview" src="/images/avatar/default.jpg" /><div>')
	.css({
		float: 'left',
		position: 'relative',
		overflow: 'hidden',
		width: '100px',
		height: '100px',
		'margin-left': '20px'
	})
	.insertAfter($('#avatar-img'));


$('#btn-avatar-confirm').click(function(){
	$.post('/user/profile/avatarcrop', {
		'source': source,
		'widthBase': 350,
		'original': original,
		'range': range,
		'scale': scale
	}, function(data){
		d = JSON.parse(data);
		if ( d.error ) {
			alert(d.msg);
		} else {
			// apply the new avatar
			source = '/images/avatar/' + d.filename;
			$('#avatar-current').attr('src', source);
			$('input[name=avatar-current]').val(source.substring(15));
			// hide the crop window
			$("#avatar-selection").hide('fast');
			$("#avatar-confirm").hide('fast');
			// remove the crop area
			$(".imgareaselect-outer").remove();
			$(".imgareaselect-selection").parent().remove();
			alert('头像上传成功，请提交保存。');
		}
	});
	return false;
});