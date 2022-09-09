<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;

use App\Http\Resources\UserResource;
use App\Http\Requests\FormRequestUser;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->userService->setFieldsAllow(['name', 'email']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = (int) $request->get('per_page', 15);

        $response = $this->userService->getAll($per_page, $request);

        if($response->status() == 500) {
            return response()->json($response->getOriginalContent(), 500);
        }

        return UserResource::collection($response->getOriginalContent());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormRequestUser $request)
    {
        $response = $this->userService->createNewUser($request);

        if($response->status() == 404) {
            return response()->json($response->getOriginalContent(), 404);
        }

        if($response->status() == 500) {
            return response()->json($response->getOriginalContent(), 500);
        }

        return new UserResource($response->getOriginalContent());
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        if (!$user = $this->userService->show($uuid)) {
            return response()->json(["mensagem" => "'identify' não encontrado!"], 404);
        }

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(FormRequestUser $request, $uuid)
    {
        $user = $this->userService->getUserByUuid($uuid);

        if(!$user) {
            return response()->json(['mensagem' => 'usuário não encontrado'], 404);
        }

        $response = $this->userService->updateUser($request, $uuid);

        if($response->status() == 404) {
            return response()->json($response->getOriginalContent(), 404);
        }

        if($response->status() == 500) {
            return response()->json($response->getOriginalContent(), 500);
        }

        return new UserResource($response->getOriginalContent());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $user = $this->userService->getUserByUuid($uuid);

        if(!$user) {
            return response()->json(['mensagem' => 'usuário não encontrado'], 404);
        }

        $this->userService->destroyUser($user);

        return response()->json([], 204);
    }
}
