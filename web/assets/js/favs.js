$(document).ready(function () {
    $('.item').hover(function(){
        var id = $(this).find(".id").text();
        var artist = $(this).find(".artist").text();
        var song = $(this).find(".song").text();
        $(this).wrap('<a href="/search/' + id + '?artist=' + artist + '&song=' + song + '"></a>');
    }, function(){
        $(this).parent().contents().unwrap();
    });
});