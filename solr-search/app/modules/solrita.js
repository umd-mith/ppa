define([
  "app",
  // Model
  'modules/solrita/models/item',
  'modules/solrita/models/record',
  // Collections
  'modules/solrita/collections/items',
  // Views
  'modules/solrita/views/search',
  'modules/solrita/views/results',
  'modules/solrita/views/result',
  'modules/solrita/views/records',
  'modules/solrita/views/facet-collection',
  'modules/solrita/views/facet-author',
  'modules/solrita/views/facet-city',
  'modules/solrita/views/filters',
  'modules/solrita/views/results-header',
  'modules/solrita/views/pagination',
  'modules/solrita/views/option',
  'modules/solrita/views/num',
  'modules/solrita/views/sort'

  ], function (app, SolrItem, SolrRecord, SolrPaginatedCollection,
    SearchView, ResultsView, ResultView, RecordsView,
    FacetCollectionView, FacetAuthorView, FacetCityView, FiltersView, ResultsHeaderView, PaginationView, OptionView, NumView, SortView) {

    // Create a new module
    var Solrita = app.module();

    Solrita.SolrItem = SolrItem;
    Solrita.SolrRecord = SolrRecord;
    Solrita.SolrPaginatedCollection = SolrPaginatedCollection;

    Solrita.Views = {};
    Solrita.Views.SearchView = SearchView;
    Solrita.Views.ResultsView = ResultsView;
    Solrita.Views.ResultView = ResultView;
    Solrita.Views.RecordsView = RecordsView;
    Solrita.Views.FacetCollectionView = FacetCollectionView;
    Solrita.Views.FacetAuthorView = FacetAuthorView;
    Solrita.Views.FacetCityView = FacetCityView;
    Solrita.Views.FiltersView = FiltersView;
    Solrita.Views.ResultsHeaderView = ResultsHeaderView;
    Solrita.Views.PaginationView = PaginationView;
    Solrita.Views.OptionView = OptionView;
    Solrita.Views.NumView = NumView;
    Solrita.Views.SortView = SortView;

    return Solrita;

  });
