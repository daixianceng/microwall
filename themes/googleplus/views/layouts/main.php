<?php
$imgSrc = Yii::app()->baseUrl . '/media/pic/';
$postScript = <<<SCRIPT
function Post(id)
{
	this.id = id;
}
Post.prototype.display = function() {
	if (this.pending) return;
	if (this.stack[this.id]) {
		this.render();
	} else {
		this.pending = true;
		$.ajax({
			type : 'get',
			url : '{$this->createUrl('postAjax')}',
			data : 'id=' + this.id,
			dataType : 'json',
			context : this,
			success : function(json) {
				if (json.error === '200') {
					this.stack[this.id] = json;
					this.render();
				} else if (json.error === '403') {
					alert('The article has been removed!');
				}
			},
			complete : function() {
				this.pending = false;
			}
		})
	}
}
Post.prototype.render = function() {
	var data = this.stack[this.id];
	this.pannel.fadeIn(400);
	this.pannel.find('.panel-article-img').html('<img src="{$imgSrc}' + data.pic + '">');
	this.pannel.find('.panel-cate span').text(data.category);
	this.pannel.find('.panel-title h2').text(data.title);
	this.pannel.find('.panel-date i').text(data.date_publish);
	this.pannel.find('.panel-text').html(data.content);
	$('#Comment_post_id, #Comment_post_id_reply').val(this.id);
	$('.panel-comment-reply').appendTo('.panel-comments-main').css('display', 'none');
	$('.panel-comments-main .comments-group, .panel-comments-main .comments-empty').remove();
    Comment.prototype.loaded = false;
}
Post.prototype.pending = false;
Post.prototype.stack = [];
Post.prototype.pannel = $('#article-panel');
Post.prototype.eventHandle = function() {
    var id = $(this).attr('data-id');
    var post = new Post(id);
    post.display();
    return false;
}

function Comment(nickname, mail, website, content, postId, replyId, level, form)
{
	this.nickname = nickname;
	this.mail = mail;
	this.website = website;
	this.content = content;
	this.postId = parseInt(postId);
	this.replyId = parseInt(replyId);
	this.level = parseInt(level);
	this.form = form;
}
Comment.prototype.loaded = false;
Comment.prototype.pending = false;
Comment.prototype.publish = function() {
	if (this.pending) return;
	this.pending = true;
	$.ajax({
		type : 'post',
		url : '{$this->createUrl('comment')}',
		data : {
			Comment:{
				name:this.nickname,
				mail:this.mail,
				website:this.website,
				content:this.content,
				post_id:this.postId,
				reply_id:this.replyId
			},
			level:this.level
		},
		dataType : 'json',
		context : this,
		success : function(json) {
			if (json.error === '200') {
				var html = $(json.html).fadeIn();
				if (this.replyId === 0) {
					$('.panel-comments-main').append(html);
				} else {
					$('[data-comment-id=' + this.replyId + ']').append(html);
				}
				if (this.form.attr('id') === 'Comment_Reply') {
					$('.panel-comment-reply').css('display', 'none');
				}
				$('.comments-empty').remove();
				this.form.find('[name="Comment[content]"]').val('');
				$('#article-panel').animate({
					scrollTop : html.offset().top - $('#article-panel').offset().top + $('#article-panel').scrollTop()
				})
			} else if (json.error === '412') {
				alert('You are crazy!');
			}
		},
		complete : function() {
			this.pending = false;
		}
	})
}
Comment.prototype.loadData = function() {
	$.ajax({
		type : 'post',
		url : '{$this->createUrl('comments')}',
		data : 'postId=' + $('#Comment_post_id').val(),
		success : function(data) {
			$('.panel-comments-main').append(data);
		}
	})
}
Comment.prototype.afterValidate = function(form, data, hasError) {
	if (hasError) return;
	var nickname = form.find('[name="Comment[name]"]').val();
	var mail = form.find('[name="Comment[mail]"]').val();
	var website = form.find('[name="Comment[website]"]').val();
	var content = form.find('[name="Comment[content]"]').val();
	var postId = form.find('[name="Comment[post_id]"]').val();
	var replyId = form.find('[name="Comment[reply_id]"]').val();
	var level = form.find('[name=level]').val();
	var comment = new Comment(nickname, mail, website, content, postId, replyId, level, form);
	comment.publish();
	return false;
}

