<?php 
function printComments($comments, $level) {
foreach ($comments as $comment) :
?>
<div class="comments-group<?php echo ($level === 0 || $level >= 3) ? ' col-md-12' : " col-md-11 col-md-offset-1"?>" data-comment-id="<?php echo $comment->id?>" data-comment-level="<?php echo $level?>">
<div class="comment-item col-md-12">
	<div class="col-md-1 hidden-md"><img class="img-circle" src="http://www.gravatar.com/avatar/<?php echo md5(strtolower(trim($comment->mail)))?>?s=50" width="50" height="50"></div>
	<div class="col-md-11">
		<div class="col-md-12 comment-name">
		<?php if (empty($comment->website)) :?>
		<?php echo htmlspecialchars($comment->name)?>
		<?php else :?>
		<a target="_blank" href="<?php echo $comment->website?>"><?php echo htmlspecialchars($comment->name)?></a>
		<?php endif;?>
		<div class="btn-group comment-menu">
			<button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown"><span class="caret"></span></button>
			<ul class="dropdown-menu">
				<li><a class="comment-reply" href="#">Reply</a></li>
			</ul>
		</div>
		</div>
		<div class="col-md-12 comment-date">Posted on <?php echo date('M d, Y g:i a', strtotime($comment->date))?></div>
		<div class="col-md-12 comment-content"><?php echo htmlspecialchars($comment->content)?></div>
	</div>
</div>
<?php printComments(Comment::model()->findAll('reply_id=:replyId', array(':replyId' => $comment->id)), $level + 1)?>
</div>
<?php endforeach;}?>
<?php if (empty($comments)) :?>
<div class="text-center comments-empty">No comments</div>
<?php else :?>
<?php printComments($comments, $level)?>
<?php endif;?>