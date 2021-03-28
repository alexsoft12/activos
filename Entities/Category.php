<?php

namespace Modules\Activos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Category extends Model
{
     protected $guarded = ['id'];

     protected $table = 'activos_categories';

}
