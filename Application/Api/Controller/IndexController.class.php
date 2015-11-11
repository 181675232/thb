<?php
namespace Api\Controller;
use Think\Controller;
use Think;
 

class IndexController extends Controller { 
	//Jpush key
	private $title = '特惠帮';
	private $app_key='ebb9531ed9b2c95d5ce5e47c';
	private $master_secret = '219f6db35902775645897510';
	
    //注册
    public  function  register(){
    	if(I('post.')){
    		$data = I('post.');
    		$data['addtime'] = time();
    		$data['password'] = md5(trim(I('post.password')));
    		$data['simg'] = '/Public/upfile/touxiang.jpg';
    		$user = M('user');
    		$yqm = I('post.pid');
    		if ($user->where("phone='{$data['phone']}'")->find()){
    			json('400','该账号已注册');
    		}
    		if (!$user->where("id = $yqm")->find()){
    			json('400','邀请码错误');
    		}
    		$return = $user->add($data);		
    		if ($return){
    			$res = $user->field('id,phone,simg,jpushid,username')->find($return);
    			json('200','成功！',$res);
    		}else{
    			json('400');   			
    		}
    	}
    	json('404','没有接收到传值');
    }
    
    //发送验证码1(手机号不存在时调用)
    public function yzm1(){
    	if(I('post.phone')){
    		$phone=I('post.phone');
    		if(!checkPhone($phone)){
    			json('400','请输入正确的手机号！');
    		}
    		$user = M('user');
    		$return = $user->where("phone=$phone")->find();
    		if($return){
    			json('400','用户名已经被注册，请登陆！');
    		}else{
    			yzm($phone);
    		}
    	}
    	json('404','没有接收到传值');
    }
    
    //发送验证码2(手机号存在时调用)
    public function yzm2(){
    	if(I('post.phone')){
    		$phone = I('post.phone');
    		$user = M('user');
    		if (!checkPhone($phone)){
    			json('400','手机格式不正确');
    		}
    		if (!$user->where("phone = $phone")->find()){
    			json('400','手机号码不存在');
    		}
    		yzm($phone);
    	}
    	json('404','没有接收到传值');
    }
    
    //登录
    public function login(){
    	if(I('post.')){
	    	$user = M('user');
	    	$phone=I('post.phone');	    	
	    	$return = $user->where("phone=$phone")->find();	    	
	    	if($return){
	    		$data = I('post.');
	    		$data['password'] = md5(trim($data['password'])); 		
	    		$res = $user->field('id,phone,simg,jpushid,username')->where($data)->find();
	    		if($res){
	    			json('200','成功',$res);
	    		}else{
	    			json('400','密码错误');
	    		}
	    	}else{
	    		json('400','未注册！');
	    	}
    	}
    	json('404','没有接收到传值');
    }
    
    //忘记密码
    public function forgetpass(){
    	if(I('post.')){
    		$phone = I('post.phone');
    		$user = M('user');
    		$data['password'] = md5(I('post.password'));
    		if ($user->where("phone = $phone")->save($data)){
    			json('200','成功');
    		}else {
    			json('400','修改失败');
    		}
    	}
    	json('404','没有接收到传值');
    }
     
    //个人资料
    public function userinfo(){
    	if(I('post.id')){
 			$user = M('user');
 			$data = $user->find(I('post.id'));
 			if ($data){
 				json('200','成功',$data);
 			}else {
 				json('400','没有获取到资料');
 			}	
		}
		json('404','没有接收到传值');
    }
    
    //修改个人信息
    public function useredit(){
    	if(I('post.')){
    		$user = M('user');
    		$data = I('post.');    
    		if ($data['birth']){
    			$data['birth'] = strtotime($data['birth']);
    		}		
    		if($_FILES){
    			$data1 = $_FILES['simg'];
    			$rand = '';
    			for ($i=0;$i<6;$i++){
    				$rand.=rand(0,9);
    			}
    			$type = explode('.', $data1['name']);
    			$simg = date('YmdHis').$rand.'.'.end($type);
    			if (move_uploaded_file($data1['tmp_name'], './Public/upfile/'.$simg)){				
    				$data['simg'] = '/Public/upfile/'.$simg;
    			}else {
    				json('400','头像上传失败');
    			} 			
    		}
    		if ($user->save($data)){
    			$res = $user->find(I('post.id'));
    			json('200','成功',$res);
    		}else {
    			json('400','修改失败');
    		}
    	}
    	json('404','没有接收到传值');
    }

