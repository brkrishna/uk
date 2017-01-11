<?php

$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/content/site_content';

?>
<ul class='nav nav-pills'>
	<li<?php echo $checkSegment == '' ? ' class="active"' : ''; ?>>
		<a href="<?php echo site_url($areaUrl); ?>" id='list'>
            <?php echo lang('site_content_list'); ?>
        </a>
	</li>
	<?php if ($this->auth->has_permission('Site_Content.Content.Create')) : ?>
	<li<?php echo $checkSegment == 'create' ? ' class="active"' : ''; ?>>
		<a href="<?php echo site_url($areaUrl . '/create'); ?>" id='create_new'>
            <?php echo lang('site_content_new'); ?>
        </a>
	</li>
	<?php endif; ?>
</ul>