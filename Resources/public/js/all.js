"use strict";

var Globals = {
  behaviours: {},
  settings: {},
  attach: function() {
    for (var behaviour in Globals.behaviours) {
      Globals.behaviours[behaviour]();
    }
  }
};

Global.behaviours.chosen = function () {
  $('select.chosen').chosen({
    allow_single_deselect: true
  });
};

Global.behaviours.filter_lists = function () {
  $('.filter').once().each(function () {
    var options = {
      valueNames: $(this).data('filter-values'),
      listClass: 'filter-list',
      searchClass: 'filter-search'
    };
    var list = new List(this, options);
  });
};

Global.behaviours.collection_item_add = function () {
  // Get all form collection elements and iterate over them.
  $('.form-collection').once().each(function() {
    var collection_holder = this;

    // Count the collection items for index.
    $(this).data('index', $(this).find('.collection-items').children().length);

    // Add click function to the add link.
    $(this).find('.collection-item-add').last().click(function (e) {
      e.preventDefault();

      // Get the prototype and the index.
      var form = $(collection_holder).data('prototype');
      var index = $(collection_holder).data('index');

      // Replace the index and increment in holder.
      form = form.replace(/__name__/g, index);
      $(collection_holder).data('index', $(collection_holder).data('index') + 1);

      // Create new collection item form and add.
      var new_li = $('<li class="list-group-item collection-item"></li>').append(form);
      $(collection_holder).children('.collection-items').append(new_li);

      // Reattach behaviours.
      Globals.attach();
    });
  });
};

Global.behaviours.collection_item_remove = function () {
  // Bind remove action to collection items.
  $('li.collection-item').once().each(function (index) {
    var li = $(this);
    var collection_holder = $(this).closest('.form-collection');

    $(li).find('.collection-item-remove').last().click(function (e) {
      e.preventDefault();

      // Remove li and decrement the index.
      $(li).remove();
      $(collection_holder).data('index', $(collection_holder).data('index') - 1);

      // Reattach behaviours.
      Globals.attach();
    });
  });
};
