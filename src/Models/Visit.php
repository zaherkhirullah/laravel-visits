<?php

namespace Hayrullah\LaravelVisits\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Config;

/**
 * This file is part of Laravel Favorite,.
 *
 * @license MIT
 */
class Visit extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'visits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id'];

    /**
     * Define a polymorphic, inverse one-to-one or many relationship.
     *
     * @return MorphTo
     */
    public function visitable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(Config::get('auth.providers.users.model'));
    }
}
