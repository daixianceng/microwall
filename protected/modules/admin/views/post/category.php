<div class="row">
	<div class="col-md-8">
		<?php $form = $this->beginWidget('CActiveForm', array(
			'id'=>'Category',
		    'enableClientValidation'=>true,
			'clientOptions' => array(
				'validateOnSubmit' => true,
				'validateOnChange' => true,
				'errorCssClass' => 'has-error',
				'successCssClass' => 'has-success'
			),
			'htmlOptions' => array(
				'class' => 'form-horizontal'
			)
		)); ?>
		  <div class="form-group">
		    <?php echo $form->label($model, 'name', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.post', 'Name')))?>
		    <div class="col-md-10">
		      <?php echo $form->textField($model, 'name', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.post', 'Name')))?>
		      <?php echo $form->error($model, 'name', array('class' => 'alert alert-danger'))?>
		    </div>
		  </div>
		  <div class="form-group">
		    <?php echo $form->label($model, 'slug', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.post', 'URL Index')))?>
		    <div class="col-md-10">
		      <?php echo $form->textField($model, 'slug', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.post', 'URL Index')))?>
		      <?php echo $form->error($model, 'slug', array('class' => 'alert alert-danger'))?>
		      <span class="help-block"><?php echo Yii::t('AdminModule.post', 'Displayed in the URL, such as:"http://www.example.com/category/<mark>category-name</mark>".')?></span>
		    </div>
		  </div>
		  <div class="form-group">
		    <?php echo $form->label($model, 'comment', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.post', 'Comment')))?>
		    <div class="col-md-10">
		      <?php echo $form->textField($model, 'comment', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.post', 'Comment')))?>
		      <?php echo $form->error($model, 'comment', array('class' => 'alert alert-danger'))?>
		      <span class="help-block"><?php echo Yii::t('AdminModule.post', 'Example:"In this category are tech articles".')?></span>
		    </div>
		  </div>
		  <div class="form-group">
		    <div class="col-md-offset-2 col-md-10">
		      <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-ok glyphicon-rm10"></span><?php echo Yii::t('AdminModule.global', 'Save')?></button>
		    </div>
		  </div>
		  <?php $this->endWidget(); ?>
	</div>
</div>