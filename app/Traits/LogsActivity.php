<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    /**
     * Fields to exclude from diff logging (sensitive/noisy).
     */
    protected array $auditExclude = [
        'updated_at', 'created_at', 'remember_token',
        'password', 'email_verification_code',
        'email_verification_code_expires_at',
    ];

    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            ActivityLog::record('created', $model, null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();

            // Remove excluded fields from the diff
            $exclude = $model->auditExclude ?? [];
            foreach ($exclude as $field) {
                unset($dirty[$field]);
            }

            if (empty($dirty)) {
                return;
            }

            $old = array_intersect_key($model->getOriginal(), $dirty);
            ActivityLog::record('updated', $model, $old, $dirty);
        });

        static::deleted(function ($model) {
            ActivityLog::record('deleted', $model);
        });

        // Support SoftDeletes restore
        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_recursive(static::class))) {
            static::restored(function ($model) {
                ActivityLog::record('restored', $model);
            });
        }
    }
}
