<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model{
    protected $table = 'tbluser2';
    // column sa table
    protected $fillable = [
        'username', 'password', 'jobid'
    ];
    public $timestamps = false;
}