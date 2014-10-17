$(function() {
    $(window).bind('scroll', function(e) {
        var fixed = false;
        var scrollTop = $(this).scrollTop();
        if (scrollTop >= 80 && fixed) {
            return;
        } else if (scrollTop >= 80) {
            $('#menu').addClass('menu-fixed');
            $('#right').addClass('right-fixed');
            $('#article-panel').addClass('article-panel-fixed');
            fixed = true;
        } else {
            $('#menu').removeClass('menu-fixed');
            $('#right').removeClass('right-fixed').css({
                top : 120 - scrollTop
            })
            $('#article-panel').removeClass('article-panel-fixed').css({
                top : 120 - scrollTop
            })
            fixed = false;
        }
    }).bind('load resize', function(e) {
        var winWidth = $(this).width();
        //$('.logo').text(winWidth);
        if (winWidth > 1300) {
            $('.article').removeClass('article-narrow').addClass('article-wide');
        } else {
            $('.article').removeClass('article-wide').addClass('article-narrow');
        }
        if (winWidth < 1100) {
            $('#right').css('display', 'none');
            $('#container').css('margin', '0');
            $('#article-panel').css('right', '0');
        } else {
            $('#right').css('display', 'block');
            $('#container').css('margin', '0 240px 0 0');
            $('#article-panel').css('right', '240px');
        }
    })
    
    $('#article-panel-close').click(function() {
    	$('#article-panel').fadeOut();
    })
    $('#panel-comment-close').click(function() {
    	$('.panel-comment-reply').fadeOut();
    })
    
    // 右侧列表弹框
    $('.right-section a').popover({
        trigger : 'hover',
        delay: { show: 500, hide: 100 }
    });
    
    // 阻止鼠标滚轮冒泡
    $('#article-panel, #right').bind('mousewheel', function(e) {
        var scrollUp = e.originalEvent.wheelDelta >= 0;
        if ($(this).scrollTop() <= 0 && scrollUp) {
            e.preventDefault();
        } else if ($(this).scrollTop() + $(this).innerHeight() >= $(this).prop('scrollHeight') && !scrollUp) {
            e.preventDefault();
        }
    })
    
    $('#article-panel').bind('scroll', function(e) {
		if (Comment.prototype.loaded) return;
		if ($(this).prop('scrollHeight') - $(this).innerHeight() - $(this).scrollTop() < 100) {
			Comment.prototype.loaded = true;
			Comment.prototype.loadData();
		}
	})
	
	$('.panel-comments-main').bind('click', function(e) {
		if ($(e.target).attr('class') === 'comment-reply') {
			var $current = $(e.target).parents('.comment-item');
			var $parent = $current.after($('.panel-comment-reply').fadeIn(200)).parent();
			var commentId = $parent.attr('data-comment-id');
			var level = parseInt($parent.attr('data-comment-level')) + 1;
			$('#Comment_reply_id_reply').val(commentId);
			$('#Comment_level_reply').val(level);
			$('#article-panel').animate({
				scrollTop : $current.offset().top - $('#article-panel').offset().top + $('#article-panel').scrollTop()
			})
			
			return false;
		}
	})
})