<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiSearchRun extends Model
{
    protected $fillable = ['admin_id','region','keyword','status','found_count','new_count','duplicate_count','valid_count','rejected_count','message','started_at','finished_at','sources','options'];
    protected function casts(): array { return ['started_at'=>'datetime','finished_at'=>'datetime','sources'=>'array','options'=>'array']; }
    public function candidates(): HasMany { return $this->hasMany(AiKosCandidate::class, 'search_run_id'); }
}
