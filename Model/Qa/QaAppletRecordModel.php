<?php


namespace Model\Qa;


use Model\BaseModel;

class QaAppletRecordModel extends BaseModel
{
    protected $table   = 'qa_applet_record';
    protected $guarded = [];

    public $timestamps = false;
}