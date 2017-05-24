<ul id="nav-mobile" class="left hide-on-med-and-down">
		<% loop $Menu(1) %>
			<li class="$LinkingMode"><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
		<% end_loop %>
</ul>
