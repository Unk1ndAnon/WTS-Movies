//--------menu toggle btn------//
var button = document.getElementById('menubtn');
button.onclick = function() {
    var div = document.getElementById('arch-menu');
    if (div.style.display !== 'block') {
        div.style.display = 'block';
    } else {
        div.style.display = 'none';
    }
};
//--------search btn toggle------//
var button = document.getElementById('searchbtn');
button.onclick = function() {
    var div = document.getElementById('form-search-resp');
    if (div.style.display !== 'block') {
        div.style.display = 'block';
    } else {
        div.style.display = 'none';
    }
};
//--------ajax search------//
$(document).ready(function() {
    var id = -1;
    $("#form-search-resp #keyword").keyup(function(e) {
        var keyword = $(this).val();
        var keyword_replace = $('#keyword_search_replace').val();
        if (keyword_replace != keyword) {
            preload(keyword, id);
            $('#keyword_search_replace').val('');
            $("#key_pres").val('');
        }
    });
    $("#form-search-resp #keyword").focus(function(e) {
        var keyword = $(this).val();
        preload(keyword, id);
    });
    $("#form-search-resp #keyword").blur(function() {
        if ($("#header_search_autocomplete").is(':hover') === true) {} else {
            var keyword = '';
            preload(keyword, id);
        }
    });
    $("#form-search-resp #keyword").keydown(function(e) {
        var keyword = $(this).val();
        var key_pres = $("#key_pres").val();
        if (e.keyCode == 13) {
            e.preventDefault();
            if (key_pres != '') {
                var alias = $('#link_alias').val();
                window.location.href = base_url + '/movies/' + alias;
            } else {
                window.location.href = '/search/' + keyword;
            }
        }
        if ($('#header_search_autocomplete_body:visible').length > 0) {
            var items = $('#header_search_autocomplete_body').children();
            var nextElement = null;
            var current_index = -1;
            event_id = $("#key_pres").val();
            if (event_id != '') {
                if (event_id.substring(0, 'header_search_autocomplete_item_'.length) == 'header_search_autocomplete_item_') {
                    current_index = parseInt(event_id.replace('header_search_autocomplete_item_', ''));
                    $('#header_search_autocomplete_body div').removeClass('focused');
                }
            }
            if (e.keyCode == 38) {
                e.preventDefault();
                current_index = Math.max(0, current_index - 1);
                nextElement = $('#header_search_autocomplete_item_' + current_index);
            } else if (e.keyCode == 40) {
                e.preventDefault();
                current_index = Math.min(items.length - 1, current_index + 1);
                nextElement = $('#header_search_autocomplete_item_' + current_index);
            }
            if (nextElement) {
                nextElement.stop(true, true);
                $('#header_search_autocomplete_item_' + current_index).focus();
                $('#header_search_autocomplete_item_' + current_index).stop(true, true).addClass('focused');
                $("#key_pres").val('header_search_autocomplete_item_' + current_index);
                var link_alias = $('#header_search_autocomplete_item_' + current_index + ' a').attr('rel');
                $("#link_alias").val(link_alias);
                $("#keyword_search_replace").val(keyword);
                id = current_index;
            }
        }
    });
});

function preload(keyword, id) {
    if (keyword.length >= 2) {
        $(".load.search").show();
        //$("#header_search_autocomplete .load").html(keyword);
    }
    $.ajax({
        type: "get",
        url: "/ajax-search.php",
        dataType: 'json',
        data: {
            keyword: keyword,
            id: id
        },
        success: function(data, response) {
            $(".load.search").hide();
            $("#header_search_autocomplete").html(data.content);
        }
    });
}

function do_search() {
    var keyword = $("#form-search-resp.lap #keyword").val();
    keyword = keyword.replace(/\s+/g, '-');
    if (keyword.length >= 2) {
        window.location.href = '/search/' + urlencode(keyword);
    } else {
        //$("#form-search-resp #keyword").focus();
    }

    return false;
}

function do_searchM() {
    var keyword = $("#form-search-resp.mobi #keyword").val();
    keyword = keyword.replace(/\s+/g, '-');
    if (keyword.length >= 2) {
        window.location.href = '/search/' + urlencode(keyword);
    } else {
        //$("#form-search-resp #keyword").focus();
    }

    return false;
}

//iframe src & btn
$('#trailer').click(function () {
    var s = $(this).data('src');
    $("#trailer").css("display", "none");
    $("#loading").css("display", "block");
    $('#player').attr('src', s);
    $('#player').load(function(){
        $("#loading").css("display", "none");
        $("#trailer").css("display", "none");
        $("#watchm").css("display", "block");
    });
    $('body,html').animate({scrollTop: 0}, 400); return false;
});
$('#watchm').click(function () {
    var s = $(this).data('src');
    $("#watchm").css("display", "none");
    $("#loading").css("display", "block");
    $('#player').attr('src', s);
    $('#player').load(function(){
        $("#loading").css("display", "none");
        $("#watchm").css("display", "none");
        $("#trailer").css("display", "block");
    });
    $('body,html').animate({scrollTop: 0}, 400); return false;
});
$('#torrent').click(function () {
    var s = $(this).data('src');
    window.open(s, "_blank", "");
});
$('#subtitle').click(function () {
    var s = $(this).data('src');
    window.open(s, "_blank", "");
});