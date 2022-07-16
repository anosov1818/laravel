<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BlogPost
 *
 * @package App\Models
 *
 * @property \App\Models\BlogCategory $category
 * @property \App\Models\User         $user
 * @property string                   $title
 * @property string                   $slug
 * @property string                   $content_html
 * @property string                   $content_raw
 * @property string                   $excerpt
 * @property string                   $published_at
 * @property boolean                  $is_published
 */
class BlogPost extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable
        =[
            'title',
            'slug',
            'category_id',
            'excerpt',
            'content_raw',
            'is_published',
            'published_at',
            'user_id',
        ];

    /**
     * категория статьи
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        //статья принадлежит категории
        return $this->belongsTo(BlogCategory::class);
    }
    /**
     * Автор статьи.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        //статья принадлежит пользователю
        return $this->belongsTo(User::class);
    }

}
