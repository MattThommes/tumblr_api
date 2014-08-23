<?php

	require "vendor/autoload.php";
	use MattThommes\Debug;
	$debug = new Debug;

	require_once("auth_tokens.php");

	$tumblr = new Tumblr\API\Client($consumer_key, $consumer_secret);
	$tumblr->setToken($token, $token_secret);

	$blog_name = "mattthommes-blog";

	// get first page.
	$page = 0;
	$per_page = 20;
	$options = array("offset" => $page * $per_page);
	$tumblr_posts = $tumblr->getBlogPosts($blog_name, $options);

//$debug->dbg($tumblr_posts);

	while (count($tumblr_posts->posts) == $per_page) {
		foreach ($tumblr_posts->posts as $p) {

			// do something with each post. example post:

			/*
			stdClass Object
			(
					[blog_name] => you-blog
					[id] => 24468876872
					[post_url] => http://your.blog.com/post/24468876872/a-post
					[slug] => a-post
					[type] => text
					[date] => 2014-08-14 23:54:00 GMT
					[timestamp] => 1408060440
					[state] => published
					[format] => markdown
					[reblog_key] => PKn3XbQE
					[tags] => Array
							(
									[0] => category1
									[1] => category2
							)

					[short_url] => http://tmblr.co/UONNLr4OMgz6A
					[followed] => 
					[highlighted] => Array
							(
							)

					[liked] => 
					[note_count] => 2
					[title] => A Post
					[body] => blog post body here
					[can_reply] => 
			)
			*/

			// the actual code you want to run for each item.
			include "fetch_posts.inc.php";

		}
		$page++;
		/*if ($page > 0) {
			break;
		}*/
		sleep(2);
		// get next page.
		$options = array("offset" => $page * $per_page);
		$tumblr_posts = $tumblr->getBlogPosts($blog_name, $options);
	}

?>