<?php
declare(strict_types=1);

namespace RadekDvorak\ApibJsonValidator;

use Hmaus\Reynaldo\Elements\BaseElement;

class ValidationRequest
{

    /**
     * @var BaseElement
     */
    private $body;

    /**
     * @var BaseElement
     */
    private $schema;

    /**
     * @var BaseElement[]
     */
    private $parents;

    /**
     * @param BaseElement $body
     * @param BaseElement $schema
     * @param BaseElement[] $parents
     */
    public function __construct(BaseElement $body, BaseElement $schema, array $parents)
    {
        $this->body = $body;
        $this->schema = $schema;
        $this->parents = $parents;
    }

    /**
     * @return BaseElement
     */
    public function getBody(): BaseElement
    {
        return $this->body;
    }

    /**
     * @return BaseElement
     */
    public function getSchema(): BaseElement
    {
        return $this->schema;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        $descriptionParts = [];
        foreach ($this->parents as $bodyParent) {
            if ($bodyParent->getTitle()) {
                $descriptionParts[] = $bodyParent->getTitle();
            }
            if ($bodyParent->getAttribute('href')) {
                $descriptionParts[] = $bodyParent->getAttribute('href');
            }
            if ($bodyParent->getAttribute('method')) {
                $descriptionParts[] = $bodyParent->getAttribute('method');
            }
        }

        return implode('.', $descriptionParts);
    }
}
