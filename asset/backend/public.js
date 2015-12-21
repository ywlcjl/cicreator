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

function attachInsertEditor(obj) {
    var imgUrl = $(obj).attr('href');
    tinymce.activeEditor.insertContent('<img src="' + imgUrl + '" >');
    return false;
}

function setCoverPic(obj) {
    var imgUrl = $(obj).attr('href');
    $('#input_cover_pic').val(imgUrl);
    return false;
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

            if (success > 0) {
                $(obj).parent().remove();
            }
        }, "json");
    }

    return false;
}

function articleUpload() {
    $('#buttonUpload').click(function () {
        var formData;
        formData = new FormData();
        formData.append('post', 1);
        formData.append('userfile', $("#inputFile").get(0).files[0]);

        $.ajax({
            url: '/backend/attach/ajaxUpload',
            data: formData,
            contentType: false,
            processData: false,
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    $("#inputFile").val("");
                    $("#attachMessage").html("");

                    var str = '<li class="list-group-item">';
                    str += '<input type="hidden" name="attachId[]" value="' + data.attachId + '">';
                    str += '<a href="/' + data.picUrl + '" target="_blank" class="bd_attach_img"><img src="/' + data.picUrlThumb + '" class="img-thumbnail" alt="' + data.result.orig_name + '"></a> ';
                    str += '<a href="/' + data.picUrl + '" onclick="return attachInsertEditor(this)">插入文章</a>&nbsp;&nbsp;';
                    str += '<a href="' + data.picUrl + '" onclick="return setCoverPic(this);">设为封面</a>&nbsp;&nbsp;';
                    str += '<a href="/' + data.picUrlThumb + '" onclick="return attachInsertEditor(this)">插入缩略图</a>&nbsp;&nbsp;';
                    str += '<a href="' + data.picUrlThumb + '" onclick="return setCoverPic(this);">封面缩略图</a>&nbsp;&nbsp;';
                    str += '<a href="' + data.attachId + '" onclick="return deleteAttach(this);">删除</a>';
                    str += '</li>';

                    $("#attachList").append(str);

                } else {
                    $("#attachMessage").html(data.message);
                }

            },
            error: function (data) {
                $("#attachMessage").html("ajax error");
            }
        });

        return false;
    });
}