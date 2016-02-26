<?php

//检查微信授权
function checkWeixinAuth(){
    if (!$_SESSION['me']['weixin']) {
        $redirect_uri = $_SERVER['HTTP_HOST'].$_SERVER['QUERY_STRING'];
        $Weixin = new \Common\Util\WeixinAuth();
        $Weixin->authorize($redirect_uri);
    }
}

function test(){
    echo "123";
}

