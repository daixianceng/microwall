<?php if (Yii::app()->user->hasFlash('success')) :?>
<div class="alert alert-success">
<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
<strong>Success!</strong> <?php echo Yii::app()->user->getFlash('success')?>
</div>
<?php elseif (Yii::app()->user->hasFlash('error')) :?>
<div class="alert alert-danger">
<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
<strong>Failed!</strong> <?php echo Yii::app()->user->getFlash('error')?>
</div>
<?php endif;?>