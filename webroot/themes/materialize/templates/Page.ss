<!DOCTYPE html>
<html>
<% include Head %>

<body class="$ClassName<% if not $Menu(2) %> no-sidebar<% end_if %>" <% if $i18nScriptDirection %>dir="$i18nScriptDirection"<% end_if %>>

<% include Header %>
<%-- <div class="container"> --%>
    <% if $Form %>
        $Form
    <% else %>
        <div class="row">
            <% if $Results.TotalItems == 0 %>
                <div class="col s12">
                    <h1>No Estimates found...</h1>
                </div>
            <% else %>
            <div class="layered-nav col s12 m3 xl2">
                <% if $GetActiveFilters %>
                    <ul class="collapsible" data-collapsible="expandable">
                        <%--Get list of currently applied/active filters--%>
                        <% loop $GetActiveFilters %>
                            <li>
                                <div class="collapsible-header active">
                                    Active - $label
                                </div>
                                <div class="collapsible-body">
                                    <ul>
                                        <% loop $values %>
                                            <li>
                                                <%--Remove filter from link if it is already active/applied--%>
                                                <a href="$Up.Up.GetFilterLink($Up.label, $ID, true)" class="remove-filter">
                                                    <i class="tiny material-icons">delete</i>
                                                    $filter.Name
                                                </a></label>
                                            </li>
                                        <% end_loop %>
                                    </ul>
                                </div>
                            </li>
                        <% end_loop %>
                    </ul>
                <% end_if %>

                <ul class="collapsible" data-collapsible="expandable">
                    <%--Get available filters--%>
                    <% loop $GetLeftNav %>
                        <% if $Up.getSanitizeMembers($className).count > 0 %>
                            <li>
                                <div class="collapsible-header active">
                                    $className
                                </div>
                                <div class="collapsible-body">
                                    <ul>
                                        <% loop $Up.getSanitizeMembers($className) %>
                                            <li>
                                                <a class="label" href="$Up.GetFilterLink($filter.ClassName, $filter.ID)">
                                                    $filter.Name ($Up.GetFilterCount($filter.ClassName, $filter.ID))
                                                </a>
                                            </li>
                                        <% end_loop %>
                                    </ul>
                                </div>
                            </li>
                        <% end_if %>
                    <% end_loop %>
                </ul>
            </div>

            <div class="estimate-list col s12 m9 xl10">
                <h5>Estimates</h5>
                <div class="divider"></div>

                <%--Pagination--%>
                <% if $Results.MoreThanOnePage %>
                    <div class="row">
                        <div class="col s10 offset-s1 center">
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


                <%--Loop over estimates--%>
                <div class="flex-row">
                    <% loop $Results %>
                        <div class="flex-col flex-xs12 flex-s6 flex-l4 flex-xl3 card-parent">
                            <div class="card hoverable <% loop $Platforms %>$Top.getCssClass($Name)<% end_loop %>">
                                <div class="card-content">
                                    <span class="card-title">
                                        <a href="$Link">$Name</a>
                                    </span>
                                    <p>$Description</p>

                                    <div class="chip <% loop $Platforms %>$Top.getCssClass($Name)<% end_loop %>">
                                        <% loop $Platforms %>$Name<% end_loop %>
                                    </div>
                                </div>
                                <div class="card-action">
                                    <a href="$Link">View</a>
                                    <a href="#" class="activator right"><i class="material-icons right">more_vert</i></a>
                                </div>
                                <div class="card-reveal">
                                    <span class="card-title">
                                        <strong>Confidence Levels</strong>
                                        <i class="close material-icons right">close</i>
                                    </span>

                                    <div class="row">
                                        <div class="col center">
                                            <p>Tech</p>
                                            <div class="chip $Top.getCssClass($TechnicalConfidence)">
                                                $TechnicalConfidence
                                            </div>
                                        </div>
                                        <div class="col center">
                                            <p>Budget</p>
                                            <div class="chip $Top.getCssClass($BudgetConfidence)">
                                                $BudgetConfidence
                                            </div>
                                        </div>
                                        <div class="col center">
                                            <p>Schedule</p>
                                            <div class="chip $Top.getCssClass($ScheduleConfidence)">
                                                $ScheduleConfidence
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <% end_loop %>
                </div>
            </div>
        </div>
            <%-- </div> --%>
        <% end_if %>

        <% end_if %>

<% include Footer %>
</body>
</html>
