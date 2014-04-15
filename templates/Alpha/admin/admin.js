	function draw_button(image , href , onclick ){
		var html = "";
			
			html += "<a href=\"" + href + "\" "
			html += "onclick=\"" + onclick + "\">"
			html += "<img src=\"images/buttons/button_" + image + ".gif\" border=\"0\" >"
			html += "</a>";

		document.write(html);
	}

function draw_box ( width , part , title ) {
	if ( part == 1 ) {
		
		var html = "";

		html += "<table cellpadding=0 cellspacing=0 width=\""+ width + "\">"
		html += "	<tr>"
		html += "		<td height=1 colspan=3 bgcolor=#9DBEE6><img src=\"images/dot.gif\"></td>"
		html += "	</tr>"

		if (title != "") {
			html += "	<tr>"
			html += "		<td width=1 bgcolor=#9DBEE6><img src=\"images/dot.gif\"></td>"
			html += "		<td height=23 style=\"padding-left:10px;padding-top:7px;font-family:verdana;color:white;font-weight:bold;\"background=\"images/forms_caption.gif\">&nbsp;" + title + " </td>"
			html += "		<td width=1 bgcolor=#9DBEE6><img src=\"images/dot.gif\"></td>"
			html += "	</tr>"		
		}

		html += "	<tr>"
		html += "		<td width=1 bgcolor=#9DBEE6><img src=\"images/dot.gif\"></td>"
		html += "		<td bgcolor=#F8F8F8>"
		


		document.write (html);

	} else {
		var html = "";
				
		html += "		</td>"
		html += "		<td width=1 bgcolor=#9DBEE6><img src=\"images/dot.gif\"></td>"
		html += "	</tr>"
		html += "	<tr>"
		html += "		<td height=1 width=1 bgcolor=#9DBEE6><img src=\"images/dot.gif\"></td>"
		html += "		<td colspan=2 height=1 bgcolor=#9DBEE6><img src=\"images/dot.gif\"></td>"
		html += "	</tr>"
		html += "</table>"

		document.write (html);
	}
}

/*	function draw_box ( width , part , title ) {
		if ( part == 1 ) {
			
			var html = "";
			html += "<p class=\"title\">" + title + "</p>"
			html += "<table align=center cellpadding=0 cellspacing=0 width=\""+ width + "\">"
			html += "	<tr>"
			html += "		<td height=1 colspan=3 bgcolor=#9DBEE6><img src=\"images/dot.gif\"></td>"
			html += "	</tr>"
			html += "	<tr>"
			html += "		<td width=1 bgcolor=#9DBEE6><img src=\"images/dot.gif\"></td>"
			html += "		<td bgcolor=#F8F8F8>"
			


			document.write (html);
		} else {
			var html = "";				
			html += "		</td>"
			html += "		<td width=1 bgcolor=#9DBEE6><img src=\"images/dot.gif\"></td>"
			html += "	</tr>"
			html += "	<tr>"
			html += "		<td height=1 width=1 bgcolor=#9DBEE6><img src=\"images/dot.gif\"></td>"
			html += "		<td colspan=2 height=1 bgcolor=#9DBEE6><img src=\"images/dot.gif\"></td>"
			html += "	</tr>"
			html += "</table>"

			document.write (html);
		}
	}

*/	function draw_tab_sep() {
		document.write("<td width=1><img src=\"images/b_modulesep.gif\"></td>");
	}

	function draw_target_tab (title, link , target) {
		if (link != "") {
			var html = "";
			html += "<td width=92 background=\"images/b_module2.gif\" valign=middle align=center>"
			html += "	<a target=\"" + target + "\" href=\""+ link + "\" class=\"module_menu\">"+ title +"</a>"
			html += "</td>"
			html += "<td width=1><img src=\"images/b_modulesep.gif\"></td>"

			document.write(html);

		} else {

			var html = "";
			html += "<td width=92 background=\"images/b_module.gif\" valign=middle align=center class=\"module_menu\">"
			html += title
			html += "</td>"
			html += "<td width=1><img src=\"images/b_modulesep.gif\"></td>"

			document.write(html);
		}
		//draw_tab_sep() 
	}

	function draw_tab ( title, link ) {
		draw_target_tab ( title, link , "");
	}

