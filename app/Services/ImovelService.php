<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Repositories\Contracts\ImovelRepositoryInterface;

class ImovelService extends AbstractService
{
    public function __construct(
        ImovelRepositoryInterface $repository
        )
    {
        $this->repository = $repository;
    }

    public function getAll(int $per_page, object $request)
    {
        $filtros = null;

        if($request->has('filtros')) {
            $filtros = $request->get('filtros');
        }

        if(isset($filtros)) {
            $response = $this->mountFilter($filtros);

            if($response->status() == 500) {
                return $response;
            }
        }

        if($request->per_page) {
            $per_page = $request->per_page;
        }

        try {
            $imovel = $this->repository->getAll($per_page);

            return response()->json($imovel, 200);
        } catch(\Exception $e) {

            return response()->json(['mensagem' => $e->getMessage()], 500);
        }
    }

    public function createNewImovel(object $request)
    {
        $data = [
            'uuid'          => (string) Str::uuid(),
            'name'          => $request->name,
            'address'       => $request->address,
            'description'   => $request->description,
            'value'         => $request->value,
        ];

        $imovel = $this->repository->create($data);

        if(!$imovel)
            return response()->json(['mensagem' => 'O servidor encontrou um erro e não pode criar o imóvel'], 500);

        return response()->json($imovel, 200);
    }

    public function show(string $uuid)
    {
        return $this->repository->show($uuid);
    }

    public function updateImovel($request, $uuid)
    {
        $imovel = $this->show($uuid);

        $data = [
            'name'          => $request->name,
            'address'       => $request->address,
            'description'   => $request->description,
            'value'         => $request->value,
        ];

        $response = $this->repository->update($imovel, $data);

        if(!$response)
            return response()->json(['mensagem' => 'O servidor encontrou um erro e não pode atualizar o imóvel'], 500);

        return response()->json($this->show($uuid), 200);
    }

    public function destroyImovel($imovel)
    {
        return $this->repository->destroy($imovel);
    }

    public function getAllTrashed(int $per_page, object $request)
    {
        $filtros = null;

        if($request->has('filtros')) {
            $filtros = $request->get('filtros');
        }

        if(isset($filtros)) {
            $response = $this->mountFilter($filtros);

            if($response->status() == 500) {
                return $response;
            }
        }

        if($request->per_page) {
            $per_page = $request->per_page;
        }

        try {
            $service = $this->repository->getAllTrashed($per_page);

            return response()->json($service, 200);
        } catch(\Exception $e) {

            return response()->json(['mensagem' => $e->getMessage()], 500);
        }
    }

    public function restoreImovel($imovel)
    {
        return $this->repository->restore($imovel);
    }

    public function getImovelByUuidTrashed($uuid){
        return $this->repository->getTrashed($uuid);
    }

    public function getImovelByUuid($uuid){
        return $this->repository->show($uuid);
    }
}
