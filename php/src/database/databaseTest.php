<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class DatabaseTest extends TestCase
{
    protected static $initialize = false;

    protected function setUp(): void
    {
        if(!self::$initialize && is_dir(Database::$path)){
            $content = scandir(Database::$path);

            array_map(function($item){
                if($item !== "." && $item !== ".."){
                    unlink(Database::$path . "/" . $item);
                }
            }, $content);
        }

        self::$initialize = true;
    }

    public function testDatabaseCreation(): void
    {
        $db = new Database("db-test");

        $this->assertDirectoryExists($db->getInfo()->path);
        $this->assertFileExists($db->getInfo()->local);
        $this->assertFileIsReadable($db->getInfo()->local);
    }

    public function testDatabaseInfo()
    {
        $db = new Database("db-info-test");

        $this->assertEquals($db->getInfo()->path, ".database-temp");
        $this->assertEquals($db->getInfo()->local, ".database-temp/db-info-test.json");
        $this->assertEquals($db->getInfo()->name, "db-info-test");
        $this->assertEquals($db->getInfo()->format, ".json");
    }

    public function testAddNewData(): void
    {
        $db = new Database("db-add-test");

        $db->add([
            "name" => "foo",
        ])->add([
            "name" => "bar"
        ])->add([
            "name" => "span"
        ])->add([
            "name" => "fuzz"
        ]);

        $content = json_decode(file_get_contents($db->getInfo()->local));

        $this->assertEquals(count($content->data), 4);
        $this->assertEquals($content->data[0]->name, "foo");
        $this->assertEquals($content->data[1]->name, "bar");
        $this->assertEquals($content->data[2]->name, "span");
        $this->assertEquals($content->data[3]->name, "fuzz");
    }

    public function testGetAllData(): void
    {
        $db = new Database("db-read-test");

        $db->add([
            "name" => "foo",
        ])->add([
            "name" => "bar"
        ])->add([
            "name" => "span"
        ])->add([
            "name" => "fuzz"
        ]);

        $content = $db->getAll();

        $this->assertIsObject($content);
        $this->assertIsArray($content->data);
        $this->assertEquals(count($content->data), 4);

        $this->assertEquals($content->data[0]->name, "foo");
        $this->assertEquals($content->data[1]->name, "bar");
        $this->assertEquals($content->data[2]->name, "span");
        $this->assertEquals($content->data[3]->name, "fuzz");
    }

    public function testQueryData(): void
    {
        $db = new Database("db-query-test");

        $db->add([
            "name" => "foo",
            "lang" => "php"
        ])->add([
            "name" => "bar",
            "lang" => "javascript"
        ])->add([
            "name" => "span",
            "lang" => "php"
        ])->add([
            "name" => "fuzz",
            "lang" => "javascript"
        ]);

        $result1 = $db->query(fn($item) => $item->lang === "php");
        $result2 = $db->query(fn($item) => $item->name === "span");
        $result3 = $db->query(fn($item) => $item->name === "fix");

        $this->assertIsArray($result1);
        $this->assertEquals(count($result1), 2);
        $this->assertEquals($result1[0]->name, "foo");
        $this->assertEquals($result1[1]->name, "span");

        $this->assertIsArray($result2);
        $this->assertEquals(count($result2), 1);
        $this->assertEquals($result2[0]->name, "span");

        $this->assertIsArray($result3);
        $this->assertEquals(count($result3), 0);
    }

    public function testUpdateData(): void
    {
        $db = new Database("db-update-test");

        $db->add([
            "name" => "foo",
            "lang" => "php"
        ])->add([
            "name" => "bar",
            "lang" => "javascript"
        ])->add([
            "name" => "span",
            "lang" => "php"
        ])->add([
            "name" => "fuzz",
            "lang" => "javascript"
        ]);

        // updating...
        $selected = $db->query(fn($item) => $item->name === "span");
        $result = $db->update($selected[0]->id, ["name" => "UPDATED-SPAN"]);

        // updated
        $updated = $db->query(fn($item) => $item->name === "UPDATED-SPAN");

        $this->assertTrue($result);
        $this->assertIsArray($updated);
        $this->assertEquals($updated[0]->name, "UPDATED-SPAN");
    }

    public function testDeleteData(): void
    {
        $db = new Database("db-delete-test");

        $db->add([
            "name" => "foo",
            "lang" => "php"
        ])->add([
            "name" => "bar",
            "lang" => "javascript"
        ])->add([
            "name" => "span",
            "lang" => "php"
        ])->add([
            "name" => "fuzz",
            "lang" => "javascript"
        ]);

        $selected = $db->query(fn($item) => $item->name === "bar");
        $db->delete($selected[0]);

        $selected = $db->query(fn($item) => $item->name === "bar");
        $allElements = $db->getAll();

        $this->assertEquals(count($selected), 0);
        $this->assertEquals(count($allElements->data), 3);
    }
}
