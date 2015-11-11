<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><title>管理后台</title>
<script type="text/javascript" src="/Public/js/jquery-1.10.2.min.js"></script><script type="text/javascript" src="/Public/js/scripts/jquery/Validform_v5.3.2_min.js"></script><script type="text/javascript" src="/Public/js/scripts/lhgdialog/lhgdialog.js?skin=idialog"></script><script type="text/javascript" src="/Public/js/layout.js"></script>	<link href="/Public/admin/css/pagination.css" rel="stylesheet" type="text/css" />	<link href="/Public/admin/base.css" rel="stylesheet" type="text/css" /><link href="/Public/admin/layout.css" rel="stylesheet" type="text/css" /><link href="/Public/admin/admin.css" rel="stylesheet" type="text/css" /><link href="/Public/admin/page.css" rel="stylesheet" type="text/css" /><script type="text/javascript" src="/Public/js/check.js"></script>
<script type="text/javascript">
    $(function () {
        $("#btnConfirm").click(function () { OrderConfirm(); });   //确认订单
        $("#btnPayment").click(function () { OrderPayment(); });   //确认付款
        $("#btnExpress").click(function () { OrderExpress(); });   //确认发货
        $("#btnComplete").click(function () { OrderComplete(); }); //完成订单
        $("#btnCancel").click(function () { OrderCancel(); });     //取消订单
        $("#btnInvalid").click(function () { OrderInvalid(); });   //作废订单
        $("#btnPrint").click(function () { OrderPrint(); });       //打印订单
        $("#btnEditAcceptInfo").click(function () { EditAcceptInfo(); }); //修改收货信息
        $("#btnEditRemark").click(function () { EditOrderRemark(); });    //修改订单备注
        $("#btnEditRealAmount").click(function () { EditRealAmount(); }); //修改商品总金额
        $("#btnEditExpressFee").click(function () { EditExpressFee(); }); //修改配送费用
        $("#btnEditPaymentFee").click(function () { EditPaymentFee(); }); //修改支付手续费
    });
    //确认订单
    function OrderConfirm() {
        var dialog = $.dialog.confirm('确认订单后将无法修改金额，确认要继续吗？', function () {
            var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "order_confirm" };
            //发送AJAX请求
            sendAjaxUrl(dialog, postData, "../../tools/admin_ajax.ashx?action=edit_order_status");
            return false;
        });
    }
    //确认付款
    function OrderPayment() {
        var dialog = $.dialog.confirm('操作提示信息：<br />1、该订单使用在线支付方式，付款成功后自动确认；<br />2、如客户确实已打款而没有自动确认可使用该功能；<br />3、确认付款后无法修改金额，确认要继续吗？', function () {
            var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "order_payment" };
            //发送AJAX请求
            sendAjaxUrl(dialog, postData, "../../tools/admin_ajax.ashx?action=edit_order_status");
            return false;
        });
    }
    //确认发货
    function OrderExpress() {
        var dialog = $.dialog({
            title: '确认发货',
            content: 'url:dialog/dialog_express.aspx?order_no=' + $("#spanOrderNo").text(),
            min: false,
            max: false,
            lock: true,
            width: 450
        });
    }
    //完成订单
    function OrderComplete() {
        var dialog = $.dialog.confirm('订单完成后，订单处理完毕，确认要继续吗？', function () {
            var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "order_complete" };
            //发送AJAX请求
            sendAjaxUrl(dialog, postData, "../../tools/admin_ajax.ashx?action=edit_order_status");
            return false;
        });
    }
    //取消订单
    function OrderCancel() {
        var dialog = $.dialog({
            title: '取消订单',
            content: '操作提示信息：<br />1、取消订单，请线下与客户沟通；<br />2、强制取消订单可能导致订单正常订单逻辑异常；<br />3、请单击相应按钮继续下一步操作！',
            min: false,
            max: false,
            lock: true,
            icon: 'confirm.gif',
            button: [{
                name: '直接取消',
                callback: function () {
                    var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "order_cancel", "check_revert": 5 };
                    //发送AJAX请求
                    sendAjaxUrl(dialog, postData, "/Admin/Order/OrderCancel");
                    return false;
                }
            }, {
                name: '关闭'
            }]
        });

    }
    //作废订单
    function OrderInvalid() {
        var dialog = $.dialog({
            title: '取消订单',
            content: '操作提示信息：<br />1、匿名用户，请线下与客户沟通；<br />2、会员用户，自动检测退还金额或积分到账户；<br />3、请单击相应按钮继续下一步操作！',
            min: false,
            max: false,
            lock: true,
            icon: 'confirm.gif',
            button: [{
                name: '检测退还',
                callback: function () {
                    var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "order_invalid", "check_revert": 1 };
                    //发送AJAX请求
                    sendAjaxUrl(dialog, postData, "../../tools/admin_ajax.ashx?action=edit_order_status");
                    return false;
                },
                focus: true
            }, {
                name: '直接作废',
                callback: function () {
                    var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "order_invalid", "check_revert": 0 };
                    //发送AJAX请求
                    sendAjaxUrl(dialog, postData, "../../tools/admin_ajax.ashx?action=edit_order_status");
                    return false;
                }
            }, {
                name: '关闭'
            }]
        });
    }
    //打印订单
    function OrderPrint() {
        var dialog = $.dialog({
            title: '打印订单',
            content: 'url:dialog/dialog_print.aspx?order_no=' + $("#spanOrderNo").text(),
            min: false,
            max: false,
            lock: true,
            width: 850//,
            //height: 500
        });
    }
    //修改收货信息
    function EditAcceptInfo() {
        var dialog = $.dialog({
            title: '修改收货信息',
            content: 'url:dialog/dialog_accept.aspx',
            min: false,
            max: false,
            lock: true,
            width: 550,
            height: 320
        });
    }
    //修改订单备注
    function EditOrderRemark() {
        var dialog = $.dialog({
            title: '订单备注',
            content: '<textarea id="txtOrderRemark" name="txtOrderRemark" rows="2" cols="20" class="input">' + $("#divRemark").html() + '</textarea>',
            min: false,
            max: false,
            lock: true,
            ok: function () {
                var remark = $("#txtOrderRemark", parent.document).val();
                if (remark == "") {
                    $.dialog.alert('对不起，请输入订单备注内容！', function () { }, dialog);
                    return false;
                }
                var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "edit_order_remark", "remark": remark };
                //发送AJAX请求
                sendAjaxUrl(dialog, postData, "/Admin/Order/EditOrderRemark");
                return false;
            },
            cancel: true
        });
    }
    //修改优惠金额
    function EditRealAmount() {
        var pop = $.dialog.prompt('请修改优惠金额',
            function (val) {
                if (!checkIsMoney(val)) {
                    $.dialog.alert('对不起，请输入正确的优惠金额！', function () { }, pop);
                    return false;
                }
                var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "edit_real_amount", "real_amount": val };
                //发送AJAX请求
                sendAjaxUrl(pop, postData, "/Admin/Order/EditRealAmount");
                return false;
            },
            $("#spanRealAmountValue").text()
        );
    }
    //修改配送费用
    function EditExpressFee() {
        var pop = $.dialog.prompt('请修改配送费用',
            function (val) {
                if (!checkIsMoney(val)) {
                    $.dialog.alert('对不起，请输入正确的配送金额！', function () { }, pop);
                    return false;
                }
                var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "edit_express_fee", "express_fee": val };
                //发送AJAX请求
                sendAjaxUrl(pop, postData, "../../tools/admin_ajax.ashx?action=edit_order_status");
                return false;
            },
            $("#spanExpressFeeValue").text()
        );
    }
    //修改手续费用
    function EditPaymentFee() {
        var pop = $.dialog.prompt('请修改支付手续费用',
            function (val) {
                if (!checkIsMoney(val)) {
                    $.dialog.alert('对不起，请输入正确的手续费用！', function () { }, pop);
                    return false;
                }
                var postData = { "order_no": $("#spanOrderNo").text(), "edit_type": "edit_payment_fee", "payment_fee": val };
                //发送AJAX请求
                sendAjaxUrl(pop, postData, "../../tools/admin_ajax.ashx?action=edit_order_status");
                return false;
            },
            $("#spanPaymentFeeValue").text()
        );
    }

    //=================================工具类的JS函数====================================
    //检查是否货币格式
    function checkIsMoney(val) {
        var regtxt = /^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/;
        if (!regtxt.test(val)) {
            return false;
        }
        return true;
    }
    //发送AJAX请求
    function sendAjaxUrl(winObj, postData, sendUrl) {
        $.ajax({
            type: "post",
            url: sendUrl,
            data: postData,
            dataType: "json",
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $.dialog.alert('尝试发送失败，错误信息：' + errorThrown, function () { }, winObj);
            },
            success: function (data, textStatus) {
                if (data.status == 1) {
                    winObj.close();
                    $.dialog.tips(data.msg, 1, '32X32/succ.png', function () { location.reload(); }); //刷新页面
                } else {
                    $.dialog.alert('错误提示：' + data.msg, function () { }, winObj);
                }
            }
        });
    }
