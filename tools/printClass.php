<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 16-6-22
 * Time: 下午10:28
 */
class PrintClass {
    public static function liansuo_post($url,$data){ // 模拟提交数据函数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检测
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:')); //解决数据包大不能提交
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_COOKIEFILE, $GLOBALS['cookie_file']); // 读取上面所储存的Cookie信息
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);
        }
        curl_close($curl); // 关键CURL会话
        return $tmpInfo; // 返回数据
    }

    public static function generateSign($params, $apiKey, $msign)
    {
        //所有请求参数按照字母先后顺序排
        ksort($params);
        //定义字符串开始所包括的字符串
        $stringToBeSigned = $apiKey;
        //把所有参数名和参数值串在一起
        foreach ($params as $k => $v)
        {
            $stringToBeSigned .= urldecode($k.$v);
        }
        unset($k, $v);
        //定义字符串结尾所包括的字符串
        $stringToBeSigned .= $msign;
        //使用MD5进行加密，再转化成大写
        return strtoupper(md5($stringToBeSigned));
    }
    //本函数为格式化菜单项目来打印
//可以通过操作$t1,$t2修改位置，其值为间距值
    public function generateFormat($p1,$p2,$p3,$format = 0){
        //条目        单价(元)          数量
        //----------------------------------
        //菜名         价格             数目

        $t1 = 13;
        $t2 = 18;
        if (1) {

            $message = $p1."\n";
            $message .= "$p2";
        } else {
            $message = $p1;

            $t1 = $t1 - $this->getLength($p1)+2;
        }
        //             //
        //高克斯干红
        $t2 = $t2 - $this->getLength($p2) - $this->getLength($p3);
        if ($format) {
            $t1 = $t1 - $this->getLength($p2);
        }
        for($j = 0;$j<$t1;$j++){
            $message .= ' ';
        }
        $message = $message.$p3;
        for($j = 0;$j<$t2;$j++){
            $message .= ' ';
        }
        $p4 = '';
        if ($format) {
            //$p4 = bcmul($p2,p3,2);
            $p4 = $p2*$p3;
        }
        $message = $message.$p4;
        return $message;
    }
    public function generateAddMoneyFormat($p1,$p2,$p3){
        //条目        单价(元)          数量
        //----------------------------------
        //菜名         价格             数目

        $t1 = 13;
        $t2 = 18;
        if (1) {

            $message = $p1;
        } else {
            $message = $p1;

            $t1 = $t1 - $this->getLength($p1)+2;
        }
        //             //
        //高克斯干红
        $t2 = $t2 - $this->getLength($p2) - $this->getLength($p3);
        for($j = 0;$j<$t1;$j++){
            $message .= ' ';
        }
        $message = $message.$p2;
        for($j = 0;$j<$t2;$j++){
            $message .= ' ';
        }
        $message = $message.$p3."\n";
        return $message;
    }
    public function getTitle ($msg = '') {
        $title = '
            欢迎您订购'.$msg.'


单价          数量           总价
--------------------------------

';
        return $title;
    }
    public function getAddMoneyTitle () {
        $title = '
            充值单


条目          单价(元)      数量
--------------------------------

';
        return $title;
    }
    public function getReturnTitle () {
        $title = '
            退货单


条目          单价(元)      数量
--------------------------------

';
        return $title;
    }
    //生成位长度
    public function getLength($p1){
        $t1 = 0;$encode = 'utf-8';
        for($i = 0; $i < mb_strlen($p1,$encode);$i++){
            //包含中文处理
            if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",mb_substr($p1,$i,1,$encode))){
                $t1 = $t1 + 2;
            } else {
                $t1 = $t1 + 1;
            }
        }
        return $t1;
    }

}