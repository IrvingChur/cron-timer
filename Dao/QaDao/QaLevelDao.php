<?php

namespace Dao\QaDao;


use Illuminate\Database\Eloquent\Collection;
use Model\Qa\QaLevelModel;

class QaLevelDao
{
    protected $objQaLevelModelList;

    /**
     * @describe 获取对应条件下对应等级
     * @param int $answerNumber 回答数
     * @param int $specialAnswerNumber 星标回答数
     * @param int $fansNumber 拥趸数
     * @param int $illegalNumber 违规数
     * @return QaLevelModel
     */
    public function getCorrespondingLevel(int $answerNumber, int $specialAnswerNumber, int $fansNumber, int $illegalNumber)
    {
        $objQaLevelModelList = $this->getLevelList();
        $objQaLevelModelList = $objQaLevelModelList->sortByDesc(function ($product, $key) {
            return $product->id;
        });

        // 获取对应等级
        $objQaLevelModel   = null;
        foreach ($objQaLevelModelList as $item) {
            $isFoundIt     = true;
            if ($answerNumber < $item->answer_number) {
                $isFoundIt = false;
            } elseif ($specialAnswerNumber < $item->special_answer_number) {
                $isFoundIt = false;
            } elseif ($fansNumber < $item->fans_number) {
                $isFoundIt = false;
            } elseif ($item->illegal_number >= 0 && $illegalNumber > $item->illegal_number) {
                $isFoundIt = false;
            }

            if ($isFoundIt) {
                $objQaLevelModel = $item;
                break;
            }
        }

        return $objQaLevelModel;
    }

    /**
     * @describe 获取问答等级列表
     * @return Collection
     */
    protected function getLevelList()
    {
        if (!$this->objQaLevelModelList instanceof Collection) {
            $this->objQaLevelModelList = QaLevelModel::all();
        }

        return $this->objQaLevelModelList;
    }
}