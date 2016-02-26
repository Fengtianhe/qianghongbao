<?php 
namespace Home\Controller;
use Think\Controller;
class UseController extends Controller {
    public function _initialize() {
        //checkWeixinAuth();
    }

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
		$this->assign('map',$map);
        $this->display('index/main');
	}

    public function test() {
        /*phpinfo();
        $str = file_get_contents('https://www.baidu.com');
        var_dump($str);*/
        /*$ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,"https://www.baidu.com");

        curl_setopt($ch,CURLOPT_HEADER,1);

        $re = curl_exec($ch);

        curl_close($ch);
        var_dump($re);*/
        echo file_get_contents('https://www.baidu.com');
    }
}
?>