<?php

namespace PortalTest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class File extends Model
{
    protected $fillable = [
        'product_code', 'quantity', 'bay', 'bay_stage', 'shelf'
    ];
    public $timestamps = false;

    public static function truncate()
    {
        DB::table('files')->truncate();
    }
}
