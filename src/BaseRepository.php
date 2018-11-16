<?php

namespace GiordanoLima\EloquentRepository;

use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /**
     * @var Application
     */
    protected $app;

    protected $perPage;
    protected $orderBy = null;
    protected $orderByDirection = 'ASC';
    protected $skipCache = false;

    public $debug = false;
    private $skipGlobalScope = false;
    private $skipOrderBy = false;
    
    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        if (!$this->perPage) {
            $this->perPage = config('repository.per_page', 15);
        }
        $this->resetQuery();
    }

    /**
     * Specify Model class name.
     *
     * @return string
     */
    abstract protected function model();
    
    // -------------------- //
    // Repo manager methods //
    // -------------------- //
    
    /**
     * Reset model query.
     *
     * @return \Ensino\Repositories\Base\BaseRepository
     */
    protected function newQuery()
    {
        return $this->resetQuery();
    }

    protected function resetQuery()
    {
        return $this->model = $this->app->make($this->model());
    }

    protected function globalScope()
    {
        return $this;
    }

    protected function skipGlobalScope()
    {
        return $this->skipGlobalScope = true;

        return $this;
    }

    protected function skipOrderBy()
    {
        $this->skipOrderBy = true;

        return $this;
    }
    
    // ------------- //
    // Model methods //
    // ------------- //
    
    protected function all($columns = ['*']) {
        $this->prepareQuery();
        $r = $this->model->all($columns);
        $this->finishQuery();
        return $r;
    }
    
    protected function with($relations) {
        return $this->model->with(is_string($relations) ? func_get_args() : $relations);
    }
    
    protected function without($relations) {
        return $this->model->without($relations);
    }
    
    protected function destroy($ids) {
        $this->prepareQuery();
        $r = $this->model->destroy($ids);
        $this->finishQuery();
        return $r;
    }
    
    // ----------------- //
    // Query get methods //
    // ----------------- //
    
    protected function select($columns = ['*']) {
        return $this->model->select($columns);
    }
    
    protected function addSelect($column) {
        return $this->model->addSelect($column);
    }
    
    protected function distinct() {
        return $this->model->distinct();
    }
    
    protected function join($table, $first, $operator = null, $second = null, $type = 'inner', $where = false) {
        return $this->model->join($table, $first, $operator, $second, $type, $where);
    }
    
    protected function selectRaw($expression, array $bindings = []) {
        return $this->model->selectRaw($expression, $bindings);
    }
    
    protected function whereKey($id) {
        return $this->model->whereKey($id);
    }
    
    protected function whereKeyNot($id) {
        return $this->model->whereKeyNot($id);
    }
    
    protected function where($column, $operator = null, $value = null, $boolean = 'and') {
        return $this->model->where($column, $operator, $value, $boolean);
    }
    
    protected function orWhere($column, $operator = null, $value = null) {
        return $this->model->orWhere($column, $operator, $value);
    }
    
    protected function whereRaw($sql, $bindings = [], $boolean = 'and') {
        return $this->model->whereRaw($sql, $bindings, $boolean);
    }
    
    protected function orWhereRaw($sql, $bindings = []) {
        return $this->model->orWhereRaw($sql, $bindings);
    }
    
    protected function whereIn($column, $values, $boolean = 'and', $not = false) {
        return $this->model->whereIn($column, $values, $boolean, $not);
    }
    
    protected function orWhereIn($column, $values) {
        return $this->model->orWhereIn($column, $values);
    }
    
    protected function whereNotIn($column, $values, $boolean = 'and') {
        return $this->model->whereNotIn($column, $values, $boolean);
    }
    
    protected function orWhereNotIn($column, $values) {
        return $this->model->orWhereNotIn($column, $values);
    }
    
    protected function whereNull($column, $boolean = 'and', $not = false) {
        return $this->model->whereNull($column, $boolean, $not);
    }
    
    protected function orWhereNull($column) {
        return $this->model->orWhereNull($column);
    }
    
    protected function whereNotNull($column, $boolean = 'and') {
        return $this->model->whereNotNull($column, $boolean);
    }
    
    protected function onlyTrashed() {
        return $this->model->onlyTrashed();
    }
    
    protected function withTrashed() {
        return $this->model->withTrashed();
    }
    
    protected function whereBetween($column, array $values, $boolean = 'and', $not = false) {
        return $this->model->whereBetween($column, $values, $boolean, $not);
    }
    
    protected function orWhereBetween($column, array $values) {
        return $this->model->orWhereBetween($column, $values);
    }
    
    protected function whereNotBetween($column, array $values, $boolean = 'and') {
        return $this->model->whereNotBetween($column, $values, $boolean);
    }
    
    protected function orWhereNotBetween($column, array $values) {
        return $this->model->orWhereNotBetween($column, $values);
    }
    
    protected function orWhereNotNull($column) {
        return $this->model->orWhereNotNull($column);
    }
    
    protected function whereDate($column, $operator, $value = null, $boolean = 'and') {
        return $this->model->whereDate($column, $operator, $value, $boolean);
    }
    
    protected function orWhereDate($column, $operator, $value) {
        return $this->model->orWhereDate($column, $operator, $value);
    }
    
    protected function whereTime($column, $operator, $value, $boolean = 'and') {
        return $this->model->whereTime($column, $operator, $value, $boolean);
    }
    
    protected function orWhereTime($column, $operator, $value) {
        return $this->model->orWhereTime($column, $operator, $value);
    }
    
    protected function whereDay($column, $operator, $value = null, $boolean = 'and') {
        return $this->model->whereDay($column, $operator, $value, $boolean);
    }
    
    protected function whereMonth($column, $operator, $value = null, $boolean = 'and') {
        return $this->model->whereMonth($column, $operator, $value, $boolean);
    }
    
    protected function whereYear($column, $operator, $value = null, $boolean = 'and') {
        return $this->model->whereYear($column, $operator, $value = null, $boolean);
    }
    
    protected function groupBy($group) {
        return $this->model->groupBy($group);
    }
    
    protected function having($column, $operator = null, $value = null, $boolean = 'and') {
        return $this->model->having($column, $operator, $value, $boolean);
    }
    
    protected function orHaving($column, $operator = null, $value = null) {
        return $this->model->orHaving($column, $operator, $value);
    }
    
    protected function havingRaw($sql, array $bindings = [], $boolean = 'and') {
        return $this->model->havingRaw($sql, $bindings, $boolean);
    }
    
    protected function orHavingRaw($sql, array $bindings = []) {
        return $this->model->havingRaw($sql, $bindings);
    }
    
    protected function latest($column = 'created_at') {
        return $this->model->latest($column);
    }
    
    protected function oldest($column = 'created_at') {
        return $this->model->oldest($column);
    }
    
    protected function orderByRaw($sql, $bindings = []) {
        return $this->model->orderByRaw($sql, $bindings);
    }
    
    protected function skip($value) {
        return $this->model->skip($value);
    }
    
    protected function offset($value) {
        return $this->model->offset($value);
    }
    
    protected function take($value) {
        return $this->model->take($value);
    }
    
    protected function limit($value) {
        return $this->model->limit($value);
    }
    
    protected function find($id) {
        $this->resetQuery();
        $r = $this->model->find($id);
        $this->finishQuery();
        return $r;
    }
    
    protected function findMany($ids, $columns = ['*']) {
        $this->resetQuery();
        $r = $this->model->findMany($ids, $columns);
        $this->finishQuery();
        return $r;
    }
    
    protected function findOrFail($id) {
        $this->resetQuery();
        $r = $this->model->findOrFail($id);
        $this->finishQuery();
        return $r;
    }
    
    protected function findOrNew($id) {
        $this->resetQuery();
        $r = $this->model->findOrNew($id);
        $this->finishQuery();
        return $r;
    }
    
    protected function updateOrCreate(array $attributes, array $values = []) {
        $this->resetQuery();
        $r = $this->model->updateOrCreate($attributes, $values);
        $this->finishQuery();
        return $r;
    }
    
    protected function first() {
        $this->prepareQuery();
        $r = $this->model->first();
        $this->finishQuery();
        return $r;
    }
    
    protected function firstOrCreate(array $attributes, array $values = []) {
        $this->resetQuery();
        $r = $this->model->firstOrCreate($attributes, $values);
        $this->finishQuery();
        return $r;
    }
    
    protected function firstOrFail($columns = ['*']) {
        $this->prepareQuery();
        $r = $this->model->firstOrFail($columns);
        $this->finishQuery();
        return $r;
    }
    
    protected function firstOr($columns = ['*'], Closure $callback = null) {
        $this->prepareQuery();
        $r = $this->model->firstOr($columns, $callback);
        $this->finishQuery();
        return $r;
    }
    
    protected function value($column) {
        $this->prepareQuery();
        $r = $this->model->value($column);
        $this->finishQuery();
        return $r;
    }
    
    protected function get($columns = ['*']) {
        $this->prepareQuery();
        $r = $this->model->get($columns);
        $this->finishQuery();
        return $r;
    }
    
    protected function lists($column, $key = null) {
        $this->prepareQuery();
        $r = $this->model->lists($column, $key);
        $this->finishQuery();
        return $r;
    }
    
    protected function pluck($column, $key = null) {
        $this->prepareQuery();
        $r = $this->model->pluck($column, $key);
        $this->finishQuery();
        return $r;
    }
    
    protected function count() {
        $this->prepareQuery();
        $r = $this->model->count();
        $this->finishQuery();
        return $r;
    }
    
    protected function paginate($perPage = null, $columns = ['*']) {
        if (is_null($perPage)) {
            $perPage = $this->perPage;
        }

        if (!$this->skipOrderBy && !is_null($this->orderBy)) {
            $this->model = $this->model->orderBy($this->orderBy, $this->orderByDirection);
        }
        if (!$this->skipGlobalScope) {
            $this->globalScope();
        }

        $r = $this->model->paginate($perPage, $columns);
        $this->resetQuery();
        $this->skipGlobalScope = false;
        $this->skipOrderBy = false;

        return $r;
    }
    
    protected function simplePaginate($perPage = null, $columns = ['*']) {
        if (is_null($perPage)) {
            $perPage = $this->perPage;
        }

        if (!$this->skipOrderBy && !is_null($this->orderBy)) {
            $this->model = $this->model->orderBy($this->orderBy, $this->orderByDirection);
        }
        if (!$this->skipGlobalScope) {
            $this->globalScope();
        }

        $r = $this->model->simplePaginate($perPage, $columns);
        $this->resetQuery();
        $this->skipGlobalScope = false;
        $this->skipOrderBy = false;

        return $r;
    }

    protected function create(array $attributes = []) {
        $r = $this->newQuery()->model->create($attributes);
        $this->skipGlobalScope = false;
        $this->skipOrderBy = false;
        $this->resetQuery();
        return $r;
    }

    protected function update(array $values) {
        $this->prepareQuery();
        $r = $this->model->update($values);
        $this->finishQuery();
        return $r;
    }

    protected function increment($column, $amount = 1, array $extra = []) {
        $this->prepareQuery();
        $r = $this->model->increment($column, $amount, $extra);
        $this->finishQuery();
        return $r;
    }

    protected function decrement($column, $amount = 1, array $extra = []) {
        $this->prepareQuery();
        $r = $this->model->decrement($column, $amount, $extra);
        $this->finishQuery();
        return $r;
    }

    protected function delete() {
        $this->prepareQuery();
        $r = $this->model->delete();
        $this->finishQuery();
        return $r;
    }

    protected function forceDelete() {
        $this->prepareQuery();
        $r = $this->model->forceDelete();
        $this->finishQuery();
        return $r;
    }

    protected function min($column) {
        $this->prepareQuery();
        $r = $this->model->min($column);
        $this->finishQuery();
        return $r;
    }

    protected function max($column) {
        $this->prepareQuery();
        $r = $this->model->max($column);
        $this->finishQuery();
        return $r;
    }

    protected function sum($column) {
        $this->prepareQuery();
        $r = $this->model->sum($column);
        $this->finishQuery();
        return $r;
    }

    protected function avg($column) {
        $this->prepareQuery();
        $r = $this->model->avg($column);
        $this->finishQuery();
        return $r;
    }

    protected function average($column) {
        $this->prepareQuery();
        $r = $this->model->average($column);
        $this->finishQuery();
        return $r;
    }
    
    protected function insert(array $values) {
        $this->resetQuery();
        $r = $this->model->insert($values);
        $this->finishQuery();
        return $r;
    }
    
    protected function insertGetId(array $values) {
        $this->resetQuery();
        $r = $this->model->insertGetId($values);
        $this->finishQuery();
        return $r;
    }

    protected function orderBy($column, $direction = 'asc') {
        $order = compact('column', 'direction');

        if ($this->model instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
            $orders = (array) $this->model->getQuery()->getQuery()->orders;
        } elseif ($this->model instanceof Model || in_array('getQuery', get_class_methods($this->model))) {
            $orders = (array) $this->model->getQuery()->orders;
        } else {
            $orders = (array) $this->model->orders;
        }

        if (!in_array($order, $orders)) {
            $this->model = $this->model->orderBy($column, $direction);
        }

        $this->skipOrderBy();

        return $this;
    }

    protected function orderByDesc($column) {
        return $this->orderBy($column);
    }

    // --------------- //
    // private methods //
    // --------------- //
    
    private function prepareQuery() {
        if (!$this->skipOrderBy && !is_null($this->orderBy)) {
            $this->model = $this->model->orderBy($this->orderBy, $this->orderByDirection);
        }
        if (!$this->skipGlobalScope) {
            $this->globalScope();
        }
    }
    
    private function finishQuery() {
        $this->resetQuery();
        $this->skipGlobalScope = false;
        $this->skipOrderBy = false;
    }
    
}
