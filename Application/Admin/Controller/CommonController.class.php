<?php
namespace Admin\Controller;
use Think\Controller;

class CommonController extends Controller {
	public function _initialize(){
		header("Content-Type:text/html; charset=utf-8");
		if (!$_SESSION['userid']){
			$this->redirect('/Admin/Public/admin');
			exit;
		}
		$rbac=new \Org\Util\Rbac();
		//检测是否登录，没有登录就打回设置的网关
		$rbac::checkLogin();
		//检测是否有权限没有权限就做相应的处理
		if(!$rbac::AccessDecision()){
			alertBack('您没有此操作权限！');
		}
// 	// 用户权限检查
// 		if (C ( 'USER_AUTH_ON' ) && ! in_array ( MODULE_NAME, explode ( ',', C ( 'NOT_AUTH_MODULE' ) ) )) {
// 			//1.如果需要验证
// 			if (! \Org\Util\Rbac::AccessDecision ()) {
// 				// 2.没有登陆
// 				if (! $_SESSION [C ( 'USER_AUTH_KEY' )]) {
// 					// 3.游客可访问
// 					if(C('GUEST_AUTH_ON')) {
// 						// 4.游客授权
// 						if(!isset($_SESSION['_ACCESS_LIST']))
// 							// 保存游客权限
// 							\Org\Util\Rbac::saveAccessList(C('GUEST_AUTH_ID'));
// 					}else{
// 						// 5.无登陆，禁止游客访问，无权限页面
// 						$this->error ( L ( '_VALID_ACCESS_' ) );
// 					}
// 				}
// 				// 6.登陆，没有权限， 如果有错误页面则定向
// 				if (C ( 'RBAC_ERROR_PAGE' )) {
// 					// 定义权限错误页面
// 					redirect ( C ( 'RBAC_ERROR_PAGE' ) );
// 				}
// 				//7.没有定义错误页面定向，跳到登陆页面
// 				else{
// 					alertBack('您没有此操作权限！');
// 				}
// 			}
// 		}
// 		$notAuth = in_array(MODULE_NAME, explode(',',C('NOT_AUTH_MODULE')));
// 		if (C('USER_AUTH_ON') && !$notAuth){
// 			//rbac
// 			$rbac = new \Org\Util\Rbac();
// 			if (!$rbac->AccessDecision()){
				//alertBack('您没有此操作权限！');
// 			}
// 		}
	}
}