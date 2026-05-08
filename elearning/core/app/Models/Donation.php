<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class);
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function(){
            $html = '';
            if($this->payment_status == 0){
                $html = '<span class="badge badge--warning">'.trans('Pending').'</span>';
            }
            elseif($this->payment_status == 1){
                $html = '<span class="badge badge--success">'.trans('Success').'</span>';
            }
            elseif($this->payment_status == 2){
                $html = '<span class="badge badge--danger">'.trans('Rejected').'</span>';
            }
            return $html;
        });
    }
}
