<?php

namespace app\components\helpers;


class RecursiveTree
{
    /**
     * @var string
     */
    private  static  $depthAttribute = 'depth';
    /**
     * @var string
     */
    private  static  $labelAttribute = 'alias';
    /**
     * @var string
     */
    private  static  $childrenOutAttribute = 'children';
    /**
     * @var string
     */
    private  static  $labelOutAttribute = 'alias';

    /**
     * @var string
     */
    private  static  $hrefOutAttribute = 'href';
    /**
     * @var null|callable
     */
    private  static  $makeLinkCallable = null;

    public static function tree(array $childrenArray)
    {
        $makeNode = function ($node) {
            $newData = [
                self::$labelOutAttribute => $node[self::$labelAttribute],
            ];
            return array_merge($node, $newData);
        };

        // Trees mapped
        $tree = [];

        if (count($childrenArray) > 0) {
            foreach ($childrenArray as &$col) $col = $makeNode($col);

            // Node Stack. Used to help building the hierarchy
            $stack = [];

            foreach ($childrenArray as $node) {
                $item = $node;
                $item[self::$childrenOutAttribute] = [];

                // Number of stack items
                $l = count($stack);

                // Check if we're dealing with different levels
                while ($l > 0 && $stack[$l - 1][self::$depthAttribute] >= $item[self::$depthAttribute]) {
                    array_pop($stack);
                    $l--;
                }

                // Stack is empty (we are inspecting the root)
                if ($l == 0) {
                    // Assigning the root node
                    $i = count($tree);
                    $tree[$i] = $item;
                    $stack[] = &$tree[$i];
                } else {
                    // Add node to parent
                    $i = count($stack[$l - 1][self::$childrenOutAttribute]);
                    $stack[$l - 1][self::$childrenOutAttribute][$i] = $item;
                    $stack[] = &$stack[$l - 1][self::$childrenOutAttribute][$i];
                }
            }
        }

        return $tree;
    }
}
