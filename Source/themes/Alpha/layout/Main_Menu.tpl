<!-- Div wrapper2 starts here -->
<div id="Wrapper2">
    <!-- Sidebar Element Starts Here -->
    <aside id="sidebar-wrapper">
        {% for sidbar in sidbars %}
            <nav class="sidebar">
                <h1>{{ sidbar.menu_title }}</h1>
                <ul>
                    {% for items in sibar.item %}
                        <li> <a href="{{ items.link }}">{{ items.name }}</a></li>
                    {% endfor %}
                </ul>
            </nav>
        {% endfor %}
        <!-- Sidebar Element Ends Here -->
    </aside>
    <!-- Another Sidebar Element Ends Here -->
