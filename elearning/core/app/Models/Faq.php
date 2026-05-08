<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use GlobalStatus;

    protected $guarded = ['id'];
}
