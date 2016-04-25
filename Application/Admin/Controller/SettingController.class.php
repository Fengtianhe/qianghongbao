<?php
namespace Admin\Controller;
use Think\Controller;
class SettingController extends CommonController {
	

    public function lists(){
        $setting_info = include "./Application/Home/Conf/config.php";
        $this->assign('setting_info', $setting_info);
        $this->display();
    }
    public function saveSetting(){
        $str = '
<?php
    return array(
        "MAX" => "'.$_POST['max'].'",
        "MIN" => "'.$_POST['min'].'",
        "PAYCASH" => "'.$_POST['paycash'].'",
        "URL_MODEL" =>  2,
    );
        ';
        file_put_contents('./Application/Home/Conf/config.php', $str);
        $result['statusCode'] = "200";
        $result['message']   = "修改成功";
        $result['navTabId'] = "setting";
        $result['rel']   = "setting";
        $result['forwardUrl']   = "";
        $result['confirmMsg'] = "";
        $this->ajaxReturn($result);
    }
}