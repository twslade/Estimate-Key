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
                    <li>
                        <div class="collapsible-header">
                            Platform
                        </div>
                        <div class="collapsible-body">
                            <ul>
                                <li>
                                    <input type="checkbox" name="magento">
                                    <label for="magento">Magento (10)</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="magento2">
                                    <label for="magento2">Magento 2 (2)</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="sfcc">
                                    <label for="sfcc">Sales Force Commerce Cloud (3)</label>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="collapsible-header">
                            Clients
                        </div>
                        <div class="collapsible-body">
                            <ul>
                                <li>
                                    <input type="checkbox" name="xyz">
                                    <label for="xyz">X Y Z Corp (8)</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="google">
                                    <label for="google">Google (2)</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="shoes">
                                    <label for="shoes">Shoes R Us (3)</label>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        <div class="col s9">
            <h5>Latest Estimates</h5>
            <div class="divider"></div>

            <% if $GetEstimates.MoreThanOnePage %>
                <div class="row">
                    <div class="col s9 center">
                        <ul class="pagination">
                        <% if $GetEstimates.NotFirstPage %>
                            <li><a href="$GetEstimates.PrevLink" class=""><i class="material-icons">chevron_left</i></a></li>
                        <% end_if %>
                        <% loop $GetEstimates.Pages %>
                            <% if $CurrentBool %>
                                <li class="active"><a href="#!">$PageNum</a></li>
                            <% else %>
                                <% if $Link %>
                                    <li class="waves-effect"><a href="$Link">$PageNum</a></li>
                                <% end_if %>
                            <% end_if %>
                        <% end_loop %>
                        <% if $GetEstimates.NotLastPage %>
                            <li><a href="$GetEstimates.NextLink" class=""><i class="material-icons">chevron_right</i></a></li>
                        <% end_if %>
                        </ul>
                    </div>
                </div>
            <% end_if %>

            
                <!-- Todo: Add row inside loop on mod 3 -->
            <% loop $GetEstimates %>
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

<script>
    $(document).ready(function(){
        $('.collapsible').collapsible('open', 0);
    });
</script>
</body>
</html>
