    <div class="col-md-12">
	<div class="dropdown content-section">
	  <?php $this->renderPartial('_category-dropdown', array('categories' => $categories, 'currentCategory' => $currentCategory))?>
	</div>
    <?php if (empty($posts)) :?>
	<p class="text-center">No Articles.</p>
	<?php else :?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th class="col-md-1">#</th>
          <th class="col-md-4"><?php echo Yii::t('AdminModule.post', 'Title')?></th>
          <th class="col-md-2"><?php echo Yii::t('AdminModule.post', 'Author')?></th>
          <th class="col-md-2"><?php echo Yii::t('AdminModule.post', 'Last Updated')?></th>
          <th class="col-md-3"><?php echo Yii::t('AdminModule.post', 'Modify')?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($posts as $post) : static $var = 0;?>
        <tr>
          <td><?php echo $pages->offset + ($var += 1);?></td>
          <td><?php echo htmlspecialchars($post->title)?></td>
          <td><?php echo $post->getRelated('author')->nickname?></td>
          <td><?php echo $post->date_update?></td>
          <td>
          	<a class="btn btn-success<?php echo Yii::app()->user->checkAccess('editPost', array('userId' => $post->author)) ? '' : ' disabled'?>" href="<?php echo $this->createUrl('edit', array('id' => $post->id))?>"><span class="glyphicon glyphicon-pencil glyphicon-rm10"></span><?php echo Yii::t('AdminModule.global', 'Edit')?></a>
          	<a class="btn btn-danger<?php echo Yii::app()->user->checkAccess('recyclePost', array('userId' => $post->author)) ? '' : ' disabled'?>" href="<?php echo $this->createUrl('recycle', array('id' => $post->id))?>"><span class="glyphicon glyphicon-trash glyphicon-rm10"></span><?php echo Yii::t('AdminModule.global', 'Trash')?></a>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php endif;?>
    </div>
    <div class="col-md-12">
    <?php $this->renderPartial('/layouts/_pagination', array('pages' => $pages))?>
   </div>