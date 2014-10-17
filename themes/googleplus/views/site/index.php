<?php foreach ($this->posts as $post) :?>
        <div class="article panel panel-default article-wide">
            <div class="article-img">
                <div class="article-title">
                    <div class="article-cate"><?php echo htmlspecialchars($post->getRelated('category')->name)?><i class="cate-arrow"></i></div>
                    <h2><a class="post-link" href="<?php echo $this->createUrl('post', array('slug' => $post->slug))?>" data-id="<?php echo $post->id?>"><?php echo htmlspecialchars($post->title)?></a></h2>
                </div>
                <div class="article-img-obj">
                    <a class="post-link" href="<?php echo $this->createUrl('post', array('slug' => $post->slug))?>" data-id="<?php echo $post->id?>"><img src="<?php echo Yii::app()->baseUrl?>/media/pic/<?php echo $post->pic?>"></a>
                </div>
            </div>
            <div class="article-intro">
                <div class="article-intro-author">
                    <div class="author-portrait"><img width="70" height="70" class="img-circle" src="<?php echo Yii::app()->baseUrl?>/media/avatar/<?php echo $post->getRelated('author')->avatar?>"></div>
                    <div class="author-info">
                        <div class="author-name"><?php echo htmlspecialchars($post->getRelated('author')->nickname)?></div>
                        <div class="author-date">Posted on <?php echo date('M d, Y', strtotime($post->date_publish))?></div>
                    </div>
                </div>
                <div class="article-intro-main"><?php echo htmlspecialchars($post->description)?></div>
            </div>
        </div>
        <?php endforeach;?>