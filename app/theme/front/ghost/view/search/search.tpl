<?= $header; ?>
<?= $post_header; ?>
<style>
#search-results {
	font-family: arial,sans-serif;
}
#search-results div {
	margin-bottom: 16px !important;
}
#search-results h3 {
	font-size: 18px;
	font-weight: normal;
}
#search-results h3 a {
	color: #1a0dab;
}
#search-results h3 a:visited {
	color:#609;
}
#search-results span {
	font-size: 14px;
}
#search-results .search-url {
	color: #006621;
}
#search-results .result-type {
	color: #777;
}
#search-results p {
	line-height: 1.4;
	word-wrap: break-word;
	color: #545454;
	font-size: small;
}
</style>
<div class="row">
	<?= $column_left; ?>
	<div class="col-sm-<?php $span = trim($column_left) ? 9 : 12; $span = trim($column_right) ? $span - 3 : $span; echo $span; ?>">
		<?= $breadcrumb; ?>
		<?= $content_top; ?>
		<div class="page-header"><h1><?= $heading_title; ?></h1></div>
		<div class="row">
			<div class="form-group">
				<div class="col-sm-8 col-sm-offset-2">
					<input type="text" name="search-field" value="<?= $search; ?>" class="form-control" placeholder="<?= $lang_entry_search; ?>" id="search-field">
				</div>
			</div>
		</div>
		<hr>
		<div class="row" id="search-results">
		<?php if ($results): ?>
			<?php foreach($results as $result): ?>
			<div class="col-sm-12">
			<h3><a href="<?= $result['url']; ?>"><?= $result['title']; ?></a></h3>
			<span class="search-url"><?= $result['url']; ?></span> <span class="result-type"><span class="fa fa-caret-right"></span> <?= $result['type']; ?></span>
			<p><?= $result['text']; ?></p>
			</div>
			<?php endforeach; ?>		
		<?php else: ?>
			<p class="text-center">Sorry, no results for this search.</p>
		<?php endif; ?>
		</div>

		<div class="pagination"><?= str_replace('....','',$pagination); ?></div>
		
		<?= $content_bottom; ?>
	</div>
	<?= $column_right; ?>
</div>
<?= $pre_footer; ?>
<?= $footer; ?>