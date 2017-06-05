<!DOCTYPE html>
<html>
<% include Head %>

<body class="$ClassName<% if not $Menu(2) %> no-sidebar<% end_if %>" <% if $i18nScriptDirection %>dir="$i18nScriptDirection"<% end_if %>>

<% include Header %>
<div class="container">

    <div class="row">
        <div class="col s3">
            <h5>Layered Navigation</h5>

            <ul class="collapsible" data-collapsible="expandable">
                <% loop $GetActiveFilters %>
                    <li>
                        <div class="collapsible-header active">
                            Active - $label
                        </div>
                        <div class="collapsible-body">
                            <ul>
                                <% loop $values %>
                                    <li>
                                        <input type="checkbox" name="platform-$filter.ID">
                                        <label for="platform-$filter.ID"><a href="">$filter.Name</a></label>
                                    </li>
                                <% end_loop %>
                            </ul>
                        </div>
                    </li>
                <% end_loop %>
            </ul>

            <ul class="collapsible" data-collapsible="expandable">
                <% loop $GetLeftNav %>
                    <li>
                        <div class="collapsible-header active">
                            $className
                        </div>
                        <div class="collapsible-body">
                            <ul>
                                <% loop $Up.getMembers($className) %>
                                    <li>
                                        <input type="checkbox" name="platform-$ID">
                                        <label for="platform-$ID"><a href="$Up.GetFilterLink($className, $ID)">$Name</a></label>
                                    </li>
                                <% end_loop %>
                            </ul>
                        </div>
                    </li>
                <% end_loop %>
            </ul>
        </div>
        <div class="col s9">
            <h5>Latest Estimates</h5>
            <div class="divider"></div>

            <% if $Results.MoreThanOnePage %>
                <div class="row">
                    <div class="col s9 center">
                        <ul class="pagination">
                        <% if $Results.NotFirstPage %>
                            <li><a href="$Results.PrevLink" class=""><i class="material-icons">chevron_left</i></a></li>
                        <% end_if %>
                        <% loop $Results.Pages %>
                            <% if $CurrentBool %>
                                <li class="active"><a href="#!">$PageNum</a></li>
                            <% else %>
                                <% if $Link %>
                                    <li class="waves-effect"><a href="$Link">$PageNum</a></li>
                                <% end_if %>
                            <% end_if %>
                        <% end_loop %>
                        <% if $Results.NotLastPage %>
                            <li><a href="$Results.NextLink" class=""><i class="material-icons">chevron_right</i></a></li>
                        <% end_if %>
                        </ul>
                    </div>
                </div>
            <% end_if %>

            
                <!-- Todo: Add row inside loop on mod 3 -->
            <% loop $Results %>
                <% if $Modulus(3) == 0 %>
                    <div class="row">
                <% end_if %>
                <div class="col s3">
                    <div class="card">
                        <div class="cart-content blue-grey-text">
                            <span class="card-title">$Name</span>
                        </div>
                        <div class="card-action">
                            <a href="$Link">View</a>
                        </div>
                    </div>
                </div>
                <% if $Modulus(3) == 0 %>
                </div>
                <div class="divider"></div>
                <% end_if %>
            <% end_loop %>
        </div>
    </div>
</div>
<% include Footer %>
<!--Import jQuery before materialize.js-->
<% require javascript('framework/thirdparty/jquery/jquery.js') %>
<% require themedJavascript('materialize') %>

</body>
</html>
