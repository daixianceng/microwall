            <div class="row">
              <div class="col-md-4">
                <div class="panel panel-success">
                  <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t('AdminModule.default', 'Overview')?></h3>
                  </div>
                  <div class="panel-body">
                    <table class="table table-hover">
                    	<tr><td class="col-md-4"><a href="<?php echo $this->createUrl('post/index')?>"><?php echo Yii::t('AdminModule.default', 'Articles')?></a></td><td class="col-md-8"><?php echo Post::getCount(Post::STATUS_PUBLISHED);?></td></tr>
                    	<tr><td><a href="<?php echo $this->createUrl('post/archived')?>"><?php echo Yii::t('AdminModule.default', 'Drafts')?></a></td><td><?php echo Post::getCount(Post::STATUS_ARCHIVED);?></td></tr>
                    	<tr><td><a href="<?php echo $this->createUrl('post/recycled')?>"><?php echo Yii::t('AdminModule.default', 'Trash')?></a></td><td><?php echo Post::getCount(Post::STATUS_RECYCLED);?></td></tr>
                    	<tr><td><a href="<?php echo $this->createUrl('comment/index')?>"><?php echo Yii::t('AdminModule.default', 'Comments')?></a></td><td><?php echo Comment::model()->count();?></td></tr>
					</table>
                  </div>
                </div>
                <div class="panel panel-success">
                  <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t('AdminModule.default', 'System')?></h3>
                  </div>
                  <div class="panel-body">
                    <table class="table table-hover">
                    	<tr><td class="col-md-4"><?php echo Yii::t('AdminModule.default', 'Server')?></td><td class="col-md-8"><?php echo PHP_OS?></td></tr>
                    	<tr><td><?php echo Yii::t('AdminModule.default', 'Software')?></td><td><?php echo $_SERVER['SERVER_SOFTWARE']?></td></tr>
                    	<tr><td>PHP</td><td><?php echo phpversion();?></td></tr>
                    	<tr><td><?php echo Yii::t('AdminModule.default', 'Database')?></td><td><?php echo ucfirst(Yii::app()->db->driverName)?> <?php echo Yii::app()->db->serverVersion?></td></tr>
					</table>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="panel panel-success">
                  <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t('AdminModule.default', 'Latest Articles')?></h3>
                  </div>
                  <div class="panel-body">
                    <table class="table table-hover">
                    	<?php foreach ($recentlyPosts as $post) : static $var = 1?>
                    	<tr><td class="col-md-1"><?php echo $var ++?></td><td class="col-md-11"><a target="_blank" href="<?php echo Yii::app()->createUrl('site/post', array('slug' => $post->slug))?>"><?php echo $post->title?></a></td></tr>
                    	<?php endforeach;?>
					</table>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="panel panel-success">
                  <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t('AdminModule.default', 'Latest Comments')?></h3>
                  </div>
                  <div class="panel-body">
                    <table class="table table-hover">
                    	<?php foreach ($recentlyComments as $comment) :?>
                    	<tr>
                    		<td class="col-md-11"><?php echo htmlspecialchars($comment->content)?></td>
                    		<?php if (empty($comment->website)) :?>
                    		<td class="col-md-1"><?php echo htmlspecialchars($comment->name)?></td>
                    		<?php else :?>
                    		<td class="col-md-1"><a target="_blank" href="<?php echo $comment->website?>"><?php echo htmlspecialchars($comment->name)?></a></td>
                    		<?php endif;?>
                    	</tr>
                    	<?php endforeach;?>
                    </table>
                  </div>
                </div>
              </div>
            </div>