    //修改密码
    public function passedit(){ 
    	if(I('post.')){
    		$user = M('user');
    		$where['id'] = I('post.id');
    		$where['password'] = md5(I('post.pass'));
    		if (!$user->where($where)->find()){
    			json('400','原密码输出有误');
    		}
    		$data['password'] = md5(I('post.password'));
    		if ($user->where("id = '{$where['id']}'")->save($data)){
    			json('200');
    		}else {
    			json('400','修改失败');
    		}
    	}
    	json('404','没有接收到传值');
    }
	
	//意见反馈
	public function addmessage(){
		if (I('post.')){
			$where = I('post.');
			$where['addtime'] = time();
			$table = M('message');
			if ($table->add($where)){
				json('200');
			}else {
				json('400','意见反馈失败');
			}
		}
		json('404','没有接收到传值');
	}
	//获取市列表
	public function getcitys(){
		$table = M('city');
		$data = $table->where('isred = 2')->select();
		if ($data){
			json('200','成功',$data);
		}else {
			json('400','暂无数据');
		}
	}	
	//获取区列表
	public function getareas(){
		if (I('post.id')){
			$where['cityid'] = I('post.id');
			$table = M('area');
			$data = $table->where($where)->select();
			if ($data){
				json('200','成功',$data);
			}else {
				json('400','暂无数据');
			}
		}
		json('404','没有接收到传值');
	}
	
	//获取分类列表
	public function shopgroup(){
		$table = M('group');
		$data = $table->where('pid = 0')->select();
		if ($data){
			foreach ($data as $key=>$val){
				$data[$key]['catid'] = $table->where("pid = '{$val['id']}'")->select();
			}
			json('200','成功',$data);
		}else {
			json('400','暂无数据');
		}
	}
	
	//周片商铺列表
	public function shoplist(){
		if (I('post.')){
			if (I('post.page')){
				$page = I('post.page');
			}else {
				$page = 1;
			}
			if (I('post.cityid')){
				$where['cityid'] = I('post.cityid');
			}else {
				if (I('post.areaid')){
					$where['areaid'] = I('post.areaid');
				}else {
					json('400','请选择所在城市');
				}
			}
			if (I('post.keyword')){
				$keyword = I('post.keyword');
				$where['name'] =  array('like',"%{$keyword}%");
			}
			if (I('post.groupid')){
				$where['groupid'] = I('post.groupid');
			}
			$table = M('shop');
			$data = $table->where($where)->order('isred desc,id desc')->limit(0,5000)->select();			
			if ($data){
				if (I('post.subgroupid')){				
					foreach ($data as $key=>$val){
						$arr =explode(',', $val['subgroupid']);
						if (in_array(I('post.subgroupid'), $arr)){
							$res[] = $val;
						}
					}
					$data = $res;
					$res = array();
				}
				if (I('post.ord') == 3){
					foreach ($data as $key=>$val){
						$arr =explode(',', $val['tags']);
						if (in_array(I('post.ord'), $arr)){
							$res[] = $val;
						}
					}
					$data = $res;
					$res = array();
				}
				foreach ($data as $key => $val){
					$data[$key]['di'] = powc(I('post.latitude'),I('post.longitude'), $val['latitude'], $val['longitude']);
					if ($data[$key]['di'] >= 1000){
						$data[$key]['distance'] = ceil($data[$key]['di']/1000).'km';
					}else {
						$data[$key]['distance'] = $res[$key]['di'].'m';
					}
				}
				if (I('post.di')){
					foreach ($data as $key=>$val){
						if ($val['di'] < I('post.di')){
							$res[] = $val;
						}
					}
					$data = $res;
					$res = array();
				}
				
				foreach ($data as $arrys) {
					$distances[] = $arrys['di'];
				}
				foreach ($res as $arrys) {
					$price[] = $arrys['price'];
				}
				foreach ($res as $arrys) {
					$mark[] = $arrys['mark'];
				}
				if (I('post.ord') == 1){
					array_multisort($mark,SORT_DESC,$data);
				}elseif (I('post.ord') == 2){
					array_multisort($price,SORT_ASC,$data);
				}else {
					array_multisort($distances,SORT_ASC,$data);					
				}
				$data = array_page($data,$page);
				if ($data){
					json('200','成功',$data);
				}else {
					json('400','没有更多数据');
				}
			}else{
				json('400','暂无数据');
			}
		}
		json('404','没有接收到传值');
	}
    
