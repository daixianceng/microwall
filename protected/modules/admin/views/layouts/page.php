<?php $this->beginContent('/layouts/column1')?>
<div class="row">
	<div class="col-md-12 content-section">
		<ul class="nav nav-tabs" role="tablist">
			<li<?php echo $this->type === 'local' ? ' class="active"' : ''?>><a href="<?php echo $this->createUrl('add')?>"><?php echo Yii::t('AdminModule.page', 'Local Page')?></a></li>
			<li<?php echo $this->type === 'link' ? ' class="active"' : ''?>><a href="<?php echo $this->createUrl('add', array('type' => 'link'))?>"><?php echo Yii::t('AdminModule.page', 'Remote Page')?></a></li>
		</ul>
	</div>
	<div class="col-md-12"><?php echo $content;?></div>
</div>
<?php $this->endContent();?>