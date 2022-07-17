<?php

namespace App\Observers;

use App\Models\BlogPost;
use Carbon\Carbon;

class BlogPostObserver
{
    /**
     * Handle the BlogPost "created" event.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function creating(BlogPost $blogPost)
    {
        $this->setPublishedAt($blogPost);

        $this->setSlug($blogPost);

        $this->setHtml($blogPost);

        $this->setUser($blogPost);
    }

    /**
     * @param BlogPost $blogPost
     * Обработка перед обновлением
     */

    public function updating(BlogPost $blogPost)
    {
//        $test[] = $blogPost->isDirty();
//        $test[] = $blogPost->isDirty('is_published');
//        $test[] = $blogPost->isDirty('user_id');
//        $test[] = $blogPost->getAttribute('is_published');
//        $test[] = $blogPost->is_published();
//        $test[] = $blogPost->getOriginal('is_published');
//        dd($test);
        $this->setPublishedAt($blogPost);

        $this->setSlug($blogPost);
    }

    /**
     * @param BlogPost $blogPost
     * Если дата публикации не уставлена и происходит установка флага - Опубликовано, то устанавливаем дату публикации на текущую
     */
    protected function setPublishedAt(BlogPost $blogPost)
    {
        $needSetPublished = empty($blogPost->published_at) && $blogPost->is_published;
       // dd($needSetPublished);
        if ($needSetPublished) {
            $blogPost->published_at = Carbon::now();
        }
    }

    /**
     * @param BlogPost $blogPost
     * Если поле слаг пустое, то заполняем его конвертацией заголовка
     */
    protected function setSlug(BlogPost $blogPost){

        if (empty($blogPost->slug)) {
            $blogPost->slug = \Str::slug($blogPost->title);
        }
    }

    /**
     * Устанавливаем значение поля content_html относительно поля content_raw
     * @param BlogPost $blogPost
     * @return void
     */

    protected function setHtml(BlogPost $blogPost)
    {
        if ($blogPost->isDirty('content_raw')) {
            //TODO: тут должна быть генерация markdown -> html
            $blogPost->content_html = $blogPost->content_raw;
        }
    }

    /**
     * Если не указан user_id, то устанавливаем пользователя по-умолчанию
     * @param BlogPost $blogPost
     * @return void
     */

    protected function setUser(BlogPost $blogPost)
    {
        $blogPost->user_id = auth()->id() ?? BlogPost::UNKNOWN_USER;
    }

    /**
     * @param BlogPost $blogPost
     * @return void
     */
    public function created(BlogPost $blogPost)
    {
        //
    }

    /**
     * Handle the BlogPost "updated" event.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function updated(BlogPost $blogPost)
    {
//
    }

    /**
     * Handle the BlogPost "deleted" event.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function deleted(BlogPost $blogPost)
    {
        //
    }

    /**
     * Handle the BlogPost "restored" event.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function restored(BlogPost $blogPost)
    {
        //
    }

    /**
     * Handle the BlogPost "force deleted" event.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function forceDeleted(BlogPost $blogPost)
    {
        //
    }
}
