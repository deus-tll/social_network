<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * Create a new instance.
     *
     * @param  Model  $model
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve all data of repository.
     *
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    /**
     * Retrieve all data of repository, paginated.
     *
     * @param  null  $limit
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate($limit = null, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->newQuery()->select($columns)->latest()->paginate($limit);
    }

    /**
     * Save a new entity in repository.
     *
     * @param  array  $data
     * @return Model|Builder
     */
    public function create(array $data): Model|Builder
    {
        return $this->model->newQuery()->create($data);
    }

    /**
     * Return an entity.
     *
     * @param  int  $id
     * @return mixed
     */
    public function findOrFail(int $id): mixed
    {
        return $this->model->newQuery()->findOrFail($id);
    }

    /**
     * Update an entity.
     *
     * @param  Model  $entity
     * @param  array  $data
     * @return bool
     */
    public function update(Model $entity, array $data): bool
    {
        return $entity->update($data);
    }

    /**
     * Delete an entity.
     *
     * @param  Model  $entity
     * @return bool|null
     */
    public function delete(Model $entity): bool|null
    {
        return $entity->delete();
    }

    /**
     * Update or create an entity.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return Builder|Model
     */
    public function updateOrCreate(array $attributes, array $values): Builder|Model
    {
        return $this->model->newQuery()->updateOrCreate($attributes, $values);
    }

    /**
     * Get entity.
     *
     * @param  array  $condition
     * @param  bool  $takeOne
     * @return mixed
     */
    public function get(array $condition = [], bool $takeOne = true): mixed
    {
        $result = $this->model->newQuery()->where($condition);

        if ($takeOne) {
            return $result->first();
        }

        return $result->get();
    }
}
