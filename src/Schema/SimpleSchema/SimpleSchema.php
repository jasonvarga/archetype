<?php

namespace Archetype\Schema\SimpleSchema;

use Archetype\Schema\SimpleSchema\SimpleSchemaParser;

class SimpleSchema
{
    public $entites = [];

    public function __construct(array $entites)
    {
        $this->entites = $entites;
    }

    public static function parse(string $schema)
    {
        return (new SimpleSchemaParser)->parse($schema);
    }
}