$(document).ready(function () {
    $("#featureContainer").sortable();
    $("#featureContainer").disableSelection();

    $(document).on('click', '#btnFeatureSubmit', function (e) {
        var n = 1;
        $('.my-box').each(function () {
            $(this).find('.my-weight').val(n);
            n++;
        });
        $('#featureForm').submit();
    });
    $(document).on('change', '.my-dir-sel', function (e) {
        var opt = $(this).attr('data-option');
        var directorId = $(this).val();

        var videoId = [];
        var curId = $(opt).val();
        $('.my-vid').each(function () {
            var temp = $(this).val();
            if (temp !== curId)
                videoId.push(temp);
        });

        $.post('dashboard/video/getVideos/', {directorId: directorId, avoidId: videoId}, function (data, status) {
            $(opt).html(data);
        });
    });

    $(document).on('click', '#addFeature', function () {
        var type = $(this).attr('data-type');

        $.get('dashboard/video/addFeature/' + type, function (data, status) {
            $('#featureContainer').append(data);
        });
    });

    $(document).on('click', '.close-feature', function () {
        var collapseId = $(this).attr('data-close-id');
        $(collapseId).remove();
    });

    $('.make-feature').on('click', function () {
        id = $(this).attr('data-id');
        if ($(id).is(":checked"))
        {
            $(id).prop('checked', false);
        }
        else {
            $(id).prop('checked', true);
        }
    });

    $('.make-home-page').on('click', function () {
        id = $(this).attr('data-id');
        if ($(id).is(":checked"))
        {
            $(id).prop('checked', false);
        }
        else {
            $(id).prop('checked', true);

        }
    });

    $('#list-submit').on('click', function (e) {
        e.preventDefault();
        var feature = [];
        var homePage = [];
        var parentId = $("input[name='parent_id']").val();


        $(".feature").each(function () {
            if ($(this).is(":checked"))
            {
                feature.push($(this).val());
            }
        });

        $(".home-page").each(function () {
            if ($(this).is(":checked"))
            {
                homePage.push($(this).val());
            }
        });

        var parameters = {
            "home_page_id[]": homePage,
            "feature_id[]": feature,
            "parent_id": parentId

        };
        $.post(
                "dashboard/video/save_featured",
                parameters,
                function (data, status) {
                    if (status == 'success')
                    {
                        $('.template-wrapper').prepend('<div class="alert alert-success"><span class="fa fa-check-circle"></span>&nbsp;Status changed successfully !</div>');

                    }
                    else
                    {
                        $('.template-wrapper').prepend('<div class="alert alert-danger"><span class="fa fa-warning"></span>&nbsp;Status not changed</div>');

                    }
                    $('.alert').animate({right: '20px'}, 500, function () {
                        setTimeout(function () {
                            $('.alert').animate({right: '-300px'});
                        }, 5000);
                    });
                });

    });

    $('.add-new').on('click', function (e) {
        e.preventDefault();
        var target = $(this).attr('data-target');
        var down = 'fa-plus';
        var up = 'fa-minus';
        $(target).slideToggle();

        if ($(this).find("span").hasClass(down))
        {
            $(this).find("span").removeClass(down);
            $(this).find("span").addClass(up);
        } else {
            $(this).find("span").removeClass(up);
            $(this).find("span").addClass(down);
        }
    });

//For left nav menu
    $('.drop').on('click', function (e) {
        e.preventDefault();
        $(this).find('+ ul').slideToggle();
    });

    $('#type').on('change', function (e) {
        var type = $(this).val();
        $('.mc').fadeOut();

        if (type !== '0') {
            if (type === 'Normal Group') {
                $('#ng').fadeIn();
            } else if (type === 'Photo Gallery') {
                $('#pg').fadeIn();
            } else if (type === 'Link') {
                $('#link').fadeIn();

            }
        }
    });


    $('.my-toggle').each(function () {
        var id = $(this).attr("data-id");
        var state = $(this).attr("data-state");
        $(this).css('cursor', 'pointer');
        var down = '<span class="fa fa-arrow-down pull-right"></span>';
        var up = '<span class="fa fa-arrow-up pull-right"></span>';
        if (state !== "in")
        {
            $(this).append(down);
            $(id).hide();
        }
        else
        {
            $(this).append(up);
            $(id).css('margin-top', '25px');
        }
    });

    $('.my-toggle').on('click', function () {
        var id = $(this).attr("data-id");
        var down = "fa-arrow-down";
        var up = "fa-arrow-up";

        if ($(this).find("span").hasClass(down))
        {
            $(this).find("span").removeClass(down);
            $(this).find("span").addClass(up);
        } else {
            $(this).find("span").removeClass(up);
            $(this).find("span").addClass(down);
        }

        $(id).css('margin-top', '25px');
        $(id).slideToggle();

    });



    $('#dataTable').dataTable({
        "sPaginationType": "full_numbers",
        "bStateSave": true

    });

    $('#name').on('change', function () {
        var name = $(this).val();
        generate_slug(name);
    });
    $('#slug').on('change', function () {
        var name = $(this).val();
        generate_slug(name);
    });

    function generate_slug(arg)
    {
        $.get('dashboard/manage_content/generate_slug/' + arg, function (data, status) {

            if (status == 'success') {
                var slug = data.slug;
                $('#slug').val(slug);

                if (data.available == 0)
                {
                    $('#slugMsg').removeClass('label-success');

                    $('#slugMsg').addClass('label-danger');
                    $('#slugMsg').html("Unavailable").show();
                }
                else {
                    $('#slugMsg').removeClass('label-danger');

                    $('#slugMsg').addClass('label-success');
                    $('#slugMsg').html("available").show();
                }
            }
            else {
                $('#slugMsg').removeClass('label-success');

                $('#slugMsg').addClass('label-danger');
                $('#slugMsg').html("Bad Character").show();
            }
        });
    }




//Video Gallery Plugin-Fancy Box

});

