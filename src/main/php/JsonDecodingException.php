<?php
declare(strict_types=1);

namespace RadekDvorak\ApibJsonValidator;

use Nette\Utils\JsonException;

class JsonDecodingException extends \RuntimeException
{

    /**
     * @param JsonException $e
     * @param string $invalidJson
     * @param string $description
     * @return JsonDecodingException
     */
    public static function schemaError(
        JsonException $e,
        string $invalidJson,
        string $description
    ): JsonDecodingException {
        $message = sprintf('Schema error [%s] at [%s]: %s', $e->getMessage(), $description, $invalidJson);

        return new self($message, $e->getCode(), $e);
    }

    /**
     * @param JsonException $e
     * @param string $invalidJson
     * @param string $description
     * @return JsonDecodingException
     */
    public static function bodyError(JsonException $e, string $invalidJson, string $description): JsonDecodingException
    {
        $message = sprintf('Body error [%s] at [%s]: %s', $e->getMessage(), $description, $invalidJson);

        return new self($message, $e->getCode(), $e);
    }
}
