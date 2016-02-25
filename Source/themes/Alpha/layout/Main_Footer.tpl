</body>
<!-- Footer Element Starts Here -->
<footer id="copyrights">
    <p><small>&copy; 2012 <a href="#">{{ website }}</a></small></p>
    <p>
        {% for links in footlinks %}
            <a href="{{ links.link }}">{{ links.title }}</a>
        {% endfor %}
    </p>
    <address>
        Website Template by <a target="_blank" href="http://www.dzyngiri.com/">Dzyngiri</a>
    </address>
</footer>
<!-- Footer Element Ends Here -->
</div>
<!-- Div Wrapper Element ends Here -->
<div style="text-align:center;">
    This template  downloaded form <a href='http://all-free-download.com/free-website-templates/'>free website templates</a>
</div>

</html>