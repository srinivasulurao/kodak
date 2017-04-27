<link rel="stylesheet" type="text/css" href="/euf/assets/css/yui/calendar.css" />
<script>
            var YUI_config={"filter":"min","maxURLLength":1024,"root":"3.18.1/","groups":{"site":{"combine":true,"comboBase":"/combo/js?","root":"","modules":{"hoverable":{"path":"hoverable-min.js","requires":["event-hover","node-base","node-event-delegate"]},"search":{"path":"search-min.js","requires":["autocomplete","autocomplete-highlighters","node-pluginhost"]},"api-filter":{"path":"apidocs/api-filter-min.js","requires":["autocomplete-base","autocomplete-highlighters","autocomplete-sources"]},"api-list":{"path":"apidocs/api-list-min.js","requires":["api-filter","api-search","event-key","node-focusmanager","tabview"]},"api-search":{"path":"apidocs/api-search-min.js","requires":["autocomplete-base","autocomplete-highlighters","autocomplete-sources","escape"]}}}}};
            if (location.protocol.indexOf('https') > -1) {
                YUI_config.comboBase = 'https://yui-s.yahooapis.com/combo?';
                YUI_config.combine = true;
            }
            </script>

<? $this->addJavaScriptInclude(\RightNow\Utils\Url::getYUICodePath('calendar/calendar-min.js'));?>

<input type="text" name="cal1Date" id="cal1Date" autocomplete="off"/>&nbsp;-to-&nbsp;<input type="text" name="cal2Date" id="cal2Date" autocomplete="off"/>  
<div id="cal1Container"></div>
