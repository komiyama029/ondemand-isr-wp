<?php

function revalidate_post($post_id, $post_after, $post_before) {
	if ($post_after->post_modified_gmt !== $post_before->post_modified_gmt) {
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
		} else {
			$status_code = wp_remote_retrieve_response_code($response);
			$response_body = wp_remote_retrieve_body($response);
	
			if ($status_code !== 200) {
				error_log('Revalidate API request error. Status Code: ' . $status_code . '. Response: ' . $response_body);
			} else {
				error_log('Revalidate API request succeeded. Response: ' . $response_body);
			}
		}
	}
	
}

add_action("post_updated", "revalidate_post", 20, 3);
