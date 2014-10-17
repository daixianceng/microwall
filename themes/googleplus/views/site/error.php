<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="<?php echo Yii::app()->charset?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="<?php echo Yii::app()->params['keywords']?>">
    <meta name="description" content="<?php echo Yii::app()->params['description']?>">
    <title><?php echo $this->pageTitle?> | <?php echo Yii::app()->name?></title>
    <link rel="icon" href="<?php echo Yii::app()->baseUrl?>/favicon.ico" type="image/x-icon">
    <style type="text/css">
	html {
		width:100%;
		height:100%;
		background:url("<?php echo Yii::app()->theme->baseUrl?>/images/404bg.jpg");
	}
	body {
		width:100%;
		height:100%;
		padding:0;
		margin:0;
		background:url("<?php echo Yii::app()->theme->baseUrl?>/images/404.jpg") center center no-repeat;
	}
	</style>
  </head>
  <body></body>
</html>