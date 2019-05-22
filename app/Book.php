<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = [];

    public function path()
    {
        return '/books/' . $this->id;
    }

    public function setAuthorIdAttribute($attribute)
    {
        $this->attributes['author_id'] = Author::firstOrCreate([
            'name' => $attribute,
        ])->id;
    }
}
