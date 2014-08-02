$(function() {
    $('.navbar-nav ul').addClass('dropdown-menu');
    $.each($('.navbar-nav ul'), function() {
        var element = $(this).parent().children('a');
        if (!$(element).hasClass('level-1')) {
            $(element).addClass('sub');
        }
    });
    $('.navbar-nav li').hover(function() {
        var ul = $(this).find('> ul');
        if ($(this).parent().css('position') == 'absolute') {
            $(ul).show().css({left: ($(this).parent().width() - 15) + 'px', top: '0px'});
        } else {
            $(ul).show().css({left: '0px', top: $(this).height()+'px'});
        }
    }, function() {
        $(this).find('> ul').hide();
    });
});