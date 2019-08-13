<?php

namespace Application\IntelligentOperation;


use Application\ApplicationInterface;
use Carbon\Carbon;
use CustomTrait\Common\SingleInstanceTrait;
use Library\Cache\Cache;
use Library\Common\ConfigureLibrary;
use Model\Auth\AuthUserModel;
use Model\Qa\QaQuestionModel;

class DetectionQuestion implements ApplicationInterface
{
    use SingleInstanceTrait;
    // 存储最后执行时间CacheIndex
    const DETECTION_QUESTION_LAST_TIME_RECORD = '_detection_question_last_time_record';
    // 检测的关键字
    const DETECT_KEYWORD = ['自杀', '不想活了', '很想死', '很想去死', '好想死', '不想活下去'];
    // 回复语句
    const AUTO_ANSWER_TEXT = [
        '<p>抱抱你，希望在这里，你会感觉生活没那么糟。</p><p>如果有时间，可以和我们聊聊事情的经过吗？</p>',
        '<p>一定很难过吧。还好你来提问了。</p><p>我在想，如果5年后，再回忆今天的这段经历，</p><p>会不会感激自己能相信自己，感激自己当初努力的照顾好自己了呢？</p>',
        '<p>有时会想，“如果能帮你分担一部分痛苦就好了。”</p><p>每个人的人生，都不容易。</p><p>让我们彼此分担吧。</p><p>别丢下我们。</p>',
        '<p>为你准备的自救手册，也许看过后，你会找到解救自己的钥匙。</p><p>《壹心理·心理急救手册》</p><p><a href="http://m.xinli001.com/article/theme/detail?id=176">http://m.xinli001.com/article/theme/detail?id=176</a></p>',
        '<p>我找了下，有一些援助电话，可以都打打看！</p><p>不要放弃呀，我们都在。</p><p>《危机干预热线》</p><p><a href="https://m.xinli001.com/info/100441159">https://m.xinli001.com/info/100441159</a></p><p>《【全国心理援助与咨询服务机构】联系信息大全》</p><p><a href="https://www.xinli001.com/info/100372821">https://www.xinli001.com/info/100372821</a></p>',
        '<p>我来了。</p><p>不要怕，我们都在。</p>',
        '<p>已经把你的问题推荐给热心的平台的社工和答主们了。</p><p>每个人都值得被温柔相待。</p><p>我们非常期待，你能把自己的经历告诉大家。</p><p>也许我们有办法帮助你呢？</p>',
        '<p>把感受说出来的你很勇敢。</p><p>如果自己承担不了，一定要去求助呀。</p><p>信任的人，能理解你的朋友、亲人、老师，或者危机干预热线也可以试试。</p><p>《危机干预热线》</p><p><a href="https://m.xinli001.com/info/100441159">https://m.xinli001.com/info/100441159</a></p>',
        '<p>你有看过《流浪猫鲍勃》吗？</p><p>其实，这个世界上还有很多人需要你。</p><p>一只猫，一条街，一个同样寂寞的朋友，那些跟你一起分享孤独夜晚的人。</p>',
        '<p>我想把我喜欢的东西告诉你，</p><p>然后你也告诉我们。</p><p>这样，我们就可以一起喜欢这个世界了。</p><p>不要离开。好吗？</p>'
    ];
    // 自动回复使用ID
    const AUTO_ANSWER_USES_ID = 1005084553;

    protected $objRedisInstance;

    public function execute(string $param)
    {
        $this->objRedisInstance = Cache::getInstance();
        $strEnv = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['env'];
        $strLastTime = $this->objRedisInstance->get($strEnv . self::DETECTION_QUESTION_LAST_TIME_RECORD);
        if (empty($strLastTime)) {
            $this->objRedisInstance->set($strEnv . self::DETECTION_QUESTION_LAST_TIME_RECORD, Carbon::now()->toDateTimeString());
            return;
        }

        $arrRequestList = self::getInstance('Dao\QaDao\QaQuestionDao')->getQuestionByTime(['id', 'title', 'user_id'], $strLastTime);

        foreach ($arrRequestList as $item) {
            if ($this->detectKeyword($item->title)) {
                $this->objRedisInstance->sadd($strEnv . SendingMessage::NEED_SENDING_MESSAGE_LIST, base64_encode(json_encode(['id' => $item->id, 'user_id' => $item->user_id, 'flag' => SendingMessage::SIX_HOUR_FLAG, 'created' => Carbon::now()->unix()])));
                $this->objRedisInstance->sadd($strEnv . SendingMessage::NEED_SENDING_MESSAGE_LIST, base64_encode(json_encode(['id' => $item->id, 'user_id' => $item->user_id, 'flag' => SendingMessage::TWENTY_FOUR_FLAG, 'created' => Carbon::now()->unix()])));
                $this->autoAnswer($item);
            }
        }

        $this->objRedisInstance->set($strEnv . self::DETECTION_QUESTION_LAST_TIME_RECORD, Carbon::now()->toDateTimeString());
    }

    /**
     * @describe 检测关键字
     * @param string $title 问题标题
     * @return boolean
     */
    protected function detectKeyword(string $title)
    {
        foreach (self::DETECT_KEYWORD as $keyword) {
            if (mb_strpos($title, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @describe 自动回复
     * @param int $questionId 问题ID
     * @return void
     */
    protected function autoAnswer(QaQuestionModel $question)
    {
        $strAnswerText = self::AUTO_ANSWER_TEXT[array_rand(self::AUTO_ANSWER_TEXT)];
        $objAnswerUser = AuthUserModel::find(self::AUTO_ANSWER_USES_ID);

        self::getInstance('Dao\QaDao\QaAnswerDao')->createAnswer(
            $objAnswerUser->id,
            $objAnswerUser->nickname,
            $question->id,
            $strAnswerText
        );

        self::getInstance('Dao\CommonDao\CommonNoticeDao')->createNotice(
            $objAnswerUser->id,
            $question->user_id,
            'answer',
            'question',
            $question->id,
            '问答',
            $objAnswerUser->nickname . '回答了你的问题'
        );
    }
}