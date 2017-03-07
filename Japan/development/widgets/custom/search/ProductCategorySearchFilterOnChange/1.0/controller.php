<?php
namespace Custom\Widgets\search;

class ProductCategorySearchFilterOnChange extends \RightNow\Widgets\ProductCategorySearchFilter {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {

        return parent::getData();

    }

    /**
     * Overridable methods from ProductCategorySearchFilter:
     */
    // protected function getFormattedChain()
    // protected function isChainReadable(array $chain)
    // protected function getReportFilters()
    // protected function setLabelDefaults()
    // protected function getProdcatInfoFromPermissionedHierarchies(array $prodcatHierarchies)
    // protected function updateProdcatsForReadPermissions(array &$prodcats, array $readableProdcatIds, array $readableProdcatIdsWithChildren)
}