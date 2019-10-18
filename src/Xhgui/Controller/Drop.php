<?php

class Xhgui_Controller_Drop extends Xhgui_Controller
{
    protected $_app;
    protected $_profiles;

    public function __construct($app, $profiles)
    {
        $this->_app = $app;
        $this->_profiles = $profiles;
    }

    public function get()
    {
        $this->_template = 'drop/index.twig';
        $this->set(array(
            'title' => '清除日志'
        ));
    }


    public function query()
    {
        $request = $this->_app->request();
        $response = $this->_app->response();
        $response['Content-Type'] = 'application/json';

        $param = $request->post();
        $range = isset($param['range']) ? $param['range'] : 0;
        $ip = isset($param['ip']) ? trim($param['ip']) : '';
        if (empty($range) || ($range == 2 && empty($ip)) || !in_array($range, [1,2])) {
            return $response->body(json_encode(['info' => '参数错误']));
        }

        if ($range == 1) {
            $this->_profiles->truncate();
        } else {
            $condition = [
                'meta.SERVER.REMOTE_ADDR' => $ip,
            ];
            $this->_profiles->remove($condition);
        }
        return $response->body(json_encode(['info' => '清除成功']));
    }
}
