<div class="row">
	<div class="col-md-12 content-section"><a class="btn btn-primary the_tooltip<?php echo Yii::app()->user->checkAccess('openCreateUser') ? '' : ' disabled'?>" data-toggle="tooltip" data-placement="right" title="Create new user" href="<?php echo $this->createUrl('new');?>"><span class="glyphicon glyphicon-plus glyphicon-rm10"></span><?php echo Yii::t('AdminModule.user', 'Add User')?></a></div>
	<div class="col-md-12">
    <table class="table table-striped">
      <thead>
        <tr>
          <th class="col-md-1">#</th>
          <th class="col-md-2"><?php echo Yii::t('AdminModule.user', 'Username')?></th>
          <th class="col-md-2"><?php echo Yii::t('AdminModule.user', 'Nickname')?></th>
          <th class="col-md-4"><?php echo Yii::t('AdminModule.user', 'Self Description')?></th>
          <th class="col-md-3"><?php echo Yii::t('AdminModule.user', 'Modify')?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user) : static $var = 1;$role = key(Yii::app()->authManager->getAuthAssignments($user->id));?>
        <tr class="delete-row">
          <td><?php echo $var++;?></td>
          <td><a href="<?php echo Yii::app()->createUrl('')?>"><?php echo $user->name?></a></td>
          <td><?php echo $user->nickname?></td>
          <td><?php echo htmlspecialchars($user->description)?></td>
          <td>
          	<a class="btn btn-success<?php echo Yii::app()->user->checkAccess('editUser', array('userId' => $user->id, 'role' => $role)) ? '' : ' disabled'?>" href="<?php echo $this->createUrl('edit', array('id' => $user->id))?>"><span class="glyphicon glyphicon-pencil glyphicon-rm10"></span><?php echo Yii::t('AdminModule.global', 'Edit')?></a>
          	<a class="btn btn-danger delete-button<?php echo Yii::app()->user->checkAccess('removeUser', array('userId' => $user->id, 'role' => $role)) ? '' : ' disabled'?>" data-toggle="modal" data-target=".modal" href="<?php echo $this->createUrl('remove', array('id' => $user->id))?>"><span class="glyphicon glyphicon-trash glyphicon-rm10"></span><?php echo Yii::t('AdminModule.global', 'Remove')?></a>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    </div>
</div>
<?php $this->renderPartial('/layouts/_modal')?>