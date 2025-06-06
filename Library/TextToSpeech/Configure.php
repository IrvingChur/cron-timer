<?php


namespace Library\TextToSpeech;


class Configure
{
    // 基础信息
    private $strSecretId  = "";
    private $strSecretKey = "";
    private $intAppId     = 0;                    // 必须是int

    // 音频信息
    private $strAction    = "TextToStreamAudio";  // 文字转语音
    private $strCodeC     = "pcm";                // 获取格式
    private $intModelType = 1;                    // 模型类型
    private $strText      = "";                   // 需要转换的文本

    // 语音设置
    private $intSpeed      = 0;                   // 语速范围[-2, 2]
    private $intVolume     = 5;                   // 音量范围[0, 10]
    private $intSampleRate = 16000;               // 音频采样率[16k, 8k]
    private $intProjectId  = 0;                   // 声道选择[1, 2]
    private $intVoiceType  = 4;                   // 音色[0:亲和女声, 1:亲和男声, 2:成熟男声, 3:活力男声, 4:温暖女声, 5:情感女声, 6:情感男声]
    private $intLanguage   = 1;                   // 语言[1:中文, 2:英文]

    // 验证设置
    private $intExpired    = 3600;                // 请求鉴权的有效时间[second]
    private $strSessionId  = "";                  // 唯一会话ID

    /**
     * @return string
     */
    public function getStrSecretId(): string
    {
        return $this->strSecretId;
    }

    /**
     * @param string $strSecretId
     */
    public function setStrSecretId(string $strSecretId): void
    {
        $this->strSecretId = $strSecretId;
    }

    /**
     * @return string
     */
    public function getStrSecretKey(): string
    {
        return $this->strSecretKey;
    }

    /**
     * @param string $strSecretKey
     */
    public function setStrSecretKey(string $strSecretKey): void
    {
        $this->strSecretKey = $strSecretKey;
    }

    /**
     * @return int
     */
    public function getIntAppId(): int
    {
        return $this->intAppId;
    }

    /**
     * @param int $intAppId
     */
    public function setIntAppId(int $intAppId): void
    {
        $this->intAppId = $intAppId;
    }

    /**
     * @return string
     */
    public function getStrAction(): string
    {
        return $this->strAction;
    }

    /**
     * @param string $strAction
     */
    public function setStrAction(string $strAction): void
    {
        $this->strAction = $strAction;
    }

    /**
     * @return string
     */
    public function getStrCodeC(): string
    {
        return $this->strCodeC;
    }

    /**
     * @param string $strCodeC
     */
    public function setStrCodeC(string $strCodeC): void
    {
        $this->strCodeC = $strCodeC;
    }

    /**
     * @return string
     */
    public function getStrText(): string
    {
        return $this->strText;
    }

    /**
     * @param string $strText
     */
    public function setStrText(string $strText): void
    {
        $this->strText = $strText;
    }

    /**
     * @return int
     */
    public function getIntSpeed(): int
    {
        return $this->intSpeed;
    }

    /**
     * @param int $intSpeed
     */
    public function setIntSpeed(int $intSpeed): void
    {
        $this->intSpeed = $intSpeed;
    }

    /**
     * @return int
     */
    public function getIntVolume(): int
    {
        return $this->intVolume;
    }

    /**
     * @param int $intVolume
     */
    public function setIntVolume(int $intVolume): void
    {
        $this->intVolume = $intVolume;
    }

    /**
     * @return int
     */
    public function getIntSampleRate(): int
    {
        return $this->intSampleRate;
    }

    /**
     * @param int $intSampleRate
     */
    public function setIntSampleRate(int $intSampleRate): void
    {
        $this->intSampleRate = $intSampleRate;
    }

    /**
     * @return int
     */
    public function getIntProjectId(): int
    {
        return $this->intProjectId;
    }

    /**
     * @param int $intProjectId
     */
    public function setIntProjectId(int $intProjectId): void
    {
        $this->intProjectId = $intProjectId;
    }

    /**
     * @return int
     */
    public function getIntVoiceType(): int
    {
        return $this->intVoiceType;
    }

    /**
     * @param int $intVoiceType
     */
    public function setIntVoiceType(int $intVoiceType): void
    {
        $this->intVoiceType = $intVoiceType;
    }

    /**
     * @return int
     */
    public function getIntLanguage(): int
    {
        return $this->intLanguage;
    }

    /**
     * @param int $intLanguage
     */
    public function setIntLanguage(int $intLanguage): void
    {
        $this->intLanguage = $intLanguage;
    }

    /**
     * @return int
     */
    public function getIntExpired(): int
    {
        return $this->intExpired;
    }

    /**
     * @param int $intExpired
     */
    public function setIntExpired(int $intExpired): void
    {
        $this->intExpired = $intExpired;
    }

    /**
     * @return string
     */
    public function getStrSessionId(): string
    {
        return $this->strSessionId;
    }

    /**
     * @param string $strSessionId
     */
    public function setStrSessionId(string $strSessionId): void
    {
        $this->strSessionId = $strSessionId;
    }

    /**
     * @return int
     */
    public function getIntModelType(): int
    {
        return $this->intModelType;
    }

    /**
     * @param int $intModelType
     */
    public function setIntModelType(int $intModelType): void
    {
        $this->intModelType = $intModelType;
    }
}