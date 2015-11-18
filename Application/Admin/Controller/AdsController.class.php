<?php
namespace Admin\Controller;

class AdsController extends CommonController {
	
	public function index(){
		
		$table = M('ads'); // 实例化User对象
		
		//接收查询数据
		if (IS_POST){
			$keyword = I('get.keyword');
			$data['title'] = array('like',"%{$keyword}%");
			$table = $table->where($data);
		}elseif (I('get.verify')){
			$data = I('get.');
			$table = $table->where($data);
			$this->assign('verify',I('get.verify'));
		}
		$count      = $table->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$res = $table->where($data)	->order('istop desc,isred desc,ord asc,addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('data',$res);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板			
	}
	
	public function add(){
		if (IS_POST){
			$table = D('ads');
			if ($table->create()){
				if ($table->add()){
					alertLocation('添加会员成功！', '/Admin/Activity');
				}else {
					$this->error('添加失败！');
				}
			}else {
				$this->error($table->getError());
			}
			
		}
		$this->display();
	}
	
	public function edit(){
		$id = I('get.id');
		if (IS_POST){
			$table = D('ads');
			if ($table->create()){
				$data = $table->create();
				$data['create_ts'] = strtotime($data['create_ts']);
				$data['avail_day'] = implode(',', $data['avail_day']);
				if ($table->save($data)){
					alertBack('修改成功！');
				}else {
					$this->error('没有任何修改！');
				}
			}else {
				$this->error($table->getError());
			}
				
		}
		$table = M('ads');
		$data = $table->where("id = $id")->find();
		$data['avail_day'] = explode(',', $data['avail_day']);
		$this->assign($data);
		$this->display();
	}
	
	public function state(){
		$data = I('get.');			
		$table = M('ads');
		if ($table->save($data)){
			$this->redirect("/Admin/Activity");
		}else {
			$this->error('没有任何修改！');
		}
	}
	
	public function delete(){		
		$post = implode(',',$_POST['id']);	
		$table = M('ads');
		$data = $table->delete($post);
		if ($data){
			echo '删除成功！';
		}else {
			echo '删除失败！';
		}
	}
	
	public function ajaxstate(){
		$data = I('get.');
		$table = M('ads');
		if ($table->save($data)){
			echo 1;
		}else {
			echo 0;
		}
	}
	
	public function ajax(){
		if (!empty($_POST['param'])){
			$table = M('ads');
			$data[$_POST['name']] = $_POST['param'];
			$return = $table->where($data)->find();
			if ($return){
				echo '手机号已存在！';
			}else {
				echo 'y';
			}
		}
	}
	
} 