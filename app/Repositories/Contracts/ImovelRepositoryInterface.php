<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface ImovelRepositoryInterface
{
    public function getRelationship(array $relations);

    public function filtro(array $condition);

    public function selectFields(string $fields);

    public function getAll(int $per_page);

    public function create(array $data);

    public function show(string $uuid);

    public function update(object $user, array $data);

    public function destroy(object $user);

    public function getTrashed(string $uuid);

    /**
     * métodos exclusivos do imóvel
     */
}
