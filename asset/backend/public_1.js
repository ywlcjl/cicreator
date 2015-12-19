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
    $('#checkAll').toggle(function() {
        $('.ids').attr('checked', 'checked');
        $('.listFormContent').css('background-color', '#FFF1A6');
        $('.listFormContent').attr('class', 'listFormContent2');
    }, function() {
        $('.ids').attr('checked', '');
        $('.listFormContent2').css('background-color', '#FFFFFF');
        $('.listFormContent2').attr('class', 'listFormContent');
    });

    //绑定普通的选中框变色
    $('.ids').click(function() {
        if ($(this).attr("checked") == true) {
            //选中
            $(this).parent().parent().css('background-color', '#FFF1A6');
            $(this).parent().parent().attr('class', 'listFormContent2');
        } else {
            //关闭
            $(this).parent().parent().css('background-color', '#FFFFFF');
            $(this).parent().parent().attr('class', 'listFormContent');
        }
    });

    return false;
}

function selectLink() {
    $('.selectLink').change(function() {  //触发分类跳转
        var url = $(this).val();
        window.location.href = url;
    });
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

function trView() {
    //表格颜色和选中勾选
    $('.listFormContent').live('mousemove', function() {
        $(this).css('background-color', '#FFF1A6');
    }).live('mouseout', function() {
        $(this).css('background-color', '#FFFFFF');
    });
}

function searchForm() {
    $('#searchFormTitle').click(function() {
        var searchForm = $('#searchForm');

        if (searchForm.css('display') == 'none') {
            searchForm.show();
        } else {
            searchForm.hide();
        }
        return false;
    });
}

function deleteAttach(obj) {
    var result = confirm('确认删除吗?');
    if (result) {
        var id = $(obj).attr('href');

        $.post(B_URL + "attach/ajaxDelete", {
            'id': id
        }, function (data, textStatus) {
            var message = data.message;
            var success = data.success;

            $('#attachMessage').html(message);
            $('#attachMessage').css('display', '');

            if (success > 0) {
                $(obj).parent().remove();
            }
        }, "json");
    }

    return false;
}

function attachInsertEditor(obj) {
    var imgUrl = $(obj).attr('href');
    editor.insertHtml('<img src="' + imgUrl + '" />');
    return false;
}

function setCoverPic(obj) {
    var imgUrl = $(obj).attr('href');
    $('#coverPic').val(imgUrl);
    return false;
}

function insertImgToDesc(obj) {
    var imgUrl = $(obj).attr('href');
    var img = '<img src="/' + imgUrl + '" />'
    var oldStr = $('#desc_txt').val();
    $('#desc_txt').val(oldStr + img);
    return false;
}
