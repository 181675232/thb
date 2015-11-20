<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>管理后台</title>
	<script type="text/javascript" src="/Public/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="/Public/js/scripts/jquery/Validform_v5.3.2_min.js"></script>
    <script type="text/javascript" src="/Public/js/scripts/lhgdialog/lhgdialog.js?skin=idialog"></script>
	<script type="text/javascript" src="/Public/js/scripts/swfupload/swfupload.js"></script>
	<script type="text/javascript" src="/Public/js/scripts/swfupload/swfupload.handlers.js"></script>
    <script type="text/javascript" src="/Public/js/layout.js"></script>	
	<script type="text/javascript" charset="utf-8" src="/Public/js/scripts/kindeditor/kindeditor.js"></script>
	<script type="text/javascript" charset="utf-8" src="/Public/js/scripts/kindeditor/lang/zh_CN.js"></script>
    <link href="/Public/admin/css/pagination.css" rel="stylesheet" type="text/css" />	
	<link href="/Public/admin/admin.css" rel="stylesheet" type="text/css" />
	<link href="/Public/admin/page.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/Public/js/check.js"></script>
	<script type="text/javascript">
		 //初始化编辑器
        KindEditor.ready(function(K) {
                window.editor = K.create('#content');
        });
        KindEditor.ready(function(K) {
                K.create('#content', {
						uploadJson : '/Public/js/scripts/kindeditor/php/upload_json.php',
						fileManagerJson : '/Public/js/scripts/kindeditor/php/file_manager_json.php',
                        allowFileManager : true
                });
        });
	    $(function () {
	        //初始化表单验证
	        $("#form").initValidform();
			 //初始化上传控件
	        $(".upload-img").each(function () {
	            $(this).InitSWFUpload({ sendurl: "/Admin/Public/upload", flashurl: "/Public/js/scripts/swfupload/swfupload.swf" });
	        });
	        $(".upload-album").each(function () {
	            $(this).InitSWFUpload({ btntext: "批量上传", btnwidth: 66, single: false, water: true, thumbnail: true, filesize: "2048", sendurl: "/Admin/Public/upload", flashurl: "/Public/js/scripts/swfupload/swfupload.swf", filetypes: "*.jpg;*.jpge;*.png;*.gif;" });
	        });
	        $(".attach-btn").click(function () {
	            showAttachDialog();
	        });
	        //设置封面图片的样式
	        $(".photo-list ul li .img-box img").each(function () {
	            if ($(this).attr("src") == $("#hidFocusPhoto").val()) {
	                $(this).parent().addClass("selected");
	            }
	        });
			$("#ddlParentId").change(function(){
				$.get('/Admin/Shop/selectajax',{id:this.value},function(data){
					 var dataobj = eval("("+data+")");
					 $("#ddlParentId1").prev().find('span').html('请选择在市级单位');					 
					 $("#ddlParentId1").html(dataobj.str);
					 $("#ddlParentId1").prev().find('ul').html(dataobj.str1);
				});
			});
			$("#ddlParentId1").change(function(){
				$.get('/Admin/Shop/selectajax1',{id:this.value},function(data){
					 var dataobj = eval("("+data+")");
					 $("#ddlParentId2").prev().find('span').html('请选择在区县单位');					 
					 $("#ddlParentId2").html(dataobj.str);
					 $("#ddlParentId2").prev().find('ul').html(dataobj.str1);
				});
			});
			$("#ddlParentId3").change(function(){
				$.get('/Admin/Shop/selectajax3',{id:this.value},function(data){
					if(data == 0){
						$("#checkbox").html('<div class="boxwrap"></div>');
					}else{
						 var dataobj = eval("("+data+")");	
						 $("#checkbox").html('<div class="boxwrap"></div>');
						 $("#checkbox").find('div').html(dataobj.str1);
					 		$("#checkbox").append(dataobj.str);
					}				 
				});
			});

	    });
		function sel(obj){		
			$(obj).siblings().removeClass("selected");
            $(obj).addClass("selected"); //添加选中样式
            var indexNum = $(obj).index();
			var titObj = $(obj).parents('.boxwrap');
            var selectObj = $(obj).parents('.boxwrap').next();
            //selectObj.find("option").attr("selected", false);			
           // selectObj.find("option").eq(indexNum).attr("selected", true); //赋值给对应的option
		   selectObj.get(0).selectedIndex =$(obj).index();
            titObj.find("span").text($(obj).text()); //赋值选中值        
            selectObj.trigger("change"); 		
		}
		
		function checkb(obj){
			if($(obj).attr("class")=='selected'){
				$(obj).removeClass("selected");         	
			}else{
				$(obj).addClass("selected"); //添加选中样式
			}		
            var indexNum = $(obj).index();
			var titObj = $(obj).parents('.boxwrap');
            var selectObj = $(obj).parents('.boxwrap').siblings('label');
            //selectObj.find("option").attr("selected", false);			
           // selectObj.find("option").eq(indexNum).attr("selected", true); //赋值给对应的option
		  selectObj.eq(indexNum).trigger("click"); 
            //titObj.find("span").text($(obj).text()); //赋值选中值        
            	
		}
		
	
		
	</script>
