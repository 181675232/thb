<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>管理后台</title>
	<script type="text/javascript" src="/Public/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="/Public/js/scripts/jquery/Validform_v5.3.2_min.js"></script>
    <script type="text/javascript" src="/Public/js/scripts/lhgdialog/lhgdialog.js?skin=idialog"></script>
	<script type="text/javascript" src="/Public/js/scripts/datepicker/WdatePicker.js"></script>
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
			 KindEditor.ready(function(K) {
	                window.editor = K.create('#content1');
	        });
	        KindEditor.ready(function(K) {
	                K.create('#content1', {
							uploadJson : '/Public/js/scripts/kindeditor/php/upload_json.php',
							fileManagerJson : '/Public/js/scripts/kindeditor/php/file_manager_json.php',
	                        allowFileManager : true
	                });
	        });
		
	</script>
	<script type="text/javascript">
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
	    });
	    //创建附件窗口
	    function showAttachDialog(obj) {
	        var objNum = arguments.length;
	        var attachDialog = $.dialog({
	            id: 'attachDialogId',
	            lock: true,
	            max: false,
	            min: false,
	            title: "上传附件",
	            content: 'url:dialog/dialog_attach.aspx',
	            width: 500,
	            height: 180
	        });
	        //如果是修改状态，将对象传进去
	        if (objNum == 1) {
	            attachDialog.data = obj;
	        }
	    }
	    //删除附件节点
	    function delAttachNode(obj) {
	        $(obj).parent().remove();
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
  <a href="/Admin/Activity"><span>幻灯列表</span></a>
  <i class="arrow"></i>
  <span>修改信息</span>
</div>
<div class="line10"></div>
<!--/导航栏-->

<!--内容-->
<div class="content-tab-wrap">
  <div id="floatHead" class="content-tab">
    <div class="content-tab-ul-wrap">
      <ul>
        <li><a href="javascript:;" onclick="tabs(this);" class="selected">基本信息</a></li>
<!--		<li><a href="javascript:;" onclick="tabs(this);">详细信息</a></li>-->
      </ul>
    </div>
  </div>
</div>

<div class="tab-content">
	<input type="hidden" name="id" value="<?php echo ($id); ?>" />
	<dl>
    <dt>活动正方图片</dt>
      <dd>
			<img src="<?php echo ($simg); ?>" class="upload-img" style="width: 250px; height: 250px; " />
			<input type="hidden" name="simg" id="txtImgUrl" value="<?php echo ($simg); ?>"  Class="input normal upload-path" />
      	<div style="position:relative; top: -13px; left: 5px;" class="upload-box upload-img"></div><span style="position:relative; top: -13px; left: 5px;" class="Validform_checktip">*建议上传400:400像素的jpg,png图片</span>
	</dd>
  </dl>
  <dl>
    <dt>活动长方图片</dt>
      <dd>
			<img src="<?php echo ($img); ?>" class="upload-img" style="width: 250px; height: 125px; " />
			<input type="hidden" name="img" id="txtImgUrl" value="<?php echo ($img); ?>"  Class="input normal upload-path" />
      	<div style="position:relative; top: -13px; left: 5px;" class="upload-box upload-img"></div><span style="position:relative; top: -13px; left: 5px;" class="Validform_checktip">*建议上传400:200像素的jpg,png图片</span>
	</dd>
  </dl>
  <dl>
	<dt>活动商铺编号</dt>
		<dd><input value="<?php echo ($shopid); ?>" type="text" name="shopid" datatype="n" sucmsg=" " Class="input normal" /><span class="Validform_checktip">*</span></dd>
	</dl>
	<dl>
	<dt>标题</dt>
		<dd><input value="<?php echo ($title); ?>" type="text" name="title" Class="input normal" /></dd>
	</dl>
	<dl>
	<dt>排序</dt>
		<dd><input value="<?php echo ($ord); ?>" type="text" name="ord" Class="input small" /><span class="Validform_checktip">数字越小越在前面</span></dd>
	</dl>
	<!--
	<dl>
		<dt>简介</dt>
		<dd>
			<textarea id="webcopyright" name="description" Class="input" /></textarea>
	      	<span class="Validform_checktip">支持HTML</span>
		</dd>
	</dl>
	
	<dl>
    <dt>详情</dt>
    <dd><textarea id="content" name="content" style="width:700px;height:200px;visibility:hidden;"><?php echo ($content); ?></textarea></dd>
  </dl>
  -->
  <!--
  <dl>
    <dt>发布时间</dt>
    <dd>
    	<div class="input-date">
        <input type="text" name="create_ts" value="<?php echo (date('Y-m-d H:i:s',$addtime)); ?>" id="txtAddTime" Class="input date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" datatype="/^\s*$|^\d{4}\-\d{1,2}\-\d{1,2}\s{1}(\d{1,2}:){2}\d{1,2}$/" errormsg="请选择正确的日期" sucmsg=" " />
        <i>日期</i>
      </div>
	</dd>
  </dl>
-->
</div>

<!--
<div class="tab-content" style="display: none;">
  <dl>
    <dt>账户金额</dt>
    <dd>
      <input name="txtAmount" type="text" value="0" id="txtAmount" class="input small" datatype="/^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/" sucmsg=" "> 元
      <span class="Validform_checktip">*账户上的余额</span>
    </dd>
  </dl>
  <dl>
    <dt>账户积分</dt>
    <dd>
      <input name="txtPoint" type="text" value="0" id="txtPoint" class="input small" datatype="n" sucmsg=" "> 分
      <span class="Validform_checktip">*积分也可做为交易</span>
    </dd>
  </dl>
  <dl>
    <dt>升级经验值</dt>
    <dd>
      <input name="txtExp" type="text" value="0" id="txtExp" class="input small" datatype="n" sucmsg=" ">
      <span class="Validform_checktip">*根据积分计算得来，与积分不同的是只增不减</span>
    </dd>
  </dl>
  <dl>
    <dt>注册时间</dt>
    <dd><span id="lblRegTime">-</span></dd>
  </dl>
  <dl>
    <dt>注册IP</dt>
    <dd><span id="lblRegIP">-</span></dd>
  </dl>
  <dl>
    <dt>最近登录时间</dt>
    <dd><span id="lblLastTime">-</span></dd>
  </dl>
  <dl>
    <dt>最近登录IP</dt>
    <dd><span id="lblLastIP">-</span></dd>
  </dl>
  <dl>
    <dt>最近登录IP</dt>
    <dd><textarea id="content" name="content" style="width:700px;height:200px;visibility:hidden;"></textarea></dd>
  </dl>
  
</div>
-->
<!--/内容-->

<!--工具栏-->
<div class="page-footer">
  <div class="btn-list">
    <input id="btnSubmit" type="submit" value="提交保存" Class="btn" onclick="btnSubmit_Click" />
    <input name="btnReturn" type="button" value="返回上一页" class="btn yellow" onclick="javascript:history.back(-1);" />
  </div>
  <div class="clear"></div>
</div>
<!--/工具栏-->
    </form>
</body>
</html>