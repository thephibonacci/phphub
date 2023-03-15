<?php

namespace System\database\orm;

use System\database\traits\HasAttributes;
use System\database\traits\HasCRUD;
use System\database\traits\HasMethodCaller;
use System\database\traits\HasQueryBuilder;
use System\database\traits\HasRelation;

abstract class Model
{
    use HasAttributes, HasCRUD, HasMethodCaller, HasQueryBuilder, HasRelation;

    protected $table;
    protected array $fillable = [];
    protected array $hidden = [];
    protected array $casts = [];
    protected string $primaryKey = 'id';
    protected string $createdAt = 'created_at';
    protected string $updatedAt = 'updated_at';
    protected $deletedAt = null;
    protected $collection = [];
}