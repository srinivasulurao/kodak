<rn:meta controller_path="custom/CIHSearch/SearchTabSet" js_path="custom/CIHSearch/SearchTabSet" />
<? $this->addJavaScriptInclude(getYUICodePath('yahoo-dom-event/yahoo-dom-event.js'));?>
<? $this->addJavaScriptInclude(getYUICodePath('dragdrop/dragdrop-min.js'));?>
<? $this->addJavaScriptInclude(getYUICodePath('element/element-min.js'));?>
<? $this->addJavaScriptInclude(getYUICodePath('tabview/tabview-min.js'));?>

<div id="searchtabset" class="yui-navset">
  <ul class="yui-nav">
        <li class="selected"><a href="#custSearch"><em>By Customer</em></a></li>
        <li><a href="#prodSearch"><em>By Product</em></a></li>
  </ul>            
  <div class="yui-content">
        <div id="custSearch">
          <rn:widget path="custom/CIHSearch/CustomerSearch" alt_text"Product ID" label_hint="Enter ID" report_page_url="/app/cih/search_home" />
        </div>
        <div id="prodSearch">
        <rn:widget path="custom/CIHSearch/SimpleProductSearch" alt_text"Product ID" label_hint="Enter Search Value" report_page_url="/app/cih/search_home" />
        </div>
  </div>
</div>
<div id="scroll_target"></div>
