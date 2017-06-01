<!DOCTYPE html>
<html>
<% include Head %>
<body class="$ClassName<% if not $Menu(2) %> no-sidebar<% end_if %>" <% if $i18nScriptDirection %>dir="$i18nScriptDirection"<% end_if %>>

<% include Header %>
<div class="container">
<% with $Estimate %>
    <h1>$Name</h1>
    <div class="divider"></div>
    <h3>Description</h3>
    <p class="flow-text">$Description</p>
    <div class="divider"></div>
    <h3>Number of Hours: $TotalHours</h3>
    <div class="divider"></div>
    <h3>ROM</h3>
    $RomLow - $RomHigh
    <div class="divider"></div>
    <h3>Stories</h3>
    <div class="row">
        <div class="col s12">
        <% loop $Stories %>
            <h4>$Name</h4>
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
    <div class="divider"></div>

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

<% end_with %>
</div>
<% include Footer %>
</body>
</html>
