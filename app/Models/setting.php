<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model {
    protected $table='settings'; protected $primaryKey='key';
    public $incrementing=false; protected $keyType='string';
    protected $fillable=['key','value'];

    public static function get(string $key, $default=null){
        $row=static::find($key); if(!$row) return $default;
        $j=json_decode($row->value,true);
        return json_last_error()===JSON_ERROR_NONE ? $j : $row->value;
    }
    public static function set(string $key,$val){
        return static::updateOrCreate(['key'=>$key],['value'=>is_array($val)?json_encode($val):$val]);
    }
}
