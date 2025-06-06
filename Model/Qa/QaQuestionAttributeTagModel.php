<?php


namespace Model\Qa;


use Model\BaseModel;

class QaQuestionAttributeTagModel extends BaseModel
{
    // 问题类型
    const QUALITY_TAG_TREE_HOLE = 6;    // 树洞问题

    protected $table   = 'qa_question_attribute_tag';
    protected $guarded = [];

    public $timestamps = false;
}