<?php
declare(strict_types=1);

namespace RadekDvorak\ApibJsonValidator;

use Hmaus\Reynaldo\Elements\BaseElement;
use Hmaus\Reynaldo\Elements\MasterCategoryElement;

class BlueprintWalker implements BlueprintWalkerInterface
{

    /**
     * @inheritdoc
     */
    public function walkValidations(MasterCategoryElement $api): \Generator
    {
        $siblingCounts = new \SplQueue();
        $childParentMap = new \SplObjectStorage();

        $queue = new \SplQueue();
        $queue->push($api);

        $lastBody = null;
        $lastSchema = null;
        $familySize = 1;

        while (!$queue->isEmpty()) {
            $el = $queue->dequeue();
            $familySize--;

            if (!$el instanceof BaseElement) {
                continue;
            }

            $metadata = (array)$el->getMetaData();
            if (isset($metadata['classes'])) {
                if (in_array('messageBodySchema', $metadata['classes'])) {
                    $lastSchema = $el;
                }
            }
            if (isset($metadata['classes']) && in_array('messageBody', $metadata['classes'])) {
                $lastBody = $el;
            }

            if ($lastBody !== null && $lastSchema !== null) {
                // tadá
                $bodyParents = $this->findParents($lastBody, $childParentMap);

                yield new ValidationRequest($lastBody, $lastSchema, $bodyParents);
                $lastBody = $lastSchema = null;
            }

            $children = $el->getContent();
            if (is_array($children)) {
                $siblingCounts->enqueue(count($children));
                foreach ($children as $child) {
                    $queue->enqueue($child);
                    if (!is_array($child)) {
                        $childParentMap->attach($child, $el);
                    }
                }
            }

            if ($familySize === 0) {
                $lastBody = $lastSchema = null;
                $familySize = $siblingCounts->dequeue();
            }
        }
    }

    /**
     * @param BaseElement $firstChild
     * @param \SplObjectStorage $childParentMap
     * @return BaseElement[]
     */
    private function findParents($firstChild, \SplObjectStorage $childParentMap): array
    {
        $parents = [];
        $childEl = $firstChild;

        while (isset($childParentMap[$childEl])) {
            $parents[] = $childParentMap[$childEl];
            $childEl = $childParentMap[$childEl];
        }
        return array_reverse($parents);
    }
}
