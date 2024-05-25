<?php

namespace Modules\Icommercepricelist\Entities;

use Astrotomic\Translatable\Translatable;
use Modules\Core\Icrud\Entities\CrudModel;
use Modules\Icommerce\Entities\Product;

class PriceList extends CrudModel
{
    use Translatable;

    protected $table = 'icommercepricelist__price_lists';
    public $transformer = 'Modules\Icommercepricelist\Transformers\PriceListTransformer';
    public $repository = 'Modules\Icommercepricelist\Repositories\PriceListRepository';
    public $requestValidation = [
      'create' => 'Modules\Icommercepricelist\Http\Requests\PriceListRequest',
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

    public $translatedAttributes = [
        'name',
    ];

    protected $fillable = [
        'status',
        'criteria',
        'related_id',
        'related_entity',
        'operation_prefix',
        'value',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'icommercepricelist__product_lists')
            ->withPivot('id', 'price')
            ->withTimestamps();
    }

  public function getEntityAttribute()
  {
    if ($this->related_entity && $this->related_id)
      return app($this->related_entity)->find($this->related_id);
    else
      return null;
  }

  public function related(){
    return $this->morphTo(__FUNCTION__, 'related_entity', 'related_id');
  }
}
