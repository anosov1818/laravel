<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Blog Category
 *
 * @package App\Models
 *
 * @property-read BlogCategory $parentCategory
 * @property-read string       $parentTitle
 */
class BlogCategory extends Model
{
    use SoftDeletes;
    const ROOT = 1; //ID корня

    protected $fillable
            =[
              'title',
              'slug',
              'parent_id',
              'description',
        ];

    /**
     * Плдучить родительскую категорию
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function parentCategory()
    {
        return $this->belongsTo(BlogCategory::class, "parent_id", "id");
    }

    /**
     * Пример аксессуара Accessor
     * @url https://laravel.com/docs/9.x/eloquent-mutators
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed|string
     */
    public function getParentTitleAttribute()
    {
        $title = $this->parentCategory->title
            ?? ($this->isRoot()
                ? "Корень"
                : "???");

        return $title;
    }

    /**
     * Является ли текущий объект корневым
     * @return bool
     */
    public function isRoot()
    {
        return $this->id ===BlogCategory::ROOT;
    }
}
