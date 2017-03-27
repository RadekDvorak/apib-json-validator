<?php
declare(strict_types=1);

namespace RadekDvorak\ApibJsonValidator;

use Hmaus\DrafterPhp\Drafter;
use Hmaus\Reynaldo\Elements\MasterCategoryElement;
use Hmaus\Reynaldo\Parser\RefractParser;
use Nette\Utils\Json;

class Parser
{

    /**
     * @var string
     */
    private $drafter;

    public function __construct(string $drafter)
    {
        $this->drafter = $drafter;
    }

    public function readApib(string $apiBlueprintFile): MasterCategoryElement
    {
        $drafter = new Drafter($this->drafter);
        $drafter->format('json');
        $drafter->input($apiBlueprintFile);
        $output = $drafter->run();

        $apiDescription = Json::decode($output, Json::FORCE_ARRAY);

        $parser = new RefractParser();
        $parseResult = $parser->parse($apiDescription);
        return $parseResult->getApi();
    }
}
