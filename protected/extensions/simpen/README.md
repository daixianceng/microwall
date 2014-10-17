#################################################################################
# 
# simpen 自述文件
#
#################################################################################


使用方法

1.将相应文件上传到服务器

2.在页面中包含jquery框架与simpen.min.js文件

3.激活textarea输入域使之成为可视化编辑器
     如：
	
	1.	<textarea id="simpen"></textarea>
	2.	<script type="text/javascript">
	3.	(function() {
	4.		$('#simpen').simpen({
	5.			elements : 'div,p,ul,li,img',	// 被允许插入的标签
	6.			skinPath : '/skins/default',	// simpen的皮肤目录
	7.			cssFile : '/css/document.css',	// 编辑器中应用的css文件
	8.			uploadPath : 'upload.php',	// 上传图片对应的服务器脚本
	8.			imageTypes : 'png,jpg,gif',	// 被允许的图片类型
	10.			maxSize : 2000000		// 被允许的图片最大大小，单位bit
	11.		});
	12.	})
	13.	</script>

参数详情

	1.elements		可选，默认值'div,p,ul,li,h1,h2,h3,h4,h5,h6,img'
	2.skinPath		必须，simpen的皮肤目录
	3.cssFile		必须，编辑器中应用的css文件
	4.uploadPath		可选，如果允许插入图片，则必须
	5.imageTypes		可选，默认值'png,jpg,jpeg,gif,bmp'
	6.maxSize		可选，默认值2000000

如何插入图片

img标签默认会被启用的，如果启用，则可能要设置以下参数：

	1.uploadPath	（必须）
	2.imageTypes	（可选，默认png,jpg,jpeg,gif,bmp）
	3.maxSize		（可选，默认2000000）
	
uploadPath参数需要配置服务器端脚本，服务器端执行流程应当如下：

	1.接收上传的图片
	2.判断图片是否合法
	3.将图片转移到页面可访问的路径中
	4.向页面（simpen中隐藏的iframe）输出js脚本让simpen提取图片信息

幸好simpen中已经封装好了SimpenImage（PHP）类（php/SimpenImage.php），
类文件很容易使用，如下所示：
	
	1.	require 'php/SimpenImage.php';
	2.	$img = new SimpenImage();
	3.	// 设置图片保存路径与该路径的url形式
	4.	$img->setSavePath('save/path')->setBaseUrl('base/url/');
	5.	// 接收图片
	6.	$img->receive();
	7.	// 向页面输出脚本
	8.	$img->output();														
