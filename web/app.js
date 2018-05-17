$(function () {
    console.log('ready');
    //active navbar link
    var url = window.location;
    $('.nav-item a').filter(function() {
        return this.href == url;
    }).parent().addClass('active');
});