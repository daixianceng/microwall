<?php $this->beginContent('/layouts/column1')?>
<div class="row">
	<div class="col-md-12 content-section">
		<ul class="nav nav-tabs nav-tabs-google">
		  <li<?php echo $this->action->id === 'index' ? ' class="active"' : ''?>><a href="<?php echo $this->createUrl('index')?>"><?php echo Yii::t('AdminModule.global', 'Published')?></a></li>
		  <li<?php echo $this->action->id === 'archived' ? ' class="active"' : ''?>><a href="<?php echo $this->createUrl('archived')?>"><?php echo Yii::t('AdminModule.global', 'Drafts')?></a></li>
		  <li<?php echo $this->action->id === 'recycled' ? ' class="active"' : ''?>><a href="<?php echo $this->createUrl('recycled')?>"><?php echo Yii::t('AdminModule.global', 'Trash')?></a></li>
		</ul>
	</div>
    <?php echo $content;?>
</div>
<?php $this->endContent();?>