function submit_form(controller)
{
    $("#my_form").attr("action", controller);
    $("#my_form").submit();
}

function addVideo()
{
    newDiv = document.createElement("div");
    str = "<div class='col-xs-12'>";
    str += "<textarea name='video_links[]' class='form-control' rows='3' ></textarea>";
    str += "</div><br>";
    newDiv.innerHTML = str;
    document.getElementById('uploadVideoHolder').appendChild(newDiv);
}



function delete_confirmation(query)
{
    if (confirm("Are you sure you want to delete?"))
    {
        location.href = query;
    }
    return false;
}
function delete_all_confirmation()
{
    if (confirm("Are you sure you want to delete All?"))
    {
        return true;
    }
    return false;
}

function confirm_submit(controller)
{
    if (delete_all_confirmation())
    {
        submit_form(controller);
    }
    return false;
}

function check_all(source) {

    checkboxes = document.getElementsByName('del_selected[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}

$(document).ready(function () {
    $('#check-all').click(function () {
        $("input:checkbox").prop('checked', true);
    });
    $('#uncheck-all').click(function () {
        $("input:checkbox").prop('checked', false);
    });
});

$(window).load(function () {

    $('.alert').animate({right: '20px'}, 500, function () {
        setTimeout(function () {
            $('.alert').animate({right: '-300px'});
        }, 5000);
    });

});
tinymce.init({
    selector: "#shortcontent",
    plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "template paste textcolor paste jbimages fullscreen"
    ],
    image_advtab: true,
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | jbimages",
    toolbar2: "print preview media | forecolor backcolor | fontsizeselect | fullscreen",
    relative_urls: false
});
tinymce.init({
    selector: "#content",
    plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "template paste textcolor jbimages fullscreen"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | jbimages",
    toolbar2: "print preview media | forecolor backcolor | fontsizeselect | fullscreen",
    image_advtab: true,
    relative_urls: false
});

