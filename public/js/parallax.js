$(window).scroll(function(e){
    var scrolled = $(window).scrollTop();
    var parallax = $('.parallax');
    var speed = -(scrolled * (parallax.data('speed')/10));
    parallax.css('top', speed + 'px');
    console.log();
});