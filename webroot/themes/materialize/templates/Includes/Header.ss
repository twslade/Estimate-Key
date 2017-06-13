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
                                <div style="position: relative">
                                    <div class="preloader-wrapper small js-search-preloader" style="width: 20px; height: 20px; position: absolute; right: 0; margin-top: 15px">
                                        <div class="spinner-layer spinner-blue-only">
                                            <div class="circle-clipper left">
                                                <div class="circle"></div>
                                            </div><div class="gap-patch">
                                            <div class="circle"></div>
                                        </div><div class="circle-clipper right">
                                            <div class="circle"></div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <input
                                        type="search"
                                        name="Search"
                                        value="$GetSearchText"
                                        placeholder="Search"
                                        required
                                        id="SearchForm_SearchForm_Search"
                                        data-url="/estimate/ajax"
                                        class="search-select"
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
    $(document).ready(function(){

        var searchSelect = $('.search-select');
        var searchPreloader = $('.js-search-preloader');

        searchSelect.autocompleteajax(
                {
                    ajax: {
                        url: searchSelect.attr('data-url'),
                        method: 'post',
                        dataType: 'json',
                        beforeSend: function (jqHR, settings) {
                            settings.url = settings.url + '?Search=' + $('.search-select').val();
                            if (!searchPreloader.hasClass('active')) {
                                searchPreloader.addClass('active');
                            }
                        },
                        error: null
                    },
                    callback: function (res) {
                        if (searchPreloader.hasClass('active')) {
                            searchPreloader.removeClass('active');
                        }
                    },
                    minLength: 2,
                    delay: 200
                }
        );
    });
</script>