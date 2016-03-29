<?php
namespace Home\Controller;
use Think\Controller;
class ApiController extends Controller {
    public function _initialize() {
        header("Content-type:text/html;charset=utf-8");
        checkWeixinAuth();
    }

    /**
     * 处理网站微信授权
     * 
     */
    public function wxlogin(){
    	
    	$user_info = base64_encode(json_encode($_SESSION['me']['weixin']));
    	$redirect_url = I('redirect');
    	redirect("http://1yg.com/?/api/weixinlogin/handle/".$redirect_url."/".$user_info);
    }
       
}