<?php
declare(strict_types=1);

namespace RadekDvorak\ApibJsonValidator;

class ValidationResult
{

    /**
     * @var bool
     */
    private $isOk;

    /**
     * @var array
     */
    private $body;

    /**
     * @var array
     */
    private $schema;

    /**
     * @var string[]
     */
    private $messages;

    /**
     * @param bool $isOk
     * @param \stdClass $body
     * @param \stdClass $schema
     * @param string[] $messages
     */
    public function __construct(bool $isOk, \stdClass $body, \stdClass $schema, array $messages = [])
    {
        $this->isOk = $isOk;
        $this->body = $body;
        $this->schema = $schema;
        $this->messages = $messages;
    }

    /**
     * @return bool
     */
    public function isIsOk(): bool
    {
        return $this->isOk;
    }

    /**
     * @return \stdClass
     */
    public function getBody(): \stdClass
    {
        return $this->body;
    }

    /**
     * @return \stdClass
     */
    public function getSchema(): \stdClass
    {
        return $this->schema;
    }

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

}
