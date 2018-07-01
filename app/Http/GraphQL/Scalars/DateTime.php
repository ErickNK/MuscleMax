<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 23/06/18
 * Time: 12:18 PM
 */

namespace App\Http\GraphQL\Scalars;


use Carbon\Carbon;
use Error;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

class DateTime extends ScalarType
{

    /**
     * Serializes an internal value to include in a response.
     *
     * @param mixed $value
     * @return mixed
     */
    public function serialize($value)
    {
        return Carbon::createFromTimeString($value)->timestamp;
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * @param mixed $value
     * @return mixed
     */
    public function parseValue($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            throw new Error("Cannot represent following value as datetime: " . Utils::printSafeJson($value));
        }

        return Carbon::createFromTimestamp($value)->toDateTimeString();
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input
     *
     * @param \GraphQL\Language\AST\Node $valueNode
     * @return mixed
     */
    public function parseLiteral($valueNode)
    {
        // Note: throwing GraphQL\Error\Error vs \UnexpectedValueException to benefit from GraphQL
        // error location in query:
        if (!$valueNode instanceof IntValueNode) {
            throw new Error('Query error: Can only parse integer got: ' . $valueNode->kind, [$valueNode]);
        }
        if (!filter_var($valueNode->value, FILTER_VALIDATE_INT)) {
            throw new Error("Not a valid timestamp", [$valueNode]);
        }

        return Carbon::createFromTimestamp($valueNode->value)->toDateTimeString();
    }
}