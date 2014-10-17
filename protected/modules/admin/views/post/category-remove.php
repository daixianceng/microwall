<div class="row">
	<div class="col-md-12">
		<div class="alert alert-danger" role="alert">
	      <h4><?php echo Yii::t('AdminModule.global', 'Dangerous!')?></h4>
	      <?php $form = $this->beginWidget('CActiveForm', array(
			'id'=>'Category-Remove',
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
		)); ?>
	      <?php if (Category::model()->count() === '1') :?>
	      <p><?php echo Yii::t('AdminModule.post', 'Detected the category({category}) has articles, delete the category and will delete all articles, do you want to continue?', array('{category}' => $category->name))?></p>
	      <?php echo $form->hiddenField($model, 'type', array('value' => RemoveAlertForm::TYPE_DELETE))?>
	      <?php else :?>
	      <p><?php echo Yii::t('AdminModule.post', 'Detects the category({category}) has articles, please select an action in the following:', array('{category}' => $category->name))?></p>
	      <p>
	      <div class="radio">
			  <label>
			    <?php echo $form->radioButton($model, 'type', array('value' => RemoveAlertForm::TYPE_MOVE, 'checked' => 'checked', 'uncheckValue' => null, 'id' => ''))?>
			    <?php echo Yii::t('AdminModule.post', 'Delete the category and move it\'s all articles to {select} category(Recommended).', array('{select}' => $form->dropDownList($model, 'moveTo', $model->getOtherList())))?>
			  </label>
			</div>
			<div class="radio">
			  <label>
			    <?php echo $form->radioButton($model, 'type', array('value' => RemoveAlertForm::TYPE_DELETE, 'uncheckValue' => null, 'id' => ''))?>
			    <?php echo Yii::t('AdminModule.post', 'Delete the category and it\'s all articles.')?>
			  </label>
			</div>
	      </p>
	      <?php endif;?>
	      <p>
	        <button type="submit" class="btn btn-danger"><?php echo Yii::t('AdminModule.global', 'Ok')?></button>
	        <a href="<?php echo $this->createUrl('categories')?>" class="btn btn-default"><?php echo Yii::t('AdminModule.global', 'Cancel')?></a>
	      </p>
	      <?php $this->endWidget(); ?>
	    </div>
	</div>
</div>