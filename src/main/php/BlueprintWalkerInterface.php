<?php
declare(strict_types=1);

namespace RadekDvorak\ApibJsonValidator;

use Hmaus\Reynaldo\Elements\MasterCategoryElement;

interface BlueprintWalkerInterface
{

    /**
     * @param MasterCategoryElement $api
     * @return \Generator|ValidationRequest[]
     */
    public function walkValidations(MasterCategoryElement $api): \Generator;
}
