<?php
declare(strict_types=1);

/**
 * @testCase
 */

namespace RadekDvorak\ApibJsonValidator;

use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/bootstrap.php';

class ExplorationTest extends TestCase
{

    public function testTrue()
    {
        $drafter = __DIR__ . '/../resources/drafter';
        $blueprint = __DIR__ . '/../resources/test.apib';

        $parser = new Parser($drafter);
        $api = $parser->readApib($blueprint);

        $walker = new BlueprintWalker();
        $schemaValidator = new SchemaValidator();

        $results = [];
        foreach ($walker->walkValidations($api) as $request) {
            try {
                $result = $schemaValidator->validate($request);
                if (!$result->isIsOk()) {
                    $results[] = implode(PHP_EOL, $result->getMessages());
                }
            } catch (JsonDecodingException $e) {
                $results[] = $e->getMessage();
            }
        }

        Assert::same([], $results);
    }

}

(new ExplorationTest())->run();
