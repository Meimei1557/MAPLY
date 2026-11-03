<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiKosCandidate extends Model
{
    protected $fillable = ['search_run_id','provider_id','nama_kos','alamat','kecamatan','kota','harga','telepon','website','foto_url','latitude','longitude','google_maps_url','fasilitas','tipe','trust_score','verification_status','verification_reasons','raw_data','source_name','source_url','found_at','published_kos_id','reviewed_by','reviewed_at','ai_extracted','ai_verdict','ai_confidence','ai_analysis','ai_processed_at'];
    protected function casts(): array { return ['fasilitas'=>'array','verification_reasons'=>'array','raw_data'=>'array','found_at'=>'datetime','reviewed_at'=>'datetime','latitude'=>'float','longitude'=>'float','ai_extracted'=>'array','ai_analysis'=>'array','ai_processed_at'=>'datetime','ai_confidence'=>'float']; }
    public function sources(): HasMany { return $this->hasMany(AiCandidateSource::class, 'candidate_id'); }
}
