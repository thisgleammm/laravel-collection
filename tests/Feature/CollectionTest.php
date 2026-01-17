<?php

namespace Tests\Feature;

use App\Data\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CollectionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testCreateCollection(): void
    {
        $collection = collect([1, 2, 3]);
        $this->assertEqualsCanonicalizing([1, 2, 3], $collection->all());
    }

    public function testForEach(): void
    {
        $collection = collect([1, 2, 3]);
        foreach ($collection as $item => $value) {
            $this->assertEqualsCanonicalizing($item + 1, $value);
        }
    }

    public function testCRUD(): void
    {
        $collection = collect([]);
        $collection->push(1, 2, 3);
        $this->assertEqualsCanonicalizing([1, 2, 3], $collection->all());

        $result = $collection->pop();
        $this->assertEquals(3, $result);
        $this->assertEqualsCanonicalizing([1, 2], $collection->all());
    }

    public function testMap()
    {
        $collection = collect([1, 2, 3]);
        $result = $collection->map(function ($item) {
            return $item * 2;
        });
        $this->assertEqualsCanonicalizing([2, 4, 6], $result->all());
    }

    public function testMapInto()
    {
        $collection = collect("gleam");
        $result = $collection->mapInto(Person::class);
        $this->assertEquals([new Person("gleam")], $result->all());
    }

    public function testMapSpread()
    {
        $collection = collect([
            ["gleam", "aja"],
            ["Bahli", "Sutojo"]
        ]);
        $result = $collection->mapSpread(function ($firstName, $lastName) {
            $fullname = $firstName . ' ' . $lastName;
            return new Person($fullname);
        });
        $this->assertEquals([
            new Person("gleam aja"),
            new Person("Bahli Sutojo")
        ], $result->all());
    }

    public function testMapToGroups()
    {
        $collection = collect([
            [
                "name" => "Gleam",
                "role" => "IT"
            ],
            [
                "name" => "Bahli",
                "role" => "Anomali"
            ]
        ]);
        $result = $collection->mapToGroups(function ($person) {
            return [
                $person["role"] => $person["name"]
            ];
        });
        $this->assertEquals([
            "IT" => collect(["Gleam"]),
            "Anomali" => collect(["Bahli"]),
        ], $result->all());
    }
}
