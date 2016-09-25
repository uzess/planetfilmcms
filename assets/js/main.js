$(document).ready(function () {



    $('.my-expand').each(function () {
        var targetId = $(this).attr('data-target');
        var height = $(targetId).height();
        $(targetId).attr('data-height', height);

        if (height <= 100)
        {
            $(this).hide();
        }
        $(targetId).css({'max-height': '100px', 'overflow': 'hidden'});
        $(this).css({'cursor': 'pointer'});

    });

    $('.my-expand').on('click', function () {
        var targetId = $(this).attr('data-target');
        var height = $(targetId).attr('data-height');

        if ($(this).hasClass('expanded'))
        {
            $(this).children('i').removeClass('fa-minus-circle');
            $(this).children('i').addClass('fa-plus-circle');
            $(this).removeClass('expanded');
            $(targetId).animate({'max-height': '100px'});
        } else {

            $(this).children('i').removeClass('fa-plus-circle');
            $(this).children('i').addClass('fa-minus-circle');
            $(this).addClass('expanded');
            $(targetId).animate({'max-height': height});
        }

    });

    size_li = $("#myList article").size();
    x = 1;
    $('#myList article:lt(' + x + ')').show();
    $('#loadMore').click(function () {
        x = (x + 1 <= size_li) ? x + 1 : size_li;
        $('#myList article:lt(' + x + ')').show();
        $('#showLess').show();
        if (x == size_li) {
            $('#loadMore').hide();
        }

    });
    $('#showLess').click(function () {
        x = (x - 1 < 0) ? 3 : x - 1;
        $('#myList article').not(':lt(' + x + ')').hide();
        $('#loadMore').show();
        $('#showLess').show();
        if (x == 1) {
            $('#showLess').hide();
        }

    });

    //collapsible management
    $('.collapsible').collapsible({
        defaultOpen: 'nav-section1,nav-section3'
    });
});

$(window).load(function () {

    masorny();


    var docheight = $(window).height();
    $('.inner-wrapper').css({'min-height': docheight - 100});
    $('.dir-wrapper').css({'min-height': docheight - 100});

    var width = $(window).width();
    if (width <= 568)
    {
        $('#director-link').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
        });
    }

    
});


function masorny() {
    var $container = $('#servicesMaso');
// initialize
    $container.masonry({
        itemSelector: '.service-img-wrap'
    });
}