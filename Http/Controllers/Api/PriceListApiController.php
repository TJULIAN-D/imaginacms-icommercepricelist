<?php

namespace Modules\Icommercepricelist\Http\Controllers\Api;

// Requests & Response
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Icrud\Controllers\BaseCrudController;
use Modules\Icommercepricelist\Entities\PriceList;
use Modules\Icommercepricelist\Http\Requests\PriceListRequest;

// Base Api
use Modules\Icommercepricelist\Repositories\PriceListRepository;

// Transformers
use Modules\Icommercepricelist\Transformers\PriceListTransformer;

class PriceListApiController extends BaseCrudController
{

  public $model;

  public $modelRepository;

  public function __construct(PriceList $model, PriceListRepository $modelRepository)
  {
    $this->model = $model;
    $this->modelRepository = $modelRepository;
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(Request $request)
  {
    \DB::beginTransaction();
    try {
      $data = $request->input('attributes') ?? []; //Get data
      //Validate Request
      $this->validateRequestApi(new PriceListRequest($data));

      //Create item
      $entity = $this->modelRepository->create($data);
      //Fresh data
      $entity = $entity->fresh();
      //Job
      if (isset($data['productIds']) && $entity->criteria == 'percentage') {
        \Modules\Icommerce\Jobs\SaveProductsPriceLists::dispatch(json_decode($data['productIds']), $entity);
      }

      //Response
      $response = ['data' => new PriceListTransformer($entity)];
      \DB::commit(); //Commit to Data Base
    } catch (\Exception $e) {
      \Log::error($e);
      \DB::rollback(); //Rollback to Data Base
      $status = $this->getStatusError($e->getCode());
      $response = ['errors' => $e->getMessage()];
    }
    //Return response
    return response()->json($response ?? ['data' => 'Request successful'], $status ?? 200);
  }
}
