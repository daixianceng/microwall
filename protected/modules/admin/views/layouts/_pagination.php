<?php $this->widget('CLinkPager', array(
    	'pages' => $pages,
    	'header' => '',
    	'nextPageLabel' => Yii::t('AdminModule.global', 'Next'),
    	'prevPageLabel' => Yii::t('AdminModule.global', 'Prev'),
    	'firstPageLabel' => Yii::t('AdminModule.global', 'First'),
    	'lastPageLabel' => Yii::t('AdminModule.global', 'Last'),
    	'selectedPageCssClass' => 'active',
    	'htmlOptions' => array(
	    	'class' => 'pagination'
	    )
    ));?>