<?php

namespace App\Services\ProjectSetting\Exceptions;

use Exception;

class ProjectSettingNotFoundException extends Exception
{
    protected $code = 404;
}
