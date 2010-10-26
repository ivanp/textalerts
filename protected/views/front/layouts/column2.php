<?php $this->beginContent('//layouts/main'); ?>
<div class="container">
	<div class="span-19">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div class="span-5 last">
		<?php foreach ($this->portlets as $portlet): ?>
		<div id="sidebar">
		<?php
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>$portlet['title'],
			));
			echo $portlet['content'];
			$this->endWidget();
		?>
		</div><!-- sidebar -->
		<?php endforeach; ?>
	</div>
</div>
<?php $this->endContent(); ?>