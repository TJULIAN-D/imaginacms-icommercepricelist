<?php

namespace Modules\Icommercepricelist\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Icrud\Transformers\CrudResource;
use Modules\Icommerce\Transformers\ProductTransformer;

class PriceListTransformer extends CrudResource
{
    /**
     * Method to merge values with response
     */
    public function modelAttributes($request)
    {
        $data = [
            'price' => $this->when(isset($this->pivot), $this->pivot->price ?? 0),
        ];

        return $data;
    }
}
