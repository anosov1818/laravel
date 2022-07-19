<?php

namespace App\Jobs\GenerateCatalog;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateCatalogMainJob extends AbstractJob
{
    //use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        //
//    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->debug('start');

        //сначала кэшируем продукты
        GenerateCatalogCacheJob::dispatchNow();

        //затем создаём цепочку заданий формирования файлов с ценами
        $chainPrices = $this->getChainPrices();

        //основные подзадачи
        $chainMain = [
            new GenerateCategoriesJob,
            new GenerateDeliveriesJob,   // генерация способов доставки
            new GeneratePointsJob,       //генерация пунктов выдачи
        ];

        //подзадачи которые должны выполнятся самыми последними
        $chainLast = [
            //Архивирование файлов и перенос архива в публичную папку
            new ArchiveUploadsJob,
            //Отправка уведомления стороннему сервису о том что можно скачать новый каталог
            new SendPriceRequestJob,
        ];

        $chain = array_merge($chainPrices, $chainMain, $chainLast);
        GenerateGoodsFileJob::withChain($chain)->dispatch();
        //GenerateGoodsFileJob::dispatch()->chain($chain);

        $this->debug('finish');
    }

    /**
     * формирование цепочек подзадач по генерации файлов с ценами
     *
     * @return array
     */
    private function  getChainPrices()
    {
        $result = [];
        $products = collect([1, 2, 3, 4, 5]);
        $fileNum = 1;

        foreach ($products->chunk(1) as $chunk) {
            $result[] = new GeneratePricesFileChunkJob($chunk, $fileNum);
            $fileNum++;
        }
        return $result;
    }
}
