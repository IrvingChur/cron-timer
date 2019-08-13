<?php

namespace Application\QaInviteList;


use Application\ApplicationInterface;
use Carbon\Carbon;
use CustomTrait\Common\SingleInstanceTrait;
use Dao\AuthDao\AuthUserDao;
use Dao\CommonDao\CommonCustomeTagShotDao;
use Dao\CommonDao\CommonPraiseDao;
use Dao\QaDao\QaAnswerDao;
use Illuminate\Database\Eloquent\Collection;
use Library\Cache\Cache;
use Library\Common\ConfigureLibrary;
use Library\Common\UrlLibrary;
use Model\Auth\AuthUserModel;
use Model\Auth\AuthUserQaModel;
use Model\Common\CommonCustomeTagShotModel;
use Model\Common\CommonPraiseModel;
use Model\Qa\QaAnswerModel;

class QaInviteList implements ApplicationInterface
{
    use SingleInstanceTrait;

    const QA_INVITE_LIST = '_qa_invite_list';

    public function execute(string $param)
    {
        // 获取回答赞数大于50的用户
        $objPraiseInviteList = self::getInstance(CommonPraiseDao::class)->getInviteList(CommonPraiseModel::OBJECT_NAME_ANSWER, 50);
        // 获取最近30天有回答的用户
        $strStartDateTime    = Carbon::now()->subDays(30)->toDateTimeString();
        $strEndDateTime      = Carbon::now()->toDateTimeString();
        $objIsHaveAnswerUser = self::getInstance(QaAnswerDao::class)->getUniquenessAnswerUserList(['id', 'user_id', 'jin'], false, $strStartDateTime, $strEndDateTime);
        // 过滤被禁止的用户
        $objIsHaveAnswerUser = $objIsHaveAnswerUser->filter(function ($item, $key) {
           return $item->jin == QaAnswerModel::ANSWER_STATUS_NORMAL;
        });

        // 其中一个为空则不运行
        if ($objPraiseInviteList->isEmpty() || $objIsHaveAnswerUser->isEmpty()) {
            return;
        }

        // 获取并集
        $arrUserList = $this->getUnion($objPraiseInviteList, $objIsHaveAnswerUser);
        if (empty($arrUserList)) {
            return;
        }

        $objUserList = self::getInstance(AuthUserDao::class)->getUsersByIds(['*'], $arrUserList);
        $objUserList->load('teacher', 'listen', 'write');
        $objUserList->load([
            'qaIdentity' => function ($query) {
                $query->whereIn('answerer_tag_id', [13, 14]);
            }
        ]);
        $objUserList = $objUserList->map(function ($item, $key) {
            return $this->filterUserRole($item);
        });
        $arrUserList = $objUserList->toArray();

        // 拼装likeNumber
        foreach ($arrUserList as $key => $item) {
            $intLikeNumber = 0;
            $objPraiseInviteList->each(function ($eachItem, $eachKey) use (&$intLikeNumber, $item) {
                if ($eachItem->object_user_id == $item['user_id']) {
                    $intLikeNumber = $eachItem->praiseNumber;
                    return false;
                }
            });
            $arrUserList[$key]['zan_num'] = $intLikeNumber;
            unset($intLikeNumber);
        }

        $strEnv = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['env'];
        Cache::getInstance()->set($strEnv . self::QA_INVITE_LIST, json_encode($arrUserList));
    }

    /**
     * @describe 过滤用户并返回用户身份信息
     * @param AuthUserModel $user 用户信息
     * @param int|null $filterGeneral 过滤标记
     * @return array
     */
    protected function filterUserRole(AuthUserModel $user)
    {
        $objTeacher = $user->teacher;
        $objListen  = $user->listen;
        $objWrite   = $user->write;
        $objSpecial = $user->qaIdentity;

        $arrFormat  = [
            'user_id'    => $user->id,
            'avatar'     => $user->avatar . '!80',
            'nickname'   => $user->nickname,
            'zan_num'    => 0,
            'has_invite' => 0,
            'answer_num' => $user->statistics ? $user->statistics->answernum : 0,
        ];

        $arrRole           = [];
        if (!empty($objTeacher)) {
            $objCustomeTag = self::getInstance(CommonCustomeTagShotDao::class)->getCustomeTag(['*'], CommonCustomeTagShotModel::ZIXUN);
            $arrTagList    = $objCustomeTag->child()
                ->where('status', CommonCustomeTagShotModel::STATUS_NORMAL)
                ->pluck('name', 'custome_tag_id')
                ->toArray();

            $arrTags           = [];
            if ($objTeacher->therapy) {
                $arrTagIds     = explode(',', $objTeacher->therapy->category);
                foreach ($arrTagIds as $item) {
                    if (isset($arrTagList[$item])) {
                        $arrTags[] = isset($arrTagList[$item]);
                    }
                }
            }
            $arrTags  = array_unique($arrTags);
            $strHonor = $objTeacher->qualification->honor;
            $arrRole  = array_merge($arrFormat, [
                'desc'     => empty($arrTags) ? $user->base->honor : '擅长：'.implode('、', $arrTags),
                'role_tag' => $strHonor ?: '',
                'home_url' => UrlLibrary::createUrl('/msite/index.html#/qingsu-teacher-page', ['id' => $user->id], UrlLibrary::URL_TYPE_STATIC),
            ]);
        } elseif ($objListen) {
            $arrRole  = array_merge($arrFormat, [
                'desc'     => $objListen->qianming,
                'role_tag' => '倾听师',
                'home_url' => UrlLibrary::createUrl('/msite/index.html#/qingsu-teacher-page', ['id' => $user->id, 'source' => 'qingsuIndex', '_version' => 'null', 'cid' => ''], UrlLibrary::URL_TYPE_STATIC),
            ]);
        } elseif ($objWrite) {
            $arrRole  = array_merge($arrFormat, [
                'desc'     => $user->base ? $user->base->honor : '',
                'role_tag' => '专栏作家',
                'home_url' => UrlLibrary::createUrl('/msite/index.html#/userIndex', ['id' => $user->id], UrlLibrary::URL_TYPE_STATIC),
            ]);
        } elseif ($objSpecial) {
            $arrRole  = array_merge($arrFormat, [
                'desc'     => $user->base ? $user->base->honor : '',
                'role_tag' => [AuthUserQaModel::TAG_QUALITY_ROLE => '优质回答者', AuthUserQaModel::TAG_ESSENCE_ROLE => '精华回答者'][$objSpecial->answerer_tag_id],
                'home_url' => UrlLibrary::createUrl('/msite/index.html#/userIndex', ['id' => $user->id], UrlLibrary::URL_TYPE_STATIC),
            ]);
        }

        return $arrRole;
    }

    /**
     * @describe 获取两列数据的用户ID并集
     * @param Collection $praiseInviteList 点赞数用户列表
     * @param Collection $isHaveAnswerUser 回答用户列表
     * @return array
     */
    protected function getUnion(Collection $praiseInviteList, Collection $isHaveAnswerUser)
    {
        $objPraiseUserList = $praiseInviteList->pluck('object_user_id');
        $objHaveAnswerList = $isHaveAnswerUser->pluck('user_id');

        // 获取并集
        $objUserList       = $objHaveAnswerList->filter(function ($item, $key) use ($objPraiseUserList) {
            return ($objPraiseUserList->search($item) !== false);
        });

        return $objUserList->toArray();
    }
}