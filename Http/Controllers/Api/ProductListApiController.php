<?php

namespace Modules\Icommercepricelist\Http\Controllers\Api;

// Requests & Response
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Icrud\Controllers\BaseCrudController;
use Modules\Icommercepricelist\Entities\ProductList;
use Modules\Icommercepricelist\Http\Requests\ProductListRequest;
// Base Api
use Modules\Icommercepricelist\Http\Requests\SyncProductListRequest;
use Modules\Icommercepricelist\Repositories\ProductListRepository;
// Transformers
use Modules\Icommercepricelist\Transformers\ProductListTransformer;

class ProductListApiController extends BaseCrudController
{
  public $model;

  public $modelRepository;

    public function __construct(ProductList $model, ProductListRepository $modelRepository)
    {
      $this->model = $model;
      $this->modelRepository = $modelRepository;
    }

    public function syncProductsList(Request $request)
    {
      $msg = "";
      \DB::beginTransaction(); //DB Transaction
      try {
        $attributes = $request->input('attributes') ?? [];//Get data

        // Validate if exist ProductId and PriceListId
        $this->validateRequestApi(new SyncProductListRequest($attributes));

        //If Get a id, get
        $criteria = $attributes["id"]
          ? ['id' => $attributes["id"]]
          : ['product_id' => $attributes["product_id"], 'price_list_id' => $attributes["price_list_id"]];

        try {
          //Update or Create the Relation
          $msg = ProductList::updateOrCreate(
            $criteria,
            [
              'product_id' => $attributes["product_id"],
              'price_list_id' => $attributes["price_list_id"],
              'price' => $attributes["price"] ?? 0
            ]
          );
        } catch (\Exception $e) {
          // Get the SQL error message
          $msg = $e->getMessage();
          $status = $this->getStatusError($e->getCode());

          // Check if the error message contains the string "SQLSTATE"
          if (strpos($msg, "SQLSTATE") !== false) {
            // Extract the column name causing the error
            preg_match("/CONSTRAINT `[^`]+` FOREIGN KEY \(`([^`]+)`\) REFERENCES/", $msg, $matches);
            $columnName = $matches[1] ?? 'unknown';
            // Return only the name of the failed field
            $msg = ("Failed to find: $columnName");
          }
          $response = ["errors" => $msg];
        }
        //Response
        $response = $response ?? ["data" => $msg];
        \DB::commit();//Commit to DataBase
      } catch (\Exception $e) {
        \DB::rollback();//Rollback to Data Base
        \Log::error("File: ". $e->getFile() ."Line: ". $e->getLine() ."Message: ". $e->getMessage());
        $status = $this->getStatusError($e->getCode());
        $response = ["errors" => $e->getMessage()];
      }

      return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
    }
}
