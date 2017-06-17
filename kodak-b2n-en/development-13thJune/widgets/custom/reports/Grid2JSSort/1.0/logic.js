RightNow.namespace('Custom.Widgets.reports.Grid2JSSort');
Custom.Widgets.reports.Grid2JSSort = RightNow.Widgets.extend({
	
    /**
     * Widget constructor.
     */
    constructor: function() {
		
      this._showTable(this.data.js.cols,this.data.js.rows);
    
	
    },//Constructor Ends here.

    _showTable: function (cols,rows) {
		
		console.log(rows);

				YUI().use('datatable', function (Y) {
					table = new Y.DataTable({
						columns: cols,
						data:rows,
						highlightMode:"row",
						selectionMode: 'row'
					});
					//this.siteDataTable = new YAHOO.widget.DataTable("div_sitetable2", siteColumnDefs, siteDataSource);
					document.getElementsByClassName("rn_Grid2JSSort_Datatable")[0].innerHTML="";
					table.render(".rn_Grid2JSSort_Datatable");
					//this.productDataTable = new YAHOO.widget.DataTable("rn_" + this.instanceID + "_div_producttable", productColumnDefs, productDataSource);
					product_table=table;
			    });

    }
})