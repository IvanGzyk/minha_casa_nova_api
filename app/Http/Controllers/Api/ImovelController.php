<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormRequestImovel;
use App\Http\Resources\ImovelResource;
use App\Services\ImovelService;
use Illuminate\Http\Request;

class ImovelController extends Controller
{

    protected ImovelService $imovelService;

    public function __construct(ImovelService $imovelService)
    {
        $this->imovelService = $imovelService;
        $this->imovelService->setFieldsAllow(['name', 'address', 'description', 'value']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = (int) $request->get('per_page', 15);

        $response = $this->imovelService->getAll($per_page, $request);

        if($response->status() == 500) {
            return response()->json($response->getOriginalContent(), 500);
        }
        return ImovelResource::collection($response->getOriginalContent());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormRequestImovel $request)
    {
        $response = $this->imovelService->createNewImovel($request);

        if($response->status() == 404) {
            return response()->json($response->getOriginalContent(), 404);
        }

        if($response->status() == 500) {
            return response()->json($response->getOriginalContent(), 500);
        }

        return new ImovelResource($response->getOriginalContent());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        if (!$imovel = $this->imovelService->show($uuid))
            return response()->json(["mensagem" => "'identify' não encontrado!"], 404);

        return new ImovelResource($imovel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $imovel = $this->imovelService->getImovelByUuid($uuid);

        if(!$imovel) {
            return response()->json(['mensagem' => 'imóvel não encontrado'], 404);
        }

        $response = $this->imovelService->updateImovel($request, $uuid);

        if($response->status() == 404) {
            return response()->json($response->getOriginalContent(), 404);
        }

        if($response->status() == 500) {
            return response()->json($response->getOriginalContent(), 500);
        }

        return new ImovelResource($response->getOriginalContent());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $imovel = $this->imovelService->getImovelByUuid($uuid);

        if(!$imovel) {
            return response()->json(['mensagem' => 'imóvel não encontrado'], 404);
        }

        $this->imovelService->destroyImovel($imovel);

        return response()->json([], 204);
    }

    /**
     *
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function trashed(Request $request)
    {
        $per_page = (int) $request->get('per_page', 15);
        $response = $this->imovelService->getAllTrashed($per_page, $request);

        if($response->status() == 500)
            return response()->json($response->getOriginalContent(), 500);

        return ImovelResource::collection($response->getOriginalContent());
    }

    /**
     *
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function restore($uuid)
    {
        if(!$imovel = $this->imovelService->getImovelByUuidTrashed($uuid))
            return response()->json(['mensagem' => 'imóvel não encontrado'], 404);

        $this->imovelService->restoreImovel($imovel);

        return response()->json(['mensagem' => 'imóvel restaurado com sucesso'], 200);
    }
}
