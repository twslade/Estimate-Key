<header>
    <nav>
        <div class="nav-wrapper">
            <div class="row">
                <div class="col s8">
					<!-- Todo: Add real search form -->
					<% if $SearchForm %>
                        <form id="SearchForm_SearchForm" action="/home/SearchForm" method="get" enctype="application/x-www-form-urlencoded" _lpchecked="1">
                            <div class="input-field">
                                <p id="SearchForm_SearchForm_error" class="message " style="display: none"></p>
                                <input type="search" name="Search" placeholder="Search" required id="SearchForm_SearchForm_Search search">
                                <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                                <i class="material-icons">close</i>
                            </div>
                        </form>
							<%--$SearchForm--%>
					<% end_if %>
                </div>
                <div class="col s4">
					<% include Navigation %>
                    <a href="$BaseHref" class="brand-log right">$SiteConfig.Title</a>
                </div>

            </div>
        </div>
    </nav>
</header>