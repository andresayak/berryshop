$(function() {
    $('.navbar-category ul ul').addClass('dropdown-menu');
    $.each($('.navbar-category ul ul'), function() {
        var element = $(this).parent().children('a');
        if (!$(element).hasClass('level-1')) {
            $(element).addClass('sub');
        }
    });
    $('.navbar-category ul li').hover(function() {
        var ul = $(this).find('> ul');console.log('hover');
        if ($(this).parent().css('position') == 'absolute') {
            $(ul).show().css({left: ($(this).parent().width() - 15) + 'px', top: '0px'});
        } else {
            $(ul).show().css({left: '0px', top: $(this).height()+'px'});
        }
    }, function() {
        $(this).find('> ul').hide();
    });
});