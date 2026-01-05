<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $table = 'system_settings';

    protected $fillable = ['key', 'value'];

    public $timestamps = true;

    public static function getValue(string $key, $default = null)
    {
        $record = static::where('key', $key)->first();
        return $record ? $record->value : $default;
    }

    public static function setValue(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
