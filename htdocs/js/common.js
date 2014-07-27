$(function() {
    $('.navbar-category ul ul').addClass('dropdown-menu');
    $.each($('.navbar-category ul ul'), function() {
        var element = $(this).parent().children('a');
        if (!$(element).hasClass('level-1')) {
            $(element).addClass('sub');
        }
    });
    $('.navbar-category ul li').hover(function() {
        var ul = $(this).find('> ul');
        if ($(this).parent().css('position') == 'absolute') {
            $(ul).show().css({left: ($(this).parent().width() - 15) + 'px', top: '0px'});
        } else {
            $(ul).show().css({left: '0px', top: $(this).height()+'px'});
        }
    }, function() {
        $(this).find('> ul').hide();
    });
    $('#back-top').click(function() {
        $('body,html').stop(false, false).animate({
            scrollTop: 0
        }, 800);
        return false;
    });
    $(window).scroll(function() {
        backTop();
    });
    
    backTop();
    
    function backTop(){
        if ($(window).scrollTop() > 0) {
            $('#back-top').fadeIn();
        } else {
            $('#back-top').fadeOut();
        }
    }
});