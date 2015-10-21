<?php
namespace Admin\Controller;

class GoodsController extends CommonController {
	
	public function index(){
		
		$table = M('Goods'); // 实例化User对象
		
		//接收查询数据
		if (IS_POST){
			$post = I('post.');
			$data['title'] = $post['keyword'];
			$count = $table->where($data)->count();		
		}elseif (I('get.')){
			$data = I('get.');
			$count = $table->where($data)->count();
			$this->assign('verify',I('get.verify'));
		}		      
		$Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$res = $table->where($data)->order('addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('data',$res);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板			
	}
	
	public function add(){
		if (IS_POST){
			$table = D('Goods');
			if ($table->create()){
				if ($table->add()){
					alertLocation('添加会员成功！', '/Admin/Goods');
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
			$table = D('Goods');
			if ($table->create()){
				if ($table->save()){
					alertBack('修改成功！');
				}else {
					$this->error('没有任何修改！');
				}
			}else {
				$this->error($table->getError());
			}
				
		}
		$table = M('Goods');
		$data = $table->where("id = $id")->find();
		$this->assign($data);
		$this->display();
	}
	
	public function state(){
		$data = I('get.');			
		$table = M('Goods');

		if ($table->save($data)){
			$this->redirect("/Admin/Goods/show");
		}else {
			$this->error('没有任何修改！');
		}
	}

	
	public function delete(){		
		$post = implode(',',$_POST['id']);	
		$table = M('Goods');
		$data = $table->delete($post);
		if ($data){
			echo '删除成功！';
		}else {
			echo '删除失败！';
		}
	}
	
	public function ajax(){
		if (!empty($_POST['param'])){
			$table = M('Goods');
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