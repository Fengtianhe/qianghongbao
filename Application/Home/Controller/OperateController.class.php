<?php 
namespace Home\Controller;
use Think\Controller;
class OperateController extends Controller {
	//生成随机红包数
	function randFloat($min = 5, $max = 10) {
    	$min = $min * 100;
    	$max = $max * 100;
		return mt_rand($min , $max)/100;
	}
	//抢红包
	public function doGrab(){
		$price = $this->randFloat();
		$value = 
	}
}
?>