<?php

namespace Prewk;

use Illuminate\Http\Request;
use PHPUnit_Framework_TestCase;

class TestRecord extends Record
{
    protected function getFields()
    {
        return ["id", "foo_id", "bar_id", "baz"];
    }
}

class RecordCollectionTest extends PHPUnit_Framework_TestCase
{
    public function test_one_record()
    {
        $collection = (new RecordCollection)->fromRequest(Request::create("foo", "GET", [
            "id" => 1,
            "foo_id" => 2,
            "bar_id" => 3,
            "baz" => "qux",
        ]), new TestRecord);

        $ids = $collection->getUniqueValues(["id", "foo_id", "bar_id"]);

        $this->assertEquals([1], $ids["id"]);
        $this->assertEquals([2], $ids["foo_id"]);
        $this->assertEquals([3], $ids["bar_id"]);
    }

    public function test_multiple_records()
    {
        $collection = (new RecordCollection)->fromRequest(Request::create("foo", "GET", [
            [
                "id" => 1,
                "foo_id" => 2,
                "bar_id" => 3,
                "baz" => "qux",
            ],
            [
                "id" => 2,
                "foo_id" => 2,
                "bar_id" => 4,
                "baz" => "qux",
            ],
        ]), new TestRecord);

        $ids = $collection->getUniqueValues(["id", "foo_id", "bar_id"]);

        $this->assertEquals([1, 2], $ids["id"]);
        $this->assertEquals([2], $ids["foo_id"]);
        $this->assertEquals([3, 4], $ids["bar_id"]);
    }
}