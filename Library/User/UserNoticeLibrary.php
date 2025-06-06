<?php
namespace Library\User;

require_once(WEBPATH.'/Library/Umsdk/notification/android/AndroidBroadcast.php');
require_once(WEBPATH.'/Library/Umsdk/notification/android/AndroidFilecast.php');
require_once(WEBPATH.'/Library/Umsdk/notification/android/AndroidGroupcast.php');
require_once(WEBPATH.'/Library/Umsdk/notification/android/AndroidUnicast.php');
require_once(WEBPATH.'/Library/Umsdk/notification/android/AndroidCustomizedcast.php');
require_once(WEBPATH.'/Library/Umsdk/notification/ios/IOSBroadcast.php');
require_once(WEBPATH.'/Library/Umsdk/notification/ios/IOSFilecast.php');
require_once(WEBPATH.'/Library/Umsdk/notification/ios/IOSGroupcast.php');
require_once(WEBPATH.'/Library/Umsdk/notification/ios/IOSUnicast.php');
require_once(WEBPATH.'/Library/Umsdk/notification/ios/IOSCustomizedcast.php');
require_once(WEBPATH.'/Library/Xinlisdk/XinliApp.php');
use Library\Common\ConfigureLibrary;
use Library\Xinlisdk\XinliApp;

class UserNoticeLibrary
{
    const FM_ANDROID_ACCESS_ID = '2100018646';
    const FM_ANDROID_SECRET_KEY = '40f4acfb747ab26e4afab7ce3c06564c';
    const FM_IOS_ACCESS_ID = '2200043460';
    const FM_IOS_SECRET_KEY = 'cbfa701606326914f8dec631d9b08ad2';

    const YI_PUSH_QUESTION_COMMENT = 1;
    const YI_PUSH_QUESTION_COMMENT_REPLY = 2;
    const YI_PUSH_QUESTION = 3;
    const YI_PUSH_YUYUE = 4;
    const YI_PUSH_NOTICE_CENTER = 5;


    /**
     * 发友盟推送通知
     * @param $data
     * @return array
     */
    public function sendUMNotice($data) {
        $userId = isset($data['user_id']) ? $data['user_id'] : 0;
        $tokens = isset($data['tokens']) ? $data['tokens'] : null;
        $title = $data['title'];
        $content = $data['content'];
        $androidCustom = isset($data['android_custom']) ? $data['android_custom'] : [];
        $iosCustom = isset($data['ios_custom']) ? $data['ios_custom'] : [];
        $type = isset($data['push_type']) && !empty($data['push_type']) ? $data['push_type'] : 'unicast';

        try {
            $xinliApp = new XinliApp('yi');
            $env = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['env'];
            if ($env == 'pro') {
                $xinliApp->setProduction('production');
            }

            $androidRes = $iosRes = null;
            if(!empty($tokens)){
                if(!empty($androidCustom)){
                    $androidRes = $xinliApp->androidPush($tokens, $title, $content, $androidCustom, $type);
                }

                if(!empty($iosCustom)){
                    $iosRes = $xinliApp->iosPush($tokens, $title, $content, $iosCustom, $type);
                }

            }else{
                $androidRes = $xinliApp->androidPushSingleAccount(XinliApp::PLATFORM_UM, $userId, $title, $content, $androidCustom);
                $iosRes = $xinliApp->iosPushSingleAccount(XinliApp::PLATFORM_UM, $userId, $title, $content, $iosCustom);
            }


            return [
                'code' => 0,
                'data' => [
                    'ios' => $iosRes,
                    'android' => $androidRes
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => -1,
                'exception' => $e->getTraceAsString(),
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ];
        }
    }
}

?>