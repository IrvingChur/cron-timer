<?php
namespace Library\Xinlisdk;

use Library\Common\ConfigureLibrary;

class XinliApp
{

    const PLATFORM_XG = 'xg';
    const PLATFORM_UM = 'um';

    protected $production = false;

    protected $errorCode = 0;

    protected $errorMessage = '';

    protected $timestamp;

    protected $appName = '';

    protected $setting;

    protected $notifyUrl = 'http://notify.xinli001.com/umpush/message-callback';

    public function __construct($appName)
    {
        $this->setting = ConfigureLibrary::getConfigure('Configure\UmsdkConfigure');
        $this->timestamp = strval(time());
        $this->appName = $appName;
        $this->setProduction('develop');
    }

    public function iosPushSingleAccount($platform, $userId, $title, $content, $custom = [],$alias_type = 'yixinli')
    {
        $contentArr = ['title'=>$title,'body'=>$content];
        $customizedcast = new \IOSCustomizedcast();
        $customizedcast->setAppMasterSecret($this->getSetting('ios_secret_key'));
        $customizedcast->setPredefinedKeyValue('appkey', $this->getSetting('ios_app_key'));
        $customizedcast->setPredefinedKeyValue('timestamp', $this->timestamp);
        $customizedcast->setPredefinedKeyValue('alias', $this->getAccountAlias($userId));
        $customizedcast->setPredefinedKeyValue('alias_type', $alias_type);
        $customizedcast->setPredefinedKeyValue('alert', $contentArr);
        $customizedcast->setPredefinedKeyValue('receipt_url', $this->getNotifyUrl());
        if ($this->isProduction() == 'develop') {
            $customizedcast->setPredefinedKeyValue("production_mode", "false");
        }
        if (is_array($custom) && count($custom) > 0) {
            foreach ($custom as $k => $v) {
                if ($k != 'custom_params') {
                    $customizedcast->setPredefinedKeyValue($k, $v);
                }
            }
        }
        if (is_array($custom['custom_params']) && count($custom['custom_params']) > 0) {
            foreach ($custom['custom_params'] as $k => $v) {
                $customizedcast->setCustomizedField($k, $v);
            }
        }
        $response = $customizedcast->send();
        return $this->checkResponseStatus('um', $response);

    }

    public function androidPushSingleAccount($platform, $userId, $title, $content, $custom = [],$alias_type = 'yixinli')
    {
        $this->resetError();
        $customizedcast = new \AndroidCustomizedcast();
        $customizedcast->setAppMasterSecret($this->getSetting( 'android_secret_key'));
        $customizedcast->setPredefinedKeyValue('appkey', $this->getSetting('android_app_key'));
        $customizedcast->setPredefinedKeyValue('timestamp', $this->timestamp);
        $customizedcast->setPredefinedKeyValue('alias', $this->getAccountAlias($userId));
        $customizedcast->setPredefinedKeyValue('alias_type', $alias_type);
        $customizedcast->setPredefinedKeyValue('ticker', $title);
        $customizedcast->setPredefinedKeyValue('title', $title);
        $customizedcast->setPredefinedKeyValue('text', $content);
        $customizedcast->setPredefinedKeyValue('receipt_url', $this->getNotifyUrl());
        if ($this->isProduction() == 'develop') {
            $customizedcast->setPredefinedKeyValue("production_mode", "false");
        }
        if (is_array($custom) && count($custom) > 0) {
            foreach ($custom as $k => $v) {
                if ($k != 'custom_params') {
                    $customizedcast->setPredefinedKeyValue($k, $v);
                }
            }
        }

        if (is_array($custom['custom_params']) && count($custom['custom_params']) > 0) {
            foreach ($custom['custom_params'] as $k => $v) {
                $customizedcast->setExtraField($k, $v);
            }
        }
        $response = $customizedcast->send();
        return $this->checkResponseStatus('um', $response);
    }

