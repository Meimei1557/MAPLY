<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiCandidateSource extends Model
{
    protected $fillable = ['candidate_id','source_name','source_url','evidence','collected_at'];
    protected function casts(): array { return ['evidence'=>'array','collected_at'=>'datetime']; }
}
