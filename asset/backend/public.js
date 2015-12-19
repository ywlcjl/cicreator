/* 后台路径 ~cc */
var BACKEND = 'backend';
var B_URL = "/" + BACKEND + "/";

function confirmAction() {
    if (confirm("确定该操作吗?")) {
        return true;
    } else {
        return false;
    }
}

function checkAll() {
    $('#checkAll').click(function () {
        if ($(this).prop("checked") == true) {
            $('input[name="ids[]"]').prop('checked', 'checked');
            $('.bd_table_tr').css('background-color', '#FFF1A6');
            $('.bd_table_tr').attr('class', 'bd_table_tr2');
        } else {
            $('input[name="ids[]"]').prop('checked', '');
            $('.bd_table_tr2').css('background-color', '');
            $('.bd_table_tr2').attr('class', 'bd_table_tr');
        }
    });

    //绑定普通的选中框变色
    $('input[name="ids[]"]').click(function () {
        if ($(this).prop("checked") == true) {
            //选中
            $(this).parent().parent().parent().css('background-color', '#FFF1A6');
            $(this).parent().parent().parent().attr('class', 'bd_table_tr2');
        } else {
            //关闭
            $(this).parent().parent().parent().css('background-color', '');
            $(this).parent().parent().parent().attr('class', 'bd_table_tr');
        }
    });

    return false;
}

function toMessage() {
    var secondObj = $('#second');
    var second = secondObj.html();
    if (second > 0) {
        secondObj.html(second - 1);
        setTimeout('toMessage()', 1000);
    } else {
        var toUrl = $('#toUrl').attr('href');
        location = toUrl;
    }
}

function bootstrapDate() {
    $('input[data-provide="datepicker"]').datepicker({
        format: 'yyyy-mm-dd',
        language: 'zh-CN',
    });
}

function searchForm() {
    $('#searchFormTitle').click(function() {
        var searchForm = $('#searchForm');

        if (searchForm.css('display') == 'none') {
            $(this).attr('class', 'btn btn-default active');
            searchForm.show();
        } else {
            $(this).attr('class', 'btn btn-default');
            searchForm.hide();
        }
        return false;
    });
}