<?php

	$exists = $db_conn->query("SELECT * FROM tumblr_live WHERE tumblr_id = " . $p->id)->fetch();
	if (!$exists) {
		$fields = array(
			"tumblr_id",
			"timestamp",
			"timestamp_dt",
			"post_type",
			"title",
			"post_url",
			"post_content",
			"tags",
		);
		if ($p->type == "link") {
			$post_content = $p->description;
		} else {
			$post_content = $p->body;
		}
		$values = array(
			$p->id,
			$p->timestamp,
			"'" . date("Y-m-d H:i:s", $p->timestamp) . "'",
			"'" . $p->type . "'",
			"'" . str_replace("'", "\'", $p->title) . "'",
			"'" . $p->post_url . "'",
			"'" . str_replace("'", "\'", $post_content) . "'",
			"'" . json_encode($p->tags) . "'",
		);
		$ins = $db_conn->query("INSERT INTO tumblr_live (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $values) . ")");
	}

?>