</head>
<body class="mainbody">
    <form id="form" method="post">
    <!--导航栏-->
<div class="location">
  <a href="javascript:history.back(-1);" class="back"><i></i><span>返回上一页</span></a>
  <a href="/Admin/Index/center" class="home"><i></i><span>首页</span></a>
  <i class="arrow"></i>
  <a href="/Admin/Shop"><span>商铺列表</span></a>
  <i class="arrow"></i>
  <span>新增信息</span>
</div>
<div class="line10"></div>
<!--/导航栏-->

<!--内容-->
<div class="content-tab-wrap">
  <div id="floatHead" class="content-tab">
    <div class="content-tab-ul-wrap">
      <ul>
        <li><a href="javascript:;" onclick="tabs(this);" class="selected">基本信息</a></li>
      </ul>
    </div>
  </div>
</div>

<div class="tab-content">
	<dl>
    <dt>头像</dt>
      <dd>
      	<img src="/Public/admin/touxiang.jpg" class="upload-img" style="width: 120px; height: 120px;" />
		<input type="hidden" id="txtImgUrl" name="simg" Class="input normal upload-path" />
      	<div style="position:relative; top: -13px; left: 5px;" class="upload-box upload-img"></div><span style="position:relative; top: -13px; left: 5px;" class="Validform_checktip">*建议上传1:1的jpg,png图片</span>
	</dd>
  </dl>
	<dl>
		<dt>市区</dt>
		<dd>
			<div class="rule-single-select">
				<select id="ddlParentId" name="provinceid">
					<option value="0" selected="selected">请选择在省级单位</option>
					<?php if(is_array($type1)): $i = 0; $__LIST__ = $type1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["provinceid"]); ?>"><?php echo ($val["province"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>
			<div class="rule-single-select">
				<select name="cityid" id="ddlParentId1"> 
					<option value="0" selected="selected">请选择在市级单位</option>
				</select>
			</div>
			
			<div class="rule-single-select">
				<select name="areaid" id="ddlParentId2"> 
					<option value="0" selected="selected">请选择在区县单位</option>
				</select>
			</div>
		</dd>
	</dl>
	<dl>
	<dt>店主手机号</dt>
		<dd><input type="text" Class="input normal" name="name"/> <span class="Validform_checktip" style="color:red">非必填，开分店时候添加总店店主手机号（即商家端登陆账号）</span></dd>
	</dl> 
	<dl>
	<dt>名称</dt>
		<dd><input type="text" name="name" datatype="*" Class="input normal" sucmsg=" " /> <span class="Validform_checktip">*</span></dd>
	</dl> 
	<dl>
	<dt>电话</dt>
		<dd><input type="text" name="phone"  Class="input normal" datatype="*" sucmsg=" " /><span class="Validform_checktip">*</span></dd>
	</dl>
	<dl>
	<dt>折扣</dt>
		<dd><input type="text" name="discount" datatype="n1-2" Class="input small"  sucmsg=" " errormsg="折扣范围0-99" value="0" /> <span class="Validform_checktip">*0-99之间的数字,0为不打折</span></dd>
	</dl>
	<dl>
	<dt>均价</dt>
		<dd><input type="text" name="price" datatype="n" Class="input small" sucmsg=" " /> <span class="Validform_checktip">*</span></dd>
	</dl>
	<dl>
	<dt>店铺评分</dt>
		<dd><input type="text" name="mark" datatype="*" Class="input small" sucmsg=" " /> <span class="Validform_checktip">*4.0-5.0之间</span></dd>
	</dl>
	<dl>
	<dt>营业时间</dt>
		<dd><input type="text" name="businesstime" Class="input normal" /> <span class="Validform_checktip">格式：8:00-22:00</span></dd>
	</dl>
	<dl>
	<dt>广告语</dt>
		<dd><input type="text" name="ads"  Class="input normal" datatype="*" sucmsg=" "  /><span class="Validform_checktip">*</span></dd>
	</dl>
	<dl>
	<dt>地址</dt>
		<dd><input type="text" name="address"  Class="input normal" datatype="*" sucmsg=" "  /><span class="Validform_checktip">*</span></dd>
	</dl>
	<dl>
	<dt>大类</dt>
		<dd>
			<div class="rule-single-select">
				<select id="ddlParentId3" name="groupid">
					<option value="0" selected="selected">请选择所属大类</option>
					<?php if(is_array($group1)): $i = 0; $__LIST__ = $group1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>"><?php echo ($val["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>
		</dd>
	</dl> 
	<dl>
    <dt>小类</dt>
    <dd>
		<div id="checkbox"  class="rule-multi-checkbox  multi-checkbox ">	
    	</div>
	</dd>
  </dl>
  <dl>
    <dt>优惠</dt>
    <dd>
		<div class="rule-multi-checkbox  multi-checkbox ">	
    		<label><input type="checkbox" Value="1" checked="checked" name="tags[]" />惠</label>
      		<label><input type="checkbox" Value="2" name="tags[]" />折</label>
			<label><input type="checkbox" Value="3" name="tags[]" />券</label>
			<label><input type="checkbox" Value="4" name="tags[]" />联</label>
			<label><input type="checkbox" Value="5" name="tags[]" />P</label>
			<label><input type="checkbox" Value="6" name="tags[]" />wifi</label>
    	</div>
	</dd>
  </dl>
  <dl>
		<dt>红色标签</dt>
		<dd>
			<textarea id="webcopyright" name="redtag" Class="input" /></textarea>
	      	<span class="Validform_checktip">标签之间用空格隔开</span>
		</dd>
	</dl>
  <dl>
		<dt>灰色标签</dt>
		<dd>
			<textarea id="webcopyright" name="blacktag" Class="input" /></textarea>
	      	<span class="Validform_checktip">标签之间用空格隔开</span>
		</dd>
	</dl>
	<dl>
		<dt>简介</dt>
		<dd>
			<textarea id="webcopyright" name="description" Class="input" /></textarea>
	      	<!--<span class="Validform_checktip">支持HTML</span>-->
		</dd>
	</dl>
	<dl>
    <dt>详情</dt>
    <dd><textarea id="content" name="content" style="width:700px;height:200px;visibility:hidden;"></textarea></dd>
  </dl>
  

</div>
<!--/内容-->

<!--工具栏-->
<div class="page-footer">
  <div class="btn-list">
    <input id="btnSubmit" type="submit" value="提交保存" Class="btn" />
    <input name="btnReturn" type="button" value="返回上一页" class="btn yellow" onclick="javascript:history.back(-1);" />
  </div>
  <div class="clear"></div>
</div>
<!--/工具栏-->
    </form>
</body>
</html>