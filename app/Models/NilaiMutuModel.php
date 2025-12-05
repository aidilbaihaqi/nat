<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiMutuModel extends Model
{
    protected $table = 'nilai_mutu';
    protected $primaryKey = 'nilai_huruf';
    protected $allowedFields = ['nilai_huruf', 'nilai_mutu'];
    protected $useTimestamps = false;

    /**
     * Get nilai mutu by nilai huruf
     * 
     * @param string $nilai_huruf
     * @return array|null
     */
    public function getMutu($nilai_huruf)
    {
        return $this->where('nilai_huruf', $nilai_huruf)->first();
    }
}
