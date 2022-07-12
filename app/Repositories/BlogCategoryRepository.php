<?php

namespace App\Repositories;

//use Illuminate\Database\Eloquent\Model;
use App\Models\BlogCategory as Model;
use Illuminate\Database\Eloquent\Collection;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class BlogCategoryRepository.
 */
class BlogCategoryRepository extends CoreRepository
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class;
    }
     /**
      * @param  int $id
     *  @return  Model
     */
    public function getEdit(int $id): Model
    {
        return $this->startConditions()->find($id);
    }

    /**
     * @return Collection
     */
    public function getForComboBox()
    {
        return $this->startConditions()->all();
    }

}
