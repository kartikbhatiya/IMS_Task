<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\DraftProduct;
use App\Models\PublishedProduct;

class UpdatePublishedProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    protected $product_code;
    /**
     * Create a new job instance.
     */
    public function __construct($product_code, $user_id)
    {
        $this->user_id = $user_id;
        $this->product_code = $product_code;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();
            $product = PublishedProduct::where('product_code',$this->product_code)->first();
            $draftProduct = DraftProduct::where('product_code',$this->product_code)->first();

            $data = array_diff_assoc($draftProduct->toArray(), $product->toArray());
            
            if(empty($data)){
                Log::info('No changes found in product details');
                return;
            }
            
            if ($product && $draftProduct && $data) {
                Log::info($data);
                $data['updated_by'] = $this->user_id;
                $data['updated_at'] = now();
                $product->update($data);
                $product->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in publishing product: ' . $e->getMessage(), [
                'product_code' => $this->product_code,
                'user_id' => $this->user_id,
            ]);
        }
    }
}
