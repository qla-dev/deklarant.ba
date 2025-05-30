<?php

namespace App\Http\Controllers\Api;

use App\Models\Importer;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\SupplierController;

class ImporterController extends SupplierController
{
    protected $model = Importer::class;

    protected string $label = 'uvoznik';
    protected string $labelPlural = 'uvoznici';
}
