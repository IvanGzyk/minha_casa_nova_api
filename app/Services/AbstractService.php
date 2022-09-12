<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

abstract class AbstractService
{
    protected $repository;
    protected array $fields;

    public function __construct() { }

    /**
     * Define as colunas permitidas para filtrar
     *
     * @return void
     */
    public function setFieldsAllow(array $fields)
    {
        $this->fieldsAllow = $fields;
    }

    /**
     * Monta o filtro
     *
     * @param [type] $filtros
     * @param [type] $fieldsAllow
     * @return void
     */
    protected function mountFilter(string $filtros): JsonResponse
    {
        $letters = strlen($filtros);

        /**
         * verifica se o último caracter é ';' e o remove
         */
        if($filtros[$letters - 1] == ";")
            $filtros = substr($filtros, 0, -1);

        $filtros = explode(";", $filtros);

        foreach($filtros as $filtro) {
            $condition = explode(":", $filtro);
            if(in_array($condition[0], $this->fieldsAllow)) {
                $this->repository->filtro($condition);
            } else {
                return response()->json([
                            'mensagem' => 'o servidor encontrou um erro',
                            'errors' => 'os filtros informados são inválidos'
                        ], 500);
            }
        }

        return response()->json([], 200);
    }

    /**
     * Verifica se o usuario logado é Master
     */
    protected function isMaster()
    {
        return auth()->user()->hasRole('master') ? true : false;
    }

    /**
     * Verifica se o usuario logado é Manager
     */
    protected function isManager()
    {
        return auth()->user()->hasRole('manager') ? true : false;
    }

    protected function convert_mins_to_hours(int $minutes_actually): string
    {
        $minutes = $minutes_actually;

        if(floor($minutes / 60) == 0) {
            return $time = ($minutes -   floor($minutes / 60) * 60) . ' min';
        } else {

            $hour = (floor($minutes / 60) == 1) ? floor($minutes / 60) . ' hora' : floor($minutes / 60) . ' horas';
            $minutes = (($minutes -   floor($minutes / 60) * 60) > 0) ? ' e ' . ($minutes -   floor($minutes / 60) * 60) . ' min' : '';

            return $time = $hour . $minutes;
        }
    }
}
