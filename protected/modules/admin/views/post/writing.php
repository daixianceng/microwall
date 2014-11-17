<div class="row">
	<div class="col-md-10">
		<?php $form = $this->beginWidget('CActiveForm', array(
			'id'=>'Post',
			'enableClientValidation'=>false,
			'clientOptions' => array(
					'validateOnSubmit' => false,
					'validateOnChange' => false,
					'errorCssClass' => 'has-error',
					'successCssClass' => 'has-success'
			),
			'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
			)
		));?>
		  <?php if (empty($categories)) :?>
		  <div class="form-group">
		    <div class="col-md-10 col-md-offset-2">
		      <div class="alert alert-warning">
		      	<span class="glyphicon glyphicon-warning-sign glyphicon-rm10"></span>
		      	<?php echo Yii::t('AdminModule.post', 'The category must be added before writing, ')?><a class="alert-link" href="<?php echo $this->createUrl('categoryAdd')?>"><?php echo Yii::t('AdminModule.post', 'click here.')?></a>
		      </div>
		    </div>
		  </div>
		  <?php endif;?>
		  <?php $this->renderPartial('/layouts/_flash-form', array('length' => 10))?>
		  <div class="form-group">
		    <?php echo $form->label($model, 'title', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.post', 'Title')))?>
		    <div class="col-md-10">
		      <?php echo $form->textField($model, 'title', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.post', 'Title')))?>
		      <?php echo $form->error($model, 'title', array('class' => 'alert alert-danger'))?>
		    </div>
		  </div>
		  <div class="form-group">
		    <?php echo $form->label($model, 'slug', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.post', 'URL Index')))?>
		    <div class="col-md-10">
		      <?php echo $form->textField($model, 'slug', array('class' => 'form-control', 'placeholder' => Yii::t('AdminModule.post', 'URL Index')))?>
		      <?php echo $form->error($model, 'slug', array('class' => 'alert alert-danger'))?>
		      <span class="help-block"><?php echo Yii::t('AdminModule.post', 'Displayed in the URL, such as:"http://www.example.com/post/<mark>article-name</mark>".')?></span>
		    </div>
		  </div>
		  <div class="form-group">
		    <?php echo $form->label($model, 'category', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.post', 'Category')))?>
		    <div class="col-md-10">
			  <?php echo $form->dropDownList($model, 'category', $categories, array('class' => 'form-control'))?>
		      <?php echo $form->error($model, 'category', array('class' => 'alert alert-danger'))?>
		    </div>
		  </div>
		  <div class="form-group">
		    <?php echo $form->label($model, 'pic', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.post', 'Picture')))?>
		    <div class="col-md-5">
		      <?php if (!$model->isNewRecord && !empty($model->pic)) :?>
		      <img class="img-thumbnail content-section" alt="The picture" src="<?php echo Yii::app()->baseUrl?>/media/pic/min_<?php echo $model->pic?>">
		      <?php endif;?>
		      <?php echo $form->fileField($model, 'pic', array('class' => 'filestyle', 'data-iconName' => 'glyphicon-picture', 'data-buttonText' => 'Choose image', 'data-buttonName' => 'btn-primary'))?>
		      <?php echo $form->error($model, 'pic', array('class' => 'alert alert-danger'))?>
		      <span class="help-block"><?php echo Yii::t('AdminModule.post', 'Please select one of the following types:"<strong>jpg, png, gif</strong>".')?></span>
		    </div>
		  </div>
		  <div class="form-group">
		    <?php echo $form->label($model, 'description', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.post', 'Description')))?>
		    <div class="col-md-10">
		      <?php echo $form->textArea($model, 'description', array('class' => 'form-control', 'rows' => '4'))?>
		      <?php echo $form->error($model, 'description', array('class' => 'alert alert-danger'))?>
		    </div>
		  </div>
		  <div class="form-group">
		  	<?php echo $form->label($model, 'content', array('class' => 'col-md-2 control-label', 'label' => Yii::t('AdminModule.post', 'Content')))?>
		    <div class="col-md-10">
		      <?php echo $form->textArea($model, 'content', array('id' => 'article_content'))?>
		      <?php echo $form->error($model, 'content', array('class' => 'alert alert-danger'))?>
		    </div>
		  </div>
		  <div class="form-group">
		    <div class="col-md-offset-2 col-md-10">
		      <?php echo $form->hiddenField($model, 'status', array('value' => Post::STATUS_PUBLISHED))?>
		      <button type="submit" class="btn btn-primary" id="button_publish"><span class="glyphicon glyphicon-open glyphicon-rm10"></span><?php echo Yii::t('AdminModule.post', 'Publish')?></button>
		      <button type="submit" class="btn btn-default" id="button_archive"><span class="glyphicon glyphicon-save glyphicon-rm10"></span><?php echo Yii::t('AdminModule.post', 'Save Draft')?></button>
		    </div>
		  </div>
		  <?php $this->endWidget();?>
	</div>
</div>
<?php 
$this->widget('ext.ueditor.UeditorWidget', array(
		'id'=>'article_content',//页面中输入框（或其他初始化容器）的ID
		'name'=>'editor',//指定ueditor实例的名称,个页面有多个ueditor实例时使用
		'options' => <<<TOOLBAR
    toolbars:[['fullscreen','source','undo','redo','|','customstyle','paragraph','fontfamily','fontsize','insertcode'],
        ['bold','italic','underline','fontborder','strikethrough','superscript','subscript','autotypeset','blockquote','pasteplain','|','forecolor','backcolor','insertorderedlist','insertunorderedlist','|','|','indent','|'],
        ['justifyleft','justifycenter','justifyright','justifyjustify','|','link','unlink','|','insertimage','scrawl','|','horizontal','inserttable','|','preview','searchreplace','help']]
TOOLBAR
	)
);

$statusPublish = Post::STATUS_PUBLISHED;
$statusArchive = Post::STATUS_ARCHIVED;
$script = <<<STATUS
$(function() {
	$('#button_publish').bind('click', function() {
		$('#Post_status').val('{$statusPublish}');
	});
	$('#button_archive').bind('click', function() {
		$('#Post_status').val('{$statusArchive}');
	});
})
STATUS;
$bootstrapUrl = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.todc-bootstrap'));
Yii::app()->clientScript->registerScriptFile($bootstrapUrl . '/js/bootstrap-filestyle.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('status', $script, CClientScript::POS_END);
?>