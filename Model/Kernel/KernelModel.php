<?php

namespace Model\Kernel;


use Library\Common\ConfigureLibrary;
use Model\BaseModel;

class KernelModel extends BaseModel
{
    const STATUS_WAIT = 0;
    const STATUS_RUNNING = 1;
    const STATUS_STOP = 2;

    protected $table = '';
    protected $guarded = [];

    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['cronTable'];
    }
}