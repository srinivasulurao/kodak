RightNow.Widget.TopicWords2 = function(data, instanceID){
    this.data = data;
    this.instanceID = instanceID;
    RightNow.Event.subscribe("evt_reportResponse", this._onTopicWordsUpdate, this);
};

RightNow.Widget.TopicWords2.prototype = {
    /**
     * Event handler for when topic words have been updated because a
     * search has been performed
     * @param type String Event name
     * @param args Object Event arguments
     */
    _onTopicWordsUpdate: function(type, args)
    {
//	alert ('onTopicWordsUpdate');
        var eventObject = args[0];
        var topicWordsHtml = "";
        var topicWordsDomList = document.getElementById("rn_" + this.instanceID + "_List");
        if(!topicWordsDomList)
        {
            var root = document.getElementById("rn_" + this.instanceID);
            if(root)
            {
                topicWordsDomList = document.createElement("DL");
                topicWordsDomList.id = "rn_" + this.instanceID + "_List";
                root.appendChild(topicWordsDomList);
            }
        }
        if(topicWordsDomList)
        {
            var Url = RightNow.Url;
            if(eventObject && eventObject.data && eventObject.data.topic_words && eventObject.data.topic_words.length)
            {
                var topicWordItems = eventObject.data.topic_words;
                var linkString = Url.buildUrlLinkString(eventObject.filters.allFilters, this.data.attrs.add_params_to_url);
//				alert(linkString);
                for(var i = 0; i < topicWordItems.length; i++)
                {
                    if (!Url.isExternalUrl(topicWordItems[i].url))
                        topicWordItems[i].url += linkString;
                    var displayIcon = (this.data.attrs.display_icon) ? topicWordItems[i].icon : "";
                    topicWordsHtml += "<dt>" + displayIcon + "<a href='" + topicWordItems[i].url + "' target='" + this.data.attrs.target + "' />" + topicWordItems[i].title + "</a></dt><dd>" + topicWordItems[i].text + "</dd>";
                }
                topicWordsDomList.innerHTML = topicWordsHtml;
                YAHOO.util.Dom.removeClass("rn_" + this.instanceID, "rn_Hidden");
            }
            else
            {
                topicWordsDomList.innerHTML = "";
                YAHOO.util.Dom.addClass("rn_" + this.instanceID, "rn_Hidden");
            }
        }
    }
};