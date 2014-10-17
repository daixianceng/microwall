<div id="content-hidden">
	<h1><?php echo htmlspecialchars($post->title)?></h1>
	<img alt="<?php echo htmlspecialchars($post->title)?>" src="<?php echo Yii::app()->baseUrl?>/media/pic/<?php echo $post->pic?>">
	<article><?php echo $post->content?></article>
</div>
<?php
$displayScript = <<<SCRIPT
$(function() {
	var post = new Post('{$post->id}');
	post.display();
})
SCRIPT;
Yii::app()->clientScript->registerScript('displayScript', $displayScript, CClientScript::POS_END);
$this->renderPartial('index');