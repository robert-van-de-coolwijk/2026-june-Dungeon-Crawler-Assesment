<?php
// This file only exists, to take the user gracefully to the application, with a link they have to click themselves.
// You only see this page when the application is configured wrongly, the public folder is the only thing that should be visible.
// Please update your vhost or other web server settings if you can see this page. THIS IS A SECURITY RISK!

echo "<title>Dungeon Crawler</title>";
echo "<h2>Dungeon Crawler</h2>";

echo "<p>Welcome to the maze, let us get started!</p>";

echo '<p><a href="./public/">Start application</a></p>';