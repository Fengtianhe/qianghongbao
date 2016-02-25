<?php 
namespace Home\Controller;
use Think\Controller;
class PublicController extends Controller {
	public function getConfig($type){
		$config = M('config');
		$configs = $config->find();
		if ($type == 'max') {
			return $configs['max'];
		}
		elseif ($type == 'min') {
			return $configs['min'];
		}
		elseif ($type == 'paycash') {
			return $configs['pay_cash'];
		}
	}
}
?>