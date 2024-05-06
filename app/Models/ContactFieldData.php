<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactFieldData extends Model
{
    protected $fillable = [
        'contact_id',
        'field_id',
        'value',
    ];

    protected $table = 'contact_fields_data';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function field()
    {
        return $this->belongsTo(ContactField::class, 'field_id')->with('default_lang_field')->with('file');
    }

    public function file()
    {
        return $this->belongsTo(DriveFile::class, 'value', 'code');
    }
}
