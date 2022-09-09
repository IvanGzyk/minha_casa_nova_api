<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserService extends AbstractService
{
    public function __construct(
        UserRepositoryInterface $repository
        )
    {
        $this->repository = $repository;
    }

    public function getAll(int $per_page, object $request)
    {
        $filtros = null;

        /**
         * Carrega os filtros
         */
        if($request->has('filtros')) {
            $filtros = $request->get('filtros');
        }

        /**
         * Monta o filtro e passa pro repository
         */
        if(isset($filtros)) {
            $response = $this->mountFilter($filtros);

            if($response->status() == 500) {
                return $response;
            }
        }

        /**
         * Verifica se foi passado paginação
         */
        if($request->per_page) {
            $per_page = $request->per_page;
        }

        try {
            $users = $this->repository->getAll($per_page);

            return response()->json($users, 200);
        } catch(\Exception $e) {

            return response()->json(['mensagem' => $e->getMessage()], 500);
        }
    }

    public function createNewUser(object $request)
    {
        $data = [
            'uuid'           => (string) Str::uuid(),
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => bcrypt($request->password),
        ];

        $user = $this->repository->create($data);

        if(!$user)
            return response()->json(['mensagem' => 'O servidor encontrou um erro e não pode criar o usuário'], 500);

        return response()->json($user, 200);
    }

    public function show(string $uuid)
    {
        return $this->repository->show($uuid);
    }

    public function updateUser($request, $uuid)
    {
        $user = $this->show($uuid);

        /**
         * Armazena os dados do usuário em um array
         */
        $data = [
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => (!empty($request->password) ? Hash::make($request->password) : $user->password),
        ];

        $response = $this->repository->update($user, $data);

        if(!$response)
            return response()->json(['mensagem' => 'O servidor encontrou um erro e não pode atualizar o usuário'], 500);

        return response()->json($this->show($uuid), 200);
    }

    public function destroyUser($user)
    {
        return $this->repository->destroy($user);
    }

    public function getUserByUuidTrashed($uuid){
        return $this->repository->getTrashed($uuid);
    }

    public function getUserByUuid($uuid){
        return $this->repository->show($uuid);
    }
}
