<?php

namespace Model\Auth;


use Model\BaseModel;

class AuthTeacherQualificationModel extends BaseModel
{
    protected $table = 'auth_teacher_qualification';
    protected $guarded = [];

    public $timestamps = false;
}