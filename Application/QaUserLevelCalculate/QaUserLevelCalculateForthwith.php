<?php

namespace Application\QaUserLevelCalculate;


use Application\ApplicationInterface;
use CustomTrait\Common\SingleInstanceTrait;
use Dao\QaDao\QaAnswerDao;
use Library\Cache\Cache;
use Library\Common\ConfigureLibrary;

class QaUserLevelCalculateForthwith implements ApplicationInterface
{
    use SingleInstanceTrait;

    const FORTHWITH_KEY = '_user_level_calculate_forthwith_list';

    public function execute(string $param)
    {
        $strEnv = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['env'];
        $strKey = $strEnv . self::FORTHWITH_KEY;

        $strJsonItem        = '';
        while ($strJsonItem = Cache::getInstance()->RPOP($strKey)) {
            $arrItem        = json_decode($strJsonItem, true);
            $objUser        = self::getInstance(QaAnswerDao::class)->getUserInfo($arrItem['user_id'], ['user_id', 'nickname']);
            if (empty($objUser)) {
                return;
            }

            self::getInstance(QaUserLevelCalculate::class)->statistics($objUser);
        }
    }
}