	//店铺详情
	public function shopinfo(){
		if (I('post.')){
			$id = I('post.id');
			$table = M('shop');
			$data['shop'] = $table->find($id);
			if (!$data['shop']){
				json('400','非法请求');
			}
			$goods = M('goods');
			$data['goods'] = $goods->field("id,count,name,simg,price,isred")->where("pid = $id")->order('isred desc,ord asc,id desc')->select();
			if ($data['goods']){
				foreach ($data['goods'] as $key => $val){
					$data['goods'][$key]['price'] = round($val['price'] * $data['shop']['discount'] / 100);
				}
			}
			$comment = M('comment');
			$data['shop']['comment'] = $comment->where("shopid = $id")->count();
			$league = M('league');
			$res = $league->where("shopid = $id and pid !=0")->find();
			if ($res){
				if ($league->where("id = '{$res['pid']}' and state = 2")->find()){
					$data['league'] = $league->field("t_shop.*")
					->join('left join t_shop on t_shop.id = t_league.shopid')
					->where("pid = '{$res['pid']}' and shopid !=$id")->select();
					foreach ($data['league'] as $key => $val){
						$data['league'][$key]['di'] = powc(I('post.latitude'),I('post.longitude'), $val['latitude'], $val['longitude']);
						if ($data['league'][$key]['di'] >= 1000){
							$data['league'][$key]['distance'] = ceil($data['league'][$key]['di']/1000).'km';
						}else {
							$data['league'][$key]['distance'] = $res[$key]['di'].'m';
						}
					}
					array_multisort($distances,SORT_ASC,$data);
				}else {
					$data['league'] = '';	
				}
			}else {
				$data['league'] = '';	
			}
			if ($data){
				json('200','成功',$data);
			}else {
				json('400','暂无数据');
			}
		}
		json('404','没有接收到传值');
	}
	
	
	
	
	
	
    
    

    
    //记录客户端位置
    public function position(){
    	$data = $_POST;
    	$position = M('position');
    	if(isset($data['uid']) && isset($data['longitude']) && isset($data['latitude'])){
    		$data['time'] = time();
    		$return = $position->add($data);
    		if ($return){
    			json('200','成功！',$return);
    		}else{
    			json('400','失败！');
    		}
    	}
    }
    
    //修改订单状态
    public function edit_order(){
    	if (I('get.order_id')){
	    	$id = I('get.order_id');    	
	    	$order = M('order');
	    	$data['status'] = 1;
	    	if ($order->where("id = $id")->save($data)){
	    		json('200','成功');
	    	}else {
	    		json('400','失败');
	    	}
    	}
    	json('404','没有获取到订单号');
    }
    
	//订单删除
	public function order_del(){
		if(I('get.id')){
 			$order = M('order');
 			if ($order->delete(I('get.id'))){
 				json('200','删除成功');
 			}else {
 				json('400','删除失败');
 			}
		}
		json('404','没有获取到订单号');
		
	}

	//手机发送短信 云之讯
	public function sms(){
		$mobile = I('get.mobile');
		$user = M('user');
		if (!checkPhone($mobile)){
			json('400','手机格式不正确');
		}
		if (!$user->where("mobile = $mobile")->find()){
			json('400','手机号码不存在');
		}
		$vcodes = '';
		for($i=0;$i<6;$i++){$authnum=rand(1,9);$vcodes.=$authnum;}//生成验证码

		$time = date('YmdHis',time()).'001';

		$sign = md5('769c7f3a98f1d7f8b6f72438167b3d43'.$time.'4aba74f04a2593b0bceef15101a8521e');

		$return = file_get_contents("http://www.ucpaas.com/maap/sms/code?sid=769c7f3a98f1d7f8b6f72438167b3d43&appId=e9274a5caa034568a04f1cfe10dcd207&sign=$sign&time=$time&templateId=6201&to=$mobile&param=$vcodes");
		if ($return){
			$resp = json_decode($return);
			if ($resp->resp->respCode == '000000'){
				$data['code'] = $vcodes;
				json('200','发送成功',$data);
			}else {
				json('400',$resp->respMsg);
			}
		}else {
			json('404','未发送');
		}		
	}
	
	
	
	
	
	
	
}