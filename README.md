Tumblr API scripts
==========

Scripts that interact with the Tumblr API.

## Installation

To get started, create a file called `auth_tokens.php` and put it in the same directory as the `fetch_posts.php` file (from this repository). The contents of `auth_tokens.php` should be

	<?php

		$client1 = "";
		$client2 = "";
		$user1 = "";
		$user2 = "";

	?>

The client values are for your [registered application on Tumblr](https://www.tumblr.com/oauth/apps). The user values are found after your authorize your application to have access to your Tumblr account.

Click "Explore API":

![Screenshot of Tumblr apps page](1.jpg)