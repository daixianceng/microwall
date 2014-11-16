<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php echo Yii::t('AdminModule.global', 'Warning!')?></h4>
        </div>
        <div class="modal-body">
        	<?php echo Yii::t('AdminModule.global', 'Do you really want to delete the data?')?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo Yii::t('AdminModule.global', 'Cancel')?></button>
          <button type="button" class="btn btn-danger delete-ok" data-loading-text="Loading..."><?php echo Yii::t('AdminModule.global', 'Ok')?></button>
        </div>
    </div>
  </div>
</div>
<?php 
$modal = <<<SCRIPT
$(function() {
	$('.modal').modal({
		remote : false,
		show : false
	})
	
	var url, row;
	$('.delete-button').bind('click', function() {
		url = $(this).attr('href');
		row = $(this).parents('.delete-row').eq(0);
	});
	
	$('.delete-ok').bind('click', function() {
		$(this).button('loading');
		$.ajax({
			type : 'get',
			url : url,
			dataType : 'json',
			context : this,
			success : function(json) {
				if (json.error === '200') {
					row.css({
						opacity : '.4'
					}).bind('click dbclick', function() {
						return false;
					});
					$('.modal').modal('hide');
				} else if (json.error === '401') {
					alert('Permission denied');
				} else if (json.error === '417') {
					alert('The operation failed');
				} else if (json.error === 'redirect') {
					location.href = json.url;
				}
			},
			complete : function() {
				$(this).button('reset');
			}
		})
	})
})
SCRIPT;
Yii::app()->clientScript->registerScript('modal', $modal, CClientScript::POS_END);
?>