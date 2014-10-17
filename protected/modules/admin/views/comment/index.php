<div class="row">
	<div class="col-md-12">
	<?php if (empty($comments)) :?>
	<p class="text-center">No Comments.</p>
    <?php else :?>
    <div class="panel-group" id="accordion">
      <?php foreach ($comments as $comment) :static $var = 0;?>
	  <div class="panel panel-default">
	    <div class="panel-heading">
	      <h4 class="panel-title">
	        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $comment->id?>">
	          <?php echo htmlspecialchars($comment->name)?>
	        </a>
	      </h4>
	    </div>
	    <div id="collapse<?php echo $comment->id?>" class="panel-collapse collapse">
	      <div class="panel-body row delete-row">
	      	<div class="col-md-12 content-section">
	      		<div class="col-md-3"><strong><?php echo Yii::t('AdminModule.comment', 'Email:')?> </strong><?php echo $comment->mail?></div>
	      		<div class="col-md-2"><strong><?php echo Yii::t('AdminModule.comment', 'Date:')?> </strong><?php echo $comment->date?></div>
		      	<div class="col-md-3 col-md-offset-4">
		      		<a class="btn btn-success" target="_blank" href="<?php echo Yii::app()->createUrl('site/post', array('slug' => $comment->getRelated('post')->slug))?>"><span class="glyphicon glyphicon-eye-open glyphicon-rm10"></span><?php echo Yii::t('AdminModule.comment', 'View')?></a>
	          		<a class="btn btn-danger delete-button<?php echo Yii::app()->user->checkAccess('removeComment') ? '' : ' disabled'?>" data-toggle="modal" data-target=".modal" href="<?php echo $this->createUrl('remove', array('id' => $comment->id))?>"><span class="glyphicon glyphicon-trash glyphicon-rm10"></span><?php echo Yii::t('AdminModule.global', 'Remove')?></a>
		      	</div>
	      	</div>
	      	<div class="col-md-12"><div class="well well-lg"><?php echo htmlspecialchars($comment->content)?></div></div>
	      </div>
	    </div>
	  </div>
	  <?php endforeach;?>
	</div>
    <?php endif;?>
    </div>
    <div class="col-md-12">
    <?php $this->renderPartial('/layouts/_pagination', array('pages' => $pages))?>
    </div>
</div>
<?php $this->renderPartial('/layouts/_modal')?>