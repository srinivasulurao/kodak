<?php
namespace Custom\Widgets\search;

class ProductCategorySearchFilterOnChange extends \RightNow\Widgets\ProductCategorySearchFilter {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {

        return parent::getData();

    }
    protected function getLinkedID ($type = 'Product') {
        $current_value=($this->attrs['default_value']->value)?$this->attrs['default_value']->value:$filters[$filterKey]->filters->data[0];
        $filtersKey = ($type === 'Product') ? 'p' : 'c';
        $filters = $this->getReportFilters();
        $explodedFilters = explode(',', $current_value);
        return end($explodedFilters) ?: null;
    }

    protected function getValue ($filterTypeKey) {
        //Get the active filters on the page to determine the default value
        $filters = $this->getReportFilters();
        $current_value=($this->attrs['default_value']->value)?$this->attrs['default_value']->value:$filters[$filterKey]->filters->data[0];

        $filterType = strtolower($this->data['attrs']['filter_type']);
        $filterKey = $filterType[0];
        $this->data['js']['filter'] = array(
            'type'  => $filterType,
            'key'   => $filterKey,
            'value' => $current_value
        );

        \RightNow\Utils\Url::setFiltersFromAttributesAndUrl($this->data['attrs'], $filters);
        if(!$filters[$filterTypeKey]->filters->optlist_id) {
            echo $this->reportError(sprintf(\RightNow\Utils\Config::getMessage(FILTER_PCT_S_EXIST_REPORT_PCT_D_LBL), $this->data['attrs']['filter_type'], $this->data['attrs']['report_id']));
            return false;
        }

        $this->populateJSStateForReport($filters, $filterTypeKey);

        return $current_value;
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