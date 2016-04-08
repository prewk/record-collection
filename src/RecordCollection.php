<?php
/**
 * Record collection
 *
 * @author Oskar Thornblad
 */

namespace Prewk;

use Illuminate\Support\Collection;
use Prewk\RecordInterface;
use Illuminate\Http\Request;

/**
 * Record collection
 */
class RecordCollection extends Collection
{
    /**
     * Create a new collection of records from a request
     *
     * @param Request $request
     * @param \Prewk\RecordInterface $record
     * @return static
     */
    public function fromRequest(Request $request, RecordInterface $record)
    {
        $all = $request->all();

        reset($all);
        $firstKey = key($all);

        if (is_numeric($firstKey)) {
            return $this->make(array_map(function($item) use ($record) {
                return $record->make($item);
            }, $all));
        } else {
            return $this->make([$record->make($all)]);
        }
    }

    /**
     * @param array $keys
     * @return mixed
     */
    public function getUniqueValues(array $keys)
    {
        return $this->reduce(function($carry, $record) {
            foreach (array_keys($carry) as $key) {
                $value = array_get($record, $key);
                if (!in_array($value, $carry[$key])) {
                    $carry[$key][] = $value;
                }
            }
            return $carry;
        }, array_reduce($keys, function($carry, $value) {
            $carry[$value] = [];
            return $carry;
        }, []));
    }
}