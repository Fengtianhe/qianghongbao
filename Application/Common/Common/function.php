<?php

//检查微信授权
function checkWeixinAuth(){
    if (!$_SESSION['me']['weixin']) {
        $redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['QUERY_STRING'];
        $Weixin = new \Common\Util\WeixinAuth();
        $user_info = $Weixin->authorize($redirect_uri);
        $_SESSION['me']['weixin'] = $user_info;
    }
}
