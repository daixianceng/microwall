(function($) {
	$.fn.extend({
		simpen : function(config) {
			var $this = $(this);
			var w = $this.outerWidth();
			var h = $this.outerHeight();
			var $simpen = $('<div class="simpenEditer"><ul></ul><div class="simpenCenter"></div><iframe class="simpenFrame"></iframe></div>');
			$this.css('display', 'none').after($simpen.width(w).height(h));
			
			$simpen.find('.simpenFrame').height($simpen.innerHeight() - $simpen.find('ul').outerHeight(true));
			
			// 初始化配置数据
			var elements = ['div', 'p', 'ul', 'li', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'img'];
			var skinPath = '';
			var cssFile = '';
			var uploadPath = '';
			var imageTypes = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];
			var maxSize = 2000000;
			
			// 该函数处理用户参数
			var strExplodeFilter = function(str, arr) {
				var r = [];
				var explode = str.split(',');
				$.each(explode, function(k, v) {
					if ($.inArray(v, arr) !== -1 && $.inArray(v, r) === -1) {
						r.push(v);
					}
				})
				
				return r;
			};
			
			// 应用自定义数据
			if ($.isPlainObject(config)) {
				if (config.elements && $.type(config.elements) === 'string') {
					elements = strExplodeFilter(config.elements, elements);
				}
				if (config.skinPath && $.type(config.skinPath) === 'string') {
					skinPath = config.skinPath;
				}
				if (config.cssFile && $.type(config.cssFile) === 'string') {
					cssFile = config.cssFile;
				}
				if (config.uploadPath && $.type(config.uploadPath) === 'string') {
					uploadPath = config.uploadPath;
				}
				if (config.imageTypes && $.type(config.imageTypes) === 'string') {
					imageTypes = strExplodeFilter(config.imageTypes, imageTypes);
				}
				if (config.maxSize) {
					maxSize = config.maxSize;
				}
			}
			
			// 给simpen穿上衣服，给窗口送去衣服
			$simpen.before('<link rel="stylesheet" type="text/css" href="' + skinPath + '/css/main.css' + '">');
			var $body = $simpen.find('.simpenFrame').contents().find('head').append('<link rel="stylesheet" type="text/css" href="' + cssFile + '">').next('body');

			// 激活的当前标签，默认body
			var $activeTag = $body;
			var action = false;
			
			var updateHTML = function() {
				var html;
				if ($activeTag !== $body) {
					$activeTag.unwrap();
					html = $body.html();
					$activeTag.wrap('<div class="wrap"></div>');
				} else {
					html = $body.html();
				}
				$this.val(html);
			};
			
			$body.dblclick(function(event) {
				if (action) return false;
				// 如果当前激活元素是body
				if ($activeTag === $body) {
					if ($(event.target).prop('tagName') === 'BODY') {
						return false;
					} else if ($.inArray($(event.target).prop('tagName').toLowerCase(), elements) !== -1) {
						$activeTag = $(event.target).wrap('<div class="wrap"></div>').data('isWrap', true);
					}
				} else {
					if ($(event.target).data('isWrap') || $(event.target).prop('tagName') === 'BODY') {
						$activeTag.unwrap().data('isWrap', false);
						$activeTag = $body;
						return false;
					} else if ($(event.target).attr('class') === 'wrap') {
						return false;
					} else if ($.inArray($(event.target).prop('tagName').toLowerCase(), elements) !== -1) {
						$activeTag.unwrap().data('isWrap', false);
						$activeTag = $(event.target).wrap('<div class="wrap"></div>').data('isWrap', true);
					}
				}
			})
			
			// 将标签一个个展开
			$.each(elements, function(k, v) {
				var $element = $('<li><a href="#">' + v + '</a></li>');
				$simpen.find('ul').append($element);
				
				// img button
				if (v === 'img') {
					(function() {
						var $uploadBox = $('<div class="simpenBox"><iframe name="simpenUpload" class="simpenUpload"></iframe><form class="simpenForm" action="" method="post" enctype="multipart/form-data" target="simpenUpload"><div class="simpenTitle">Upload Image</div><div class="simpenText"><input type="hidden" name="MAX_FILE_SIZE" value="' + maxSize + '"><input type="file" name="simpenImage"></div><div class="simpenButtons"><div><button type="button" name="ok">OK</button></div><div><button type="button" name="cancel">Cancel</button></div></div></form></div>');
						$simpen.find('.simpenCenter').append($uploadBox);
						
						var $form = $uploadBox.find('.simpenForm').attr('action', uploadPath);
						$form.find('button[name=ok]').click(function() {
							var fileName = $form.find('input[name=simpenImage]').val().replace('C:\\fakepath\\', '');
							var arr = fileName.split('.');
							if ($.inArray(arr[arr.length - 1], imageTypes) === -1) {
								alert('The picture type does not allow.');
								return false;
							}
							$form.submit();
							$uploadBox.fadeOut(200);
							action = false;
						}).end().find('button[name=cancel]').click(function() {
							$form.find('input[name=simpenImage]').val('');
							$uploadBox.fadeOut(200);
							action = false;
						});
						
						$.extend({
							simpenImage : function(json) {
								json = $.parseJSON(json);
								if (json.error === 0) {
									var imgTag = '<img src="' + json.src + '">';
									$activeTag.append(imgTag);
									updateHTML();
								} else {
									alert(json.errorMsg);
								}
							}
						});
						
						$element.click(function() {
							if (!action) {
								$form.find('input[name=simpenImage]').val('');
								$uploadBox.fadeIn(200);
								action = true;
							}
							return false;
						})
					})();
					
				} else {
					$element.click(function() {
						if (action) return false;
						$activeTag.append('<' + v + '></' + v + '>');
						updateHTML();
						return false;
					})
				}
			});
			
			// text button
			(function() {
				var $text = $('<li><a href="#"><i>text</i></a></li>');
				var $textBox = $('<div class="simpenBox"><div class="simpenTitle">Insert Text</div><div class="simpenText"><textarea name="simpenText"></textarea></div><div class="simpenButtons"><div><button type="button" name="ok">OK</button></div><div><button type="button" name="cancel">Cancel</button></div></div></div>');
				$simpen.find('ul').append($text).end().find('.simpenCenter').append($textBox);
				
				$textBox.find('button[name=ok]').click(function() {
					var $t = $textBox.find('textarea[name=simpenText]');
					var textValue = $t.val();
					$activeTag.append($('<div/>').text(textValue).html());
					$t.val('');
					$textBox.fadeOut(200);
					action = false;
					updateHTML();
					return false;
				}).end().find('button[name=cancel]').click(function() {
					$textBox.find('textarea[name=simpenText]').val('');
					$textBox.fadeOut(200);
					action = false;
					return false;
				})
				
				$text.click(function() {
					if (!action) {
						$textBox.fadeIn(200);
						action = true;
					}
					return false;
				})
			})();
			
			// html button
			(function() {
				var $html = $('<li><a href="#"><i>html</i></a></li>');
				var $htmlBox = $('<div class="simpenBox"><div class="simpenTitle">Edit Html</div><div class="simpenText"><textarea name="simpenText"></textarea></div><div class="simpenButtons"><div><button type="button" name="ok">OK</button></div><div><button type="button" name="cancel">Cancel</button></div></div></div>');
				$simpen.find('ul').append($html).end().find('.simpenCenter').append($htmlBox);
				
				$htmlBox.find('button[name=ok]').click(function() {
					var $t = $htmlBox.find('textarea[name=simpenText]');
					var textValue = $t.val();
					$activeTag.html(textValue);
					$t.val('');
					$htmlBox.fadeOut(200);
					action = false;
					updateHTML();
					return false;
				}).end().find('button[name=cancel]').click(function() {
					$htmlBox.find('textarea[name=simpenText]').val('');
					$htmlBox.fadeOut(200);
					action = false;
					return false;
				})
				
				$html.click(function() {
					if (!action) {
						$htmlBox.find('textarea[name=simpenText]').val($activeTag.html());
						$htmlBox.fadeIn(200);
						action = true;
					}
					return false;
				})
			})();
			
			// remove button
			(function() {
				var $remove = $('<li><a href="#"><i>remove</i></a></li>');
				$simpen.find('ul').append($remove);
				
				$remove.click(function() {
					if (!action && $activeTag !== $body) {
						$activeTag.unwrap().remove();
						$activeTag = $body;
						updateHTML();
					}
					return false;
				})
			})();
		}
	})
})(jQuery);