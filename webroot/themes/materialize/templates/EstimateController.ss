<!DOCTYPE html>
<html>
    <% include Head %>
<body class="$ClassName<% if not $Menu(2) %> no-sidebar<% end_if %>" <% if $i18nScriptDirection %>dir="$i18nScriptDirection"<% end_if %>>

<% include Header %>
    <% with $Estimate %>
        <div class="row <% loop $Platforms %>$Top.getCssClass($Name)<% end_loop %> estimate-view">
            <div class="estimate-left col s12 m3 xl2">
                <div class="left-wrapper pin-top">
                    <h4>$Name</h4>

                    <div class="flex-row hours">
                        <div class="card-panel hours-card flex-col flex-s6 z-depth-0">
                            <div class="card-content white-text ">
                                <span class="card-title">Hours:</span>
                                <h6 class="flow-text">$TotalHours</h6>
                            </div>
                        </div>

                        <div class="card-panel flex-col flex-s6 grey white-text z-depth-0">
                            <div class="card-content">
                                <span class="card-title">ROM</span>
                                <h6 class="flow-text">$RomLow - $RomHigh</h6>
                            </div>
                        </div>
                    </div>

                    <div class="platforms flex-row">
                        <div class="card-panel flex-col flex-s12 z-depth-0">
                            <div class="card-content">
                                <span class="card-title">Platforms:</span>

                                <div class="flow-text">
                                    <% loop $Platforms %>
                                        <div class="chip $Top.getCssClass($Name)">$Name</div>
                                    <% end_loop %>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="confidence-levels flex-row">
                        <div class="card-panel flex-col flex-s12 z-depth-0">
                            <div class="card-content">
                                <div class="card-title">Confidence Levels</div>
                                <div class="flex-row">
                                    <div class="flex-col flex-s12 flex-l4">
                                        <p>Tech</p>
                                        <div class="chip $Top.getCssClass($TechnicalConfidence)">
                                            $TechnicalConfidence
                                        </div>
                                    </div>
                                    <div class="flex-col flex-s12 flex-l4">
                                        <p>Budget</p>
                                        <div class="chip $Top.getCssClass($BudgetConfidence)">
                                            $BudgetConfidence
                                        </div>
                                    </div>
                                    <div class="flex-col flex-s12 flex-l4">
                                        <p>Schedule</p>
                                        <div class="chip $Top.getCssClass($ScheduleConfidence)">
                                            $ScheduleConfidence
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="estimate-desc col s12 m7 xl9 z-depth-2">
                <h1 class="hide-on-med-and-down">$Name</h1>

                <div class="divider"></div>

                <div id="description" class="description section scrollspy">
                    <h3>Description</h3>
                    <p class="flow-text">$Description</p>
                </div>

                <div id="requirements" class="requirements section scrollspy">
                    <h3>Requirements</h3>
                    <div class="divider"></div>

                    <h4>Business Requirements</h4>
                    $BusinessRequirements

                    <h4>Functional Requirements</h4>
                    $FunctionalRequirements
                </div>

                <div id="risks" class="risks section scrollspy">
                    <h3>Risks</h3>
                    <div class="divider"></div>

                    <ul class="collapsible z-depth-0" data-collapsible="expandable">
                        <% loop $Risks %>
                            <li>
                                <div class="collapsible-header">
                                    <span class="title">$Name</span>
                                    <span class="type">
                                        <strong>Type:</strong>
                                        <% loop $RiskTypes %>$Name <% end_loop %>
                                    </span>
                                </div>
                                <div class="collapsible-body">
                                    <div class="row">
                                        <div class="col s12">
                                            <p>$Description</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12">
                                            <span class="risks-label">Mitigation Plan:</span>
                                            <p>$MitigationPlan</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s4">
                                            <span class="risks-label">Mitigation Cost:</span>
                                            <p>$MitigationCost</p>
                                        </div>
                                        <div class="col s4">
                                            <span class="risks-label">Potential Cost:</span>
                                            <p>$PotentialCost</p>
                                        </div>
                                        <div class="col s4">
                                            <span class="risks-label">Probability:</span>
                                            <p>$Probability</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <% end_loop %>
                    </ul>
                </div>

                <div id="stories" class="stories section scrollspy">
                    <h3>Stories</h3>
                    <div class="divider"></div>

                    <% loop $Stories %>
                        <h4>$Name</h4>
                        <table class="striped responsive-table">
                            <thead>
                            <tr>
                                <th>Hours</th>
                                <th>Description</th>
                                <th>Roles</th>
                                <th>Skills</th>
                            </tr>
                            </thead>

                            <tbody>
                                <% loop $LineItems %>
                                <tr>
                                    <td>$NumHours</td>
                                    <td>$Description</td>
                                    <td>
                                        <% loop $Roles %>
                                            <div class="chip role $Top.getCssClass($Name)">$Top.getRoleAbbr($Name)</div>
                                        <% end_loop %>
                                    </td>
                                    <td>
                                        <% loop $Skills %>
                                            <div class="skill-chip $Top.getCssClass($Name)"><span>$Name</span></div>
                                        <% end_loop %>
                                    </td>
                                </tr>
                                <% end_loop %>
                                <tr class="blue-grey lighten-4 hide-on-large-only">
                                    <td><strong>$TotalHours</strong></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>

                            <tfoot class="hide-on-med-and-down">
                                <tr class="blue-grey lighten-4">
                                    <td><strong>$TotalHours</strong></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    <% end_loop %>
                </div>
            </div>
            <div class="estimate-nav col m2 xl1 hide-on-med-and-down">
                <div class="toc-wrapper pin-top">
                    <ul class="section table-of-contents">
                        <li><a data-new="#description">Description</a></li>
                        <li><a data-new="#requirements">Requirements</a></li>
                        <li><a data-new="#risks">Risks</a></li>
                        <li><a data-new="#stories">Stories</a></li>
                    </ul>
                </div>
            </div>
        </div>

    <% end_with %>
<% include Footer %>
</body>
</html>
