<style>
body {
	background:#f8f8f8;
}
</style>
<div id="login-container">
<div class="logo"><img alt="Microwall" src="<?php echo $this->module->getAssetsUrl() . '/images/microwall.png'?>"></div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'LoginForm',
    'enableClientValidation'=>true,
	'clientOptions' => array(
		'validateOnSubmit' => true,
		'validateOnChange' => false,
		'errorCssClass' => 'has-error',
		'successCssClass' => 'has-success'
	),
	'htmlOptions' => array(
		'class' => 'form-horizontal'
	)
)); ?>
  <div class="form-group">
    <?php echo $form->label($model,'username', array('class' => 'col-md-3 control-label', 'label' => Yii::t('AdminModule.access', 'Username'))); ?>
    <div class="col-md-9 has-feedback">
      <?php echo $form->textField($model,'username', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.access', 'Username'))) ?>
      <span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>
      <?php echo $form->error($model, 'username', array('class' => 'alert alert-danger'))?>
    </div>
  </div>
  <div class="form-group">
    <?php echo $form->label($model,'password', array('class' => 'col-md-3 control-label', 'label' => Yii::t('AdminModule.access', 'Password'))); ?>
    <div class="col-md-9 has-feedback">
      <?php echo $form->passwordField($model,'password', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.access', 'Password'))); ?>
      <span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>
      <?php echo $form->error($model, 'password', array('class' => 'alert alert-danger'))?>
    </div>
  </div>
  <div class="form-group">
    <div class="col-md-offset-3 col-md-9">
      <div class="checkbox">
        <label class="control-label">
          <?php echo $form->checkBox($model,'rememberMe'); ?> <?php echo Yii::t('AdminModule.access', 'Remember me')?>
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-md-offset-3 col-md-9">
      <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-off glyphicon-rm10"></span><?php echo Yii::t('AdminModule.access', 'Login')?></button>
    </div>
  </div>
  <div class="form-group">
    <div class="col-md-offset-3 col-md-9">
      <strong><a href="<?php echo Yii::app()->getBaseUrl(true)?>"><?php echo Yii::t('AdminModule.access', 'Back Home')?></a></strong>
    </div>
  </div>
<?php $this->endWidget(); ?>
</div>