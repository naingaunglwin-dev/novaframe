<?php

namespace NovaFrame\Database;

abstract class Seeder
{
    /**
     * Run the database seeding logic.
     *
     * @return void
     */
    abstract public function run(): void;
}
