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
	<?php $this->renderPartial('/layouts/_flash-form', array('length' => 10))?>
	  <div class="form-group">
	    <?php echo $form->label($model, 'title', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.page', 'Title')))?>
	    <div class="col-md-10">
	      <?php echo $form->textField($model, 'title', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.page', 'Title')))?>
	      <?php echo $form->error($model, 'title', array('class' => 'alert alert-danger'))?>
	    </div>
	  </div>
	  <div class="form-group">
	    <?php echo $form->label($model, 'slug', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.page', 'URL Index')))?>
	    <div class="col-md-10">
	      <?php echo $form->textField($model, 'slug', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.page', 'URL Index')))?>
	      <?php echo $form->error($model, 'slug', array('class' => 'alert alert-danger'))?>
	      <span class="help-block"><?php echo Yii::t('AdminModule.page', 'Displayed in the URL, such as:"http://www.example.com/page/<mark>page-name</mark>".')?></span>
	    </div>
	  </div>
	  <div class="form-group">
	  	<?php echo $form->label($model, 'content', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.page', 'Content')))?>
	    <div class="col-md-10">
	      <?php echo $form->textArea($model, 'content', array('id' => 'page_content'))?>
	      <?php echo $form->error($model, 'content', array('class' => 'alert alert-danger'))?>
	    </div>
	  </div>
	  <div class="form-group">
	    <div class="col-md-offset-2 col-md-10">
	      <?php echo $form->hiddenField($model, 'status', array('value' => Page::STATUS_PUBLISHED))?>
	      <button type="submit" class="btn btn-primary" id="button_publish"><span class="glyphicon glyphicon-open glyphicon-rm10"></span><?php echo $model->isNewRecord ? Yii::t('AdminModule.page', 'Publish') : Yii::t('AdminModule.page', 'Save and Publish')?></button>
	      <button type="submit" class="btn btn-default" id="button_archive"><span class="glyphicon glyphicon-save glyphicon-rm10"></span><?php echo Yii::t('AdminModule.page', 'Save Draft')?></button>
	    </div>
	  </div>
	  <?php $this->endWidget();?>
</div>
<?php
$this->widget('ext.ueditor.UeditorWidget', array(
		'id'=>'page_content',//页面中输入框（或其他初始化容器）的ID
		'name'=>'editor',//指定ueditor实例的名称,个页面有多个ueditor实例时使用
		'options' => <<<TOOLBAR
    toolbars:[['fullscreen','source','undo','redo','|','customstyle','paragraph','fontfamily','fontsize','insertcode'],
        ['bold','italic','underline','fontborder','strikethrough','superscript','subscript','autotypeset','blockquote','pasteplain','|','forecolor','backcolor','insertorderedlist','insertunorderedlist','|','|','indent','|'],
        ['justifyleft','justifycenter','justifyright','justifyjustify','|','link','unlink','|','insertimage','scrawl','|','horizontal','inserttable','|','preview','searchreplace','help']]
TOOLBAR
	)
);

$statusPublish = Page::STATUS_PUBLISHED;
$statusArchive = Page::STATUS_ARCHIVED;
$script = <<<STATUS
$(function() {
	$('#button_publish').bind('click', function() {
		$('#Page_status').val('{$statusPublish}');
	});
	$('#button_archive').bind('click', function() {
		$('#Page_status').val('{$statusArchive}');
	});
})
STATUS;
Yii::app()->clientScript->registerScript('status', $script, CClientScript::POS_END);
?>