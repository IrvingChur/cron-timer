<?php

namespace Model\Auth;


use Model\BaseModel;

class AuthTeacherTherapyModel extends BaseModel
{
    protected $table = 'auth_teacher_therapy';
    protected $guarded = [];

    public $timestamps = false;
}