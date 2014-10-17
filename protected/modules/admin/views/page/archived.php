	<div class="col-md-12">
	<?php if (empty($list)) :?>
	<p class="text-center">No Pages.</p>
	<?php else :?>
	<table class="table table-striped">
      <thead>
        <tr>
          <th class="col-md-1">#</th>
          <th class="col-md-4"><?php echo Yii::t('AdminModule.page', 'Title')?></th>
          <th class="col-md-2"><?php echo Yii::t('AdminModule.page', 'Type')?></th>
          <th class="col-md-2"><?php echo Yii::t('AdminModule.page', 'Last Updated')?></th>
          <th class="col-md-3"><?php echo Yii::t('AdminModule.page', 'Modify')?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($list as $key => $page) :?>
        <tr>
          <td><?php echo $key + 1;?></td>
          <td><?php echo htmlspecialchars($page->title)?></td>
          <td><?php echo $page->type === 'local' ? Yii::t('AdminModule.page', 'Local Page') : Yii::t('AdminModule.page', 'Remote Page');?></td>
          <td><?php echo $page->date_update?></td>
          <td>
          	<a class="btn btn-success<?php echo Yii::app()->user->checkAccess('editPage') ? '' : ' disabled'?>" href="<?php echo $this->createUrl('edit', array('id' => $page->id))?>"><span class="glyphicon glyphicon-pencil glyphicon-rm10"></span><?php echo Yii::t('AdminModule.global', 'Edit')?></a>
          	<a class="btn btn-danger<?php echo Yii::app()->user->checkAccess('recyclePage') ? '' : ' disabled'?>" href="<?php echo $this->createUrl('recycle', array('id' => $page->id))?>"><span class="glyphicon glyphicon-trash glyphicon-rm10"></span><?php echo Yii::t('AdminModule.global', 'Trash')?></a>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php endif;?>
    </div>