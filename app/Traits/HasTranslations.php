<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait HasTranslations
{
    protected function generateUniqueSlug($baseSlug)
    {
        $slug = $baseSlug;
        $count = 1;

        while ($this->slugExists($slug)) {
            $slug = $baseSlug.'-'.$count;
            $count++;
        }

        return $slug;
    }

    protected function slugExists($slug)
    {
        return DB::table('web_sitemaps')->where('slug', $slug)->exists();
    }

    protected function isDifferentSlug($value)
    {
        return $this->attributes['slug'] == $value ? false : true;
    }

    public function setSlugAttribute($value)
    {
        if (! $this->exists) {
            // This is a new model being created
            $this->attributes['slug'] = $this->generateUniqueSlug(Str::slug($value));
        } else {
            // This is an existing model being updated
            $this->attributes['slug'] = $this->isDifferentSlug($value)
                ? $this->generateUniqueSlug(Str::slug($value))
                : $value;
        }
    }
}
