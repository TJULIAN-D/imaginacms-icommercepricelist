<?php

namespace Modules\Icommercepricelist\Repositories\Cache;

use Modules\Core\Icrud\Repositories\Cache\BaseCacheCrudDecorator;
use Modules\Icommercepricelist\Repositories\PriceListRepository;

class CachePriceListDecorator extends BaseCacheCrudDecorator implements PriceListRepository
{
    public function __construct(PriceListRepository $list)
    {
        parent::__construct();
        $this->entityName = 'icommercepricelist.pricelists';
        $this->repository = $list;
    }

    /**
     * create a resource
     *
     * @return mixed
     */
    public function create($data)
    {
        $this->clearCache();

        return $this->repository->create($data);
    }
}
