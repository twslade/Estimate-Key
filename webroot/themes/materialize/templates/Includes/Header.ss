<header>
    <nav class="z-depth-0">
        <div class="nav-wrapper">
            <div class="row">
                <div class="col s6">
                    <!-- Todo: Add real search form -->
                    <% if $SearchForm %>
                        <form id="SearchForm_SearchForm" class="search" action="/" method="get" enctype="application/x-www-form-urlencoded" _lpchecked="1">
                            <div class="input-field">
                                <p id="SearchForm_SearchForm_error" class="message " style="display: none"></p>
                                <input
                                        type="text"
                                        name="Search"
                                        value="$GetSearchText"
                                        placeholder="Search"
                                        required
                                        id="SearchForm_SearchForm_Search"
                                        class="search-select typeahead"
                                >
                                <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                                <%-- <i class="material-icons">close</i> --%>
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

    var searchJsonData = $getEstimatesForSearchJSON;
    var searchJsonArray = Object.keys(searchJsonData).map(
        function(val) {
            return searchJsonData[val].Clients + ' - ' +
                    searchJsonData[val].Name + ' - ' +
                    searchJsonData[val].Platforms + '%' + val + '%';
        }
    );

    var estimates = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: searchJsonArray
    });

    $('.typeahead').typeahead({
        hint: true,
        highlight: true,
        minLength: 1,
    },{
        name: 'estimates',
        source: estimates
    });
    $('.typeahead').bind('typeahead:select', function(ev, suggestion) {
        var start = suggestion.indexOf('%') + 1;
        var stop = suggestion.indexOf('%',start);
        var selectedId  = suggestion.substring(start,stop);
        window.location.href='./estimate/view/' + selectedId;
    });

</script>
