<?php 
namespace Home\Controller;
use Think\Controller;
class UseController extends Controller {
	public $max = parent::getConfig('max');
	$min = parent::getConfig('min');
	$pay_cash = parent::getConfig('paycash');
	//生成随机红包数
	function randFloat($min , $max) {
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