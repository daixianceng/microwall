	  <button class="btn btn-default dropdown-toggle" title="Select category" type="button" data-toggle="dropdown">
	    <?php echo isset($categories[$currentCategory]) ? $categories[$currentCategory] : 'All'?>
	    <span class="caret"></span>
	  </button>
	  <ul class="dropdown-menu">
	    <?php foreach ($categories as $key => $category) :?>
	    <li><a href="<?php echo $this->createUrl('', array('category' => $key))?>"><?php echo $category?></a></li>
	    <?php endforeach;?>
	    <li class="divider"></li>
	    <li><a href="<?php echo $this->createUrl('')?>">All</a></li>
	  </ul>