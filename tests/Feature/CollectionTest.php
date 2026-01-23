<?php

namespace Tests\Feature;

use App\Data\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertEqualsCanonicalizing;

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

    public function testZip()
    {
        $collection1 = collect([1, 2, 3]);
        $collection2 = collect([4, 5, 6]);
        $collection3 = $collection1->zip($collection2);

        $this->assertEquals([
            collect([1, 4]),
            collect([2, 5]),
            collect([3, 6])
        ], $collection3->all());
    }

    public function testConcat()
    {
        $collection1 = collect([1, 2, 3]);
        $collection2 = collect([4, 5, 6]);
        $collection3 = $collection1->concat($collection2);

        $this->assertEquals([1, 2, 3, 4, 5, 6], $collection3->all());
    }

    public function testCombine()
    {
        $collection1 = collect(["name", "country"]);
        $collection2 = collect(["Gleam", "Indonesia"]);
        $collection3 = $collection1->combine($collection2);

        $this->assertEquals([
            "name" => "Gleam",
            "country" => "Indonesia"
        ], $collection3->all());
    }

    public function testCollapse()
    {
        $collection1 = collect([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9]
        ]);
        $result = $collection1->collapse();

        $this->assertEqualsCanonicalizing([
            1, 2, 3, 4, 5, 6, 7, 8, 9
        ], $result->all());
    }

    public function testFlatMap()
    {
        $collection = collect([
            [
                "name" => "Gleam",
                "country" => ["Indonesia", "Singapore"]
            ],
            [
                "name" => "Bahli",
                "country" => ["Indonesia", "Prindapan"]
            ]
        ]);
        $result = $collection->flatMap(function ($item) {
            $country = $item["country"];
            return $country;
        });

        $this->assertEqualsCanonicalizing([
            "Indonesia", "Singapore", "Indonesia", "Prindapan"
        ], $result->all());
    }

    public function testStringRepresentation()
    {
        $collection = collect(["Gleam", "Bahli"]);
        $result = $collection->join("-");

        $this->assertEquals("Gleam-Bahli", $result);
        $this->assertEquals("Gleam_Bahli", $collection->join("_"));
    }

    public function testFilter()
    {
        $collection = collect([
            "Gleam" => 100,
            "Bahli" => 90,
            "Anomali" => 80
        ]);
        $result = $collection->filter(function ($score) {
            return $score >= 90;
        });

        $this->assertEquals([
            "Gleam" => 100,
            "Bahli" => 90,
        ], $result->all());
    }
    public function testFilterIndex()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $result = $collection->filter(function ($value, $key) {
            return $value % 2 == 0;
        });

        $this->assertEqualsCanonicalizing([
            2, 4, 6, 8, 10
        ], $result->all());
    }
    public function testPartition()
    {
        $collection = collect([
            "Gleam" => 100,
            "Bahli" => 90,
            "Anomali" => 80
        ]);
        [$result, $result2] = $collection->partition(function ($value) {
            return $value >= 90;
        });

        $this->assertEquals([
            "Gleam" => 100,
            "Bahli" => 90,
        ], $result->all());
        $this->assertEquals([
            "Anomali" => 80,
        ], $result2->all());
    }

    public function testTesting() 
    {
        $collection = collect(["Gleam", "Bahli", "Anomali"]);
        $result = $collection->contains("Bahli");

        $this->assertTrue($result);
        $this->assertTrue($collection->contains(function ($value, $key) {
            return $value === "Bahli";
        }));
    }
    public function testGrouping() 
    {
        $collection = collect([
            ["name" => "Gleam", "role" => "IT"],
            ["name" => "Bahli", "role" => "Anomali"]
        ]);
        $result = $collection->groupBy("role");

        $this->assertEquals([
            "IT" => collect([
                ["name" => "Gleam", "role" => "IT"]
            ]),
            "Anomali" => collect([
                ["name" => "Bahli", "role" => "Anomali"]
            ])
        ], $result->all());

        $result = $collection->groupBy(function ($value) {
            return $value["role"];
        });

        $this->assertEquals([
            "IT" => collect([
                ["name" => "Gleam", "role" => "IT"]
            ]),
            "Anomali" => collect([
                ["name" => "Bahli", "role" => "Anomali"]
            ])
        ], $result->all());
    }

    public function testSlice()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);
        $result = $collection->slice(3);

        $this->assertEqualsCanonicalizing([
            4, 5, 6, 7, 8, 9
        ], $result->all());

        $result = $collection->slice(3, 2);

        $this->assertEqualsCanonicalizing([
            4, 5
        ], $result->all());
    }

    public function testTake()
    {
        $collection = collect([1,2,3,4,5,6,7,8,9]);
        $result = $collection->take(3);

        assertEqualsCanonicalizing([1,2,3], $result->all());

        $result = $collection->takeUntil(function ($value, $key){
            return $value == 3;
        });

        assertEqualsCanonicalizing([1,2], $result->all());

        $result = $collection->takeWhile(function ($value, $key){
            return $value < 3;
        });

        assertEqualsCanonicalizing([1,2], $result->all());
    }

    public function testSkip()
    {
        $collection = collect([1,2,3,4,5,6,7,8,9]);
        $result = $collection->skip(3);

        assertEqualsCanonicalizing([4,5,6,7,8,9], $result->all());

        $result = $collection->skipUntil(function ($value, $key){
            return $value == 3;
        });

        assertEqualsCanonicalizing([3,4,5,6,7,8,9], $result->all());

        $result = $collection->skipWhile(function ($value, $key){
            return $value < 3;
        });

        assertEqualsCanonicalizing([3,4,5,6,7,8,9], $result->all());
    }

    public function testChunk()
    {
        $collection = collect([1,2,3,4,5,6,7,8,9,10]);

        $result = $collection->chunk(3);

        assertEqualsCanonicalizing(
            [1,2,3], $result->all()[0]->all());

        assertEqualsCanonicalizing(
            [4,5,6], $result->all()[1]->all());

        assertEqualsCanonicalizing(
            [7,8,9], $result->all()[2]->all());

        assertEqualsCanonicalizing(
            [10], $result->all()[3]->all());
    }

    public function testFirst()
    {
        $collection = collect([1,2,3,4,5,6,7,8,9]);
        $result = $collection->first();

        assertEqualsCanonicalizing(1, $result);

        $result = $collection->first(function ($value, $key){
            return $value > 5;
        });

        assertEqualsCanonicalizing(6, $result);
    }

    public function testLast()
    {
        $collection = collect([1,2,3,4,5,6,7,8,9,10]);
        $result = $collection->last();

        assertEqualsCanonicalizing(10, $result);

        $result = $collection->last(function ($value, $key){
            return $value < 5;
        });

        assertEqualsCanonicalizing(4, $result);
    }
    
    public function testRandom()
    {
        $collection = collect([1,2,3,4,5,6,7,8,9,10]);
        $result = $collection->random();

        self::assertTrue(in_array($result, $collection->all()));
    }

    public function testCheckingExistence()
    {
        $collection = collect([1,2,3,4,5,6,7,8,9]);

        self::assertTrue($collection->isNotEmpty());
        self::assertFalse($collection->isEmpty());

        self::assertTrue($collection->contains(5));
        self::assertFalse($collection->contains(10));
        self::assertTrue($collection->contains(function ($value, $key){
            return $value == 8;
        }));
    }
}
