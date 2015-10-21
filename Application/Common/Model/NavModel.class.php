<?php
namespace Common\Model;
use Think\Model;

class NavModel extends Model{
	protected $_validate = array(		
		array('title','require','导航名称不能为空！'), 
		array('name','require','调用ID不能为空！'),
	);
	public function nav_info($bid = 0,$state = 0){
		$nav = M('Nav');
		$arr = array();
		if ($state == 0){
			$result = $nav->where("bid = $bid and level !=3")->order('id asc')->select();
		}else {
			$data['bid'] = $bid;
			$data['state'] = $state;
			$data['level'] = array('neq',3);
			$result = $nav->where($data)->order('id asc')->select();
		}
		$arr = $result;
 		if ($result){
			foreach ($result as $key => $val){
				if ($val['id'] != null){
					$arr[$key]['catid'] = $this->nav_info($val['id'],$state);
				}
			}
			return $arr;
 		}else {
 			return array();
 		}	
	}
	
	public function nav_role($bid = 0,$state = 0){
		$nav = M('Nav');
		$arr = array();
		if ($state == 0){
			$result = $nav->where("bid = $bid and level !=3")->order('id asc')->select();
			foreach ($result as $key=>$val){
				$result[$key]['role'] = $nav->where("bid = '{$val['id']}' and level =3")->select();
			}
		}else {
			$data['bid'] = $bid;
			$data['state'] = $state;
			$data['level'] = array('neq',3);
			$result = $nav->where($data)->order('id asc')->select();
		}
		$arr = $result;
		if ($result){
			foreach ($result as $key => $val){
				if ($val['id'] != null){
					$arr[$key]['catid'] = $this->nav_role($val['id'],$state);
				}
			}
			return $arr;
		}else {
			return array();
		}
	}
	
}