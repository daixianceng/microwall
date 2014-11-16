<?php
$drag = <<<SCRIPT
$(function() {
	$('.sorted_table').sortable({
	  containerSelector: 'table',
	  itemPath: '> tbody',
	  itemSelector: 'tr',
	  placeholder: '<tr class="draggable-placeholder"/>',
	  onDrop : function(item, container, _super, event) {
  		var data;
  		$('.draggable').each(function(index) {
  			if (index === 0)
  				data = $(this).attr('data-id');
  			else
  				data += ',' + $(this).attr('data-id');
		});
		$.ajax({
			url : '{$url}',
			type : 'get',
			data : 'list=' + data
		})
		item.removeClass("dragged").removeAttr("style");
  		$("body").removeClass("dragging");
	  }
	})
})
SCRIPT;
$assetUrl = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.jquery-plugins'));
Yii::app()->clientScript->registerScriptFile($assetUrl . '/jquery-sortable.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('drag', $drag, CClientScript::POS_END);