<?php

//检查微信授权
function checkWeixinAuth(){
    //线下模拟用户数数据
    if ($_SERVER['HTTP_HOST'] != 'dq1yt.ztpet.cn') {
        $_SESSION['me']['weixin'] = array( 
            'openid'    => 'sssdfs0zSDssNuV1So6NlhtAzFw',
            'nickname'  => '马234ssdf莹',
            'sex'       => 1,
            'language'  => 'zh_CN',
            'city'      => '吉林',
            'province'  => '吉林',
            'country'   => '中国',
            'headimgurl' => 'http://wx.qlogo.cn/mmopen/uOleZXC9MkHt18ILqqRobph0G6XHjPFicCYWMRDAZSW3vbDFNWQnT5sg2Hk3ADz6pJfpkrA4gKVbvBLwcicsvOBuOPOgrIfnwK/0',
            'privilege'  => array(),
            'unionid'   => 'o8sSjvyUp75GonpouRZZVjXVVky0'
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
    }

    $openid = $_SESSION['me']['weixin']['openid'];
    //对当前用户进行判断，没有则加入数据库
    $is_user = M('user')->where(array('openid' => $openid))->find();   
    if (!$is_user) {
        $userinfo = $_SESSION['me']['weixin'];
        $save_user = M('user')->add($userinfo);
    }
}

/**
 * 二维数组多字段排序
 * array_orderby($data,field1,sort,field2,sort,...)
 * @return array 排序后的数组
 */
function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}

function is_weixin(){ 
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    }  
    return false;
}