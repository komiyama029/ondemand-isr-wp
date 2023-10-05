<?php

function revalidate_post($post_id) {
	$post = get_post($post_id);

	$api_url = 'http://localhost:3000/api/revalidate';

	$response = wp_remote_post($api_url, array(
		'body' => array(
			'path' => '/post/' . $post_id,
			'token' => REVALIDATION_TOKEN,
		),
	));

	// 応答のチェックとエラーログ
	if (is_wp_error($response)) {
		error_log('Revalidate API request failed: ' . $response->get_error_message());
	} else if (wp_remote_retrieve_response_code($response) !== 200) {
		error_log('Revalidate API request error. Status Code: ' . wp_remote_retrieve_response_code($response));
	}
}

add_action("save_post", "revalidate_post", 20, 1);
