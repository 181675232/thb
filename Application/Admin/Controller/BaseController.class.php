<?php
namespace Admin\Controller;

class BaseController extends CommonController {
	public function index(){
		$base = D('Base');
		if (I('post.')){
			if ($base->create()){
				if ($base->save(I('post.'))){
					alertBack('修改成功！');	
				}else {
					$this->error('没有任何修改！');
				}
			}else {
				$this->error($base->getError());
			}
		}
		$data = $base->find(1);
		$this->assign($data);
		$this->display();
	}
}