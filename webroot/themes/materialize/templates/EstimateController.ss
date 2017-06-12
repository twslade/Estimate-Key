<!DOCTYPE html>
<html>
    <% include Head %>
<body class="$ClassName<% if not $Menu(2) %> no-sidebar<% end_if %> estimate-view" <% if $i18nScriptDirection %>dir="$i18nScriptDirection"<% end_if %>>

<% include Header %>
    <% with $Estimate %>
        <div class="row">
            <div class="estimate-left col s12 m3 xl2">
                <h1 class="hide-on-large-only">$Name</h1>

                <div class="row hours">
                    <div class="card-panel col s6 light-blue darken-3 z-depth-0">
                        <div class="card-content white-text ">
                            <span class="card-title">Hours:</span>
                            <h5>$TotalHours</h5>
                        </div>
                    </div>

                    <div class="card-panel col s6 grey white-text z-depth-0">
                        <div class="card-content white-text ">
                            <span class="card-title">ROM</span>
                            <h5>$RomLow - $RomHigh</h5>
                        </div>
                    </div>
                </div>

            </div>
            <div class="estimate-desc col s12 m7 xl9">
                <h1 class="hide-on-med-and-down">$Name</h1>

                <div class="divider"></div>

                <div id="description" class="section scrollspy">
                    <h3 class="light-blue-text text-darken-3">Description</h3>
                    <p class="flow-text">$Description</p>
                </div>

                <div id="stories" class="section scrollspy">
                    <h3 class="light-blue-text text-darken-3">Stories</h3>

                    <% loop $Stories %>
                        <h5>$Name</h5>
                        <table class="striped responsive-table">
                            <thead>
                            <tr>
                                <th>Number of Hours</th>
                                <th>Description</th>
                            </tr>
                            </thead>
                            <tbody>

                                <% loop $LineItems %>
                                <tr>
                                    <td>$NumHours</td>
                                    <td>$Description</td>
                                </tr>
                                <% end_loop %>
                            <tr><td><b>$TotalHours Hours</b></td><td></td></tr>
                            </tbody>
                        </table>
                    <% end_loop %>
                </div>
            </div>
            <div class="estimate-nav col m2 xl1 hide-on-med-and-down">
                <div class="toc-wrapper pin-top">
                    <ul class="section table-of-contents">
                        <li><a data-new="#description">Description</a></li>
                        <li><a data-new="#stories">Stories</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <%--

        <h3>Requirements</h3>
        <div class="divider"></div>

        <h4>Business Requirements</h4>
        $BusinessRequirements

        <h4>Functional Requirements</h4>
        $FunctionalRequirements

        <div class="divider"></div>

        <h3>Risks</h3>
        <div class="divider"></div>
        <div class="row">
            <div class="col s12">
                <% loop $Risks %>
                    <ul class="collection">
                        <li class="collection-item">
                            <!-- @todo: Risk Type Fix -->
                            <span class="title">$Name</span>
                        </li>
                        <li class="collection-item">
                            <b>Type:</b> <% loop $RiskTypes %>$Name <% end_loop %>
                        </li>
                        <li class="collection-item">
                            $Description
                        </li>
                        <li class="collection-item">
                            <b>Mitigation Plan:</b> $MitigationPlan
                        </li>
                        <li class="collection-item">
                            <b>Mitigation Cost:</b> $MitigationCost
                        </li>
                        <li class="collection-item">
                            <b>Potential Cost:</b> $PotentialCost
                        </li>
                        <li class="collection-item">
                            <b>Probability:</b> $Probability
                        </li>


                    </ul>
                <% end_loop %>
            </div>
        </div>
        <div class="divider"></div>
        <h3>Confidence Levels</h3>
        <div class="row">
            <div class="col s4">
                <ul class="collection">
                    <li class="collection-item center"><b>Technical Confidence</b></li>
                    <li class="collection-item center">$GetTechnicalConfidence</li>
                </ul>
            </div>
            <div class="col s4">
                <ul class="collection">
                    <li class="collection-item center"><b>Budget Confidence</b></li>
                    <li class="collection-item center">$GetBudgetConfidence</li>
                </ul>
            </div>
            <div class="col s4">
                <ul class="collection">
                    <li class="collection-item center"><b>Schedule Confidence</b></li>
                    <li class="collection-item center">$GetScheduleConfidence</li>
                </ul>
            </div>
        </div>
 --%>
    <% end_with %>
<% include Footer %>
</body>
</html>
