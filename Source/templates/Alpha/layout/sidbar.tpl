{% for sidbar as sidbars %}
<nav class="sidebar">
    <h1>{{ sidbar.menu_title }}</h1>
    {% for items as sibar.item %}
        {{ items.link }}
    {% endfor %}
</nav>
{% endfor %}