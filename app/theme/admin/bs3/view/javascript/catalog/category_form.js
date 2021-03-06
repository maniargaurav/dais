<script>
<?php foreach($languages as $language): ?>

$('#meta-description<?= $language["language_id"]; ?>').bind('click', function(e) {
	e.preventDefault();
	$data = $('textarea[name="category_description[<?= $language["language_id"]; ?>][description]"]').code();
	$.ajax({
		url: 'manage/catalog/category/description',
		type: 'post',
		dataType: 'json',
		data: {
			description: $data
		},
		success: function (json) {
			if (json['success']) {
				$('textarea[name="category_description[<?= $language["language_id"]; ?>][meta_description]"]').html(json['success']);
			}
		} 
	});
});

$('#meta-keyword<?= $language["language_id"]; ?>').bind('click', function(e) {
	e.preventDefault();
	$data = $('textarea[name="category_description[<?= $language["language_id"]; ?>][description]"]').code();
	$.ajax({
		url: 'manage/catalog/category/keyword',
		type: 'post',
		dataType: 'json',
		data: {
			keywords: $data
		},
		success: function (json) {
			if (json['success']) {
				$('textarea[name="category_description[<?= $language["language_id"]; ?>][meta_keyword]"]').html(json['success']);
			}
		} 
	});
});

<?php endforeach; ?>
</script>