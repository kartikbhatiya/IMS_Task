<?php

namespace App\Jobs;

use App\Models\DraftProduct;
use App\Models\PublishedProduct;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PublishProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product_code;
    protected $user_id;

    /**
     * Create a new job instance.
     */
    public function __construct($product_code, $user_id)
    {
        $this->product_code = $product_code;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $product = DraftProduct::where('product_code', $this->product_code)->first();
            $PubProduct = PublishedProduct::where('product_code',  $this->product_code)->first();
            if($PubProduct) {
                Log::info('Product already published', [
                    'product_code' =>  $this->product_code,
                    'user_id' => $this->user_id,
                ]);
                return;
            }

            if ($product) {
                DB::beginTransaction();

                $productDetails = [
                    'product_name' => $product->product_name,
                    'product_code' => $product->product_code,
                    'manufacturer_name' => $product->manufacturer_name,
                    'mrp' => $product->mrp,
                    'combination_string' => $product->combination_string,
                    'category_id' => $product->category_id,
                    'is_banned' => $product->is_banned,
                    'is_active' => $product->is_active,
                    'is_discontinued' => $product->is_discontinued,
                    'is_assured' => $product->is_assured,
                    'is_refrigerated' => $product->is_refrigerated,
                    'created_by' => $this->user_id,
                ];

                PublishedProduct::create($productDetails);

                $product->is_published = true;
                $product->save();
                Log::info('Product published successfully', [
                    'product_code' =>  $this->product_code,
                    'user_id' => $this->user_id,
                ]);
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in publishing product: ' . $e->getMessage(), [
                'product_code' =>  $this->product_code,
                'user_id' => $this->user_id,
            ]);
        }
    }
}
