<?php
namespace allinpay\base;

class AppUtil{
    public static function Sign(array $array, string $private_key)
    {
        ksort($array);
        $bufSignSrc = AppUtil::ToUrlParams($array);
        $private_key = chunk_split($private_key , 64, "\n");
        $key = "-----BEGIN RSA PRIVATE KEY-----\n".wordwrap($private_key)."-----END RSA PRIVATE KEY-----";
        //   echo $key;
        if(openssl_sign($bufSignSrc, $signature, $key )){
            return base64_encode($signature);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
        }else{
            return '';
        }

    }
	
	/**
	 * 拼接签名字符串
	 */
	public static function ToUrlParams(array $array)
	{
		$buff = "";
		foreach ($array as $k => $v)
		{
			if($v != "" && !is_array($v)){
				$buff .= $k . "=" . $v . "&";
			}
		}
		
		$buff = trim($buff, "&");
		return $buff;
	}
	
	/**
	 * 校验签名
	 * @param array 参数
	 * @param bool appkey
	 */
    public static function ValidSign(array $array, string $public_key)
    {
        $sign =$array['sign'];
        unset($array['sign']);
        ksort($array);
        $bufSignSrc = AppUtil::ToUrlParams($array);
        $public_key='MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCm9OV6zH5DYH/ZnAVYHscEELdCNfNTHGuBv1nYYEY9FrOzE0/4kLl9f7Y9dkWHlc2ocDwbrFSm0Vqz0q2rJPxXUYBCQl5yW3jzuKSXif7q1yOwkFVtJXvuhf5WRy+1X5FOFoMvS7538No0RpnLzmNi3ktmiqmhpcY/1pmt20FHQQIDAQAB';
        $public_key = chunk_split($public_key , 64, "\n");
        $key = "-----BEGIN PUBLIC KEY-----\n$public_key-----END PUBLIC KEY-----\n";
        $result= openssl_verify($bufSignSrc,base64_decode($sign), $key );
        return $result;
    }
	
	/**
	 * 
	 * 产生随机字符串，不长于32位
	 * @param int $length
	 * @return string 产生的随机字符串
	 */
	public static function GetNonceStr($length = 32) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		} 
		return $str;
	}
}
