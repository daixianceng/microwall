<div class="row">
	<div class="col-md-8">
		<?php $form = $this->beginWidget('CActiveForm', array(
			'id'=>'User',
		    'enableClientValidation'=>true,
			'clientOptions' => array(
				'validateOnSubmit' => true,
				'validateOnChange' => true,
				'errorCssClass' => 'has-error',
				'successCssClass' => 'has-success'
			),
			'htmlOptions' => array(
				'class' => 'form-horizontal',
				'enctype' => 'multipart/form-data'
			)
		)); ?>
		  <div class="form-group">
		    <?php echo $form->label($model, 'role', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.user', 'Role')))?>
		    <div class="col-md-10">
		      <?php if ($model->isNewRecord) :?>
		      <?php echo $form->dropDownList($model, 'role', $roles, array('class' => 'form-control'))?>
		      <?php else :?>
		      <?php echo $form->dropDownList($model, 'role', $roles, array('class' => 'form-control', 'disabled' => 'disabled'))?>
		      <?php endif;?>
		      <?php echo $form->error($model, 'role', array('class' => 'alert alert-danger'))?>
		      <span class="help-block"><?php echo Yii::t('AdminModule.user', 'Each role has certain privileges.')?></span>
		    </div>
		  </div>
		  <div class="form-group">
		    <?php echo $form->label($model, 'name', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.user', 'Username')))?>
		    <div class="col-md-10">
		      <?php if ($model->isNewRecord) :?>
		      <?php echo $form->textField($model, 'name', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.user', 'Username')))?>
		      <?php else :?>
		      <?php echo $form->textField($model, 'name', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.user', 'Username'), 'disabled' => 'disabled'))?>
		      <?php endif;?>
		      <?php echo $form->error($model, 'name', array('class' => 'alert alert-danger'))?>
		    </div>
		  </div>
		  <div class="form-group">
		    <?php echo $form->label($model, 'password', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.user', 'Password')))?>
		    <div class="col-md-10">
		      <?php echo $form->passwordField($model, 'password', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.user', 'Password')))?>
		      <?php echo $form->error($model, 'password', array('class' => 'alert alert-danger'))?>
		      <?php if (!$model->isNewRecord) :?>
		      <span class="help-block"><?php echo Yii::t('AdminModule.user', 'If you do not change your password, please keep empty.')?></span>
		      <?php endif;?>
		    </div>
		  </div>
		  <div class="form-group">
		    <?php echo $form->label($model, 'passwordRepeat', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.user', 'Confirm Password')))?>
		    <div class="col-md-10">
		      <?php echo $form->passwordField($model, 'passwordRepeat', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.user', 'Confirm Password')))?>
		      <?php echo $form->error($model, 'passwordRepeat', array('class' => 'alert alert-danger'))?>
		    </div>
		  </div>
		  <div class="form-group">
		    <?php echo $form->label($model, 'nickname', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.user', 'Nickname')))?>
		    <div class="col-md-10">
		      <?php echo $form->textField($model, 'nickname', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.user', 'Nickname')))?>
		      <?php echo $form->error($model, 'nickname', array('class' => 'alert alert-danger'))?>
		    </div>
		  </div>
		  <div class="form-group">
		    <?php echo $form->label($model, 'mail', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.user', 'E-mail')))?>
		    <div class="col-md-10">
		      <?php echo $form->emailField($model, 'mail', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.user', 'E-mail')))?>
		      <?php echo $form->error($model, 'mail', array('class' => 'alert alert-danger'))?>
		    </div>
		  </div>
		  <div class="form-group">
		    <?php echo $form->label($model, 'avatar', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.user', 'Avatar')))?>
		    <div class="col-md-10">
		      <?php if (!$model->isNewRecord && !empty($model->avatar)) :?>
		      <img class="img-thumbnail content-section" alt="The picture" src="<?php echo Yii::app()->baseUrl?>/media/avatar/<?php echo $model->avatar?>">
		      <?php endif;?>
		      <?php echo $form->fileField($model, 'avatar', array('class' => 'filestyle', 'data-iconName' => 'glyphicon-picture', 'data-buttonText' => 'Choose image', 'data-buttonName' => 'btn-primary'))?>
		      <?php echo $form->error($model, 'avatar', array('class' => 'alert alert-danger'))?>
		      <span class="help-block"><?php echo Yii::t('AdminModule.user', 'Please select one of the following types:"<strong>jpg, png, gif</strong>".')?></span>
		    </div>
		  </div>
		  <div class="form-group">
		    <?php echo $form->label($model, 'description', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.user', 'Self Description')))?>
		    <div class="col-md-10">
		      <?php echo $form->textField($model, 'description', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.user', 'Self Description')))?>
		      <?php echo $form->error($model, 'description', array('class' => 'alert alert-danger'))?>
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
<?php 
$bootstrapUrl = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.todc-bootstrap'));
Yii::app()->clientScript->registerScriptFile($bootstrapUrl . '/js/bootstrap-filestyle.min.js', CClientScript::POS_END);
$script = <<<SCRIPT
$(function() {
	$('#User_name').bind('change keyup', function() {
		$('#User_nickname').val($(this).val());
	});
})
SCRIPT;
Yii::app()->clientScript->registerScript('name', $script, CClientScript::POS_END);
?>