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
  <a href="/Admin/User"><span>用户</span></a>
  <i class="arrow"></i>
  <span>修改用户信息</span>
</div>
<div class="line10"></div>
<!--/导航栏-->

<!--内容-->
<div class="content-tab-wrap">
  <div id="floatHead" class="content-tab">
    <div class="content-tab-ul-wrap">
      <ul>
        <li><a href="javascript:;" onclick="tabs(this);" class="selected">基本信息</a></li>
		<li><a href="javascript:;" onclick="tabs(this);">账户信息</a></li>
      </ul>
    </div>
  </div>
</div>

<div class="tab-content">
	<input type="hidden" name="id" value="<?php echo ($id); ?>" />
	<dl>
    <dt>头像</dt>
      <dd>
      	<?php if(empty($simg)): ?><img src="/Public/admin/touxiang.jpg" class="upload-img" style="width: 120px; height: 120px; border-radius: 50%" />
			<input type="hidden" id="txtImgUrl" name="simg" Class="input normal upload-path" />
		<?php else: ?>
			<img src="<?php echo ($simg); ?>" class="upload-img" style="width: 120px; height: 120px; border-radius: 50%" />
			<input type="hidden" id="txtImgUrl" value="<?php echo ($simg); ?>" name="simg" Class="input normal upload-path" /><?php endif; ?>
      	<div style="position:relative; top: -13px; left: 5px;" class="upload-box upload-img"></div><span style="position:relative; top: -13px; left: 5px;" class="Validform_checktip">*建议上传1:1的jpg,png图片</span>
	</dd>
  </dl>
  <dl>
    <dt>用户名</dt>
      <dd><input type="text" name="name" value="<?php echo ($name); ?>" datatype="*" Class="input normal" sucmsg=" " nullmsg="请输入用户名！" /> <span class="Validform_checktip">*</span></dd>
  </dl> 
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
  <dl>
    <dt>手机</dt>
    <dd><input type="text" name="phone" value="<?php echo ($phone); ?>" datatype="m"  nullmsg="请输入手机号！" errormsg="请输入正确的手机号码！" sucmsg=" " Class="input normal" /></dd>
  </dl>
  <dl>
    <dt>Email</dt>
    <dd><input type="text" name="email" value="<?php echo ($email); ?>" datatype="/^\s*$|[\w\-\.]+@[\w\-\.]+(\.\w+)+$/" errormsg="请输入正确的邮箱！" sucmsg=" " Class="input normal" /></dd>
  </dl>
  <dl>
    <dt>地址</dt>
    <dd><input type="text" name="address" value="<?php echo ($address); ?>" Class="input normal" /></dd>
  </dl>
  <dl>
    <dt>注册时间</dt>
    <dd><input type="text" name="addtime" value="<?php echo (date('Y-m-d H:i:s',$addtime)); ?>" disabled="disabled" Class="input normal" /></dd>
  </dl>
  <!-- 
   <dl>
    <dt>认证状态</dt>
    <dd>
    	<div class="rule-multi-radio multi-radio">	
    		<label><input type="radio" Value="0" <?php if($verify == 0): ?>checked="checked"<?php endif; ?> name="verify" />未认证</label>
      		<label><input type="radio" Value="1" <?php if($verify == 1): ?>checked="checked"<?php endif; ?> name="verify" />认证中</label>
			<label><input type="radio" Value="2" <?php if($verify == 2): ?>checked="checked"<?php endif; ?> name="verify" />已认证</label>
    	</div>
  </dl>
   -->
  <!--
  <dl ID="div_albums_container" runat="server" visible="false">
    <dt>图片相册</dt>
    <dd>
      <div class="upload-box upload-album"></div>
      <input type="hidden" name="hidFocusPhoto" id="hidFocusPhoto" class="focus-photo" />
      <div class="photo-list">
        <ul>
			<li>
              <input type="hidden" name="hid_photo_name" value="<%#Eval("id")%>|<%#Eval("original_path")%>|<%#Eval("thumb_path")%>" />
              <input type="hidden" name="hid_photo_remark" value="<%#Eval("remark")%>" />
              <div class="img-box" onclick="setFocusImg(this);">
                <img src="<%#Eval("thumb_path")%>" bigsrc="<%#Eval("original_path")%>" />
                <span class="remark"><i><%#Eval("remark").ToString() == "" ? "暂无描述..." : Eval("remark").ToString()%></i></span>
              </div>
              <a href="javascript:;" onclick="setRemark(this);">描述</a>
              <a href="javascript:;" onclick="delImg(this);">删除</a>
            </li>
        </ul>
      </div>
    </dd>
  </dl>

  <dl ID="div_attach_container" runat="server" visible="false">
    <dt>上传附件</dt>
    <dd>
      <a class="icon-btn add attach-btn"><span>添加附件</span></a>
      <div id="showAttachList" class="attach-list">
        <ul>

              <li>
                <input name="hid_attach_id" type="hidden" value="" />
                <input name="hid_attach_filename" type="hidden" value="" />
                <input name="hid_attach_filepath" type="hidden" value="" />
                <input name="hid_attach_filesize" type="hidden" value="" />
                <i class="icon"></i>
                <a href="javascript:;" onclick="delAttachNode(this);" class="del" title="删除附件"></a>
                <a href="javascript:;" onclick="showAttachDialog(this);" class="edit" title="更新附件"></a>
                <div class="title"></div>
                <div class="info">类型：<span class="ext"></span> 大小：<span class="size"></span> 下载：<span class="down"></span>次</div>
                <div class="btns">下载积分：<input type="text" name="txt_attach_point" onkeydown="return checkNumber(event);" value="" /></div>
              </li>

        </ul>
      </div>
    </dd>
  </dl>
  -->
</div>


<div class="tab-content" style="display: none;">
  <dl>
    <dt>帮币余额</dt>
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
  <!--
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