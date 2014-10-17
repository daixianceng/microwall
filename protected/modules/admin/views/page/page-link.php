<div class="col-md-10">
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id'=>'Page',
		'enableClientValidation'=>false,
		'clientOptions' => array(
				'validateOnSubmit' => false,
				'validateOnChange' => false,
				'errorCssClass' => 'has-error',
				'successCssClass' => 'has-success'
		),
		'htmlOptions' => array(
				'class' => 'form-horizontal'
		)
	));?>
	  <div class="form-group">
	    <?php echo $form->label($model, 'title', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.page', 'Title')))?>
	    <div class="col-md-10">
	      <?php echo $form->textField($model, 'title', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.page', 'Title')))?>
	      <?php echo $form->error($model, 'title', array('class' => 'alert alert-danger'))?>
	    </div>
	  </div>
	  <div class="form-group">
	  	<?php echo $form->label($model, 'content', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.page', 'URL')))?>
	    <div class="col-md-10">
	      <?php echo $form->textField($model, 'content', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.page', 'URL')))?>
	      <?php echo $form->error($model, 'content', array('class' => 'alert alert-danger'))?>
	    </div>
	  </div>
	  <div class="form-group">
	    <div class="col-md-offset-2 col-md-10">
	      <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-ok glyphicon-rm10"></span><?php echo Yii::t('AdminModule.global', 'Save')?></button>
	    </div>
	  </div>
	  <?php $this->endWidget();?>
</div>