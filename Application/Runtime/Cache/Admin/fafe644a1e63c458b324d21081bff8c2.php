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
    <link href="/Public/admin/css/pagination.css" rel="stylesheet" type="text/css" />	
	<link href="/Public/admin/admin.css" rel="stylesheet" type="text/css" />
	<link href="/Public/admin/page.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/Public/js/check.js"></script>
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
  <a href="/Admin/Model"><span>车系</span></a>
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
      </ul>
    </div>
  </div>
</div>

<div class="tab-content">
	<input type="hidden" name="ID" value="<?php echo ($id); ?>" />
	<dl>
		<dt>品牌</dt>
		<dd>
			<div class="rule-single-select">
				<select name="MFG_ID" datatype="*" sucmsg=" " id="ddlParentId">
					<option value=" ">请选择所属品牌</option>
					<?php if(is_array($mfg)): $i = 0; $__LIST__ = $mfg;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($val["id"] == $mfg_id): ?>selected="selected"<?php endif; ?> ><?php echo ($val["mfg"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>
		</dd>
	</dl> 
	<dl>
		<dt>车系</dt>
		<dd><input type="text" name="MODEL" value="<?php echo ($model); ?>" datatype="*" Class="input normal" sucmsg=" " /> <span class="Validform_checktip">*</span></dd>
	</dl> 
	<!-- 
  <dl>
    <dt>性别</dt>
    <dd>
		<div class="rule-multi-radio multi-radio">	
    		<label><input type="radio" Value="0" <?php if($sex == 0): ?>checked="checked"<?php endif; ?> name="sex" />保密</label>
      		<label><input type="radio" Value="1" <?php if($sex == 1): ?>checked="checked"<?php endif; ?> name="sex" />男</label>
			<label><input type="radio" Value="2" <?php if($sex == 2): ?>checked="checked"<?php endif; ?> name="sex" />女</label>
    	</div>
	</dd>
  </dl>
   -->
 </div>
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