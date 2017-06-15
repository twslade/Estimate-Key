<header>
    <nav class="z-depth-0">
        <div class="nav-wrapper">
            <div class="row">
                <div class="col s6">
                    <!-- Todo: Add real search form -->
                    <% if $SearchForm %>
                        <form id="SearchForm_SearchForm" action="/" method="get" enctype="application/x-www-form-urlencoded" _lpchecked="1">
                            <div class="input-field">
                                <p id="SearchForm_SearchForm_error" class="message " style="display: none"></p>
                                <input
                                        type="search"
                                        name="Search"
                                        value="$GetSearchText"
                                        placeholder="Search"
                                        required
                                        id="SearchForm_SearchForm_Search"
                                        data-url="/estimate/ajax"
                                        class="search-select autocomplete"
                                >
                                <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                                <i class="material-icons">close</i>
                            </div>
                        </form>
                    <%--$SearchForm--%>
                    <% end_if %>
                </div>
                <div class="col s6">
                    <a href="$BaseHref" class="brand-log right">$SiteConfig.Title</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<script>
    var searchJsonData;
    $(document).ready(function(){
        searchJsonData = $getEstimatesForSearchJSON;

            $('input.autocomplete').autocomplete({
                data: searchJsonData,
                limit: 20, // The max amount of results that can be shown at once. Default: Infinity.
                onAutocomplete: function(val) {
                    // Callback function when value is autcompleted.
                },
                minLength: 1, // The minimum length of the input for the autocomplete to start. Default: 1.
            });
    });
</script>