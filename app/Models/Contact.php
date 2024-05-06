<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    protected $fillable = [
        'source_lang_id',
        'task_id',
        'project_id',
        'clevada_team_id',
        'name',
        'email',
        'subject',
        'message',
        'ip',
        'referer',
        'created_at',
        'read_at',
        'responded_at',
        'is_important',
        'is_spam',
        'deleted_at',
    ];

    protected $table = 'contact';

    public $timestamps = false;

    public function lang(): BelongsTo
    {
        return $this->BelongsTo(SysLang::class, 'source_lang_id');
    }
}
