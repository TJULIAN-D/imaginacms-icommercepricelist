<?php

namespace Modules\Icommercepricelist\Repositories\Cache;

use Modules\Core\Repositories\Cache\BaseCacheDecorator;
use Modules\Icommercepricelist\Repositories\ProductListRepository;

class CacheProductListDecorator extends BaseCacheDecorator implements ProductListRepository
{
    public function __construct(ProductListRepository $productlist)
    {
        parent::__construct();
        $this->entityName = 'icommercepricelist.productlists';
        $this->repository = $productlist;
    }

  public function getItemsBy($params)
  {
    $this->clearCache();

    return $this->repository->getItemsBy($params);
  }

  public function getItem($criteria, $params = false)
  {
    $this->clearCache();

    return $this->repository->getItem($criteria, $params);
  }

  public function updateBy($criteria, $data, $params = false)
  {
    $this->clearCache();

    return $this->repository->updateBy($criteria, $data, $params);
  }

  public function deleteBy($criteria, $params = false)
  {
    $this->clearCache();

    return $this->repository->deleteBy($criteria, $params);
  }
}
