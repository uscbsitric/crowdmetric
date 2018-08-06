<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\CustomTraits\CustomTrait;

class Controller extends BaseController
{
    use AuthorizesRequests, CustomTrait, DispatchesJobs, ValidatesRequests
    {
        CustomTrait::authorize insteadof AuthorizesRequests;
    }
}
