<?php
declare(strict_types=1);

/**
 * @testCase
 */

namespace RadekDvorak\ApibJsonValidator;

use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/bootstrap.php';

class DummyTest extends TestCase
{

    public function testTrue()
    {
        Assert::true(true);
    }

}

(new DummyTest())->run();
