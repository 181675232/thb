<?php
namespace Api\Controller;
use Think\Controller;
use Think;
 

class ShopController extends Controller { 
	//Jpush key
	private $title = '特惠帮';
	private $app_key='52b9e181f96679e59ffb4fa3';
	private $master_secret = '146a40bb61c640bc1ff6ad1e';
	
	//融云
	private $appKey = 'mgb7ka1nb904g';
	private $appSecret = 'emEDENErpWhAct';
	
    
    //登录
    public function login(){
    	if(I('post.')){
	    	$member = M('member');
	    	$phone=I('post.phone');	    	
	    	$return = $member->where("phone=$phone")->find();	    	
	    	if($return){
	    		$data = I('post.');
	    		
	    		$data['password'] = md5(trim($data['password'])); 		
	    		$res = $member->where($data)->field('id,phone,simg,jpushid,name,pid,level')->find();
	    		if($res){
	    			json('200','成功',$res);
	    		}else{
	    			json('400','密码错误');
	    		}
	    	}else{
	    		json('401','未注册！');
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
    public function memberinfo(){
    	if(I('post.id')){
 			$member = M('member');
 			$data = $member->find(I('post.id'));
 			if ($data){
 				json('200','成功',$data);
 			}else {
 				json('400','没有获取到资料');
 			}	
		}
		json('404','没有接收到传值');
    }
    
    //修改个人信息
    public function memberedit(){
    	if(I('post.')){
    		$member = M('member');
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
    		if ($member->save($data)){
    			$res = $member->find(I('post.id'));
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
	
    
    
    //生成订单
    public function order(){
    	if(I('post.phone')){
    		$user = M('user');
    		$order = M('order');
    		$phone = $_POST['phone'];
    		$data = $_POST;
    		$res = $user->where("phone=$phone")->find();
    		if($res){
    			$data1['uid'] = $res['id'];//用户id
    			$data1['shopid'] = $data['shopid'];
    			$data1['price'] = $data['price'];
    			$data1['addtime'] = time();
    			$return =	$order->add($data1);
    			if($return){		
    				$jpush = new \Org\Util\Jpush($this->appkey, $this->secret);
    				$jpushid[0] = $res['jpushid'];
    				$jpush->push($jpushid, $this->title, $data['price']);
    				json('200','成功');
    			}else{
    				json('401','失败');
    			}
    		}else{
    			json('400','无当前用户');
    		}
    	}
    }
    
    
    
    //店粉
    public function powder(){
    	if(I('post.shopid')){
    		$push = M('push');
    		$shop = M('shop');
    		$shopid = $_POST['shopid'];
    		$page = (I('post.page')-1)*10;
    		$res['num'] = $shop->field('push')->find($shopid);
    		$res['res'] = $push->where("shopid=$shopid")->limit("$page,10")->select();
			if($res){
				json('200','成功',$res);
			}else{
				json('400','失败');
			}
    	}
    }
    
    //看官
    public function trace(){
    	if(I('post.shopid')){
    		$trace = M('trace');
    		$shopid = $_POST['shopid'];
    		
    		
    		
    		
    		//php获取今日开始时间戳和结束时间戳
    	 	$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
    		$endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
    		//php获取昨日起始时间戳和结束时间戳
    		$beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
    		$endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
    		//php获取上周起始时间戳和结束时间戳
    		$beginLastweek=mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
    		$endLastweek=mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
    		//php获取本月起始时间戳和结束时间戳
    		$beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
    		$endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));
    		
    		
    		
//     		$time1 = time()-86400;//一天
//     		$time2 = time()-172800;//两天
    		$time3 = time()-604800;//七天
//     		$time4 = time()-2592000;//月
    		$res['today'] = $trace->where("shopid=$shopid and addtime>$beginToday")->count();
    		$res['yesterday'] = $trace->where("shopid=$shopid and addtime>$beginYesterday and addtime<$endYesterday")->count();
    		$res['thisweek'] = $trace->where("shopid=$shopid and addtime>$time3")->count();
    		$res['thismonth'] = $trace->where("shopid=$shopid and addtime>$beginThismonth and addtime<$endThismonth")->count();
    		$res['all'] = $trace->where("shopid=$shopid")->count();
    		
    		if($res){
    			json('200','成功',$res);
    		}else{
    			json('400','失败');
    		}
    	}
    }
    
    
    //账本
    public function account_books(){
    	if(I('post.shopid')){
    		$order = M('order');
    		$shopid = $_POST['shopid'];
    		
    		
    		//php获取今日开始时间戳和结束时间戳
    	 	$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
    		$endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
    		//php获取昨日起始时间戳和结束时间戳
    		$beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
    		$endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
    		//php获取上周起始时间戳和结束时间戳
    		$beginLastweek=mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
    		$endLastweek=mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
    		//php获取本月起始时间戳和结束时间戳
    		$beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
    		$endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));
    		
    		
    		
			//今日
    		$res['today']['num'] = $order->where("shopid=$shopid and addtime>$beginToday")->count();
    		$res['today']['res'] = $order->where("shopid=$shopid and addtime>$beginToday")->select();
    		
    		//本周
    		$res['thisweek']['num'] = $order->where("shopid=$shopid and addtime>$endLastweek")->count();
    		$res['thisweek']['res'] = $order->where("shopid=$shopid and addtime>$endLastweek")->select();
    		
    		//本月
    		$res['thismonth']['num'] = $order->where("shopid=$shopid and addtime>$beginThismonth and addtime<$endThismonth")->count();
    		$res['thismonth']['res'] = $order->where("shopid=$shopid and addtime>$beginThismonth and addtime<$endThismonth")->select();
    		
    		//全部
    		$res['all']['num'] = $order->where("shopid=$shopid")->count();
    		$res['all']['res'] = $order->where("shopid=$shopid")->select();
    		
    		if($res){
    			json('200','成功',$res);
    		}else{
    			json('400','失败');
    		}
    	}
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
			$id = $where['shopid'] = I('post.id');
			$uid = $where['uid']  = I('post.uid');
			$table = M('shop');
			$data['shop'] = $table->find($id);
			if (!$data['shop']){
				json('400','非法请求');
			}
			$time = $where['addtime'] = time();
			$trace = M('trace');			
			if ($trace->where("uid = $uid and shopid = $id")->find()){
				$trace->where("uid = $uid and shopid = $id")->setField('addtime',$time);
			}else {
				$trace->add($where);
			}
			$goods = M('goods');
			$data['goods'] = $goods->field("id,count,name,simg,price,isred")->where("pid = $id and starttime < $time and stoptime > $time")->order('isred desc,ord asc,id desc')->select();
			if ($data['goods']){
				foreach ($data['goods'] as $key => $val){
					$data['goods'][$key]['price'] = round($val['price'] * $data['shop']['discount'] / 100);
				}
			}
			$comment = M('comment');
			$data['shop']['comment'] = $comment->where("shopid = $id")->count();
			$coll = M('collection');
			if ($coll->where("uid = $uid and bid = $id")->find()){
				$data['shop']['collection'] = 2;
			}else {
				$data['shop']['collection'] = 1;
			}		
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
	
	//商品详情
	public function goodsinfo(){
		if (I('post.id')){
			$id = I('post.id');
			$table = M('goods');
			$data['goods'] = $table->field("id,count,name,simg,price,isred,pid,starttime,stoptime,description,istab")->find($id);
			$shop = M('shop')->where("id = '{$data['goods']['pid']}'")->find();
			if (!$data['goods']){
				json('400','非法操作');
			}
			$data['goods']['phone'] = $shop['phone'];
			$data['goods']['trueprice'] = round($data['goods']['price'] * $shop['discount'] / 100);
			if ($data['goods']['istab'] == 2){
				$tab = M('tab');
				$data['goods']['table'] = $tab->where("goodsid = $id")->order('id asc')->select();
			}
			$time = time();
			$data['goodslist'] = $table->field("id,count,name,simg,price,isred")->where("pid = '{$data['goods']['pid']}' and starttime < $time and stoptime > $time")->order('isred desc,ord asc,id desc')->limit(2)->select();
			if ($data){
				json('200','成功',$data);
			}else {
				json('400','非法操作');
			}
		}
		json('404');
	}
	
	//商品web
	public function shopweb(){
		$id = I('post.id');
		$table = M('shop');
		$data = $table->find($id);
		$this->assign('content',$data['content']);
		$this->display();
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
	
	//好友列表
	public function friends_list(){
		if(I('post.id')){
			$user = M('user');
			$where['pid'] = $_POST['id'];
			$page = (I('post.page')-1)*10;
			$return = $user->where($where)->field('id,simg,username,addtime')->limit("$page,10")->select();
				
			foreach ($return as $key=>$val){
				$return[$key]['count'] = $user->where("pid='{$val['id']}'")->count();
			}
				
			$indirect_friends=0;
			foreach ($return as $key=>$val){
				$return[$key]['indirect'] = $user->where("pid='{$val['id']}'")->field('id,simg,username,addtime')->select();
				$indirect_friends = $indirect_friends+$return[$key]['count'];
			}
				
				
			$res['friend'] = 	$return;
			$res['direct_num'] = $user->where($where)->count();//直接好友总数
			$res['indirect_num'] = $indirect_friends;//间接好友总数
			$res['all_num'] = $res['direct_num']+$res['indirect_num'];//全部好友总数
			if($res){
				json('200','成功',$res);
			}else{
				json('400','失败');
			}
		}
	}
	
	//添加和取消收藏店铺
	public function collection_shop(){
		$collection= M('collection');
		$data = $_POST;
		$bid = $data['bid'];
		$uid = $data['uid'];
		if(isset($bid) && isset($uid)){
			$return=$collection->where($data)->find();
			if($return){
				$return1 = $collection->delete($return['id']);
				if ($return1){
					json('201','取消收藏成功！',$return1);
				}else{
					json('401','取消收藏失败！');
				}
			}else{
				$data['addtime'] = time();
				$return1 = $collection->add($data);
				if ($return1){
					json('200','收藏成功！',$return1);
				}else{
					json('400','收藏失败！');
				}
			}
		}
	}
	
	//收藏列表
	public function  collection_list(){
		$data = $_POST;
		$shop = M('shop');
		//收藏
		$collection = M('collection');
		$rs=$collection->where($data['uid'])->field('bid')->select();
		foreach ($rs as $key=>$val){
			$rs1[]=$val['bid'];
		}
	
		$rs1 = implode(',', $rs1);
		//店铺数据
		$return=$shop->field('id,name,simg,address')->select($rs1);
	
		//数组分页
		$page = ($_POST['page']-1)*10;
		$return = array_slice($return, $page, 10);
	
		if ($return){
			json('200','成功！',$return);
		}else{
			json('400','失败！');
		}
	}
	
	//摇一摇
	public function shake(){
		if(I('post.cityid') or I('post.areaid')){
			$shop = M('shop');
			$data = $_POST;
			$data['isred'] = '2';
			$return = $shop->where($data)->field('id,name,simg,address')->select();
			if($return){
				$res = array_rand($return);
			}else{
				$where = $_POST;
				$return = $shop->where($where)->field('id,name,simg,address')->select();
				$res = array_rand($return);
			}
				
			foreach ($return as $key=>$val){
				if($key==$res){
					$res1 = $val;
				}
			}
				
			if ($res1){
				json('200','成功！',$res1);
			}else{
				json('400','失败！');
			}
		}
	}
	
	//商品评论
	public function comment(){
		if(I('post.uid')){
			$comment = M('comment');
			$comment_img = M('comment_img');
			$data = $_POST;
			$return = $comment->where("goodsid='{$data['goodsid']}'")->select();
			if($return){
				json('401','已评价！');
			}else{
				$return1 = $comment->add($data);
				if(isset($_FILES)){
					//			$data = $_FILES;
					//   		$data=json_encode($_FILES);
					//   		file_put_contents('./b.txt', $data);
					foreach ($_FILES as $key=>$val){
						move_uploaded_file($val['tmp_name'], './Public/comment_img/'.$val['name']);
						$data1['cid'] = $return1;
						$data1['img'] = '/Public/comment_img/'.$val['name'];
						$data1['addtime'] = time();
						$return2 = $comment_img->add($data1);
					}
				}
				if($return1){
					json('200','评价成功！',$return1);
				}else{
					json('400','评价失败！');
				}
			}
		}
	}
	
	//店铺评论列表
	public function comment_list(){
		if(I('post.shopid')){
			$conmment = M('comment');
			$conmment_img = M('comment_img');
			$shop = M('shop');
			$page = ($_POST['page']-1)*10;
			$data['t_comment.shopid'] = $_POST['shopid'];
			
			//店铺信息
			$return['shop'] = $shop->field('id,simg,name,address,tags,price,mark,redtag,blacktag')->find($_POST['shopid']);
			
			//评论总条数
			$return['count'] = $conmment->where("shopid='{$_POST['shopid']}'")->count();
			
			$redtag = explode(" ", $return['shop']['redtag']);
			$blacktag = explode(" ", $return['shop']['blacktag']);
			
			//标签数据
			foreach ($redtag as $key=>$val){
				$where['content'] = array('like',"%{$val}%");
				$count = $conmment->where($where)->count();
				$return['redtag'][] = $val.$count;
			}
			
			//标签数据
			foreach ($blacktag as $key=>$val){
				$where['content'] = array('like',"%{$val}%");
				$count = $conmment->where($where)->count();
				$return['blacktag'][] = $val.$count;
			}
			
			//标签搜索
			if(!empty($_POST['tag'])){
				$data['t_comment.content'] = array('like',"%{$_POST['tag']}%");
			}
			
			//评论数据
			$return['comment'] = $conmment
			->join('left join t_user on t_comment.uid=t_user.id')
			->where($data)
			->field('t_comment.id,t_comment.uid,t_comment.content,t_comment.addtime,t_user.simg,t_user.username')
			->order('t_comment.id desc')
			->limit("$page,10")
			->select();
			
		  	foreach ($return['comment'] as $key=>$val){
  				$return['comment'][$key]['img'] = $conmment_img->where("cid='{$val['id']}'")->field('img')->select();
  			}  
			
			if($return){
				json('200','成功！',$return);
			}else{
				json('400','失败！');
			}
		}
	}  
	
	
	//足迹列表
	public function  trace_list(){
		$data = $_POST;
		$shop = M('shop');
		//足迹
		$trace = M('trace');
		$rs=$trace->where($data['uid'])->field('shop')->select();
		foreach ($rs as $key=>$val){
			$rs1[]=$val['shop'];
		}
	
		$rs1 = implode(',', $rs1);
		//店铺数据
		$return=$shop->field('id,name,simg,address')->select($rs1);
	
		//数组分页
		$page = ($_POST['page']-1)*10;
		$return = array_slice($return, $page, 10);
	
		if ($return){
			json('200','成功！',$return);
		}else{
			json('400','失败！');
		}
	}
	
	
	
	
	
	
	
}