<?php

namespace Modules\Icommercepricelist\Entities;

use Modules\Core\Icrud\Entities\CrudModel;
use Modules\Icommerce\Entities\Product;

class ProductList extends CrudModel
{
    protected $table = 'icommercepricelist__product_lists';
    public $transformer = 'Modules\Icommercepricelist\Transformers\ProductListTransformer';
    public $repository = 'Modules\Icommercepricelist\Repositories\ProductListRepository';
    public $requestValidation = [
      'create' => 'Modules\Icommercepricelist\Http\Requests\ProductListRequest',
      'update' => 'Modules\Icommercepricelist\Http\Requests\UpdatePriceListRequest',
    ];

    //Instance external/internal events to dispatch with extraData
    public $dispatchesEventsWithBindings = [
      //eg. ['path' => 'path/module/event', 'extraData' => [/*...optional*/]]
      'created' => [],
      'creating' => [],
      'updated' => [],
      'updating' => [],
      'deleting' => [],
      'deleted' => [],
    ];

    protected $fillable = [
        'product_id',
        'price_list_id',
        'price',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function priceList(){
        return $this->belongsTo(PriceList::class);
    }

  public function getPriceAttribute($value)
  {
    $price = $value;

    if ($this->relationLoaded('product') && $this->relationLoaded('priceList') && isset($this->product) && isset($this->priceList)) {
      $priceList = $this->priceList;
      $productPrice = $this->product->price;

      if($priceList->criteria !== 'fixed') {
        $price = icommercepricelist_calculatePriceByPriceList($priceList, $productPrice);
      }
    }

    return $price;
  }

}
