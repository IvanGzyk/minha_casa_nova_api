<?php

namespace App\Repositories;

abstract class AbstractRepository
{

    protected $entity;

    public function __construct($model)
    {
        $this->entity = $model;
    }

    /**
     * faz as relações entre as tabelas Ex:(cooperative,agencies,company)
     */
    public function getRelationship($relationship)
    {
        $this->entity = $this->entity->with($relationship);
    }

    /**
     * Filtra os campos na tabela Ex: (nome:like:%a%;id:=:1)
     */
    public function filtro($condition)
    {
        $this->entity = $this->entity->where($condition[0], $condition[1], $condition[2]);
    }

    /**
     * Seleciona os campos desejados na tabela Ex: (id,nome,cooperative_id)
     */
    public function selectFields($fields)
    {
        $this->entity = $this->entity->selectRaw($fields);
    }

    /**
     * traz todos os resultados paginados
     */
    public function getAll($per_page)
    {
        return $this->entity->paginate($per_page);
    }

    /**
     * criar registro
     *
     * @return void
     */
    public function create($data)
    {
        return $this->entity->create($data);
    }

    /**
     * traz um unico resultado pelo uuid
     */
    public function show($uuid)
    {
        return $this->entity->where('uuid', $uuid)->first();
    }

    /**
     * atualiza dados
     */
    public function update($model, $data)
    {
        $this->entity = $model;

        return $this->entity->update($data);
    }

    /**
     * Atualiza ou cria
     */
    public function updateOrCreate($data)
    {
        // dd($data);
        return $this->entity->updateOrCreate($data[0], $data[1]);
    }

    /**
     * exclui dados
     */
    public function destroy($model)
    {
        $this->entity = $model;

        return $this->entity->delete();
    }

    public function getTrashed($uuid)
    {
        return $this->entity->onlyTrashed()->where('uuid', $uuid)->first();
    }

    public function getAllTrashed($per_page)
    {
        return $this->entity->onlyTrashed()->paginate($per_page);
    }

    public function restore($model)
    {
        $this->entity = $model;

        return $this->entity->restore();
    }
}

?>
