<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ title }}</title>
    <!--<meta charset="utf-8"> -->
    <!--<meta name="viewport" content="width=device-width; initial-scale=1">-->
    <link rel="stylesheet" type="text/css" href="templates/Alpha/css/{{ style }}">
</head>
<body>

<!-- Div Wrapper Element Starts Here -->
<div id="Wrapper">
    <!-- Header Element Starts Here -->
    <header id="header">
        <!-- Hgroup Element Starts Here -->
        <hgroup id="title">
            <div id="logo"></div>
        </hgroup>
        <!-- Hgroup Element Ends Here -->

        <!-- Nav Element Starts Here -->
        <nav class="navigation">
            {% for button in buttons %}
            <a href="{{ button.url }}">{{ button.link_name }}</a>
            {% endfor %}
        </nav>
    </header>
    <!-- Header Element Ends Here -->