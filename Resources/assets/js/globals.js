"use strict";

var Chosen = require('chosen-js');
var List = require('list.js');

var Globals = {
  behaviours: {
    chosen: function() {
      $('select.chosen').chosen({
        allow_single_deselect: true
      });
    },
    filter_lists: function () {
      $('.filter').once().each(function () {
        var options = {
          valueNames: $(this).data('filter-values'),
          listClass: 'filter-list',
          searchClass: 'filter-search'
        };
        var list = new List(this, options);
      });
    }
  },
  settings: {},
  attach: function() {
    for (var behaviour in Globals.behaviours) {
      Globals.behaviours[behaviour]();
    }
  }
};

module.exports = Globals;
