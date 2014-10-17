<div class="row">
	<div class="col-md-12">
		<div class="alert alert-danger" role="alert">
	      <h4><?php echo Yii::t('AdminModule.global', 'Dangerous!')?></h4>
	      <?php $form = $this->beginWidget('CActiveForm', array(
			'id'=>'User-Remove',
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
	      <p><?php echo Yii::t('AdminModule.user', 'Detects the user({user}) has articles, please select an action in the following:', array('{user}' => $user->nickname))?></p>
	      <p>
	      <div class="radio">
			  <label>
			    <?php echo $form->radioButton($model, 'type', array('value' => RemoveAlertForm::TYPE_MOVE, 'checked' => 'checked', 'uncheckValue' => null, 'id' => ''))?>
			    <?php echo Yii::t('AdminModule.user', 'Delete the user and move it\'s all articles to {select} user(Recommended).', array('{select}' => $form->dropDownList($model, 'moveTo', $model->getOtherList())))?>
			  </label>
			</div>
			<div class="radio">
			  <label>
			    <?php echo $form->radioButton($model, 'type', array('value' => RemoveAlertForm::TYPE_DELETE, 'uncheckValue' => null, 'id' => ''))?>
			    <?php echo Yii::t('AdminModule.user', 'Delete the user and it\'s all articles.')?>
			  </label>
			</div>
			</p>
	      <p>
	        <button type="submit" class="btn btn-danger"><?php echo Yii::t('AdminModule.global', 'Ok')?></button>
	        <a href="<?php echo $this->createUrl('index')?>" class="btn btn-default"><?php echo Yii::t('AdminModule.global', 'Cancel')?></a>
	      </p>
	      <?php $this->endWidget(); ?>
	    </div>
	</div>
</div>