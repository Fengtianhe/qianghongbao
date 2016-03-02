<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function _initialize() {
        header("Content-type:text/html;charset=utf-8");
        checkWeixinAuth();
    }

    public function index(){
    	//测试数据
    	$_SESSION['uid'] = 2;

    	$uid = $_SESSION['uid'];

    	$is_rob = M('rob')->where(array('uid' => $uid))->find();
    	if ($is_rob) {
    		$map['first_rob'] = $is_rob['first_rob'];
			$map['total_rob'] = $is_rob['total_rob'];
			$paycash =C('PAYCASH');
			$map['value'] = $paycash - $map['price'];
    		$roblist = $this->paihang();
			$this->assign('roblist',$roblist);
			$this->assign('map',$map);
			$this->assign('status',1);
    		$this->display('rob_package');
    	}else{
    		$this->display('main');
    	}
    	
       	
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
		$count = count($rob_list);
		for($i=0;$i<$count;$i++){
			$uid = $rob_list[$i]['uid'];
			$friendid = $rob_list[$i]['friendid'];
			if ($uid != $friendid) {
				$user = M('user')->where(array('id' => $friendid))->find();
			}else{
				$user = M('user')->where(array('id' => $uid))->find();
			}
			$rob_list[$i]['username'] = $user['nickname'];
			$rob_list[$i]['time'] = date('Y年m月d日 h:i:s',$rob_list[$i]['rob_time']);
		}
		return $rob_list;
	}
	//抢红包
	public function doGrab(){
		
		$uid = $_SESSION['uid'];
		$temp = M('user')->where(array('id' => $uid))->find();
		$map['nickname'] = $temp['nickname'];
		$is_rob = M('rob')->where(array('uid' => $uid))->find();
		if ($is_rob) {  //判断是否已抢过红包
			$map['first_rob'] = $is_rob['first_rob'];
			$map['total_rob'] = $is_rob['total_rob'];

			$this->assign('status',1);
		}else{
			$map['first_rob'] = $this->randFloat();
			$map['total_rob'] = $map['first_rob'];
			$data['first_rob'] = $map['first_rob'];
			$data['uid'] = $uid;
			$data['total_rob'] = $map['total_rob'];	
			M('rob')->add($data);

			$list['uid'] = $uid;
			$list['friendid'] = $uid;
			$list['rob_time'] = time();
			$list['rob_price'] = $map['first_rob'];
			M('rob_list')->add($list);
			$this->assign('status',1);
		}

		
		$paycash =C('PAYCASH');
		$map['value'] = $paycash - $map['price'];
		$roblist = $this->paihang();
		$this->assign('roblist',$roblist);
		$this->assign('map',$map);
        $this->display('rob_package');
	}
	public function rob_package(){
		$inviter_id = $_SESSION['inviter_id'];
		$uid = $_SESSION['uid'];
		$temp = M('user')->where(array('id' => $inviter_id))->find();
		$map['nickname'] = $temp['nickname'];
		$rob = M('rob')->where(array('uid' => $inviter_id))->find();
		$map['first_rob'] = $rob['first_rob'];
		$map['total_rob'] = $rob['total_rob'];
		$roblist = $this->paihang();
		$this->assign('roblist',$roblist);
		$this->assign('map',$map);
		$this->display();  
	}
	public function rules(){
		$this->display();
	}
	public function help_rob(){
		$inviter_id = I('get.inviter' , $_SESSION['uid']);    //邀请人id
		$_SESSION['inviter_id'] = $inviter_id;
		$uid = $_SESSION['uid'];
		$temp = M('user')->where(array('id' => $inviter_id))->find();
		$map['nickname'] = $temp['nickname'];
		$rob = M('rob')->where(array('uid' => $inviter_id))->find();
		$map['first_rob'] = $rob['first_rob'];
		$map['total_rob'] = $rob['total_rob'];

		if (M('rob_list')->where(array('uid' => $inviter_id,'friendid' => $uid))->find()) {
			$map['st'] = 1;
		}
		if (M('rob_list')->where(array('uid' => $uid,'friendid' => $uid))->find()) {
			$map['sta'] = 1;
		}
		$roblist = $this->paihang();
		$this->assign('roblist',$roblist);
		$this->assign('map',$map);
		$this->display('rob_package');
	}
	public function dorob(){
		$inviter_id = I('get.inviter' , 0);
		$uid = $_SESSION['uid'];
		$rob_price = $this->randFloat();

		$list['uid'] = $inviter_id;
		$list['friendid'] = $uid;
		$list['rob_time'] = time();
		$list['rob_price'] = $rob_price;
		M('rob_list')->add($list);

		$temp = M('rob')->where(array('uid' => $inviter_id))->find();
		$data['total_rob'] = floatval($temp['total_rob']) + floatval($rob_price);
		$data['friend'] =$temp['friend'] + 1;
		M('rob')->where(array('uid' => $inviter_id))->save($data);
		$this->redirect('rob_package');

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