<?php

	require "vendor/autoload.php";
	use MattThommes\Debug;
	$debug = new Debug;

	// database connection to your WordPress blog tables.
	$db_conn = new Mysql("localhost", "user", "pass", "database_name");

	require_once("auth_tokens.php");

	$tumblr = new Tumblr\API\Client($consumer_key, $consumer_secret);
	$tumblr->setToken($token, $token_secret);

	$blog_name = "your-blog";
	$blog_domain = "blog.me.com"; // your Tumblr domain, or custom domain.

	// get existing data, so we can create the serialized array to put into our existing WP install to handle the redirects.
/*
	$rows = $db_conn->query("SELECT * FROM tumblr ORDER BY id ASC")->fetch_array();
	$_301s = array("/" => "http://" . $blog_domain);

	foreach ($rows as $row) {
		// get just the new URL.
		$_301_pieces = explode(" ", $row["301"]);
		$_301s[$row["wp_uri"]] = $_301_pieces[3];
	}

	$_301s = serialize($_301s);
	echo $_301s;
	exit;
*/

	$id = 0; // if you just want to import a single post, use it's WP id.
	$skipover = 1866; // this should always be the total number of rows in `tumblr` DB table.
	$limit = 30; // this is how many we import each time we reload the page.

	if ($id) {
		$posts_query = "SELECT * FROM wp_posts WHERE `ID` = " . $id;
	} else {
		$posts_query = "SELECT * FROM wp_posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_date DESC LIMIT " . $skipover . ", " . $limit;
	}

	$posts = $db_conn->query($posts_query)->fetch_array();

	foreach ($posts as $post) {

		// see if we already added to tumblr.
		$exists = $db_conn->query("SELECT * FROM tumblr WHERE wp_id = " . $post["ID"])->fetch();

		if (!$exists) {

			$wp_slug = date("Y/m/d", strtotime($post["post_date"]));
			$wp_slug .= "/" . $post["post_name"];

			$tags = $db_conn->query("SELECT * FROM wp_term_relationships WHERE object_id = '" . $post["ID"] . "'")->fetch_array();

			$tags_arr = array();
			$tags_str = "";

			foreach ($tags as $k => $tag) {
				$tag_ = $db_conn->query("SELECT * FROM wp_terms WHERE term_id = '" . $tag["term_taxonomy_id"] . "'")->fetch();
				$tag_name = $tag_["name"];
				$tags_arr[] = $tag_name;
				$tags[$k]["name"] = $tag_name;
			}

			$tags_str = implode(", ", $tags_arr);

			$wp_images = array();
			// check for occurrences of "wp-content/uploads" in the post body (to find images).
			/*if (preg_match_all("/wp\-content\/uploads/i", $post["post_content"], $wp_images)) {
			
			}*/

			$tumblr_post_data = array(
				"type" => "text",
				"state" => "published",
				"date" => $post["post_date"],
				"format" => "html",
				"slug" => $post["post_name"],
				"tags" => $tags_str,
				"title" => $post["post_title"],
				"body" => $post["post_content"],
			);

			try {
				$tumblr_post = $tumblr->createPost($blog_name, $tumblr_post_data);
			} catch (Exception $e) {
				$debug->dbg($e->getMessage(),1);
				$debug->dbg($e);
			}

			$_301 = "Redirect 301 /archive/" . $wp_slug . " http://" . $blog_domain . "/post/" . $tumblr_post->id . "/" . $tumblr_post_data["slug"];

			$ins = $db_conn->query("INSERT INTO tumblr (wp_id, tumblr_id, wp_uri, `301`) VALUES (" . $post["ID"] . ", " . $tumblr_post->id . ", '/archive/" . $wp_slug . "', '" . $_301 . "')");

			sleep(3);

		}

	}

?>