$(function() {
	$('.post-link').bind('click', Post.prototype.eventHandle);
	
    var pending = false;
    var count = $('.article').length;
    var noPost = false;
	$(window).bind('scroll', function(e) {
		if (pending || noPost) return;
		if ($(document).height() - $(this).scrollTop() - $(this).height() < 300) {
			pending = true;
			$.ajax({
				type : 'get',
				url : '{$this->createUrl('ajax')}',
				data : 'category=' + '{$this->currCategory}' + '&start=' + count,
				dataType : 'json',
				success : function(json) {
					if (json.count == 0) {
						noPost = true;
						return;
					}
					$('#container').append(json.html);
					$('.post-link').unbind('click', Post.prototype.eventHandle);
					$('.post-link').bind('click', Post.prototype.eventHandle);
					$(window).resize();
					count += json.count;
				},
				complete : function() {
					pending = false;
				}
			})
		}
	})
})
SCRIPT;
Yii::app()->clientScript->registerScript('postScript', $postScript, CClientScript::POS_END);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="<?php echo Yii::app()->charset?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="<?php echo Yii::app()->params['keywords']?>">
    <meta name="description" content="<?php echo Yii::app()->params['description']?>">
    <title><?php echo empty($this->pageTitle) ? '' : $this->pageTitle . ' | '?><?php echo Yii::app()->name?></title>
    <link rel="icon" href="<?php echo Yii::app()->baseUrl?>/favicon.ico" type="image/x-icon">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="scrollable">
    <div id="top">
        <div class="logo"><img src="<?php echo Yii::app()->theme->baseUrl?>/images/microwall.png"></div>
    </div>
    <div id="menu-container">
        <div id="menu">
            <div class="menu-logo"><a href="<?php echo Yii::app()->baseUrl?>"><img src="<?php echo Yii::app()->theme->baseUrl?>/images/microwall-small.png"></a></div>
            <ul>
                <li<?php echo $this->currCategory === 'all' ? ' class="menu-active"' : ''?>><a href="<?php echo $this->createUrl('index')?>">All</a></li>
                <?php foreach ($this->menu as $menu) :?>
                <li<?php echo $this->currCategory === $menu->id ? ' class="menu-active"' : ''?>><a href="<?php echo $this->createUrl('category', array('slug' => $menu->slug))?>"><?php echo htmlspecialchars($menu->name)?></a></li>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
    <div id="right" class="scrollable">
        <div class="right-section">
            <form action="<?php echo $this->createUrl('search')?>" method="get" target="_blank">
                <input type="text" name="w" class="form-control" placeholder="search...">
            </form>
        </div>
        <div class="right-line"></div>
        <div class="right-section">
            <h3>Popular</h3>
            <ul>
            	<?php foreach ($this->popular as $popular) :?>
                <li><a class="post-link" href="<?php $this->createUrl('post', array('slug' => $popular->slug))?>" data-id="<?php echo $popular->id?>" data-container="body" data-toggle="popover" data-placement="left" data-content="<?php echo htmlspecialchars($popular->title)?>"><img src="<?php echo Yii::app()->baseUrl?>/media/pic/min_<?php echo $popular->pic?>" alt=""></a></li>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
    <div id="container">
    	<?php echo $content;?>
    </div>
    <div id="article-panel" class="scrollable">
        <div class="panel-top">
            <div class="panel-button"><button type="button" class="close" id="article-panel-close"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button></div>
            <div class="panel-cate"><span></span><i class="cate-arrow"></i></div>
            <div class="panel-title">
                <h2></h2>
                <div class="panel-date">Posted on <i></i></div>
            </div>
        </div>
        <div class="panel-main">
            <div class="panel-article">
                <div class="panel-article-img"></div>
                <div class="panel-text"></div>
            </div>
            <div class="panel-comment">
                <?php $form = $this->beginWidget('CActiveForm', array(
					'id'=>'Comment',
                	'action' => $this->createUrl('comment'),
				    'enableClientValidation'=>true,
					'clientOptions' => array(
						'validateOnSubmit' => true,
						'validateOnChange' => false,
						'errorCssClass' => 'has-error',
						'successCssClass' => 'has-success',
						'afterValidate' => 'js:Comment.prototype.afterValidate'
					),
					'htmlOptions' => array(
						'class' => 'form-horizontal',
					)
				));
                $visitor = Yii::app()->request->cookies->contains('visitor') ? unserialize(Yii::app()->request->cookies['visitor']->value) : array()
				?>
                  <div class="form-group">
                    <?php echo $form->label($this->model, 'name', array('class' => 'col-md-2 control-label', 'label' => 'Nickname'))?>
                    <div class="col-md-4">
                      <?php echo $form->textField($this->model, 'name', array('class' => 'form-control', 'placeholder' => 'Nickname', 'value' => isset($visitor['n']) ? $visitor['n'] : ''))?>
                      <?php echo $form->error($this->model, 'name', array('class' => 'alert alert-danger'))?>
                    </div>
                  </div>
                  <div class="form-group">
                    <?php echo $form->label($this->model, 'mail', array('class' => 'col-md-2 control-label', 'label' => 'Email'))?>
                    <div class="col-md-4">
                      <?php echo $form->emailField($this->model, 'mail', array('class' => 'form-control', 'placeholder' => 'Email', 'value' => isset($visitor['m']) ? $visitor['m'] : ''))?>
                      <?php echo $form->error($this->model, 'mail', array('class' => 'alert alert-danger'))?>
                    </div>
                  </div>
                  <div class="form-group">
                    <?php echo $form->label($this->model, 'website', array('class' => 'col-md-2 control-label', 'label' => 'Website'))?>
                    <div class="col-md-4">
                      <?php echo $form->textField($this->model, 'website', array('class' => 'form-control', 'placeholder' => 'Website', 'value' => isset($visitor['w']) ? $visitor['w'] : ''))?>
                      <?php echo $form->error($this->model, 'website', array('class' => 'alert alert-danger'))?>
                    </div>
                  </div>
                  <div class="form-group">
                    <?php echo $form->label($this->model, 'content', array('class' => 'col-md-2 control-label', 'label' => 'What\'s up?'))?>
                    <div class="col-md-10">
                      <?php echo $form->textArea($this->model, 'content', array('class' => 'form-control', 'placeholder' => 'What\'s up?', 'rows' => 4))?>
                      <?php echo $form->error($this->model, 'content', array('class' => 'alert alert-danger'))?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                      <?php echo $form->hiddenField($this->model, 'post_id')?>
                      <?php echo $form->hiddenField($this->model, 'reply_id', array('value' => 0))?>
                      <input type="hidden" name="level" value="0">
                      <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok glyphicon-rm10"></span>Publish</button>
                    </div>
                  </div>
                <?php $this->endWidget(); ?>
            </div>
            <div class="panel-comments">
            	<div class="panel-comments-main">
            	<div class="panel-comment-reply col-md-12">
            	<div class="panel-button"><button type="button" class="close" id="panel-comment-close"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button></div>
            	<?php $form = $this->beginWidget('CActiveForm', array(
					'id'=>'Comment_Reply',
                	'action' => $this->createUrl('comment'),
				    'enableClientValidation'=>true,
					'clientOptions' => array(
						'validateOnSubmit' => true,
						'validateOnChange' => false,
						'errorCssClass' => 'has-error',
						'successCssClass' => 'has-success',
						'afterValidate' => 'js:Comment.prototype.afterValidate'
					),
					'htmlOptions' => array(
						'class' => 'form-horizontal',
					)
				));
				?>
                  <div class="form-group">
                    <?php echo $form->label($this->model, 'name', array('class' => 'col-md-2 control-label', 'label' => 'Nickname'))?>
                    <div class="col-md-4">
                      <?php echo $form->textField($this->model, 'name', array('id' => 'Comment_name_reply', 'class' => 'form-control', 'placeholder' => 'Nickname', 'value' => isset($visitor['n']) ? $visitor['n'] : ''))?>
                      <?php echo $form->error($this->model, 'name', array('class' => 'alert alert-danger', 'inputID' => 'Comment_name_reply'))?>
                    </div>
                  </div>
                  <div class="form-group">
                    <?php echo $form->label($this->model, 'mail', array('class' => 'col-md-2 control-label', 'label' => 'Email'))?>
                    <div class="col-md-4">
                      <?php echo $form->emailField($this->model, 'mail', array('id' => 'Comment_mail_reply', 'class' => 'form-control', 'placeholder' => 'Email', 'value' => isset($visitor['m']) ? $visitor['m'] : ''))?>
                      <?php echo $form->error($this->model, 'mail', array('class' => 'alert alert-danger', 'inputID' => 'Comment_mail_reply'))?>
                    </div>
                  </div>
                  <div class="form-group">
                    <?php echo $form->label($this->model, 'website', array('class' => 'col-md-2 control-label', 'label' => 'Website'))?>
                    <div class="col-md-4">
                      <?php echo $form->textField($this->model, 'website', array('id' => 'Comment_website_reply', 'class' => 'form-control', 'placeholder' => 'Website', 'value' => isset($visitor['w']) ? $visitor['w'] : ''))?>
                      <?php echo $form->error($this->model, 'website', array('class' => 'alert alert-danger', 'inputID' => 'Comment_website_reply'))?>
                    </div>
                  </div>
                  <div class="form-group">
                    <?php echo $form->label($this->model, 'content', array('class' => 'col-md-2 control-label', 'label' => 'What\'s up?'))?>
                    <div class="col-md-10">
                      <?php echo $form->textArea($this->model, 'content', array('id' => 'Comment_content_reply', 'class' => 'form-control', 'placeholder' => 'What\'s up?', 'rows' => 4))?>
                      <?php echo $form->error($this->model, 'content', array('class' => 'alert alert-danger', 'inputID' => 'Comment_content_reply'))?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                      <?php echo $form->hiddenField($this->model, 'post_id', array('id' => 'Comment_post_id_reply'))?>
                      <?php echo $form->hiddenField($this->model, 'reply_id', array('id' => 'Comment_reply_id_reply'))?>
                      <input id="Comment_level_reply" type="hidden" name="level" value="0">
                      <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-retweet glyphicon-rm10"></span>Reply</button>
                    </div>
                  </div>
                <?php $this->endWidget(); ?>
                </div>
            	</div>
            	<div class="clearfix"></div>
            </div>
        </div>
    </div>
  </body>
</html>