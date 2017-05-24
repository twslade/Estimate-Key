<header>
    <nav>
        <div class="nav-wrapper">
            <div class="row">
                <div class="col s8">
					<!-- Todo: Add real search form -->
					<% if $SearchForm %>
							$SearchForm
					<% end_if %>
                    <form>
                        <div class="input-field">
                            <input id="search" type="search" required>
                            <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                            <i class="material-icons">close</i>
                        </div>
                    </form>
                </div>
                <div class="col s4">
					<% include Navigation %>
                    <a href="$BaseHref" class="brand-log right">$SiteConfig.Title</a>
                </div>

            </div>
        </div>
    </nav>
</header>