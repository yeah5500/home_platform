$(function(){
    layui.use(['laydate','layer','element','layedit','form','upload'], function(){
        var element = layui.element;
        var laydate=layui.laydate;
        var layer=layui.layer;
        var layedit = layui.layedit;
        var form = layui.form;
        var $ = layui.jquery
            ,upload = layui.upload;
        laydate.render({
            elem: '#date'
        });
        var uploadInst = upload.render({
            elem: '#test1'
            ,url: './index.php?c=site&a=entry&do=web&m=sd_135K&s=banner%2Fupload'

            ,done: function(res){
                //如果上传失败
                if(res.code){
                    return layer.msg('上传失败');
                }
                $('#image').attr('value', res.data); //图片链接（src）
                $('#demo1').attr('src',  res.data); //图片链接（src)
                $("input[name='image_url']").val(res.data);
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
            }
        });
        form.on('submit(search)', function(data){
            window.location.href=this.form.action+'&search='+data.field.search;
            return false;
        });

        form.on('submit(assign)', function(data){
            $.post(this.form.action,data.field,function(res){
                if(res.code){
                    layer.alert(res.msg?res.msg:'操作失败', {icon: 5});
                }else{
                    layer.closeAll();
                    layer.alert(res.msg,{icon:1},function(){
                        if(res.url){
                            location.href=res.url
                        }else{
                            location.reload();
                        }

                    });
                }
            },'json');
            return false;
        });
        form.on('submit(submit)', function(data){

            $.post(this.form.action,data.field,function(res){
                if(res.code){
                    layer.alert(res.msg?res.msg:'操作失败', {icon: 5});
                }else{
                    layer.closeAll();
                    layer.alert(res.msg,{icon:1},function(){
                        if(res.url){
                            location.href=res.url
                        }else{
                            location.reload();
                        }

                    });
                }
            },'json');
            return false;
        });
    });

    $.post('./index.php?c=site&a=entry&do=web&m=sd_135K&s=order/remind','',function(res){
        $('#order_count').html(res.count);

    },'json');
    function startCount(){

        $.post('./index.php?c=site&a=entry&do=web&m=sd_135K&s=order/remind','',function(res){
            $('#order_count').html(res.count);
            if(res.code===2){
                var audio = document.createElement("audio");
                audio.src = "../addons/sd_135K/core/public/static/alert.mp3";
                audio.play();
                layui.layer.alert('您有新的订单请注意查看',{icon:6,title:'订单提醒',btn: ['查看'],skin: 'layui-layer-lan'},function(){
                    window.location.href='./index.php?c=site&a=entry&do=web&m=sd_135K&s=order%2Fappoint';
                });
            }
        },'json');
        setTimeout(startCount,10000);    //setTimeout是超时调用，使用递归模拟间歇调用
    }
    setTimeout(startCount,10000);    //1s后执行

    var E = window.wangEditor;
    var editor = new E('#editor');
    var $text = $('#text');
    editor.customConfig.zIndex = 100;
    editor.customConfig.uploadImgShowBase64 = true;
    editor.customConfig.onchange = function (html) {
        // 监控变化，同步更新到 textarea
        $text.val(html)
    };

    editor.create();

    $text.val(editor.txt.html())
});

function confirm(url,msg,icon,data){
    layer.confirm(msg, {
        icon:icon
    }, function(){
        $.post(url,data,function(res){
            if(res.code===1){
                layer.msg('操作失败',{icon:5});
            }else{
                layer.msg('操作成功',{icon:1});
                location.reload();
            }
        })
    })
}
function go(url) {
    window.location.href=url;
}

