<?php
include 'wechatEE.php';
$hd = new wechatEE('wxb0267b90d3dbc9ac','EPQq1bCx2Lqf7VUDNVP8fnlgAZ_FD26hot7MUzNcKRpmXqT8ThrxpT7fREsf6IC1');
$config = ['host'=>'host', 'port'=>'port'];
$hd->initCache('redis', $config);
// var_dump($hd);
$msg = $hd->msg;
$msg->setuser('chenwj')->setagentid(8)->sendText('由agent8对tag1的用户发的消息', ['测试','标签'], $msg->touser);//"{"errcode":0,"errmsg":"ok"}"



// $msg->setuser('chenwj')->setagentid(8)->sendNews([msg::newsEntity(['测试','决斗'],'服务器警告','决斗在线人数为0','http://op.pandoe.com/Operation#/userol','http://op.pandoe.com/favicon.ico')]);