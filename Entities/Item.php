<?php

namespace Modules\Activos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{

   protected $guarded = ['id'];

   protected $table = 'activos_item';

}
