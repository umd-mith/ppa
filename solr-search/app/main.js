require([
  "app",
  "router",
  "modules/solrita"
  ], function (app, AppRouter, Solrita) {

    var solrRecords = new Solrita.SolrRecord();
    var solrPaginatedCollection = new Solrita.SolrPaginatedCollection({}, { records: solrRecords });

    // Define your master router on the application namespace and trigger all
    // navigation from this instance.
    app.router = new AppRouter({
      collection: solrPaginatedCollection
    });

    app.router.initLayout();

    //alert(location.href);
    // Trigger the initial route and enable HTML5 History API support, set the
    // root folder to '/' by default.  Change in app.js.
    Backbone.history.start({
      pushState: app.pushState,
      root: app.root,
      hashChange: false
    });

    
    //Backbone.history.start();

    //Backbone.history.navigate('search/text#?q=tree', { trigger: true, replace: true});

    // All navigation that is relative should be passed through the navigate
    // method, to be processed by the router. If the link has a `data-bypass`
    // attribute, bypass the delegation completely.
    $(document).on("click", "a[href][data-route]", function (evt) {
      // Get the absolute anchor href.
      var href = {
        prop: $(this).prop("href"),
        attr: $(this).attr("href")
      };
      // Get the absolute root.
      var root = location.protocol + "//" + location.host + app.root;

      // Ensure the root is part of the anchor href, meaning it's relative.
      if (href.prop.slice(0, root.length) === root) {
        // Stop the default event to ensure the link will not cause a page
        // refresh.
        evt.preventDefault();

        // `Backbone.history.navigate` is sufficient for all Routers and will
        // trigger the correct events. The Router's internal `navigate` method
        // calls this anyways.  The fragment is sliced from the root.
        Backbone.history.navigate(href.attr, true);
        // jbarroso: Go to the top!
        $("body").scrollTop(0);
      }
    });

  });