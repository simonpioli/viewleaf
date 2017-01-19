<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlackProfile extends Model
{
    public function findByNickOrId($searchTerm = '')
    {
        if (!empty($searchTerm)) {
            if (starts_with($searchTerm, 'U')) {
                return $this->find($searchTerm);
            } else {
                return $this->where('nick', $searchTerm)->first();
            }
        }

        return false;
    }
}
