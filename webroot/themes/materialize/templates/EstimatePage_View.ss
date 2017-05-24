<!DOCTYPE html>
<html>
<head>
	<% base_tag %>
    <title><% if $MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> &raquo; $SiteConfig.Title</title>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--Import materialize.css-->
    <% require themedCSS('materialize') %>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <link rel="shortcut icon" href="$ThemeDir/images/favicon.ico" />
</head>

<body class="$ClassName<% if not $Menu(2) %> no-sidebar<% end_if %>" <% if $i18nScriptDirection %>dir="$i18nScriptDirection"<% end_if %>>

<% include Header %>
<div class="container">

    <div class="row">
        <div class="col s3">
            <h5>Layered Navigation</h5>
        </div>
        <div class="col s9">
            <h5>Latest Estimates</h5>
            <div class="divider"></div>

                <!-- Todo: Add row inside loop on mod 3 -->
            <% loop $LatestEstimates %>
                <% if $Modulus(3) == 0 %>
                    <div class="row">
                <% end_if %>
                <div class="col s3">
                    <div class="card-panel hoverable">
                    Estimate Name: $Name
                    $TotalHours()
                    <a href="$Link" class="waves-effect waves-light btn">View</a>
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