<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
	use HasFactory;

	protected $fillable = [
		'title',
		'species',
		'level',
		'description',
		'file_path',
		'file_type'
	];
}
