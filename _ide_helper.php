<?php

namespace Illuminate\Database\Eloquent {
    class Model {
        /**
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public static function query() {
            return null;
        }

        /**
         * @return int
         */
        public static function count() {
            return 0;
        }

        /**
         * @param string|array|\Closure $column
         * @param mixed $operator
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public static function where($column, $operator = null, $value = null, $boolean = 'and') {
            return null;
        }

        /**
         * @param string $column
         * @param array $values
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public static function whereIn($column, $values, $boolean = 'and', $not = false) {
            return null;
        }

        /**
         * @param string $column
         * @param array $values
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public static function whereBetween($column, array $values, $boolean = 'and', $not = false) {
            return null;
        }

        /**
         * @param string $column
         * @param mixed $operator
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public static function whereMonth($column, $operator, $value = null, $boolean = 'and') {
            return null;
        }

        /**
         * @param string $column
         * @param mixed $operator
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public static function whereYear($column, $operator, $value = null, $boolean = 'and') {
            return null;
        }

        /**
         * @param string $table
         * @param string $first
         * @param string|null $operator
         * @param string|null $second
         * @param string $type
         * @param bool $where
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public static function join($table, $first, $operator = null, $second = null, $type = 'inner', $where = false) {
            return null;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public static function distinct() {
            return null;
        }

        /**
         * @param array|mixed $columns
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public static function select($columns = ['*']) {
            return null;
        }

        /**
         * @param array|string $relations
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public static function with($relations) {
            return null;
        }

        /**
         * @param string $column
         * @return float|int
         */
        public static function sum($column) {
            return 0;
        }

        /**
         * @param string $column
         * @return float|int
         */
        public static function avg($column) {
            return 0;
        }

        /**
         * @return \Illuminate\Support\Collection
         */
        public static function all() {
            return new \Illuminate\Support\Collection();
        }
    }

    class Builder {
        /**
         * @return int
         */
        public function count() {
            return 0;
        }

        /**
         * @param string|array|\Closure $column
         * @param mixed $operator
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function where($column, $operator = null, $value = null, $boolean = 'and') {
            return $this;
        }

        /**
         * @param string $column
         * @param array $values
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function whereIn($column, $values, $boolean = 'and', $not = false) {
            return $this;
        }

        /**
         * @param string $column
         * @param array $values
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function whereBetween($column, array $values, $boolean = 'and', $not = false) {
            return $this;
        }

        /**
         * @param string $column
         * @param mixed $operator
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function whereMonth($column, $operator, $value = null, $boolean = 'and') {
            return $this;
        }

        /**
         * @param string $column
         * @param mixed $operator
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function whereYear($column, $operator, $value = null, $boolean = 'and') {
            return $this;
        }

        /**
         * @param string $table
         * @param string $first
         * @param string|null $operator
         * @param string|null $second
         * @param string $type
         * @param bool $where
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function join($table, $first, $operator = null, $second = null, $type = 'inner', $where = false) {
            return $this;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function distinct() {
            return $this;
        }

        /**
         * @param array|mixed $columns
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function select($columns = ['*']) {
            return $this;
        }

        /**
         * @param array|string $relations
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function with($relations) {
            return $this;
        }

        /**
         * @param string $column
         * @return float|int
         */
        public function sum($column) {
            return 0;
        }

        /**
         * @param string $column
         * @return float|int
         */
        public function avg($column) {
            return 0;
        }
    }
}

namespace Illuminate\Database\Query {
    class Builder {
        /**
         * @return int
         */
        public function count() {
            return 0;
        }

        /**
         * @param string|array|\Closure $column
         * @param mixed $operator
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Query\Builder
         */
        public function where($column, $operator = null, $value = null, $boolean = 'and') {
            return $this;
        }

        /**
         * @param string $column
         * @param array $values
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Query\Builder
         */
        public function whereIn($column, $values, $boolean = 'and', $not = false) {
            return $this;
        }

        /**
         * @param string $column
         * @param array $values
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Query\Builder
         */
        public function whereBetween($column, array $values, $boolean = 'and', $not = false) {
            return $this;
        }

        /**
         * @param string $column
         * @param mixed $operator
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Query\Builder
         */
        public function whereMonth($column, $operator, $value = null, $boolean = 'and') {
            return $this;
        }

        /**
         * @param string $column
         * @param mixed $operator
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Query\Builder
         */
        public function whereYear($column, $operator, $value = null, $boolean = 'and') {
            return $this;
        }

        /**
         * @param string $table
         * @param string $first
         * @param string|null $operator
         * @param string|null $second
         * @param string $type
         * @param bool $where
         * @return \Illuminate\Database\Query\Builder
         */
        public function join($table, $first, $operator = null, $second = null, $type = 'inner', $where = false) {
            return $this;
        }

        /**
         * @return \Illuminate\Database\Query\Builder
         */
        public function distinct() {
            return $this;
        }

        /**
         * @param array|mixed $columns
         * @return \Illuminate\Database\Query\Builder
         */
        public function select($columns = ['*']) {
            return $this;
        }

        /**
         * @param string $column
         * @return float|int
         */
        public function sum($column) {
            return 0;
        }

        /**
         * @param string $column
         * @return float|int
         */
        public function avg($column) {
            return 0;
        }
    }
}
