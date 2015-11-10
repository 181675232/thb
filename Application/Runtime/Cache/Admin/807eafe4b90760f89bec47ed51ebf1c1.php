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
	    });	
	</script>
</head>
<body class="mainbody">
    <form id="form" method="post">
    <!--导航栏-->
<div class="location">
  <a href="javascript:history.back(-1);" class="back"><i></i><span>返回上一页</span></a>
  <a href="/Admin/Index/center" class="home"><i></i><span>首页</span></a>
  <i class="arrow"></i>
  <a href="/Admin/Nav"><span>分类列表</span></a>
  <i class="arrow"></i>
  <span>新增</span>
</div>
<div class="line10"></div>
<!--/导航栏-->

<!--内容-->
<div class="content-tab-wrap">
  <div id="floatHead" class="content-tab">
    <div class="content-tab-ul-wrap">
      <ul>
        <li><a href="javascript:;" onclick="tabs(this);" class="selected">基本信息</a></li>
<!--		<li><a href="javascript:;" onclick="tabs(this);">账户信息</a></li>-->
      </ul>
    </div>
  </div>
</div>

<div class="tab-content">
	
  <dl>
    <dt>上级导航</dt>
    <dd>
      <div class="rule-single-select">
      	<select name="pid" id="ddlParentId">
      		<!--
      		<?php if(empty($bid)): ?><option value="0" selected="selected">无父级导航</option>
      			<?php else: ?>
				<option value="0">无父级导航</option><?php endif; ?>	
			-->
			<?php if(is_array($group)): $i = 0; $__LIST__ = $group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($val["id"] == $pid): ?>selected="selected"<?php endif; ?>><?php echo ($val["title"]); ?></option>
				<!--
				<?php if(is_array($val["catid"])): $i = 0; $__LIST__ = $val["catid"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val1): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val1["id"]); ?>" <?php if($val1["id"] == $bid): ?>selected="selected"<?php endif; ?>>　├ <?php echo ($val1["title"]); ?></option>
					<?php if(is_array($val1["catid"])): $i = 0; $__LIST__ = $val1["catid"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val2): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val2["id"]); ?>" <?php if($val2["id"] == $bid): ?>selected="selected"<?php endif; ?>>　　├ <?php echo ($val2["title"]); ?></option>
						<?php if(is_array($val2["catid"])): foreach($val2["catid"] as $key=>$val3): ?><option value="<?php echo ($val3["id"]); ?>" <?php if($val3["id"] == $bid): ?>selected="selected"<?php endif; ?>>　　　├ <?php echo ($val3["title"]); ?></option><?php endforeach; endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
				--><?php endforeach; endif; else: echo "" ;endif; ?>
		</select>
      </div>
    </dd>
  </dl>
  <dl>
    <dt>分类名称</dt>
      <dd><input type="text" name="title" datatype="*" Class="input normal" sucmsg=" " nullmsg="请输入导航名称！" /> <span class="Validform_checktip">*</span></dd>
  </dl> 
  <dl>
    <dt>排序</dt>
      <dd><input type="text" name="ord" Class="input small" datatype="n" sucmsg=" "  value="99" /><span class="Validform_checktip">*数字，越小越向前</span></dd>
  </dl>
</div>
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