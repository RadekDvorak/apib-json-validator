<?php
declare(strict_types=1);

namespace RadekDvorak\ApibJsonValidator;

use JsonSchema\Validator;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class SchemaValidator
{

    private $validator;

    /**
     * @param ValidationRequest $request
     * @return ValidationResult
     * @throws JsonDecodingException
     */
    public function validate(ValidationRequest $request): ValidationResult
    {
        try {
            $body = Json::decode($request->getBody()->getContent());
        } catch (JsonException $e) {
            throw JsonDecodingException::bodyError($e, (string)$request->getBody()->getContent(),
                $request->getDescription());
        }
        try {
            $schema = Json::decode($request->getSchema()->getContent());
        } catch (JsonException $e) {
            throw JsonDecodingException::schemaError($e, (string)$request->getSchema()->getContent(),
                $request->getDescription());
        }
        $validator = $this->getValidator();

        try {
            $validator->validate($body, $schema);
            $messages = $this->readErrors($validator, $request);
            $result = new ValidationResult($validator->isValid(), $body, $schema, $messages);
        } catch (\Exception $e) {
            $message = sprintf('[%s] %s', $request->getDescription(), $e->getMessage());
            $result = new ValidationResult(false, $body, $schema, [$message]);
        } finally {
            $validator->reset();
        }

        return $result;
    }

    /**
     * @return Validator
     */
    private function getValidator(): Validator
    {
        if (!isset($this->validator)) {
            $this->validator = new Validator();
        }
        return $this->validator;
    }

    /**
     * @param Validator $validator
     * @param ValidationRequest $request
     * @return string[]
     */
    private function readErrors(Validator $validator, ValidationRequest $request): array
    {
        $messages = [];
        foreach ($validator->getErrors() as $error) {
            $messages[] = sprintf('[%s] [%s] %s', $error['property'], $request->getDescription(),
                $error['message']);
        }
        return $messages;
    }
}
