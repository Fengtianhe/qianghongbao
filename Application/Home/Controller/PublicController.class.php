<?php 
namespace Home\Controller;
use Think\Controller;
class PublicController extends Controller {
	public function getConfig($type){
		$config = M('config');
		$temp = $config->where(array('type' = $type))->find();
		return $temp['value'];
	}
}
?>