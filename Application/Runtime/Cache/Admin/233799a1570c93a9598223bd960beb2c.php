<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>管理后台</title>
	<script type="text/javascript" src="/Public/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/Public/js/scripts/lhgdialog/lhgdialog.js?skin=idialog"></script>
    <script type="text/javascript" src="/Public/js/layout.js"></script>	
    <link href="/Public/admin/css/pagination.css" rel="stylesheet" type="text/css" />	
	<link href="/Public/admin/base.css" rel="stylesheet" type="text/css" />
	<link href="/Public/admin/layout.css" rel="stylesheet" type="text/css" />
	<link href="/Public/admin/admin.css" rel="stylesheet" type="text/css" />
	<link href="/Public/admin/page.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/Public/js/check.js"></script>
</head>
<body class="mainbody">
    <form id="form1" method="get">
    <div>
        <!--导航栏-->
        <div class="location">
            <a href="javascript:history.back(-1);" class="back"><i></i><span>返回上一页</span></a>
            <a href="/Admin/Index/center" class="home"><i></i><span>首页</span></a> <i class="arrow">
            </i><span>订单列表</span>
        </div>
        <!--/导航栏-->
        <!--工具栏-->	
        <div class="toolbar-wrap">
            <div id="floatHead" class="toolbar">
                <div class="l-list">
                    <ul class="icon-list">
                       <!-- <li><a class="add" href="<?php echo U('/Admin/Activity/add');?>"><i></i><span> 新增</span></a></li>-->
						<!--<li><a id="btnSave" Class="save"><i></i><span>保存</span></a></li>-->
                        <li><a class="all" href="javascript:;" onclick="checkAll(this)"><i></i><span>全选</span></a></li>
                        <li><a class="del" style="cursor:pointer;" id="btnDelete" OnClick="return ExePostBack('Order')"><i></i><span>删除</span></a></li>
                    </ul>
					<div class="menu-list">

				        <div class="rule-single-select single-select">
				          <select id="ddlProperty" name="state" onchange="location='/Admin/Order/index/state/'+options[selectedIndex].value">
				            <option Value=""  <?php if($state == ''): ?>selected="selected"<?php endif; ?>>全部</option>
				            <option Value="0" <?php if($state == '0'): ?>selected="selected"<?php endif; ?>>未付款</option>
				            <option Value="1" <?php if($state == '1'): ?>selected="selected"<?php endif; ?>>已付款</option>
				            <option Value="2" <?php if($state == '2'): ?>selected="selected"<?php endif; ?>>已取消</option>
				          </select>
				        </div>

      				</div>
                </div>
                <div class="r-list">
                    <input type="text" id="txtKeywords" Class="keyword" name="keyword" />
                    <input type="submit" id="lbtnSearch" name="search" Class="btn-search" value="查询" />
                </div>
            </div>
        </div>
        <!--/工具栏-->
		
		<!--文字列表-->
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="ltable">
		  <tr>
		    <th width="8%">选择</th>
		     <th align="center" width="15%">订单号</th>
		    <th align="center" width="20%">订单名称</th>
			<th align="center" width="10%">会员账号(手机号)</th>
			<th align="center" width="10%">金额</th>		    
			<th align="center" width="10%">订单状态</th>
			<th align="center" width="15%">发布时间</th>
		    <th align="center" width="">操作</th>
		  </tr>
		<?php if($data): if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
			    <td align="center"><input type="checkbox" Class="checkall" value="<?php echo ($val["id"]); ?>" Style="vertical-align: middle;" /></td>
			     <td align="center"><?php echo ($val["order"]); ?></td>
			    <td align="center"><?php echo ($val["title"]); ?></td>
				<td align="center"><?php echo ($val["uname"]); ?></td>
				<td align="center"><?php echo ($val["price"]); ?></td>
			    <td align="center">
			    	<?php if($val['state'] == 0): ?>未付款
						<?php elseif($val['state'] == 1): ?>
						已付款
						<?php elseif($val['state'] == 2): ?>
						已取消
						<?php else: ?>
						草稿<?php endif; ?>
			    </td>
				<td align="center"><?php echo (date( "Y-m-d H:i:s",$val["time"])); ?></td>
			    <td align="center"><a href="/Admin/Order/edit/id/<?php echo ($val["id"]); ?>">详细</a></td>
			  </tr><?php endforeach; endif; else: echo "" ;endif; ?>
		<?php else: ?>
			<tr><td align="center" colspan="7">暂无记录</td></tr><?php endif; ?>
		</table>
		<!--/文字列表-->
        <!--内容底部-->
        <div class="line20">
        </div>
        <div class="pagelist">
            <div class="flickr">
                <?php echo ($page); ?>
            </div>
        </div>
        <!--/内容底部-->
    </div>
    </form>
</body>
</html>