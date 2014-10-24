<?php $this->beginContent('/layouts/main'); ?>
<nav id="top" class="navbar navbar-toolbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="http://microwall.cn" target="_blank">Microwall</a>
        </div>

        <div class="collapse navbar-collapse" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <li<?php echo $this->menu === 'default' ? ' class="active"' : '';?>><a href="<?php echo $this->createUrl('default/index');?>"><?php echo Yii::t('AdminModule.global', 'Dashboard')?></a></li>
            <li<?php echo $this->menu === 'writing' ? ' class="active"' : '';?>><a href="<?php echo $this->createUrl('post/writing');?>"><?php echo Yii::t('AdminModule.global', 'Fast Writing')?></a></li>
            <li><a target="_blank" href="<?php echo Yii::app()->getBaseUrl(true);?>"><?php echo Yii::t('AdminModule.global', 'Website')?></a></li>
          </ul>
          <ul class="nav navbar-nav pull-right">
            <li><a href="<?php echo $this->createUrl('default/logout');?>"><?php echo Yii::t('AdminModule.global', 'Logout')?></a></li>
          </ul>
          <p class="navbar-text pull-right">Signed in as <a href="<?php echo $this->createUrl('user/edit', array('id' => Yii::app()->user->id))?>" class="navbar-link"><?php echo Yii::app()->user->nickname;?></a></p>
        </div>
      </div>
    </nav>
    <div class="container-fluid">
        <?php $this->renderPartial('/layouts/_list2');?>
        <div id="content">
        	<h2 class="content-header"><?php echo $this->pageTitle?><small><?php echo $this->subTitle?></small></h2>
            <?php echo $content;?>
        </div>
    </div>
<?php $this->endContent();?>