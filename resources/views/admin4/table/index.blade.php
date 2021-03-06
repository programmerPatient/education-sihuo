<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<!--[if lt IE 9]>
<script type="text/javascript" src="/admin/lib/html5shiv.js"></script>
<script type="text/javascript" src="/admin/lib/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="/admin/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="/admin/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="/admin/lib/Hui-iconfont/1.0.8/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/admin/static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="/admin/static/h-ui.admin/css/style.css" />
<script type='text/javascript' src='{{Session::get("tableau_domain")}}/javascripts/api/viz_v1.js'></script>
<!--[if IE 6]>
<script type="text/javascript" src="/admin/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>我的桌面</title>
</head>
<body>
<!--     <div class='tableauPlaceholder' id='viz1567761350433' style='position: relative'>
        <noscript>
            <a href='#'>
                <img alt=' ' src='https:&#47;&#47;public.tableau.com&#47;static&#47;images&#47;Cr&#47;CreditCardsCampaignAnalysis&#47;CampaignsbyCreditCards&#47;1_rss.png' style='border: none' />
            </a>
        </noscript>
        <object class='tableauViz'  style='display:none;'>
            <param name='host_url' value='https%3A%2F%2Fpublic.tableau.com%2F' />
            <param name='embed_code_version' value='3' />
            <param name='site_root' value='' />
            <param name='name' value='CreditCardsCampaignAnalysis&#47;CampaignsbyCreditCards' />
            <param name='tabs' value='{{$contentUrl}}' />
            <param name='toolbar' value='yes' />
            <param name='static_image' value='https:&#47;&#47;public.tableau.com&#47;static&#47;images&#47;Cr&#47;CreditCardsCampaignAnalysis&#47;CampaignsbyCreditCards&#47;1.png' />
            <param name='animate_transition' value='yes' />
            <param name='display_static_image' value='yes' />
            <param name='display_spinner' value='yes' />
            <param name='display_overlay' value='yes' />
            <param name='display_count' value='yes' />
        </object>
    </div> -->
    <div class='tableauPlaceholder'>
        <button onClick="collection('{{$filter}}','{{$hascontentUrl}}','{{$report_id}}')" class="btn btn-primary radius" style="position:fixed;right:0;top:48%;bottom:0;">收藏</button>
        <object id="obj" class='tableauViz' width='500' height='1014' style='display:none;'>
            <param name="ticket" value="{{$ticket}}" />
            <param name='host_url' value='{{Session::get("tableau_domain")}}/' />
            <param name='embed_code_version' value='3' />
            <param name='site_root' value='' />
            <param name='name' value='{{$contentUrl}}' />
            <param name='tabs' value='no' />
            <param name='toolbar' value='{{$toolbar}}' />
            <param name='showAppBanner' value='false' />
            <param name='filter' value='{{$filter}}' />
        </object>
    </div>
<script type="text/javascript" src="/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/admin/static/h-ui/js/H-ui.min.js"></script>
<!-- <script type="text/javascript">
    $(document).ready(function(){
             $.get("/admin/table/refresh",function(data,status){
                console.log("更行成功");
            });
        });
</script> -->
<!-- <script type='text/javascript'>
    var divElement = document.getElementById('viz1567761350433');
    var vizElement = divElement.getElementsByTagName('object')[0];
    vizElement.style.width='1000px';
    vizElement.style.height='827px';
    var scriptElement = document.createElement('script');
    scriptElement.src = 'https://public.tableau.com/javascripts/api/viz_v1.js';
    vizElement.parentNode.insertBefore(scriptElement, vizElement);
</script> -->
    <!-- <script type='text/javascript'>
        var divElement = document.getElementById('viz1567761350433');
        var vizElement = divElement.getElementsByTagName('object')[0];
        vizElement.style.width='1000px';vizElement.style.height='827px';
        var scriptElement = document.createElement('script');
        scriptElement.src = 'https://public.tableau.com/javascripts/api/viz_v1.js';
        vizElement.parentNode.insertBefore(scriptElement, vizElement);
    </script> -->
</body>
<script type="text/javascript" src="/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript">


    var width = document.body.clientWidth;
    var height =  document.body.clientHeight;
    document.getElementById('obj').width = width;
    document.getElementById('obj').height = height;
    window.onresize = function(){
        //监听浏览器窗口的大小的改变
        width = document.body.clientWidth;
        height = document.body.clientHeight;
        document.getElementById('obj').width = width;
        document.getElementById('obj').height = height;
        window.location.reload();
    }


    function collection(filter,hascontentUrl,report_id){
    // alter(obj);
    // console.log(obj.html());
    $.ajax({
        // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
        type: 'POST',
        url: '/adminfour/report/collection',
        data:{'contentUrl':hascontentUrl,'filter':filter,'report_id':report_id,'co':true},
        dataType: 'json',
        success: function(data){
             if(data == '1'){
                // console.log('sss');
                    layer.msg('收藏成功!',{icon:1,time:1000},function(){
                    });
                }else{
                    layer.msg('收藏失败或已经收藏过!',{icon:2,time:2000});
                }
        },
        error:function(data) {
            alert('收藏失败！');
        },
    });
}

</script>
</html>
