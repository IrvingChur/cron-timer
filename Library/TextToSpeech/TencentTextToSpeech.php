<?php


namespace Library\TextToSpeech;


use Carbon\Carbon;
use GuzzleHttp\Client;

class TencentTextToSpeech
{
    const REQUEST_URL = "https://tts.cloud.tencent.com/stream"; // 请求地址
    const CREATE_SIGN_DOMAIN = "tts.cloud.tencent.com";         // 生成签名域名
    const CREATE_SIGN_PATH   = "/stream";                       // 请求签名路径

    /**
     * @describe 获取语音
     * @param Configure $configure 配置文件
     * @return string [pcm音频文件流]
     */
    public function getVoice(Configure $configure)
    {
        $arrRequestData = [
            "Action"          => $configure->getStrAction(),
            "AppId"           => $configure->getIntAppId(),
            "Codec"           => $configure->getStrCodeC(),
            "Expired"         => $configure->getIntExpired() + time(),    // 离线识别
            "ModelType"       => $configure->getIntModelType(),
            "PrimaryLanguage" => $configure->getIntLanguage(),
            "ProjectId"       => $configure->getIntProjectId(),
            "SampleRate"      => $configure->getIntSampleRate(),
            "SecretId"        => $configure->getStrSecretId(),
            "SessionId"       => $configure->getStrSessionId(),
            "Speed"           => $configure->getIntSpeed(),
            "Text"            => $configure->getStrText(),
            "Timestamp"       => Carbon::now()->timestamp,
            "VoiceType"       => $configure->getIntVoiceType(),
            "Volume"          => $configure->getIntVolume(),
        ];

        $strAuth   = $this->createSign($arrRequestData, "POST", self::CREATE_SIGN_DOMAIN, self::CREATE_SIGN_PATH, $configure->getStrSecretKey());
        $arrHeader = [
            "Authorization" => $strAuth,
            "Content-Type"  => "application/json",
        ];

        // 发送请求
        $objHttpClient   = new Client();
        $objHttpResponse = $objHttpClient->post(self::REQUEST_URL, [
            "headers"    => $arrHeader,
            "body"       => json_encode($arrRequestData),
        ]);
        $strHttpResponse = $objHttpResponse->getBody()->getContents();
        $objHttpResponse->getBody()->close();

        return $strHttpResponse;
    }

    /**
     * @describe 创建签名
     * @param array $requestData 请求数据
     * @param string $method 请求方法
     * @param string $domain 请求域名
     * @param string $path 请求路径
     * @param string $secretKey 用户秘钥
     * @return string
     */
    private function createSign(array $requestData, string $method, string $domain, string $path, string $secretKey)
    {
        $strSign  = "";
        $strSign .= $method;
        $strSign .= $domain;
        $strSign .= $path;
        $strSign .= "?";
        ksort($requestData, SORT_STRING);

        foreach ($requestData as $key => $item) {
            $strSign .= $key . "=" . $item . "&";
        }

        $strSign = substr($strSign, 0, -1);
        $strSign = base64_encode(hash_hmac("SHA1", $strSign, $secretKey, true));

        return $strSign;
    }

    /**
     * @describe 获取guid
     * @return string
     */
    public function getGuid()
    {
        mt_srand((double) microtime() * 10000);
        $strCharId = strtoupper(md5(uniqid(rand(), true)));
        $strHyphen = chr(45);
        $strUUid   = substr($strCharId, 0, 8)
            . $strHyphen
            . substr($strCharId, 8, 4)  . $strHyphen
            . substr($strCharId, 12, 4) . $strHyphen
            . substr($strCharId, 16, 4) . $strHyphen
            . substr($strCharId, 20, 12);

        return $strUUid;
    }
}