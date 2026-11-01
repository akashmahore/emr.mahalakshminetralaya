<?php
/**
 * Mahalakshmi Netralaya - a reusable consultation note template.
 *
 * Copyright (c) Mahalakshmi Netralaya <https://www.ictinnovations.com>
 * Part of Mahalakshmi Netralaya <https://www.Mahalakshmi Netralaya.com>
 * Licensed under the GNU General Public License v3.0.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultTemplate extends Model
{
    protected $table = 'consult_template';

    protected $fillable = ['name', 'doctor_id', 'fields'];

    protected $casts = ['fields' => 'array'];
}
