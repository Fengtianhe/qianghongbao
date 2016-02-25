<?php 
namespace Home\Controller;
use Think\Controller;
class UseController extends Controller {
	//生成随机红包数
	function randFloat() {
		$min = C('MIN');
		$max = C('MAX');
    	$min = $min * 100;
    	$max = $max * 100;
		return mt_rand($min , $max)/100;
	}
	//抢红包
	public function doGrab(){
		$map['price']= $this->randFloat();
		$paycash =C('PAYCASH');
		$map['value'] = $paycash - $map['price'];
		this->assign('map',$map);
	}
}
?>