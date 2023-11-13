<?php

namespace App\Jobs;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProductController;
use App\Product;
use App\Variant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class saveProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct($code)
    {
        $this->code = $code;
//        $new = new ProductController();
//        $new->syncProducts($this->code, null);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $new = new ProductController();
        $new->syncProducts($this->code, null);
    }
}
