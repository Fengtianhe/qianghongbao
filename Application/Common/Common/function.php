<?php

//检查微信授权
function checkWeixinAuth(){
    //线下模拟用户数数据
    if ($_SERVER['HTTP_HOST'] != 'dq1yt.ztpet.cn') {
        $_SESSION['me']['weixin'] = array( 
            'openid'    => 'ojkW1s0zFD-UQNuV1Fo6ElhtAzFw',
            'nickname'  => '冯天鹤',
            'sex'       => 1,
            'language'  => 'zh_CN',
            'city'      => '吉林',
            'province'  => '吉林',
            'country'   => '中国',
            'headimgurl' => 'http://wx.qlogo.cn/mmopen/uOleZXC9MkHt18ILqqRobph0G6XHjPFicCYWMRDAZSW3vbDFNWQnT5sg2Hk3ADz6pJfpkrA4gKVbvBLwcicsvOBuOPOgrIfnwK/0',
            'privilege'  => array()
        );
    }

    if (!$_SESSION['me']['weixin']) {
        $redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['QUERY_STRING'];
        $Weixin = new \Common\Util\WeixinAuth();
        $user_info = $Weixin->authorize($redirect_uri);
        $_SESSION['me']['weixin'] = $user_info;
    }
}
