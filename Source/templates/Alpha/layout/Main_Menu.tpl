<!-- Div wrapper2 starts here -->
<div id="Wrapper2">
    <!-- Sidebar Element Starts Here -->
    <aside id="sidebar-wrapper">
        {% for sidbar as sidbars %}
            <nav class="sidebar">
                <h1>{{ sidbar.menu_title }}</h1>
                {% for items as sibar.item %}
                    {{ items.link }}
                {% endfor %}
            </nav>
        {% endfor %}
        <!-- Sidebar Element Ends Here -->
    </aside>
    <!-- Another Sidebar Element Ends Here -->