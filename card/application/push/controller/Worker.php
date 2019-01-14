<?php

namespace app\push\controller;

use think\worker\Server;

class Worker extends Server
{
    protected $processes = 1;

    /**
     * 每个进程启动
     * @param $worker
     */
    public function onWorkerStart($worker)
    {
        $handle = new PushMessage();
        $handle->add_time();
    }
}
