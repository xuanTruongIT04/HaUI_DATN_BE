<?php

namespace App\Repositories;

abstract class BaseRepository
{
    protected $model;

    protected $query;

    protected $with = [];

    protected $withCount = [];

    protected $take;

    protected $wheres = [];

    protected $whereIns = [];

    protected $orderBys = [];

    protected $scopes = [];

    public function getAll()
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad()->eagerLoadCount();
        return $this->query->paginate(20);
    }

    public function findOrFail($id)
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad()->eagerLoadCount();
        return $this->query->findOrFail($id);
    }

    public function create($attributes = [])
    {
        return $this->model->create($attributes);
    }

    public function update($id, $attributes = [])
    {
        $result = $this->findOrFail($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }
        return false;
    }

    public function delete($id)
    {
        $result = $this->findOrFail($id);
        if ($result) {
            $result->delete();
            return true;
        }
        return false;
    }
    /**
     * Set Eloquent relationships to eager load.
     *
     * @param $relations
     *
     * @return $this
     */
    public function with($relations)
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }
        $this->with = $relations;
        return $this;
    }

    /**
     * Eager load relationships with count.
     *
     * @param $relations
     *
     * @return $this
     */
    public function withCount($relations)
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }
        $this->withCount = $relations;
        return $this;
    }

    /**
     * Create a new instance of the model's query builder.
     *
     * @return $this
     */
    protected function newQuery()
    {
        $this->query = $this->model->newQuery();
        return $this;
    }

    /**
     * Add relationships to the query builder to eager load.
     *
     * @return $this
     */
    protected function eagerLoad()
    {
        foreach ($this->with as $relation) {
            $this->query->with($relation);
        }
        return $this;
    }

    /**
     * Eager load with count.
     *
     * @return $this
     */
    protected function eagerLoadCount()
    {
        foreach ($this->withCount as $relation) {
            $this->query->withCount($relation);
        }
        return $this;
    }

    /**
     * Reset the query clause parameter arrays.
     *
     * @return $this
     */
    protected function unsetClauses()
    {
        $this->wheres = [];
        $this->whereIns = [];
        $this->scopes = [];
        $this->take = null;

        return $this;
    }
}