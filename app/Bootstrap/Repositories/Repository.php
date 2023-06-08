<?php

namespace App\Bootstrap\Http\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class Repository
{

    /* @var Model */
    protected $model;

    /**
     * Repository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function select(?bool $prepended = true)
    {
        $query = $this->model->newQuery()
            ->where('active', true)
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id');

        if ($prepended) {
            $query = $query->prepend('Selecione...', '');
        }

        return $query;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->model
            ->newQuery()
            ->get();
    }

    /**
     *
     * @param null $search
     * @param null $perPage
     * @return mixed
     */
    public function fetchAll($search = null, $perPage = null)
    {
        return $this->model
            ->newQuery()
            ->orderBy('id', 'DESC')
            ->search($search)
            ->paginate($perPage);
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function get(int $id)
    {
        return $this->model->newQuery()
            ->find($id);
    }

    /**
     * @param array $params
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function create(array $params)
    {
        $entity = $this->model->newQuery();
        return $entity->create($params);
    }

    /**
     * @param Model $entity
     * @param array $params
     * @return Model
     */
    public function update(Model $entity, array $params)
    {
        $entity->fill($params);
        $entity->save();

        return $entity;
    }

    /**
     * @param Model $entity
     * @return int
     */
    public function destroy(Model $entity)
    {
        return $entity->delete();
    }
}