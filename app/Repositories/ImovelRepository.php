<?php

namespace App\Repositories;

use App\Models\Imovel;
use App\Repositories\Contracts\ImovelRepositoryInterface;

class ImovelRepository extends AbstractRepository implements ImovelRepositoryInterface
{

    public function __construct(Imovel $imovel)
    {
        parent::__construct($imovel);
    }
}
