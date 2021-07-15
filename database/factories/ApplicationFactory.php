<?php

namespace Database\Factories;

use App\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class ApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Application::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $ip = $this->faker->ipv4;
        $types = ['web', 'api'];

        return [
            'name' => $ip,
            'application_code' => Uuid::uuid4(),
            'application_url' => "http://".$ip,
            'application_type' => $types[rand(0, 1)],
            'added_by' => 0
        ];
    }
}
