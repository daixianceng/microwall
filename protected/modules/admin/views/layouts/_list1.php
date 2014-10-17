<ul class="list-group left-list">
  <li class="list-group-header">全局</li>
  <li class="list-group-item<?php echo $this->menu === 'default' ? ' active' : '';?>"><a href="<?php echo $this->createUrl('default/index');?>">仪表盘</a></li>
  <li class="list-group-item"><a href="#">设置</a></li>
  <li class="list-group-header">文章</li>
  <li class="list-group-item<?php echo $this->menu === 'post' ? ' active' : '';?>"><a href="<?php echo $this->createUrl('post/index');?>">列表</a></li>
  <li class="list-group-item<?php echo $this->menu === 'writing' ? ' active' : '';?>"><a href="<?php echo $this->createUrl('post/writing');?>">写作</a></li>
  <li class="list-group-item<?php echo $this->menu === 'categories' ? ' active' : '';?>"><a href="<?php echo $this->createUrl('post/categories');?>">分类</a></li>
  <li class="list-group-header">评论</li>
  <li class="list-group-item<?php echo $this->menu === 'comment' ? ' active' : '';?>"><a href="<?php echo $this->createUrl('comment/index');?>">列表</a></li>
  <li class="list-group-header">用户</li>
  <li class="list-group-item<?php echo $this->menu === 'user' ? ' active' : '';?>"><a href="<?php echo $this->createUrl('user/index');?>">列表</a></li>
</ul>