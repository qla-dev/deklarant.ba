 
  $(document).on('select2:open', function(e) {
    // give Select2 a moment to render its search input
    setTimeout(() => {
      // find all open search fields
      $('.select2-container--open .select2-search__field').each(function() {
        this.focus();
      });
    }, 50);
  });