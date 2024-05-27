<?php

namespace Modules\Icommercepricelist\Repositories\Cache;

use Modules\Core\Icrud\Repositories\Cache\BaseCacheCrudDecorator;
use Modules\Icommercepricelist\Repositories\ProductListRepository;

class CacheProductListDecorator extends BaseCacheCrudDecorator implements ProductListRepository
{
    public function __construct(ProductListRepository $productlist)
    {
        parent::__construct();
        $this->entityName = 'icommercepricelist.productlists';
        $this->repository = $productlist;
    }
}
