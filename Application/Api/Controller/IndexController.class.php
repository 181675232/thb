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
    		$data['addtime'] = time();
    		$data['password'] = md5(trim(I('post.password')));
    		$data['simg'] = '/Public/upfile/touxiang.jpg';
    		$user = M('user');
    		$yqm = I('post.pid');
    		if (!$user->where("pid = $yqm")->find()){
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
 				json('400');
 			}	
		}
		json('404','没有接收到传值');
    }
    
    //修改个人信息
    public function useredit(){
    	if(I('post.')){
    		$user = M('owner');
    		$data = I('post.');
    		
    		if($_FILES){
    			$data1 = $_FILES['SIMG'];
    			if (move_uploaded_file($data1['tmp_name'], './Public/upfile/'.$data1['name'])){ 
    				
    				$data['SIMG'] = '/Public/upfile/'.$data1['name'];
    			}else {
    				json('403','头像上传失败');
    			} 			
    		}
    		if ($user->save($data)){
    			$res = $user->find(I('post.ID'));
    			json('200','成功',$res);
    		}else {
    			json('400');
    		}
    	}
    	json('404','没有接收到传值');
    }

    //修改密码
    public function editpass(){
    	if(I('post.')){
    		$mobile = I('post.PHONE');
    		$owner = M('owner');
    		$data['PASSWORD'] = md5($_POST['PASSWORD']);
    		if ($owner->where("PHONE = $mobile")->save($data)){
    			json('200');
    		}else {
    			json('400');
    		}
    	}
    	json('404','没有接收到传值');
    }
	
	
	//我的车辆
	public function myvehicle(){
		if(I('post.ID')){
			$table = M('owner_vehicle');
			$where['owner_vehicle.OWNER_ID'] = I('post.ID');
			$data = $table
			->join("left join vehicle_model_type ON vehicle_model_type.ID = owner_vehicle.VEHICLE_MODEL_TYPE_ID")
			->join("left join vehicle_model_year ON vehicle_model_year.ID = vehicle_model_type.MODEL_YEAR_ID")
			->join("left join vehicle_model ON vehicle_model.ID = vehicle_model_year.MODEL_ID")
			->join("left join vehicle_mfg ON vehicle_mfg.ID = vehicle_model.MFG_ID")
			->where($where)->field("owner_vehicle.*,vehicle_model_type.MODEL_TYPE,vehicle_model_year.MODEL_YEAR,vehicle_model.MODEL,vehicle_mfg.MFG_ORIG,vehicle_mfg.MFG")
			->order('owner_vehicle.IS_ACTIVE desc,owner_vehicle.IS_DEFAULT desc')->select();
			if ($data){
				json('200','成功',$data);
			}else {
				json('400');
			}					
		}
		json('404','没有接收到传值');
	}
	
	//添加车辆1
	public function addvehicle1(){
		$table = M('vehicle_mfg');
		$data = $table->order('PREFIX asc')->select();
		if ($data){
			json('200','成功',$data);
		}else{
			json('400');
		}
	}
	
	//添加车辆2
	public function addvehicle2(){
		if(I('post.ID')){
			$table = M('vehicle_model');
			$where['MFG_ID'] = I('post.ID');
			$data = $table->where($where)->order('ID asc')->select();
			if ($data){
				json('200','成功',$data);
			}else{
				json('400');
			}
			
		}
		json('404','没有接收到传值');
	}
	
	//添加车辆3
	public function addvehicle3(){
		if(I('post.ID')){
			$table = M('vehicle_model_year');
			$where['MODEL_ID'] = I('post.ID');
			$data = $table->where($where)->order('ID asc')->select();
			if ($data){
				json('200','成功',$data);
			}else{
				json('400');
			}
		}
		json('404','没有接收到传值');
	}
	
	//添加车辆4
	public function addvehicle4(){
		if(I('post.ID')){
			$table = M('vehicle_model_type');
			$where['MODEL_YEAR_ID'] = I('post.ID');
			$data = $table->where($where)->order('ID asc')->select();
			if ($data){
				json('200','成功',$data);
			}else{
				json('400');
			}
		}
		json('404','没有接收到传值');
	}
	//添加车辆5
	public function addvehicle5(){
		if(I('post.')){	
			$table = M('owner_vehicle');
			$where1['OWNER_ID'] = I('post.OWNER_ID');
			$where1['IS_DEFAULT'] = 'Y';
			if ($table->where($where1)->find()){
				$where2['IS_DEFAULT'] = 'N';
				$table->where($where1)->save($where2);
			}
			$where = I('post.');
			$where['DATE_PURCHASE'] = strtotime(I('post.DATE_PURCHASE'));
			$data = $table->add($where);
			if ($data){
				json('200','成功',$data);
			}else{
				json('400');
			}
		}
		json('404','没有接收到传值');
	}
	//设置报废
	public function state(){
		if(I('post.')){
			$table = M('owner_vehicle');
			$where['ID'] = I('post.ID');
			$where['IS_ACTIVE'] = 'N';			
			$table->save($where);
			if ($table){
				json('200','设置成功');
			}else {
				json('400');
			}
		}
		json('404','没有接收到传值');
	}
	//设置默认
	public function def(){
		if(I('post.')){
			$table = M('owner_vehicle');
			$where = I('post.');
			$res = $table->where($where)->find();
			if ($res){
				$id = I('post.ID');	
				$where1['ID'] = array('neq',I('post.ID'));
				$where1['OWNER_ID'] = I('post.OWNER_ID');	
				$data = $table->where($where)->setField("IS_DEFAULT","Y");
				$data1 = $table->where($where1)->setField("IS_DEFAULT","N");
				if ($data && $data1){
					json('200','设置成功');
				}else {
					json('400');
				}
			}else {
				json('401','车辆不存在');
			}
			
		}
		json('404','没有接收到传值');
	}

	//车辆详情
	public function vehicleinfo(){
		if(I('post.ID')){
			$table = M('owner_vehicle');
			$where['owner_vehicle.ID'] = I('post.ID');
			$data = $table
			->join("left join vehicle_model_type ON vehicle_model_type.ID = owner_vehicle.VEHICLE_MODEL_TYPE_ID")
			->join("left join vehicle_model_year ON vehicle_model_year.ID = vehicle_model_type.MODEL_YEAR_ID")
			->join("left join vehicle_model ON vehicle_model.ID = vehicle_model_year.MODEL_ID")
			->join("left join vehicle_mfg ON vehicle_mfg.ID = vehicle_model.MFG_ID")
			->where($where)->field("owner_vehicle.*,vehicle_model_type.MODEL_TYPE,vehicle_model_year.MODEL_YEAR,vehicle_model.MODEL,vehicle_mfg.MFG_ORIG,vehicle_mfg.MFG")
			->order('owner_vehicle.IS_DEFAULT desc')->find();
			if ($data){
				json('200','成功',$data);
			}else {
				json('400');
			}
		}
		json('404','没有接收到传值');
	}
	
	//我的地址
	public function myaddr(){
		if(I('post.')){
			$where = I('post.');
			$table = M('owner_address');
			$data = $table->where($where)->select();
			if ($data){
				json('200','成功',$data);

			}else {
				json('400');
			}
		}
		json('404','没有接收到传值');
	}

	//添加地址
	public function addaddr(){
		if(I('post.')){
			$where = I('post.');
			$table = M('owner_address');
			$map['OWNER_ID'] = I('post.OWNER_ID');
			$row = $table->where($map)->find();
			if ($row){
				$where['IS_DEFAULT'] = 'N';
			}else {
				$where['IS_DEFAULT'] = 'Y';
			}
			$data = $table->add($where);
			if ($data){
				json('200','成功');
			}else {
				json('400');
			}
		}
		json('404','没有接收到传值');
	}
	
	//地址详情
	public function addrinfo(){
		if(I('post.ID')){
			$table = M('owner_address');
			$data = $table->find(I('post.ID'));
			if ($data){
				json('200','成功',$data);
			}else {
				json('400');
			}
		}
		json('404','没有接收到传值');
	}
	
	//地址删除
	public function deladdr(){
		if(I('post.ID')){
			$table = M('owner_address');
			$data = $table->delete(I('post.ID'));
			if ($data){
				json('200','成功');
			}else {
				json('400');
			}
		}
		json('404','没有接收到传值');
	}
	
	//地址设为默认
	public function addrdef(){
		if(I('post.')){
			$where = I('post.');
			$table = M('owner_address');		
			$res = $table->where($where)->find();
			if ($res){
				$id = I('post.ID');			
				$where1['OWNER_ID'] = I('post.OWNER_ID');
				$data1 = $table->where($where1)->setField("IS_DEFAULT","N");
				$data = $table->where($where)->setField("IS_DEFAULT","Y");				
				if ($data && $data1){
					json('200','设置成功');
				}else {
					json('400');
				}
			}else {
				json('401','车辆不存在');
			}
		}
		json('404','没有接收到传值');
	}
	
	//关于优修
	public function about(){
		$table = M('base');
		$data = $table->find(1);
		if ($data){
			json('200','成功',$data);
		}else {
			json('400');
		}
	}
    
    //维修店注册
	public  function  proreg(){
		if (!empty($_POST['PASSWORD']))
		{
			$data = $_POST;
			$data2 = $_FILES['SIMG'];
			if (move_uploaded_file($data2['tmp_name'], './Public/upfile/'.$data2['name'])){
				$data['BUSINESS_START_DATE'] = time();
				$data1['PASSWORD'] = md5(trim($data['PASSWORD']));	
				$data1['JPUSHID'] = $data['JPUSHID'];
				$data['SIMG'] = '/Public/upfile/'.$data2['name'];
				
				unset($data['PASSWORD']);
				unset($data['JPUSHID']);
				
				$user = M('provider');
				$return = $user->add($data);
				if ($return){
					$table = M('provider_user');
					$data1['PROVIDER_ID'] = $return;
					$data1['NAME'] = $data['CONTACT_NAME'];
					$data1['PHONE'] = $data['CONTACT_PHONE'];
					$data1['EMAIL'] = $data['CONTACT_EMAIL'];
					$res = $table->add($data1);
					$res = $table->find($res);			
					json('200','成功！',$res);
				}else{
					json('400'); 
				}	
			}else{
				json('403','头像上传失败');
			}
			
		}else{
			if (!empty($_POST['CONTACT_PHONE']))
			{
				$mobile=$_POST['CONTACT_PHONE'];
				if(!checkPhone($mobile)){
					json('401','请输入正确的手机号！');
				}
				$user = M('provider_user');
				$return = $user->where("PHONE=$mobile")->find();
				if($return)
				{
					json('402','用户名已经被注册，请登陆！');
				}else{
					yzm($mobile);
				}
			}
		}
	}
	
	//维修店用户登录
	public function prologin(){
		$user = M('provider_user');
		$mobile=$_POST["PHONE"];
		$return = $user->where("PHONE='$mobile'")->find();
		if($return){
			$data = $_POST;
			$data['PASSWORD'] = md5(trim($data['PASSWORD']));
			unset($data['JPUSHID']);
			$return1 = $user->where($data)->find();
			if($return1){
				if (I('post.JPUSHID')){
					$where['ID'] = $return1['id'];
					$where['JPUSHID'] = I('post.JPUSHID');
					$user->save($where);
				}
				json('200','成功',$return1);
				 
			}else{
				json('401','密码错误');
			}
		}else{
			json('402','未注册！');
		}
	}
	
	//维修店首页
	public function proindex(){
		if(I('post.ID')){
			$user = M('provider_user');
			$where['provider_user.ID'] = I('post.ID');
			$data = $user->where($where)
			->join('left join provider on provider.ID = provider_user.PROVIDER_ID')
			->field('provider.*,provider_user.ROLE')->find();
			if ($data){
				json('200','成功',$data);
			}else {
				json('400');
			}			
		}
		json('404','没有接收到传值');
	}
	
	//个人资料
	public function provideruser(){
		if(I('post.ID')){
			$user = M('provider_user'); 
			$data = $user->find(I('post.ID'));
			if ($data){
				json('200','成功',$data);
			}else {
				json('400');
			}
		}
		json('404','没有接收到传值');
	}
    
	//维修店用户修改密码
	public function proeditpass(){
		if(I('post.')){
			$mobile = I('post.PHONE');
			$owner = M('provider_user');
			$data['PASSWORD'] = md5($_POST['PASSWORD']);
			if ($owner->where("PHONE = $mobile")->save($data)){
				json('200');
			}else {
				json('400');
			}
		}
		json('404','没有接收到传值');
	}
	
	//维修店用户发送验证码
	public function proyzm(){
		if(I('post.')){
			$mobile = I('post.PHONE');
			$owner = M('provider_user');
			if (!checkPhone($mobile)){
				json('401','手机格式不正确');
			}
			if (!$owner->where("PHONE = $mobile")->find()){
				json('402','手机号码不存在');
			}
			yzm($mobile);
		}
		json('404','没有接收到传值');
	}
	
	//进入发起服务
	public function startserver(){
		if (I('post.ID')){
			$id = I('post.ID');
			$addr = M('owner_address');
			$vehicle = M('owner_vehicle');
			$service_type = M('service_type');
			$addr = $addr->where("OWNER_ID = $id")->order('IS_DEFAULT desc')->select();
			foreach ($addr as $key => $val){
				$data['addr'][$key]['id'] = $val['id'];
				$data['addr'][$key]['is_default'] = $val['is_default'];
				$data['addr'][$key]['title'] = $val['country'].' '.$val['province'].' '.$val['city'].' '.$val['district'];
			}
			$vehicle1 = $vehicle->field('owner_vehicle.*,vehicle_model_type.MODEL_TYPE,vehicle_model_year.MODEL_YEAR,vehicle_model.MODEL,vehicle_mfg.MFG')
			->join("left join vehicle_model_type ON vehicle_model_type.ID = owner_vehicle.VEHICLE_MODEL_TYPE_ID")
			->join("left join vehicle_model_year ON vehicle_model_year.ID = vehicle_model_type.MODEL_YEAR_ID")
			->join("left join vehicle_model ON vehicle_model.ID = vehicle_model_year.MODEL_ID")
			->join("left join vehicle_mfg ON vehicle_mfg.ID = vehicle_model.MFG_ID")
			->where("owner_vehicle.OWNER_ID = $id and owner_vehicle.IS_ACTIVE = 'Y'")->order('owner_vehicle.IS_DEFAULT desc')->select();
			$data['vehicle'] = array();
			foreach ($vehicle1 as $key => $val){
				$data['vehicle'][$key]['id'] = $val['id'];
				$data['vehicle'][$key]['is_default'] = $val['is_default'];
				$data['vehicle'][$key]['title'] = $val['mfg'].' '.$val['model'].' '.$val['model_year'].' '.$val['model_type'];
			}
			$data['service'] = $service_type->select();
			if ($data){
				json('200','成功',$data);
			}else {
				json('400');
			}
		}
		json('404','没有接收到传值');
	}
	
	//发起服务提交
	public function addserver(){
		if (I('post.')){
			$table = M('service_request');
			$where = I('post.');
			
			$where['SERVICE_DATE'] = strtotime($where['SERVICE_DATE']);
			$where['SERVICE'] = $where['SERVICE'];
			$where['DATE_CREATED'] = time();
			
			$res = $table->add($where);		
			if ($res){
				if ($where['ADDR_ID']){
					$owner_address = M('owner_address');
					$addr = $owner_address->find($where['ADDR_ID']);				
					$provider = M('provider');
					$pro = $provider->where("CITY = '{$addr['city']}'")->select();
					if ($pro){
						$data['count'] = $provider->where("CITY = '{$addr['city']}'")->count();
						$ids = '';
						foreach ($pro as $val){
							$ids .= $val['id'].','; 
						}
						$ids = substr($ids,-1);
						$map['id'] = array('in',$ids);
						$user = M('provider_user');
						$user = $user->where($map)->select();
						$jpushid = array();
						foreach ($user as $val){
							$jpushid[] = $val['jpushid'];
						}
				    	$jpush = new \Org\Util\Jpush($this->app_key,$this->master_secret);
				    	$content = '您有一条服务订单';
				    	$jpush->push($jpushid, $this->title,$content);
						json('200','成功',$data);
					}else {
						json('402','本市区没有优修维修店');
					}
					
				}else {
					json('403','地址没有选择');
				}
			}else {
				json('400');
			}
		}
		json('404','没有接收到传值');
	}
	
	//用户进行中的订单
	public function myserver(){
		if (I('post.ID')){
			if (I('post.PAGE')){
				$page = I('post.PAGE');
			}else {
				$page = 1;
			}
			$page = ($page-1)*15;
			$id = I('post.ID');
			$table = M('service_request');
			$data = $table->field('service_request.REQUEST_STATUS_ID,service_request.SERVICE_DATE,service_request.ID,service_request.SERVICE,vehicle_model_type.MODEL_TYPE,vehicle_model_year.MODEL_YEAR,vehicle_model.MODEL,vehicle_mfg.MFG')
			->join('left join owner_vehicle on owner_vehicle.ID = service_request.VEHICLE_ID')
			->join("left join vehicle_model_type ON vehicle_model_type.ID = owner_vehicle.VEHICLE_MODEL_TYPE_ID")
			->join("left join vehicle_model_year ON vehicle_model_year.ID = vehicle_model_type.MODEL_YEAR_ID")
			->join("left join vehicle_model ON vehicle_model.ID = vehicle_model_year.MODEL_ID")
			->join("left join vehicle_mfg ON vehicle_mfg.ID = vehicle_model.MFG_ID")
			->where("service_request.OWNER_ID = $id and (service_request.REQUEST_STATUS_ID = 2 or service_request.REQUEST_STATUS_ID = 3)")
			->limit($page,15)->order('service_request.DATE_CREATED desc')->select();
			
			if ($data){
				foreach ($data as $key => $val){
					$res[$key]['id'] = $val['id'];
					$res[$key]['title'] = $val['mfg'].' '.$val['model'].' '.$val['model_year'].' '.$val['model_type'];
					$res[$key]['state'] = $val['request_status_id'];
					$res[$key]['service_date'] = $val['service_date'];
					$service_type = M('service_type');
					$res[$key]['type'] = $service_type->select($val['service']);
					$service_request_bid = M('service_request_bid');
					$res[$key]['count'] = $service_request_bid->where("REQUEST_ID = '{$val['id']}'")->count();
					if ($val['request_status_id'] == 3){
						$service_request_bid = M('service_request_bid');
						$where['REQUEST_ID'] = $val['id'];
						$where['request_status_id'] = 3;
						$ress = $service_request_bid->where($where)->find();
						$res[$key]['provider_id'] = $ress['provider_id'];
					}
				}
				json('200','成功',$res);
			}else {
				json('400','没有更多数据');
			}
		}
		json('404','没有接收到传值');
	}
	
	//用户已完成订单
	public function completeserver(){
		if (I('post.ID')){
			if (I('post.PAGE')){
				$page = I('post.PAGE');
			}else {
				$page = 1;
			}
			$page = ($page-1)*15;
			$id = I('post.ID');
			$table = M('service_request');
			$data = $table->field('service_request.REQUEST_STATUS_ID,service_request.SERVICE_DATE,service_request.ID,service_request.SERVICE,vehicle_model_type.MODEL_TYPE,vehicle_model_year.MODEL_YEAR,vehicle_model.MODEL,vehicle_mfg.MFG')
			->join('left join owner_vehicle on owner_vehicle.ID = service_request.VEHICLE_ID')
			->join("left join vehicle_model_type ON vehicle_model_type.ID = owner_vehicle.VEHICLE_MODEL_TYPE_ID")
			->join("left join vehicle_model_year ON vehicle_model_year.ID = vehicle_model_type.MODEL_YEAR_ID")
			->join("left join vehicle_model ON vehicle_model.ID = vehicle_model_year.MODEL_ID")
			->join("left join vehicle_mfg ON vehicle_mfg.ID = vehicle_model.MFG_ID")
			->where("service_request.OWNER_ID = $id and (service_request.REQUEST_STATUS_ID = 4 or service_request.REQUEST_STATUS_ID = 5)")
			->limit($page,15)->order('service_request.DATE_CREATED desc')->select();
				
			if ($data){
				foreach ($data as $key => $val){
					$res[$key]['id'] = $val['id'];
					$res[$key]['title'] = $val['mfg'].' '.$val['model'].' '.$val['model_year'].' '.$val['model_type'];
					$res[$key]['state'] = $val['request_status_id'];
					$res[$key]['service_date'] = $val['service_date'];
					$service_type = M('service_type');
					$res[$key]['type'] = $service_type->select($val['service']);
					$feedback = M('owner_feedback');
					$res[$key]['feedback'] = $feedback->where("REQUEST_ID = '{$val['id']}'")->count();
					if ($val['request_status_id'] == 4){
						$service_request_bid = M('service_request_bid');
						$where['REQUEST_ID'] = $val['id'];
						$where['request_status_id'] = 4;
						$ress = $service_request_bid->where($where)->find();
						$res[$key]['provider_id'] = $ress['provider_id'];
					}
				}
				json('200','成功',$res);
			}else {
				json('400','没有更多数据');
			}
		}
		json('404','没有接收到传值');
	}
	
	//取消订单
	public function offserver(){
		if (I('post.ID')){
			$table = M('service_request');
			$where['ID'] = $where1['REQUEST_ID'] = I('post.ID');
			$where['REQUEST_STATUS_ID'] = 5;
			if ($table->save($where)){
				$service_request_bid = M('service_request_bid');
				if ($service_request_bid->where($where1)->find()){
					$where2['BID_STATUS'] = 3;
					$service_request_bid->where($where1)->save($where2);
				}
				json('200');
			}else {
				json('400','取消失败');
			}
		}
		json('404');
	}
	
	
	//用户端报价列表
	public function ownerofferlist(){
		if (I('post.ID')){
			$id = I('post.ID');
			if (I('post.PAGE')){
				$page = I('post.PAGE');
			}else {
				$page = 1;
			}
			
			$table = M('service_request_bid');
			$request = M('service_request');
			$addr = $request->field('owner_address.LATITUDE,owner_address.LONGITUDE')
			->join('left join owner_address on owner_address.ID = service_request.ADDR_ID')
			->where("service_request.ID = $id")->find();
			
			$data = $table->field('service_request_bid.ID,service_request_bid.NET_TOTAL,service_request_bid.UPDATE_TIME,service_request_bid.RESPONSE_TIME,provider.LATITUDE,provider.LONGITUDE,provider.NAME,provider.ADDR1,provider_user.PHONE')
			->join('left join provider_user on provider_user.ID = service_request_bid.PROVIDER_USER_ID')
			->join('left join provider on provider.ID = service_request_bid.PROVIDER_ID')
			->where("service_request_bid.REQUEST_ID = $id")->select();
			if ($data){
				foreach ($data as $key => $val){
					$res[$key]['id'] = $val['id'];
					$res[$key]['net_total'] = $val['net_total'];
					$res[$key]['update_time'] = $val['update_time'];
					$res[$key]['response_time'] = $val['response_time'];
					$res[$key]['name'] = $val['name'];
					$res[$key]['addr1'] = $val['addr1'];
					$res[$key]['phone'] = $val['phone'];				
					$res[$key]['di'] = powc($addr['latitude'], $addr['longitude'], $val['latitude'], $val['longitude']);
					if ($res[$key]['di'] >= 1000){
						$res[$key]['distance'] = ceil($res[$key]['di']/1000).'km';
					}else {
						$res[$key]['distance'] = $res[$key]['di'].'m';
					}
					$ress = $table->find($val['id']);
					$res[$key]['provider_id'] = $ress['provider_id'];	
				}
				foreach ($res as $arrys) {
					$distances[] = $arrys['di'];
				}
				foreach ($res as $arrys) {
					$time[] = $arrys['response_time'];
				}
				foreach ($res as $arrys) {
					$total[] = $arrys['net_total'];
				}
				if (I('post.ORDER') == 2){
					array_multisort($distances,SORT_ASC,$res);
				}elseif (I('post.ORDER') == 3){
					array_multisort($total,SORT_ASC,$res);
				}else {
					array_multisort($time,SORT_DESC,$res);
				}
				$res = array_page($res,$page);
				if ($res){
					json('200','成功',$res);
				}else {
					json('400','没有更多数据');
				}				
			}else{
				json('403','没有数据');	
			}
		}
		json('404');
	}
	
	//查看维修点信息
	public function provider(){
		if (I('post.ID')){
			$id = I('post.ID');
			if (I('post.PAGE')){
				$page = I('post.PAGE');
			}else {
				$page = 1;
			}
			$page = ($page-1)*15;
			$table = M('provider');
			$data['provider'] = $table->find($id);
			$feedback = M('owner_feedback');
			$data['feedback'] = $feedback->where("PROVIDER_ID = $id")->order("DATE_CREATED desc")->limit("$page,15")->select();

			json('200','成功',$data);

		}
		json('404');
	}
	
	//通过订单id查看报价详情
	public function serverofferinfo(){
		if (I('post.ID')){		
			$table = M('service_request_bid');
			$bid_material_detail = M('bid_material_detail');
			$bid_service_detail = M('bid_service_detail');
			$where['REQUEST_ID'] = I('post.ID');
			if (I('post.USER_ID')){
				$pro = M('provider_user');
				$user = $pro->find(I('post.USER_ID'));
				$where['PROVIDER_ID'] = $user['provider_id'];
			}else {
				$where['BID_STATUS'] = array('in',array(2,4));
			}				
			$data = $table->where($where)->find(false);

			if ($data){
				$id = $data['id'];
				$data['service_detail'] = $bid_service_detail->where("BID_ID = $id")->select();
				$data['material_detail'] = $bid_material_detail->where("BID_ID = $id")->select();
				json('200','成功',$data);
			}else {
				json('400','没有此订单');
			}
		}
		json('404');
	}
	
	//通过报价单id查看报价详情
	public function myofferinfo(){
		if (I('post.ID')){
			$id = I('post.ID');
			$table = M('service_request_bid');
			$bid_material_detail = M('bid_material_detail');
			$bid_service_detail = M('bid_service_detail');
			$data = $table->find($id);
			if ($data){
				$data['service_detail'] = $bid_service_detail->where("BID_ID = $id")->select();
				$data['material_detail'] = $bid_material_detail->where("BID_ID = $id")->select();
				json('200','成功',$data);
			}else {
				json('400','没有此订单');
			}
		}
		json('404');
	}
	
	//签约
	public function selectoffer(){
		if (I('post.ID')){
			$service_request_bid = M('service_request_bid');
			$where['ID'] = I('post.ID');
			$where['BID_STATUS'] = 2;
			if ($service_request_bid->save($where)){
				$request_bid = $service_request_bid->find(I('post.ID'));
				$where1['REQUEST_ID'] = $where2['ID'] = $request_bid['request_id'];
				$where1['BID_STATUS'] = 1;
				$service_request_bid->where($where1)->setField('BID_STATUS','3');
				$table = M('service_request');
				$where2['REQUEST_STATUS_ID'] = 3;				
				if ($table->save($where2)){
					$user = M('provider_user');
					$data = $user->find($request_bid['provider_user_id']);
					$jpushid[0] = $data['jpushid'];
					$jpush = new \Org\Util\Jpush($this->app_key,$this->master_secret);
					$content = '您的报价已被签约，请尽快联系车主进行维修';
					$jpush->push($jpushid, $this->title,$content);
					json('200');
				}else {
					json('401','签约失败');
				}			
			}else {
				json('400','签约失败');
			}
		}
		json('404');
	}
	
	//评价
	public function message(){
		if (I('post.')){
			$where = I('post.');
			$server = M('service_request');
			$data = $server->field('service_request_bid.PROVIDER_ID')
			->join('left join service_request_bid on service_request.ID = service_request_bid.REQUEST_ID')
			->where("service_request.ID = '{$where['REQUEST_ID']}'")->find();
			$where['PROVIDER_ID'] = $data['provider_id'];
			$where['DATE_CREATED'] = time();
			$table = M('owner_feedback');
			if ($table->add($where)){
				json('200');
			}else {
				json('400','评价失败');
			}		
		}
		json('404');
	}
	
	
	//等待报价列表
	public function offerlist(){
		if (I('post.ID')){
			$id = I('post.ID');
			if (I('post.PAGE')){
				$page = I('post.PAGE');
			}else {
				$page = 1;
			}
			$user = M('provider_user');
			$addr = $user->field('provider.ID,provider.CITY,provider.LATITUDE,provider.LONGITUDE')->join('left join provider on provider.ID = provider_user.PROVIDER_ID')->where("provider_user.ID = $id")->find();
			$table = M('service_request');			
			$where['service_request.REQUEST_STATUS_ID'] = 2;
			$where['owner_address.CITY'] = $addr['city'];
			$data = $table->field('service_request.REQUEST_STATUS_ID,service_request.SERVICE_DATE,service_request.ID,service_request.SERVICE,owner_address.LATITUDE,owner_address.LONGITUDE,vehicle_model_type.MODEL_TYPE,vehicle_model_year.MODEL_YEAR,vehicle_model.MODEL,vehicle_mfg.MFG_ORIG,vehicle_mfg.MFG')
			->join('left join owner_address on owner_address.ID = service_request.ADDR_ID')
			->join('left join owner_vehicle on owner_vehicle.ID = service_request.VEHICLE_ID')
			->join("left join vehicle_model_type ON vehicle_model_type.ID = owner_vehicle.VEHICLE_MODEL_TYPE_ID")
			->join("left join vehicle_model_year ON vehicle_model_year.ID = vehicle_model_type.MODEL_YEAR_ID")
			->join("left join vehicle_model ON vehicle_model.ID = vehicle_model_year.MODEL_ID")
			->join("left join vehicle_mfg ON vehicle_mfg.ID = vehicle_model.MFG_ID")
			->where($where)->order('service_request.DATE_CREATED desc')->select();
			if ($data){
				$service_request_bid = M('service_request_bid');				
				foreach ($data as $key => $val){ 
					$where1['REQUEST_ID'] = $val['id'];
					$where1['PROVIDER_ID'] = $addr['id'];
					if ($service_request_bid->where($where1)->find()){
						continue;
					}
					$res[$key]['id'] = $val['id'];
					$res[$key]['title'] = $val['mfg'].' '.$val['model'].' '.$val['model_year'].' '.$val['model_type'];
					$res[$key]['state'] = '等待报价';
					$res[$key]['service_date'] = $val['service_date'];
					$service_type = M('service_type');
					$res[$key]['type'] = $service_type->select($val['service']);
					$res[$key]['di'] = powc($addr['latitude'], $addr['longitude'], $val['latitude'], $val['longitude']);
					if ($res[$key]['di'] >= 1000){
						$res[$key]['distance'] = ceil($res[$key]['di']/1000).'km';
					}else {
						$res[$key]['distance'] = $res[$key]['di'].'m';
					}
				}
		 		foreach ($res as $arrys) {
		 			$distances[] = $arrys['di'];
		 		}
				array_multisort($distances,SORT_ASC,$res);
				$res = array_page($res,$page);
				if ($res){
					json('200','成功',$res);
				}else {
					json('400','没有更多数据');
				}				
			}else{
				json('400','没有更多数据');
			}
		}
		json('404');
	}
	
	//进入报价页面
	public function offerinfo(){
		if (I('post.ID')){
			$service_request_bid = M('service_request_bid');
			$where['REQUEST_ID'] = $where1['REQUEST_ID'] = I('post.ID');
			$where['PROVIDER_USER_ID'] = $where1['PROVIDER_USER_ID'] = I('post.USER_ID');
			$where['BID_STATUS'] = 1;
			if ($service_request_bid->where($where)->find()){
				json('401','您已报价，请耐心等待！');
			}
			$where['BID_STATUS'] = 3;
			if ($service_request_bid->where($where)->find()){
				json('401','对不起，您未中标！');
			}
			$id = I('post.ID');
			$table = M('service_request');
			$data = $table->field('service_request.SERVICE_DATE,owner.PHONE,service_request.ID,service_request.NOTE,service_request.SERVICE,vehicle_model_type.MODEL_TYPE,vehicle_model_year.MODEL_YEAR,vehicle_model.MODEL,vehicle_mfg.MFG_ORIG,vehicle_mfg.MFG')
			->join('left join owner on owner.ID = service_request.OWNER_ID')
			->join('left join owner_vehicle on owner_vehicle.ID = service_request.VEHICLE_ID')
			->join("left join vehicle_model_type ON vehicle_model_type.ID = owner_vehicle.VEHICLE_MODEL_TYPE_ID")
			->join("left join vehicle_model_year ON vehicle_model_year.ID = vehicle_model_type.MODEL_YEAR_ID")
			->join("left join vehicle_model ON vehicle_model.ID = vehicle_model_year.MODEL_ID")
			->join("left join vehicle_mfg ON vehicle_mfg.ID = vehicle_model.MFG_ID")
			->where("service_request.ID = $id")->find();
			if ($data){
				$res['service_request']['id'] = $data['id'];
				$res['service_request']['title'] = $data['mfg'].' '.$data['model'].' '.$data['model_year'].' '.$data['model_type'];
				$res['service_request']['service_date'] = $data['service_date'];
				$res['service_request']['phone'] = $data['phone'];
				$service_type = M('service_type');
				$res['service_request']['select_service'] = $service_type->select($data['service']);
				json('200','成功',$res);
			}else{
				json('400');
			}
		}
		json('404');
	}
	
	//调用所有服务
	public function allserver(){
		$service = M('service_type');
		$res['service'] = $service->select();
		json('200','成功',$res);
	}
	
	//调用所有零件
	public function allpart(){
		$part = M('service_part');
		$res['part'] = $part->select();
		json('200','成功',$res);
	}
	
	//提交报价
	public function addoffer(){
		if (I('post.')){		
			$table = M('service_request_bid');
			$where = I('post.');

			$provider_user = M('provider_user');
			$pu = $provider_user->find(I('post.PROVIDER_USER_ID'));		
			$where['PROVIDER_ID'] = $pu['provider_id'];
			$where['TOTAL_BEFORE_DISCOUNT'] = $where['TOTAL_LABOR'] + $where['TOTAL_MATERIAL'];
			$where['NET_TOTAL'] = $where['TOTAL_BEFORE_DISCOUNT'] - $where['DISCOUNT'];
			$where['RESPONSE_TIME'] = time();
			$where1 = json_decode(strtoupper($_POST['SERVICE']));
			$where2 = json_decode(strtoupper($_POST['PART']));

			unset($where['SERVICE']);
			unset($where['PART']);
			$res = $table->add($where);
			if ($res){
				$bid_material_detail = M('bid_material_detail');			
				$bid_service_detail = M('bid_service_detail');
				foreach ($where1 as $key => $val){
					$where3['BID_ID'] = $res;					
 					$where3['SERVICE_TYPE_ID'] = $val->ID;
 					$where3['DESCRIPTION'] = $val->TITLE;
 					$where3['LABOR_HOUR'] = $val->LABORHOUR;
 					$where3['AMOUNT'] = $val->AMOUNT;
 					$bid_service_detail->add($where3);
				}	
				foreach ($where2 as $key => $val){
					$where4['BID_ID'] = $res;
					$where4['SERVICE_PART_ID'] = $val->ID;
					$where4['PART_NAME'] = $val->PART_NAME;
					$where4['UNIT_OF_MEASURE'] = $val->UOM;
					$where4['QTY'] = $val->BUY_NUM;
					$where4['UNIT_PRICE'] = $val->UNIT_PRICE;
					$where4['AMOUNT'] = $where4['UNIT_PRICE']*$where4['QTY'];
					$bid_material_detail->add($where4);
				}								
				$service_request = M('service_request');
				$request_id = I('post.REQUEST_ID');
				$user = $service_request->field('owner.JPUSHID')->join('left join owner on owner.ID = service_request.OWNER_ID')->where("service_request.id = $request_id")->select(); 
				$jpushid = array();
				
				foreach ($user as $val){
					$jpushid[] = $val['jpushid'];
				}			
				$jpush = new \Org\Util\Jpush($this->app_key,$this->master_secret);
				$content = '您收到一张报价单';
				$jpush->push($jpushid, $this->title,$content);
				json('200');
			}
			json('404');
		}
	}
	
	//已确认服务列表
	public function selectofferlist(){
		if (I('post.ID')){
			$id = I('post.ID');
			if (I('post.PAGE')){
				$page = I('post.PAGE');
			}else {
				$page = 1;
			}
			$page = ($page-1)*15;
			$user = M('provider_user');
			$addr = $user->field('provider.ID,provider.LATITUDE,provider.LONGITUDE')->join('left join provider on provider.ID = provider_user.PROVIDER_ID')->where("provider_user.ID = $id")->find();
			
			$table = M('service_request_bid');			
			$where['service_request_bid.BID_STATUS'] = 2;
			$where['service_request_bid.PROVIDER_ID'] = $addr['id'];			
			$data = $table->field('service_request.REQUEST_STATUS_ID,service_request.OWNER_ID,service_request.SERVICE_DATE,service_request.ID,service_request.SERVICE,owner_address.LATITUDE,owner_address.LONGITUDE,vehicle_model_type.MODEL_TYPE,vehicle_model_year.MODEL_YEAR,vehicle_model.MODEL,vehicle_mfg.MFG_ORIG,vehicle_mfg.MFG')
			->join('left join service_request on service_request_bid.REQUEST_ID = service_request.ID')
			->join('left join owner_address on owner_address.ID = service_request.ADDR_ID')
			->join('left join owner_vehicle on owner_vehicle.ID = service_request.VEHICLE_ID')
			->join("left join vehicle_model_type ON vehicle_model_type.ID = owner_vehicle.VEHICLE_MODEL_TYPE_ID")
			->join("left join vehicle_model_year ON vehicle_model_year.ID = vehicle_model_type.MODEL_YEAR_ID")
			->join("left join vehicle_model ON vehicle_model.ID = vehicle_model_year.MODEL_ID")
			->join("left join vehicle_mfg ON vehicle_mfg.ID = vehicle_model.MFG_ID")
			->where($where)->limit("$page,15")->order('service_request.DATE_CREATED desc')->select();
			if ($data){
				foreach ($data as $key => $val){ 
					$res[$key]['id'] = $val['id'];
				 	$res[$key]['title'] = $val['mfg'].' '.$val['model'].' '.$val['model_year'].' '.$val['model_type'];
					$res[$key]['state'] = '等待维修';
					$res[$key]['service_date'] = $val['service_date'];
					$res[$key]['owner_id'] = $val['owner_id'];
					$service_type = M('service_type');
					$map['ID'] = array('in',$val['service']);
					$type = $service_type->where($map)->getField('title',true);
					$res[$key]['type'] = implode(',', $type); 
					$res[$key]['di'] = powc($addr['latitude'], $addr['longitude'], $val['latitude'], $val['longitude']);
					if ($res[$key]['di'] >= 1000){
						$res[$key]['distance'] = ceil($res[$key]['di']/1000).'km';
					}else {
						$res[$key]['distance'] = $res[$key]['di'].'m';
					}
				}		 
				json('200','成功',$res);
			}else{
				json('400','没有更多数据');
			}
		}
		json('404');
	}
	
	//我的订单
	public function myorder(){
		if (I('post.ID')){
			$id = I('post.ID');
			if (I('post.PAGE')){
				$page = I('post.PAGE');
			}else {
				$page = 1;
			}
			$page = ($page-1)*15;
			$user = M('provider_user');
			$addr = $user->field('provider.ID,provider.LATITUDE,provider.LONGITUDE')->join('left join provider on provider.ID = provider_user.PROVIDER_ID')->where("provider_user.ID = $id")->find();
				
			$table = M('service_request_bid');
			$where['service_request_bid.PROVIDER_ID'] = $addr['id'];
			$data = $table->field('service_request_bid.BID_STATUS,service_request.OWNER_ID,service_request.SERVICE_DATE,service_request.ID,service_request.SERVICE,owner_address.LATITUDE,owner_address.LONGITUDE,vehicle_model_type.MODEL_TYPE,vehicle_model_year.MODEL_YEAR,vehicle_model.MODEL,vehicle_mfg.MFG_ORIG,vehicle_mfg.MFG')
			->join('left join service_request on service_request_bid.REQUEST_ID = service_request.ID')
			->join('left join owner_address on owner_address.ID = service_request.ADDR_ID')
			->join('left join owner_vehicle on owner_vehicle.ID = service_request.VEHICLE_ID')
			->join("left join vehicle_model_type ON vehicle_model_type.ID = owner_vehicle.VEHICLE_MODEL_TYPE_ID")
			->join("left join vehicle_model_year ON vehicle_model_year.ID = vehicle_model_type.MODEL_YEAR_ID")
			->join("left join vehicle_model ON vehicle_model.ID = vehicle_model_year.MODEL_ID")
			->join("left join vehicle_mfg ON vehicle_mfg.ID = vehicle_model.MFG_ID")
			->where($where)->limit("$page,15")->order('service_request.DATE_CREATED desc')->select();
			if ($data){
				foreach ($data as $key => $val){
					$res[$key]['id'] = $val['id'];
					$res[$key]['title'] = $val['mfg'].' '.$val['model'].' '.$val['model_year'].' '.$val['model_type'];
					$res[$key]['state'] = $val['bid_status'];
					$res[$key]['service_date'] = $val['service_date'];
					$res[$key]['owner_id'] = $val['owner_id'];
					$service_type = M('service_type');
					$map['ID'] = array('in',$val['service']);
					$type = $service_type->where($map)->getField('title',true);
					$res[$key]['type'] = implode(',', $type);
					$res[$key]['di'] = powc($addr['latitude'], $addr['longitude'], $val['latitude'], $val['longitude']);
					if ($res[$key]['di'] >= 1000){
						$res[$key]['distance'] = ceil($res[$key]['di']/1000).'km';
					}else {
						$res[$key]['distance'] = $res[$key]['di'].'m';
					}
				}
				json('200','成功',$res);
			}else{
				json('400','没有更多数据');
			}
		}
		json('404');
	}
	
	//查看车主
	public function ownerinfo(){
		if (I('post.ID')){
			$id = I('post.ID');
			if (I('post.PAGE')){
				$page = I('post.PAGE');
			}else {
				$page = 1;
			}
			$page = ($page-1)*15;
			$table = M('owner');
			$data1['owner'] = $table->find($id);
			$vehicle = M('owner_vehicle');
			$ids = $vehicle->where("OWNER_ID = $id")->getField(id,true);
			$log = M('vehicle_maintenance_log');
			$where['vehicle_maintenance_log.VEHICLE_ID'] = array('in',$ids);
			$data = $log->field('vehicle_maintenance_log.ID,vehicle_maintenance_log.SERVICE_DATE,vehicle_maintenance_log.SERVICE_TYPE_ID,vehicle_model_type.MODEL_TYPE,vehicle_model_year.MODEL_YEAR,vehicle_model.MODEL,vehicle_mfg.MFG_ORIG,vehicle_mfg.MFG')
			->join('left join owner_vehicle on owner_vehicle.ID = vehicle_maintenance_log.VEHICLE_ID')
			->join("left join vehicle_model_type ON vehicle_model_type.ID = owner_vehicle.VEHICLE_MODEL_TYPE_ID")
			->join("left join vehicle_model_year ON vehicle_model_year.ID = vehicle_model_type.MODEL_YEAR_ID")
			->join("left join vehicle_model ON vehicle_model.ID = vehicle_model_year.MODEL_ID")
			->join("left join vehicle_mfg ON vehicle_mfg.ID = vehicle_model.MFG_ID")
			->where($where)->limit("$page,15")->order('vehicle_maintenance_log.SERVICE_DATE desc')->select();
			if ($data){
				foreach ($data as $key => $val){
					$res[$key]['id'] = $val['id'];
					$res[$key]['title'] = $val['mfg'].' '.$val['model'].' '.$val['model_year'].' '.$val['model_type'];
					$res[$key]['service_date'] = $val['service_date'];
					$service_type = M('service_type');
					$map['ID'] = array('in',$val['service_type_id']);
					$type = $service_type->where($map)->getField('title',true);
					$res[$key]['service_type'] = implode(',', $type);
				}
				$data1['log'] = $res;				
			}
			json('200','成功',$data1);	
		}
		json('404');
	}
	
	//确认完成 
	public function complete(){
		if (I('post.ID')){
			$where['ID'] = I('post.ID');			
			$where['REQUEST_STATUS_ID'] = 4;
			$service_request = M('service_request');
			$service = $service_request->find(I('post.ID'));
			$vehicle = M('owner_vehicle');
			$vehicledata = $vehicle->find($service['vehicle_id']);
			if (I('post.MILEAGE') < $vehicledata['mileage']){
				json('402','添加的里程不能小于原里程数');
			}
			$service_requestdata = $service_request->save($where);
			$service_request_bid = M('service_request_bid');
			$service_request_biddata = $service_request_bid->where("REQUEST_ID = '{$where['ID']}' and BID_STATUS = 2")->setField('BID_STATUS',4);			
			if ($service_requestdata && $service_request_biddata){
				$table = M('vehicle_maintenance_log');
				$where1['MILEAGE'] = I('post.MILEAGE');
				$where1['SERVICE_DATE'] = time();
				$where1['SERVICE_TYPE_ID'] = $service['service'];
				$where1['VEHICLE_ID'] = $service['vehicle_id'];
				if ($table->add($where1)){
					$owner = M('owner');
					$ownerdata = $owner->find($service['owner_id']);
					$jpushid[0] = $ownerdata['jpushid'];
					$jpush = new \Org\Util\Jpush($this->app_key,$this->master_secret);
					$content = '您的车辆已维修完成';
					$jpush->push($jpushid, $this->title,$content);
					json('200','成功');
				}else {
					json('400','维修日志添加失败');
				}
			}else {
				json('401','确认失败');
			}
		}
		json('404');
	}

	//账户管理
	public function userlist(){
		if (I('post.ID')){
			$table = M('provider_user');
			$user = $table->find(I('post.ID'));			
			$where['PROVIDER_ID'] = $user['provider_id'];
			$where['ROLE'] = 2;
			$data = $table->where($where)->select();
			if ($data){
				json('200','成功',$data);
			}else {
				json('400','您还没有添加店员');
			}
		}
		json('404');
	}
	
	//添加员工
	public function adduser(){
		if (I('post.')){			
			$table = M('provider_user');
			$user = $table->find(I('post.ID'));
			$where = I('post.');
			unset($where['ID']);
			if(!checkPhone($where['PHONE'])){
				json('401','请输入正确的手机号！');
			}
			$where['PASSWORD'] = md5($where['PASSWORD']);
			$where['PROVIDER_ID'] = $user['provider_id'];
			$where['ROLE'] = 2;
			if ($table->add($where)){
				json('200');
			}else {
				json('400','添加失败');
			}
		}
		json('404');
	}
	
	//删除员工
	public function deluser(){
		if (I('post.ID')){
			$table = M('provider_user');
			if ($table->delete(I('post.ID'))){
				json('200');
			}else {
				json('400','删除失败');
			}
		}
		json('404');
	}
	
	//数据统计
	public function tongji(){
		if (I('post.ID')){
			$user = M('provider_user');
			$data = $user->find(I('post.ID'));
			$time = time()-30*24*60*60;
			$provider_id = $data['provider_id'];
			$table = M('service_request_bid');
			$res['offer30'] = $table->where("PROVIDER_ID = $provider_id and RESPONSE_TIME>$time")->count();
			$res['bid30'] = $table->where("PROVIDER_ID = $provider_id and RESPONSE_TIME>$time and (BID_STATUS = 2 or BID_STATUS = 4)")->count();
			$res['total30'] = $table->where("PROVIDER_ID = $provider_id and RESPONSE_TIME>$time and BID_STATUS = 4")->sum('NET_TOTAL');
			$res['offer'] = $table->where("PROVIDER_ID = $provider_id")->count();
			$res['bid'] = $table->where("PROVIDER_ID = $provider_id and (BID_STATUS = 2 or BID_STATUS = 4)")->count();
			$res['total'] = $table->where("PROVIDER_ID = $provider_id and BID_STATUS = 4")->sum('NET_TOTAL');
			json('200','成功',$res);
		}
		json('404');
	}
	
	//店铺管理
	public function providerinfo(){
		if(I('post.ID')){
			$user = M('provider_user');
			$where = $user->find(I('post.ID'));
			$table = M('provider');
			$data = $table->find($where['provider_id']);
			if ($data){
				json('200','成功',$data);
			}else {
				json('400');
			}
		}
		json('404','没有接收到传值');
	}
	
	//修改店铺
	public function provideredit(){
		if(I('post.')){
			$user = M('provider');
			$data = I('post.');	
			if($_FILES){
				$data1 = $_FILES['SIMG'];
				if (move_uploaded_file($data1['tmp_name'], './Public/upfile/'.$data1['name'])){	
					$data['SIMG'] = '/Public/upfile/'.$data1['name'];
				}else {
					json('403','头像上传失败');
				}
			}
			if ($user->save($data)){
				json('200','成功');
			}else {
				json('400');
			}
		}
		json('404','没有接收到传值');
	}
	
	//意见反馈
	public function addmessage(){
		if (I('post.')){
			$where = I('post.');
			$table = M('message');
			if ($table->add($where)){
				json('200');
			}else {
				json('400','意见反馈失败');
			}
		}
		json('404');
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
    //查询用户时间内记录
    public function query(){
    	$data = $_POST;
    	$position = M('position');
    	$day=time()-($data['day']*86400000);
    	if(isset($data['uid'])){
    		$return = $position->where("uid = '{$data['uid']}' and time>'$day'")->select();
    		if ($return){
    			json('200','成功！',$return);
    		}else{
    			json('400','失败！');
    		}
    	}
    }
    //查询所有用户最新的记录
    public function latest(){
    	$position = M('position');
    	$subquery = $position->table('t_position')->order('time desc')->buildSql();
    	$return=$position->table($subquery.'t_position')->group('uid')->select();
    	if ($return){
    		json('200','成功！',$return);
    	}else{
    		json('400','失败！');
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
	//检测手机是否存在
	public function check_mobile(){
		if(I('get.mobile')){
 			$user = M('user');
			$data['mobile'] = I('get.mobile');
 			if ($user->where($data)->find()){
 				json('200','手机号已存在');
 			}else {
 				json('400','手机号不存在');
 			}
		}
		json('404','没有获取到需要检测的手机号');
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



	//搜索
	public function search(){
		if ($_POST['data']){
			$activity = M('activity');				
			$json = json_decode($_POST['data'],ture);
			if ($json['page']){
				$page = ($json['page']-1)*15;
			}
			if ($json['sex'] != 2){
				$data['t_user.sex'] = $json['sex'];
			}
			if ($json['reputation'] != 0){
				$data['t_user.reputation '] = $json['reputation '];
			}
			if (!empty($json['nickname'])){
				$data['t_user.nickname'] = array('like',"%{$json['nickname']}%");
			}
			if (!empty($json['age'])){
				$data['t_user.age'] = array('between',$data['age']);
			}
			if (!empty($json['province'])){
				$data['t_activity.province'] = $json['province'];
			}
			if (!empty($json['city'])){
				$data['t_activity.city'] = $json['city'];
			}		
			if (!empty($json['price'])){
				$data['t_user.price'] = array('between',$data['price']);
			}
			if($json['activity_cate'] != 0){
				$data['t_activity.activity_cate'] = $json['activity_cate'];
			}
			
			if (!empty($json['avail_time'])){
				$time = explode(',', $json['avail_time']);
				$data['t_activity.avail_time'] = array('between',array("$time[0]","$time[1]"));	
			}				
			$return = $activity->join('left join t_user ON t_activity.user_id = t_user.id')
			->where($data)->field('t_activity.id activity_id,t_activity.standard,t_activity.long_term,
					t_activity.remark,t_activity.activity_cate,t_activity.avail_time,t_activity.avail_day,t_activity.price,
					t_activity.down_vote,t_activity.up_vote,t_activity.city,t_activity.province,t_activity.activity_type,t_activity.longtitude,t_activity.latitude,
					t_user.id user_id,t_user.verify,t_user.nickname,t_user.sex,t_user.face_icon,t_user.age,t_user.reputation')->limit($page,15)->select();
			foreach ($return as $key=>$val){			
				$return[$key]['distance'] = powc($json['longitude'], $json['latitude'], $val['longtitude'], $val['latitude']);
				$act = M('activity_vote');
				$where['user_id'] = $json['user_id'];
				$where['activity_id'] = $val['activity_id'];
				if ($act->where($where)->where('type = 1')->find()){
					$return[$key]['is_up'] = 1;
				}else {
					$return[$key]['is_up'] = 0;
				}
				if ($act->where($where)->where('type = 2')->find()){
					$return[$key]['is_down'] = 1;
				}else {
					$return[$key]['is_down'] = 0;
				}
			}			
			$count = $activity->join('left join t_user ON t_activity.user_id = t_user.id')->where($data)->count();
			if ($return){
				json('200',ceil($count/15),$return);
			}else {
				json('400','空');
			}
			
		}
		json('404','没有获取到任何搜索选项');
	}
	



	
	//看过谁添加
	public function addlook(){
		if (I('get.')){
			$look = M('look1');
			$data['user_id'] = I('get.user_id');
			$data['look_user_id'] = I('get.look_user_id');		
			if ($look->where($data)->find()){
				$data1['create_ts'] = time();
				if ($look->where($data)->save($data1)){
					json('200','成功');
				}else{
					json('400','失败');
				}
			}else {
				$data['create_ts'] = time();
				if ($look->add($data)){
					json('200','成功');
				}else{
					json('400','失败');
				}
			}			
		}
		json('404','没有获取到任何数据');
	}
	//谁看过我
	public function lookme(){
		if (I('get.id')){
			$look = M('look1');
			$data['look_user_id'] = I('get.id');
			if (I('get.page')){
				$page = (I('get.page')-1)*15;
			}
			$return = $look->join('left join t_user on t_user.id = t_look1.user_id')
			->where($data)->field('t_user.id user_id,t_user.face_icon,t_user.nickname,t_look1.create_ts')->limit($page,15)->select();
			$count = $look->join('left join t_user on t_user.id = t_look1.user_id')->where($data)->count();
			
			json('200',ceil($count/15),$return);
		}
		json('404','没有获取到任何数据');
	}
	//我看过谁
	public function melook(){
		if (I('get.id')){
			$look = M('look1');
			$data['user_id'] = I('get.id');
			if (I('get.page')){
				$page = (I('get.page')-1)*15;
			}
			$return = $look->join('left join t_user on t_user.id = t_look1.look_user_id')
			->where($data)->field('t_user.id user_id,t_user.face_icon,t_user.nickname,t_look1.create_ts')->select();
			$count = $look->join('left join t_user on t_user.id = t_look1.look_user_id')->where($data)->count();
			json('200',ceil($count/15),$return);
		}
		json('404','没有获取到任何数据');
	}
	
	
	
	
	
	
	
}