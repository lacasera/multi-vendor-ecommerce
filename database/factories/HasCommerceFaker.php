<?php

namespace Database\Factories;

use Bezhanov\Faker\Provider\Commerce;
use Faker\Generator;

trait HasCommerceFaker
{
    protected ?Generator $commerceFaker = null;

    public function commerceFaker(): Generator
    {
        if (empty($this->commerceFaker)) {
            $this->commerceFaker = \Faker\Factory::create();
            $this->commerceFaker->addProvider(new Commerce($this->commerceFaker));
        }

        return $this->commerceFaker;
    }
}
