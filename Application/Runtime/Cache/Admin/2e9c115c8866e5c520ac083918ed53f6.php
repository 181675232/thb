<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>管理后台</title>
	<script type="text/javascript" src="/Public/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="/Public/js/scripts/jquery/Validform_v5.3.2_min.js"></script>
    <script type="text/javascript" src="/Public/js/scripts/lhgdialog/lhgdialog.js?skin=idialog"></script>
	<script type="text/javascript" src="/Public/js/scripts/swfupload/swfupload.js"></script>
	<script type="text/javascript" src="/Public/js/scripts/swfupload/swfupload.handlers.js"></script>
	<script type="text/javascript" src="/Public/js/scripts/datepicker/WdatePicker.js"></script>
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
		
		function otable(str){
			if(str == 2){
				$('#otable').css('display','block');
			}else{
				$('#otable').css('display','none');
			}
		}
		
		function addtable(){
			$str = '<tr><td style="padding: 5px;"><input type="text" name="tab[]" style="width: 160px;height: 26px;text-align: center;"  /></td><td style="padding: 5px;"><input type="text" name="tab[]" style="width: 160px;height: 26px;text-align: center;"  /></td><td style="padding: 5px;"><input type="text" name="tab[]" style="width: 160px;height: 26px;text-align: center;"  /></td></tr>';
			$('#tab').append($str);
		}
		
		function deltable(){
			$("#tab tr:last").remove();
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
  <a href="/Admin/Shop"><span>用户</span></a>
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
	<input type="hidden" name="id" value="<?php echo ($id); ?>" />
	<dl>
    <dt>头像</dt>
      <dd>
      	<?php if(empty($simg)): ?><img src="/Public/admin/touxiang.jpg" class="upload-img" style="width: 120px; height: 120px;" />
			<input type="hidden" id="txtImgUrl" name="simg" Class="input normal upload-path" />
		<?php else: ?>
			<img src="<?php echo ($simg); ?>" class="upload-img" style="width: 120px; height: 120px;" />
			<input type="hidden" id="txtImgUrl" value="<?php echo ($simg); ?>" name="simg" Class="input normal upload-path" /><?php endif; ?>
      	<div style="position:relative; top: -13px; left: 5px;" class="upload-box upload-img"></div><span style="position:relative; top: -13px; left: 5px;" class="Validform_checktip">*建议上传1:1的jpg,png图片</span>
	</dd>
  </dl>
  <dl>
		<dt>商铺</dt>
		<dd>
			<div class="rule-single-select">
				<select id="ddlParentId" name="pid">
					<option value="0" selected="selected">请选择所属商铺</option>
					<?php if(is_array($type1)): $i = 0; $__LIST__ = $type1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($val["id"] == $pid): ?>selected="selected"<?php endif; ?>><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>
		</dd>
	</dl>
	<dl>
	<dt>标题</dt>
		<dd><input type="text" value="<?php echo ($name); ?>" name="name" datatype="*" Class="input normal" sucmsg=" " /> <span class="Validform_checktip">*</span></dd>
	</dl> 
	<dl>
	<dt>价格</dt>
		<dd><input type="text" value="<?php echo ($price); ?>" name="price" datatype="n" Class="input small" sucmsg=" " /> <span class="Validform_checktip">*</span></dd>
	</dl>
	<dl>
    <dt>起始时间</dt>
    <dd>
    	<div style="width: 190px" class="input-date">
        <input type="text" value="<?php echo (date('Y-m-d H:i:s',$starttime)); ?>" name="starttime" id="txtAddTime" Class="input date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" datatype="/^\s*$|^\d{4}\-\d{1,2}\-\d{1,2}\s{1}(\d{1,2}:){2}\d{1,2}$/" errormsg="请选择正确的日期" sucmsg=" " />
        <i>日期</i>
      </div>
	</dd>
  </dl>
	<dl>
    <dt>结束时间</dt>
    <dd>
    	<div style="width: 190px" class="input-date">
        <input type="text" value="<?php echo (date('Y-m-d H:i:s',$stoptime)); ?>" name="stoptime" id="txtAddTime" Class="input date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" datatype="/^\s*$|^\d{4}\-\d{1,2}\-\d{1,2}\s{1}(\d{1,2}:){2}\d{1,2}$/" errormsg="请选择正确的日期" sucmsg=" " />
        <i>日期</i>
      </div>
	</dd>
  </dl>
	<dl>
		<dt>详细信息</dt>
		<dd>
			<textarea id="webcopyright" name="description" Class="input" /><?php echo ($description); ?></textarea>
	      	<span class="Validform_checktip">非必填</span>
		</dd>
	</dl>
	<dl>
    <dt>使用表格</dt>
    <dd>
		<div class="rule-multi-radio multi-radio">	   		
			<label><input type="radio" onclick="otable(this.value)" Value="2" <?php if($istab == 2): ?>checked="checked"<?php endif; ?> name="istab" />是</label>
			<label><input type="radio" onclick="otable(this.value)" Value="1" <?php if($istab == 1): ?>checked="checked"<?php endif; ?> name="istab" />否</label>
    	</div>
	</dd>
  </dl>
	<dl id="otable" style="display: block;">
		<dt>表格详情</dt>
		<dd>
			<p><a onclick = "addtable()" style="display: inline-block; height: 20px; line-height:20px;cursor: pointer; border: 1px solid #ccc; margin: 0 10px 10px 0; padding: 5px;">添加一行</a><a onclick = "deltable()" style="display: inline-block; height: 20px; line-height:20px;cursor: pointer; border: 1px solid #ccc; margin: 0 10px 10px 0; padding: 5px;">删除一行</a>　*标题可写在中间</p>
			<table id="tab" border = '1' bordercolor = '#ccc'>
				<?php if(is_array($ttt)): $i = 0; $__LIST__ = $ttt;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val1): $mod = ($i % 2 );++$i;?><tr>
						<?php if(is_array($val1["content"])): $i = 0; $__LIST__ = $val1["content"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><td style="padding: 5px;"><input type="text" value="<?php echo ($val); ?>" name="tab[]" style="width: 160px;height: 26px;text-align: center;"  /></td><?php endforeach; endif; else: echo "" ;endif; ?>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			</table>
		</dd>
	</dl>
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