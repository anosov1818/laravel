<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class DiggingDeeperController extends Controller
{
    public function collections()
    {
        //$result = [];
        /**
         * @var \Illuminate\Database\Eloquent\Collection $eloquentCollection
         */

        $eloquentCollection = BlogPost::withTrashed()->get();

        dd(__METHOD__, $eloquentCollection, $eloquentCollection->toArray());
        /**
         * @var \Illuminate\Database\Eloquent\Collection $collection
         */
        $collection = collect($eloquentCollection->toArray());

        dd(
            get_class($eloquentCollection),
            get_class($collection),
            $collection
        );

    }
}
