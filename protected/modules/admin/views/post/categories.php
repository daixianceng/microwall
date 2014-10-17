<div class="row">
	<div class="col-md-12 content-section"><a class="btn btn-primary the_tooltip<?php echo Yii::app()->user->checkAccess('createCategory') ? '' : ' disabled'?>" data-toggle="tooltip" data-placement="right" title="Create new category" href="<?php echo $this->createUrl('categoryAdd');?>"><span class="glyphicon glyphicon-plus glyphicon-rm10"></span><?php echo Yii::t('AdminModule.post', 'Add Category')?></a></div>
	<div class="col-md-12">
	<?php if (empty($categories)) :?>
	<p class="text-center">No Categories.</p>
    <?php else :?>
    <table class="table table-striped sorted_table">
      <thead>
        <tr>
          <th class="col-md-1">#</th>
          <th class="col-md-2"><?php echo Yii::t('AdminModule.post', 'Name')?></th>
          <th class="col-md-2"><?php echo Yii::t('AdminModule.post', 'URL Index')?></th>
          <th class="col-md-4"><?php echo Yii::t('AdminModule.post', 'Comment')?></th>
          <th class="col-md-3"><?php echo Yii::t('AdminModule.post', 'Modify')?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($categories as $key => $category) :?>
        <tr class="delete-row draggable" data-id="<?php echo $category->id?>">
          <td class="col-md-1"><?php echo $key + 1;?></td>
          <td class="col-md-2"><a target="_blank" href="<?php echo Yii::app()->createUrl('site/category', array('slug' => $category->slug))?>"><?php echo $category->name?></a></td>
          <td class="col-md-2"><?php echo $category->slug?></td>
          <td class="col-md-4"><?php echo htmlspecialchars($category->comment)?></td>
          <td class="col-md-3">
          	<a class="btn btn-success<?php echo Yii::app()->user->checkAccess('editCategory') ? '' : ' disabled'?>" href="<?php echo $this->createUrl('categoryEdit', array('id' => $category->id))?>"><span class="glyphicon glyphicon-pencil glyphicon-rm10"></span><?php echo Yii::t('AdminModule.global', 'Edit')?></a>
          	<a class="btn btn-danger delete-button<?php echo Yii::app()->user->checkAccess('removeCategory') ? '' : ' disabled'?>" data-toggle="modal" data-target=".modal" href="<?php echo $this->createUrl('categoryRemove', array('id' => $category->id))?>"><span class="glyphicon glyphicon-trash glyphicon-rm10"></span><?php echo Yii::t('AdminModule.global', 'Remove')?></a>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php endif;?>
    </div>
</div>
<?php 
$this->renderPartial('/layouts/_modal');
if (Yii::app()->user->checkAccess('editCategory'))
	$this->renderPartial('/layouts/_sortable', array('url' => $this->createUrl('categorySort')));
?>