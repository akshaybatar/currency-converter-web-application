<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyHistory extends Model
{

    protected $fillable = ['user_id','from_currency', 'to_currency', 'amount', 'result'];
}
