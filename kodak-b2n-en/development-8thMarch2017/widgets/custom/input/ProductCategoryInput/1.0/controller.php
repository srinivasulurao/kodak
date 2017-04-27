<?php
namespace Custom\Widgets\input;

class ProductCategoryInput extends \RightNow\Widgets\ProductCategoryInput {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {

        return parent::getData();

    }

    /**
     * Overridable methods from ProductCategoryInput:
     */
    // protected function getDefaultChain()
    // protected function pruneEmptyPaths(array $hierMap, array $defaultChain = array())
    // protected function buildListOfPermissionedProdcatIds()
    // protected function getProdcatInfoFromPermissionedHierarchies(array $prodcatHierarchies)
    // protected function updateProdcatsForReadPermissions(array &$prodcats, array $readableProdcatIds, array $readableProdcatIdsWithChildren)
}