    public function iosPush($token, $title, $content, $custom = [], $type = 'unicast', $alias_type = 'yixinli')
    {
        $this->resetError();
        $contentArr = ['title'=>$title,'body'=>$content];
        $customizedcast = new \IOSCustomizedcast($type);
        $customizedcast->setAppMasterSecret($this->getSetting('ios_secret_key'));
        $customizedcast->setPredefinedKeyValue('appkey', $this->getSetting('ios_app_key'));
        $customizedcast->setPredefinedKeyValue('timestamp', $this->timestamp);
        $customizedcast->setPredefinedKeyValue('alias_type', $alias_type);
        if($type == 'customizedcast'){
            $customizedcast->setPredefinedKeyValue('alias', $this->getAccountAlias($token));
        }else{
            $customizedcast->setPredefinedKeyValue('device_tokens', $token);
        }
        $customizedcast->setPredefinedKeyValue('alert', $contentArr);
        $customizedcast->setPredefinedKeyValue('receipt_url', $this->getNotifyUrl());
        if ($this->isProduction() == 'develop') {
            $customizedcast->setPredefinedKeyValue("production_mode", "false");
        }
        if (is_array($custom) && count($custom) > 0) {
            foreach ($custom as $k => $v) {
                if ($k != 'custom_params') {
                    $customizedcast->setPredefinedKeyValue($k, $v);
                }
            }
        }
        if (is_array($custom['custom_params']) && count($custom['custom_params']) > 0) {
            foreach ($custom['custom_params'] as $k => $v) {
                $customizedcast->setCustomizedField($k, $v);
            }
        }
        $response = $customizedcast->send();
        return $this->checkResponseStatus('um', $response);
        
    }

    public function androidPush($token, $title, $content, $custom = [], $type = 'unicast', $alias_type = 'yixinli')
    {
        $this->resetError();
       
        $customizedcast = new \AndroidCustomizedcast($type);
        $customizedcast->setAppMasterSecret($this->getSetting('android_secret_key'));
        $customizedcast->setPredefinedKeyValue('appkey', $this->getSetting('android_app_key'));
        $customizedcast->setPredefinedKeyValue('timestamp', $this->timestamp);
        $customizedcast->setPredefinedKeyValue('alias_type', $alias_type);
        if($type == 'customizedcast'){
            $customizedcast->setPredefinedKeyValue('alias', $this->getAccountAlias($token));
            
        }else{
            $customizedcast->setPredefinedKeyValue('device_tokens', $token);
        }
        $customizedcast->setPredefinedKeyValue('ticker', $title);
        $customizedcast->setPredefinedKeyValue('title', $title);
        $customizedcast->setPredefinedKeyValue('text', $content);
        $customizedcast->setPredefinedKeyValue('receipt_url', $this->getNotifyUrl());
        if ($this->isProduction() == 'develop') {
            $customizedcast->setPredefinedKeyValue("production_mode", "false");
        }
        if (is_array($custom) && count($custom) > 0) {
            foreach ($custom as $k => $v) {
                if ($k != 'custom_params') {
                    $customizedcast->setPredefinedKeyValue($k, $v);
                }
            }
        }

        if (is_array($custom['custom_params']) && count($custom['custom_params']) > 0) {
            foreach ($custom['custom_params'] as $k => $v) {
                $customizedcast->setExtraField($k, $v);
            }
        }
        $response = $customizedcast->send();
        return $this->checkResponseStatus('um', $response);
        
    }

    public function checkResponseStatus($platform, $response)
    {
        return $response;
    }

    public function getAccountAlias($userId)
    {
        if ($this->appName == 'yi' || $this->appName == 'fm') {
            if (is_array($userId) && count($userId) > 0) {
                $alias = [];
                foreach ($userId as $v) {
                    $alias[] = $this->getAccountAlias($v);
                }
            }else{
                $alias = sprintf('user-%d', $userId);
            }

        } else {
            $alias = sprintf('userid-%d', $userId);
        }

        return is_array($alias) ? implode(',', $alias) : $alias;
    }

    public function getSetting($key)
    {
        $environment = $this->isProduction() ? 'production' : 'develop';
        $key = $this->appName.'_'.$key;
        return array_key_exists($key, $this->setting[$environment]) ?
            $this->setting[$environment][$key] : '';
    }

    public function isProduction()
    {
        return $this->production;
    }

    public function setProduction($production)
    {
        if (is_bool($production) || is_integer($production) || is_string($production)) {
            $this->production = $production;
        };
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function resetError()
    {
        $this->errorCode = 0;
        $this->errorMessage = '';
    }

    public function getNotifyUrl(){
        $environment = $this->isProduction() ? 'production' : 'develop';
        return $this->setting[$environment]['notify_url'];
    }


}