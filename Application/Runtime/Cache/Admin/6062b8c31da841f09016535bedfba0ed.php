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
  <a href="/Admin/Nav"><span>导航列表</span></a>
  <i class="arrow"></i>
  <span>编辑后台导航</span>
</div>
<div class="line10"></div>
<!--/导航栏-->

<!--内容-->
<div class="content-tab-wrap">
  <div id="floatHead" class="content-tab">
    <div class="content-tab-ul-wrap">
      <ul>
        <li><a href="javascript:;" onclick="tabs(this);" class="selected">后台导航信息</a></li>
<!--		<li><a href="javascript:;" onclick="tabs(this);">账户信息</a></li>-->
      </ul>
    </div>
  </div>
</div>

<div class="tab-content">
	
  <dl>
    <dt>上级导航</dt>
	<input type="hidden" name="id" value="<?php echo ($id); ?>" />
    <dd>
      <div class="rule-single-select">
      	<select name="bid" id="ddlParentId">
      		<?php if(empty($bid)): ?><option value="0" selected="selected">无父级导航</option>
      			<?php else: ?>
				<option value="0">无父级导航</option><?php endif; ?>		
			<?php if(is_array($nav)): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($val["id"] == $bid): ?>selected="selected"<?php endif; ?>><?php echo ($val["title"]); ?></option>
				<?php if(is_array($val["catid"])): $i = 0; $__LIST__ = $val["catid"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val1): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val1["id"]); ?>" <?php if($val1["id"] == $bid): ?>selected="selected"<?php endif; ?>>　├ <?php echo ($val1["title"]); ?></option>
					<?php if(is_array($val1["catid"])): $i = 0; $__LIST__ = $val1["catid"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val2): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val2["id"]); ?>" <?php if($val2["id"] == $bid): ?>selected="selected"<?php endif; ?>>　　├ <?php echo ($val2["title"]); ?></option>
						<?php if(is_array($val2["catid"])): foreach($val2["catid"] as $key=>$val3): ?><option value="<?php echo ($val3["id"]); ?>" <?php if($val3["id"] == $bid): ?>selected="selected"<?php endif; ?>>　　　├ <?php echo ($val3["title"]); ?></option><?php endforeach; endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
		</select>
      </div>
    </dd>
  </dl>
  <dl>
    <dt>导航名称</dt>
      <dd><input type="text" name="title" datatype="*" Class="input normal" sucmsg=" " nullmsg="请输入导航名称！" value="<?php echo ($title); ?>" /> <span class="Validform_checktip">*</span></dd>
  </dl> 
  <dl>
    <dt>是否显示</dt>
    <dd>
		<div class="rule-multi-radio multi-radio">	
    		<label><input type="radio" Value="1" <?php if($state == 1): ?>checked="checked"<?php endif; ?> name="state" />是</label>
      		<label><input type="radio" Value="0" <?php if($state == 0): ?>checked="checked"<?php endif; ?> name="state" />否</label>
    	</div>
	</dd>
  </dl>
  <dl>
    <dt>调用ID</dt>
      <dd><input type="text" name="name" datatype="*" Class="input normal" sucmsg=" " nullmsg="请输入调用ID！" value="<?php echo ($name); ?>" /> <span class="Validform_checktip">*控制权限</span></dd>
  </dl>
  <dl>
    <dt>权限等级</dt>
      <dd><input type="text" name="level" datatype="*" Class="input normal" sucmsg=" " nullmsg="请输入权限等级！" value="<?php echo ($level); ?>" /> <span class="Validform_checktip">*权限等级（1.模块 2.控制器 3.方法）</span></dd>
  </dl>
  <dl>
    <dt>链接地址</dt>
      <dd><input type="text" name="url" Class="input normal" value="<?php echo ($url); ?>" /></dd>
  </dl>
  <dl>
    <dt>权限资源</dt>
    <dd>
      <div class="rule-multi-porp multi-porp">
          <span id="cblActionType" style="display: none;">
			  <input id="cblActionType_1" type="checkbox" name="cblActionType[]" <?php if(in_array('index',$select)){ ?>checked="checked"<?php } ?> value="index 查看">
			  <label for="cblActionType_1">查看(Index)</label>
			  <input id="cblActionType_2" type="checkbox" name="cblActionType[]" <?php if(in_array('add',$select)){ ?>checked="checked"<?php } ?> value="add 添加">
			  <label for="cblActionType_2">添加(Add)</label>
			  <input id="cblActionType_3" type="checkbox" name="cblActionType[]" <?php if(in_array('edit',$select)){ ?>checked="checked"<?php } ?> value="edit 修改">
			  <label for="cblActionType_3">修改(Edit)</label>
			  <input id="cblActionType_4" type="checkbox" name="cblActionType[]" <?php if(in_array('delete',$select)){ ?>checked="checked"<?php } ?> value="delete 删除">
			  <label for="cblActionType_4">删除(Delete)</label>
			  <input id="cblActionType_5" type="checkbox" name="cblActionType[]" <?php if(in_array('state',$select)){ ?>checked="checked"<?php } ?> value="state 审核">
			  <label for="cblActionType_5">审核(State)</label>
			  <input id="cblActionType_6" type="checkbox" name="cblActionType[]" <?php if(in_array('reply',$select)){ ?>checked="checked"<?php } ?> value="reply 回复">
			  <label for="cblActionType_6">回复(Reply)</label>
			  <input id="cblActionType_15" type="checkbox" name="cblActionType[]" <?php if(in_array('reset',$select)){ ?>checked="checked"<?php } ?> value="reset 重置">
			  <label for="cblActionType_15">重置(Reset)</label>
			  <!--
			  <input id="cblActionType_7" type="checkbox" name="cblActionType$7">
			  <label for="cblActionType_7">确认(Confirm)</label>
			  <input id="cblActionType_8" type="checkbox" name="cblActionType$8">
			  <label for="cblActionType_8">取消(Cancel)</label>
			  <input id="cblActionType_9" type="checkbox" name="cblActionType$9">
			  <label for="cblActionType_9">作废(Invalid)</label>
			  <input id="cblActionType_10" type="checkbox" name="cblActionType$10">
			  <label for="cblActionType_10">生成(Build)</label>
			  <input id="cblActionType_11" type="checkbox" name="cblActionType$11">
			  <label for="cblActionType_11">安装(Instal)</label>
			  <input id="cblActionType_12" type="checkbox" name="cblActionType$12">
			  <label for="cblActionType_12">卸载(Unload)</label>
			  <input id="cblActionType_13" type="checkbox" name="cblActionType$13">
			  <label for="cblActionType_13">备份(Back)</label>
			  <input id="cblActionType_14" type="checkbox" name="cblActionType$14">
			  <label for="cblActionType_14">还原(Restore)</label>
			  -->
		  </span>
      </div>
    </dd>
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