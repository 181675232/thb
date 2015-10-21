<?php
namespace Admin\Controller;

class MessageController extends CommonController {
	
	public function index(){
		
		$table = M('Message'); // 实例化User对象
		
		$count      = $table->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$res = $table->order('ID desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('data',$res);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display(); // 输出模板			
	}	
	
	public function add(){
		if (IS_POST){
			$table = M('Vehicle_mfg');
			if ($table->add(I('post.'))){
				alertLocation('添加成功！', '/Admin/Mfg');
			}else {
				$this->error('添加失败！');
			}		
		}
		$this->display();
	}
	
	public function edit(){
		$id = I('get.id');
		if (IS_POST){
			$table = M('Vehicle_mfg');
			if ($table->save(I('post.'))){
				alertBack('修改成功！');
			}else {
				$this->error('没有任何修改！');
			}			
		}
		$table = M('Vehicle_mfg');
		$data = $table->where("id = $id")->find();
		$this->assign($data);
		$this->display('');
	}
	
//	public function state(){
//		$data = I('get.');			
//		$user = M('User');
//
//		if ($user->save($data)){
//			$this->redirect("/Admin/User/show");
//		}else {
//			$this->error('没有任何修改！');
//		}
//	}

	
	public function delete(){		
		$post = implode(',',$_POST['id']);	
		$table = M('Message');
		$data = $table->delete($post);
		if ($data){
			echo '删除成功！';
		}else {
			echo '删除失败！';
		}
	}

	
} 