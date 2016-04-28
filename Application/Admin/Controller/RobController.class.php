<?php
namespace Admin\Controller;
use Think\Controller;
class RobController extends CommonController {
    public function lists(){
        $limit = 20;
        $pageNum        = I('pageNum', 1);
        $orderField     = I('orderField', 'id');
        $orderDirection = I('orderDirection', 'desc');
        $numPerPage     = I('numPerPage', $limit);
        
        $offset = ($pageNum -1) * $numPerPage;
        if (I('request.id')) {
            $where['id'] = I('request.id');
        }
        if (I('request.title')) {
            $where['title'] = I('request.title');
        }
        if (I('request.type')) {
            $where['type'] = I('request.type');
        }
        if (I('request.category')) {
            $where['category'] = I('request.category');
        }

        $totalCount  = M('rob')->where($where)->count('id');
        $lists = M('rob')->where($where)->order($orderField.' '.$orderDirection)->limit($offset.','.$numPerPage)->select();
        foreach($lists as $key => &$value) {
            $user_info = D('user')->where(array('unionid'=>$value['unionid']))->find();
            $value['nickname'] = $user_info['nickname'];
        }
        $page = array('pageNum'=>$pageNum, 'orderField'=>$orderField, 'orderDirection'=>$orderDirection, 'numPerPage'=>$numPerPage, 'totalCount'=>$totalCount);
        $this->assign('page', $page);
        $this->assign('lists', $lists);
        $this->display();
    }


    public function editorRob(){
        $id = I('get.id');
        if ($id) {
            $rob_info = M('rob')->where(array('id' => $id))->find();
            $rob_list = M('rob_list')->where(array('friendunionid'=>$rob_info['unionid']))->select();
            foreach($lists as $key => &$value) {
                $user_info = D('user')->where(array('unionid'=>$value['unionid']))->find();
                $value['nickname'] = $user_info['nickname'];
            }
            $this->assign('rob_list',$rob_list);
        }
        $this->display();
    }
    
}