</script>
</head>

<body class="mainbody">
<form id="form1">
<!--导航栏-->
<div class="location">
  <a href="javascript:history.back(-1);" class="back"><i></i><span>返回上一页</span></a>
  <a href="/Admin/Index/center" class="home"><i></i><span>首页</span></a>
  <i class="arrow"></i>
  <a href="/Admin/Order"><span>订单管理</span></a>
  <i class="arrow"></i>
  <span>订单详细</span>
</div>
<div class="line10"></div>
<!--/导航栏-->

<!--内容-->
<div class="content-tab-wrap">
  <div id="floatHead" class="content-tab">
    <div class="content-tab-ul-wrap">
      <ul>
        <li><a href="javascript:;" onclick="tabs(this);" class="selected">订单详细信息</a></li>
      </ul>
    </div>
  </div>
</div>

<div class="tab-content">
  <dl>
    <dd style="margin-left:50px;text-align:center;">
      <div class="order-flow" style="width:560px">
        <div class="order-flow-left">
          <a class="order-flow-input">生成</a>
          <span><p class="name">订单已生成</p><p><!-- date --></p></span>
        </div>
        <?php if(($state == 0)): ?><div class="order-flow-arrive">
          <a class="order-flow-input">未付款</a>
          <span><p class="name">未付款</p><p></p></span>
        </div>           
        <?php elseif(($state == 1)): ?>
        <div class="order-flow-arrive">
          <a class="order-flow-input">已付款</a>
          <span><p class="name">已付款</p></span>
        </div><?php endif; ?>
        <?php if(($state == 1)): ?><div class="order-flow-right-arrive">
           <a class="order-flow-input">完成</a>
           <span><p class="name">订单完成</p><p></p></span>
         </div>
         <?php elseif(($state != 1)): ?>
         <div class="order-flow-right-wait">
           <a class="order-flow-input">完成</a>
           <span><p class="name">订单完成</p></span>
         </div><?php endif; ?>
		 <?php if($state == 2): ?><div style="text-align:center;line-height:30px; font-size:20px; color:Red;">该订单已取消</div><?php endif; ?>
      </div>
    </dd>
  </dl>
  <dl>
    <dt>订单号</dt>
    <dd><span><?php echo ($order); ?></span></dd>
	<dd style="display:none;"><span id="spanOrderNo"><?php echo ($service["id"]); ?></span></dd>
  </dl>
  <dl>

    <dt>订单信息</dt>

    <dd>

      <table border="0" cellspacing="0" cellpadding="0" class="border-table" width="98%">

        <thead>

          <tr>

            <th width="30%">订单名称</th>

            <th width="12%">价格</th>

            <th width="18%">订单类型</th>


            <th>发布时间</th>

          </tr>

        </thead>

        <tbody>



          <tr class="td_c">

            <td style="text-align:center;white-space:normal;"><?php echo ($title); ?></td>          

            <td><?php echo ($price); ?></td>

            <td>
			<?php if($class == 1): ?>会员卡
			<?php elseif($class == 2): ?>游玩
			<?php elseif($class == 3): ?>活动
			<?php elseif($class == 4): ?>产品<?php endif; ?>
		</td>


            <td><?php echo (date("Y-m-d",$time)); ?></td>

          </tr>

        </tbody>

      </table>

    </dd>

  </dl>
  <dl>
    <dt>会员信息</dt>
    <dd>
      <table border="0" cellspacing="0" cellpadding="0" class="border-table" width="98%">
        <thead>
          <tr>
            <th width="30%">账号（手机）</th>
            <th width="12%">儿童姓名</th>
            <th width="18%">家长姓名</th>
            <th>发布时间</th>
          </tr>
        </thead>
        <tbody>

          <tr class="td_c">
            <td style="text-align:center;white-space:normal;"><?php echo ($uname); ?></td>          
            <td><?php echo ($children); ?></td>			<td><?php echo ($parent); ?></td>
            <td><?php echo (date("Y-m-d",$regtime)); ?></td>
          </tr>
        </tbody>
      </table>
    </dd>
  </dl>

 
  
</div>
<!--/内容-->


<!--工具栏-->
<div class="page-footer">
  <div class="btn-list">
  	<!--
    <input id="btnConfirm" runat="server" visible="false" type="button" value="确认订单" class="btn" />
    <input id="btnPayment" runat="server" visible="false" type="button" value="确认付款" class="btn" />
    <input id="btnExpress" runat="server" visible="false" type="button" value="确认发货" class="btn" />
	
    <input id="btnComplete" runat="server" visible="false" type="button" value="完成订单" class="btn" />
	
    <input id="btnCancel" runat="server" visible="false" type="button" value="取消订单" class="btn green" />
	<!--
    <input id="btnPrint" type="button" value="打印订单" class="btn violet" />
	-->
    <input id="btnReturn" type="button" value="返回上一页" class="btn yellow" onclick="javascript:history.back(-1);" />
  </div>
  <div class="clear"></div>
</div>
<!--/工具栏-->
</form>
</body>
</html>