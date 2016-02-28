<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	//测试数据
    	$_SESSION['uid'] = 1;
       	$this->display('main');
    }
    
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
	function paihang(){//读取排行榜
		$inviter_id = I('get.inviter' , $_SESSION['uid']);
		$rob_list = M('rob_list')->where(array('uid' => $inviter_id))->select();
		$count = count('$rob_list');
		for($i=0;$i<$count;$i++){
			$uid = $rob_list[$i]['uid'];
			$friendid = $rob_list[$i]['friendid'];
			if ($uid != $friendid) {
				$inviter = M('user')->where(array('id' => $friendid))->find();
			}
			$user = M('user')->where(array('id' => $uid))->find();
			$rob_list[$i]['username'] = $user['nickname'];
			$rob_list[$i]['time'] = date('Y年m月d日 h:i:s');
		}
		return $rob_list;
	}
	//抢红包
	public function doGrab(){
		$inviter_id = I('get.inviter' , $_SESSION['uid']);    //邀请人id
		if ($_SESSION['uid'] == $inviter_id) { //判断是否被邀请
			$map['invite'] = 0;
		}else{
			$map['invite'] = 1;
			$map['inviter_id'] = $inviter_id;
		}
		$uid = $_SESSION['uid'];
		$temp = M('user')->where(array('id' => $uid))->find();
		$map['nickname'] = $temp['nickname'];
		$is_rob = M('rob')->where(array('uid' => $uid))->find();
		if ($is_rob) {  //判断是否已抢过红包
			$map['first_rob'] = $is_rob['first_rob'];
			$map['total_rob'] = $is_rob['total_rob'];
		}else{
			$map['first_rob'] = $this->randFloat();
			$map['total_rob'] = $map['first_rob'];
			$data['first_rob'] = $map['first_rob'];
			$data['uid'] = $uid;
			$data['total_rob'] = $map['total_rob'];	
			M('rob')->add($data);

			$list['uid'] = $inviter_id;
			$list['friendid'] = $uid;
			$list['rob_time'] = time();
			$list['rob_price'] = $map['first_rob'];
			M('rob_list')->add($list);
		}

		
		$paycash =C('PAYCASH');
		$map['value'] = $paycash - $map['price'];
		$roblist = $this->paihang();
		$this->assign('roblist',$roblist);
		$this->assign('map',$map);
        $this->display('rob_package');
	}

	public function rob_package(){
		
		$this->display();  
	}
	public function rule(){
		$this->display();
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