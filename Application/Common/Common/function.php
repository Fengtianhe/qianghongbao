<?php

//检查微信授权
function checkWeixinAuth(){
    //线下模拟用户数数据
    if ($_SERVER['HTTP_HOST'] != 'dq1yt.ztpet.cn') {
        $_SESSION['me']['weixin'] = array( 
            'openid'    => 'ojkFasdfa6NlhtAzFw',
            'nickname'  => 'asdssss',
            'sex'       => 1,
            'language'  => 'zh_CN',
            'city'      => '吉林',
            'province'  => '吉林',
            'country'   => '中国',
            'headimgurl' => 'http://wx.qlogo.cn/mmopen/uOleZXC9MkHt18ILqqRobph0G6XHjPFicCYWMRDAZSW3vbDFNWQnT5sg2Hk3ADz6pJfpkrA4gKVbvBLwcicsvOBuOPOgrIfnwK/0',
            'privilege'  => array()
        );

        $openid = $_SESSION['me']['weixin']['openid'];
        //对当前用户进行判断，没有则加入数据库
        $is_user = M('user')->where(array('openid' => $openid))->find();   
        if (!$is_user) {
            $userinfo = $_SESSION['me']['weixin'];
            $save_user = M('user')->add($userinfo);
        }
    }

    if (!$_SESSION['me']['weixin']) {
        $redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['QUERY_STRING'];
        $Weixin = new \Common\Util\WeixinAuth();
        $user_info = $Weixin->authorize($redirect_uri);
        $_SESSION['me']['weixin'] = $user_info;

        $openid = $_SESSION['me']['weixin']['openid'];
        //对当前用户进行判断，没有则加入数据库
        $is_user = M('user')->where(array('openid' => $openid))->find();   
        if (!$is_user) {
            $userinfo = $_SESSION['me']['weixin'];
            $save_user = M('user')->add($userinfo);
        }
    }
}
