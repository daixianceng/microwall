<div class="list-group left-list">
        	<a class="list-group-item" data-toggle="collapse" href="#submenu-1"><span class="glyphicon glyphicon-home glyphicon-rm10"></span><?php echo Yii::t('AdminModule.global', 'Home')?></a>
        	<div class="submenu panel-collapse collapse in" id="submenu-1">
        		<a class="list-group-item<?php echo $this->menu === 'default' ? ' active' : '';?>" href="<?php echo $this->createUrl('default/index');?>"><?php echo Yii::t('AdminModule.global', 'Dashboard')?></a>
				<a class="list-group-item<?php echo $this->menu === 'setting' ? ' active' : '';?>" href="<?php echo $this->createUrl('default/setting');?>"><?php echo Yii::t('AdminModule.global', 'Setting')?></a>
			</div>
        	<a class="list-group-item" data-toggle="collapse" href="#submenu-2"><span class="glyphicon glyphicon-align-center glyphicon-rm10"></span><?php echo Yii::t('AdminModule.global', 'Article')?></a>
        	<div class="submenu panel-collapse collapse in" id="submenu-2">
        		<a class="list-group-item<?php echo $this->menu === 'post' ? ' active' : '';?>" href="<?php echo $this->createUrl('post/index');?>"><?php echo Yii::t('AdminModule.global', 'All')?></a>
				<a class="list-group-item<?php echo $this->menu === 'writing' ? ' active' : '';?>" href="<?php echo $this->createUrl('post/writing');?>"><?php echo Yii::t('AdminModule.global', 'Writing')?></a>
				<a class="list-group-item<?php echo $this->menu === 'categories' ? ' active' : '';?>" href="<?php echo $this->createUrl('post/categories');?>"><?php echo Yii::t('AdminModule.global', 'Category')?></a>
			</div>
        	<a class="list-group-item" data-toggle="collapse" href="#submenu-3"><span class="glyphicon glyphicon-comment glyphicon-rm10"></span><?php echo Yii::t('AdminModule.global', 'Comment')?></a>
        	<div class="submenu panel-collapse collapse in" id="submenu-3">
        		<a class="list-group-item<?php echo $this->menu === 'comment' ? ' active' : '';?>" href="<?php echo $this->createUrl('comment/index');?>"><?php echo Yii::t('AdminModule.global', 'All')?></a>
			</div>
			<a class="list-group-item" data-toggle="collapse" href="#submenu-4"><span class="glyphicon glyphicon-file glyphicon-rm10"></span><?php echo Yii::t('AdminModule.global', 'Page')?></a>
        	<div class="submenu panel-collapse collapse in" id="submenu-4">
        		<a class="list-group-item<?php echo $this->menu === 'pages' ? ' active' : '';?>" href="<?php echo $this->createUrl('page/index');?>"><?php echo Yii::t('AdminModule.global', 'All')?></a>
        		<a class="list-group-item<?php echo $this->menu === 'page' ? ' active' : '';?>" href="<?php echo $this->createUrl('page/add');?>"><?php echo Yii::t('AdminModule.global', 'Add')?></a>
			</div>
			<a class="list-group-item" data-toggle="collapse" href="#submenu-5"><span class="glyphicon glyphicon-user glyphicon-rm10"></span><?php echo Yii::t('AdminModule.global', 'User')?></a>
        	<div class="submenu panel-collapse collapse in" id="submenu-5">
        		<a class="list-group-item<?php echo $this->menu === 'user' ? ' active' : '';?>" href="<?php echo $this->createUrl('user/index');?>"><?php echo Yii::t('AdminModule.global', 'All')?></a>
        		<a class="list-group-item<?php echo $this->menu === 'new' ? ' active' : '';?>" href="<?php echo $this->createUrl('user/new');?>"><?php echo Yii::t('AdminModule.global', 'Add')?></a>
        		<a class="list-group-item<?php echo $this->menu === 'profile' ? ' active' : '';?>" href="<?php echo $this->createUrl('user/edit', array('id' => Yii::app()->user->id));?>"><?php echo Yii::t('AdminModule.global', 'Profile')?></a>
			</div>
        </div>