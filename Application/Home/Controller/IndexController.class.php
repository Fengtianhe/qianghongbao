<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	public $reply_msg = array(
		0 => '太感谢了，有你的支持我就要可以兑换啦！',
		1 => '手气不错哦，真棒！',
		2 => '这么多，如果可以真的希望你再帮我抢十次呢！',
		);

    public function _initialize() {
        header("Content-type:text/html;charset=utf-8");
        checkWeixinAuth();
    }
    public function index(){
     	$unionid = $_SESSION['me']['weixin']['unionid'];
    	//判断用户是否已抢过 sign标识是否抢过红包
    	$is_rob = M('rob')->where(array('unionid' => $unionid))->find();
    	if ($is_rob) {
    		$this->redirect('rob_package',array('unionid' => $unionid,'sign' => '1'));
    	}else{
    		$this->redirect('main',array('unionid' => $unionid,'sign' => '0'));
    	}
    }
    //抢红包首页
    public function main(){
    	$this->assign('map',$_GET);
    	$this->display();
    }
    //生成随机红包数
	function randFloat() {
		$min = C('MIN');
		$max = C('MAX');
    	$min = $min * 100;
    	$max = $max * 100;
		return mt_rand($min , $max)/100;
	}
	//读取排行榜
	function paihang(){
		$unionid = I('get.unionid' , 0);
		$rob_list = M('rob_list')->where(array('unionid' => $unionid))->select();
		$count = count($rob_list);
		for($i=0;$i<$count;$i++){
			$friendunionid = $rob_list[$i]['friendunionid'];
			$user = M('user')->where(array('unionid' => $friendunionid))->find();
			$rob_list[$i]['username'] = $user['nickname'];
			$rob_list[$i]['headimgurl'] = $user['headimgurl'];
			$the_time = $rob_list[$i]['rob_time'];
			$rob_list[$i]['time'] = $this->time_tran($the_time);
		}
		return $rob_list;
	}
	//抢红包页面
	public function rob_package(){
		$sign = $_GET['sign'];
		$unionid = $_GET['unionid'];
		$meunionid = $_SESSION['me']['weixin']['unionid'];
		if ($unionid == $meunionid) {   //如果unionid和当前用户相同则进入自己的页面
			$user_infomation = M('user')->where(array('unionid'=>$unionid))->find();
			if (!$sign) { //如果没抢过红包
				$map['first_rob'] = $this->randFloat();
				$map['total_rob'] = $map['first_rob'];
				$map['headimgurl'] = $user_infomation['headimgurl'];
				$map['nickname'] = $user_infomation['nickname'];
				//判断差多少可以兑现
				$paycash =C('PAYCASH');
				$map['value'] = $paycash - $map['total_rob'];
				if ($map['value'] <= 0) {
					$this->assign('exchange',1);
					$map['value'] = 0;
				}
				//保存数据
				$data['first_rob'] = $map['first_rob'];
				$data['unionid'] = $unionid;
				$data['total_rob'] = $map['total_rob'];	
				M('rob')->add($data);
				$list['unionid'] = $unionid;
				$list['friendunionid'] = $_SESSION['me']['weixin']['unionid'];
				$list['rob_time'] = time();
				$list['rob_price'] = $map['first_rob'];
				M('rob_list')->add($list);
				$this->redirect('rob_package',array('unionid' => $unionid,'sign' => '1'));
			}else{ //如果抢过红包
				$rob = M('rob')->where(array('unionid'=>$unionid))->find();
				$map['first_rob'] = $rob['first_rob'];
				$map['total_rob'] = $rob['total_rob'];
				$map['headimgurl'] = $user_infomation['headimgurl'];
				$map['nickname'] = $user_infomation['nickname'];
				//判断差多少可以兑现
				$paycash =C('PAYCASH');
				$map['value'] = $paycash - $map['total_rob'];
				if ($map['value'] <= 0) {
					$map['sta'] = 2;
					$map['value'] = 0;
				}else{
					$map['sta'] = -2;
				}
    			$roblist = $this->paihang();
				$this->assign('roblist',$roblist);
				$this->assign('map',$map);
				$this->assign('status',1);
			}
		}else{//如果如果unionid和当前用户不相同则进入帮忙抢的页面
			//$is_rob = M('rob_list')->where(array('unionid'=>$unionid,'friendunionid' =>$meunionid))->find();
			//if ($is_rob) {//判断是否帮TA抢过
				$temp = M('user')->where(array('unionid' => $unionid))->find();   //获取邀请人信息
				$map['nickname'] = $temp['nickname'];
				$map['headimgurl'] = $temp['headimgurl'];
				$rob = M('rob')->where(array('unionid' => $unionid))->find();    //获取邀请人抢过红包的金额
				$map['first_rob'] = $rob['first_rob'];
				$map['total_rob'] = $rob['total_rob'];
				if (M('rob_list')->where(array('unionid' => $unionid,'friendunionid' => $meunionid))->find()) {
					$map['st'] = 1;
				}
				if (M('rob')->where(array('unionid' => $meunionid))->find()) {
					$map['sta'] = 1;
				}else{
					$map['sta'] = -1;
				}
			//}
		}
		$roblist = $this->paihang();
		$this->assign('roblist',$roblist);
		$count = count($roblist);
		$this->assign('reply_msg', $this->reply_msg);
		$this->assign('count',$count-1);
		$this->assign('map',$map);
		$this->display();  
	}	
	public function rules(){
		$this->display();
	}
	public function dorob(){
		$unionid = $_GET['unionid'];
		$user = M('user')->where(array('unionid'=>$unionid))->find();
		if (!$user) {
			$userinfo = $_SESSION['me']['weixin'];
    		$save_user = M('user')->add($userinfo);
		}
		$meunionid = $_SESSION['me']['weixin']['unionid'];
		$rob_price = $this->randFloat();
		//保存帮抢数据
		$list['unionid'] = $unionid;
		$list['friendunionid'] = $meunionid;
		$list['rob_time'] = time();
		$list['rob_price'] = $rob_price;
		M('rob_list')->add($list);
		//邀请人数+1
		$temp = M('rob')->where(array('unionid' => $unionid))->find();
		$data['total_rob'] = floatval($temp['total_rob']) + floatval($rob_price);
		$data['friend'] =$temp['friend'] + 1;
		M('rob')->where(array('unionid' => $unionid))->save($data);

		if (M('rob')->where(array('unionid' => $meunionid))->find()) {
			$sta = '1';
		}else{
			$sta = '-1';
		}
		$this->redirect('rob_package',array('unionid'=>$unionid,'sign' => '1','sta' => $sta));
	}

	//读取人气榜
	public function ranking_list(){
		$rob = M('rob')->select();
		$count = count($rob);
		for($i=0;$i<$count;$i++){
			$unionid = $rob[$i]['unionid'];
			$user = M('user')->where(array('unionid'=>$unionid))->find();
			$rob[$i]['headimgurl'] = $user['headimgurl'];
			$rob[$i]['nickname'] = $user['nickname'];
		}
		foreach ($rob as $key=>$value){  //按人气排序
    		$friend[$key] = $value['friend'];
		}
		array_multisort($friend,SORT_DESC,$rob);
		$this->assign('map',$rob);
		$this->display();
	}

	function time_tran($the_time){
   		$now_time = time();
   		$show_time = $the_time;
   		$dur = $now_time - $show_time;
   		if($dur < 0){
    		return $the_time;
   		}elseif($dur == 0){
     		return '1秒前';
    	}elseif($dur < 60){
     		return $dur.'秒前';
    	}elseif($dur < 3600){
      		return floor($dur/60).'分钟前';
     	}elseif($dur < 86400){
       		return floor($dur/3600).'小时前';
       	}else{
        	return date("m/d h:i:s",$the_time);
       	}
    }
    public function balance() {
    	$unionid = $_SESSION['me']['weixin']['unionid'];
    	$where['unionid'] 	= $unionid;
    	$where['is_balance']= 0;
    	$rob = M('rob')->where($where)->find();
    	if (is_array($rob) && !empty($rob)) {
    		if ($rob['total_rob'] > C('PAYCASH')) {
    			$user_info = base64_encode(json_encode($_SESSION['me']['weixin']));
	    		$redirect_url = "http://1yg.com/?/api/weixinlogin/hbbalance";
	    		$redirect_url = base64_encode($redirect_url);
	    		redirect("http://1yg.com/?/api/weixinlogin/handle/".$redirect_url."/".$user_info);
    		} else {
    			$this->error('尚未满足提现条件');
    		}
    		
    	} else {
    		$this->error('已结算或失效');
    	}
	}    
}