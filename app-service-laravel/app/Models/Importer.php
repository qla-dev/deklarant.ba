<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Importer extends Supplier
{
    use HasFactory;

    protected $table = 'importers';

    // Add any importer-specific properties or methods here
}
