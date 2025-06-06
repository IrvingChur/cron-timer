<?php

namespace Model\Users;


use Model\BaseModel;

class UsersMessageModel extends BaseModel
{
    const OBJECT_NAME_RC_TXT = 'RC:TxtMsg';
    const OBJECT_NAME_RC_VOICE = 'RC:VcMsg';
    const OBJECT_NAME_RC_IMAGE = 'RC:ImgMsg';
    const OBJECT_NAME_READ_NTF = 'Rc:ReadNtf';

    protected $guarded = ['id'];
    protected $table = 'users_message';

    public $timestamps = false;
}