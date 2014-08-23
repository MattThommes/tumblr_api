Tumblr API scripts
==========

Scripts that interact with the Tumblr API.

## Installation

To get started, create a file called `auth_tokens.php` and put it in the same directory as the `fetch_posts.php` file (from this repository). The contents of `auth_tokens.php` should be

	<?php

		$consumer_key = "";
		$consumer_secret = "";
		$token = "";
		$token_secret = "";

	?>

The consumer values are for your [registered application on Tumblr](https://www.tumblr.com/oauth/apps). The token values are found after you authorize your application to have access to your Tumblr account.

Click "Explore API":

![Screenshot of Tumblr apps page](1.jpg)

You'll then be taken to a page where you grant permission for your application to access your Tumblr account:

![Screenshot of Tumblr OAuth page](2.jpg)

When you click "Allow" you land on a page where you can grab the values:

![Screenshot of Tumblr access keys](3.jpg)

Put the name of the blog you want to access in the `fetch_posts.php` script:

	$blog_name = "your-blog";

### fetch_posts.php

This script will fetch all blog posts from any blog you have access to. Posts are then accessible through the `$tumblr_posts` variable.

The code you want to run for each post should reside in `fetch_posts.inc.php` which is included by `fetch_posts.php` (within the loop) already.

### wordpress2tumblr.php

This script will grab posts from your WordPress database and create a corresponding post on Tumblr.

First we'll create a local Tumblr MySQL database to store the posts that we already moved (as a reference):

	CREATE TABLE `tumblr` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`wp_id` int(10) unsigned NOT NULL,
		`tumblr_id` bigint(20) unsigned NOT NULL,
		`wp_uri` varchar(250) NOT NULL,
		`301` varchar(254) NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;
	
Put in your Tumblr blog domain (either the Tumblr domain, or your custom domain). This is used for setting up 301 redirects if you want to include that on your WordPress site.

	$blog_domain = "blog.me.com";