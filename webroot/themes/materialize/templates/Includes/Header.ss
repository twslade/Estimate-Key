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

    var substringMatcher = function(strs) {
        return function findMatches(q, cb) {
            var matches, substringRegex;

            // an array that will be populated with substring matches
            matches = [];

            // regex used to determine if a string contains the substring `q`
            substrRegex = new RegExp(q, 'i');

            // iterate through the pool of strings and for any string that
            // contains the substring `q`, add it to the `matches` array
            $.each(strs, function(i, str) {
                if (substrRegex.test(str.Name)) {
                    matches.push(str);
                }
            });

            cb(matches);
        };
    };

    var searchJsonData = $getEstimatesForSearchJSON;

    $('.typeahead').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    },{
        name: 'estimates',
        source: substringMatcher(searchJsonData)
    });

</script>
