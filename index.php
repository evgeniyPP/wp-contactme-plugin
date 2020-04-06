<?php

/*
	Plugin Name: ContactMe Form
*/

add_action('wp_ajax_contactme', 'contactme_ajax');
add_action('wp_ajax_nopriv_contactme', 'contactme_ajax');

function contactme_ajax()
{
	$name = htmlspecialchars(trim($_POST['name']));
	$email = htmlspecialchars(trim($_POST['email']));
	$subject = htmlspecialchars(trim($_POST['subject']));
	$message = htmlspecialchars(trim($_POST['message']));

	if ($name == null || $email == null || $subject == null || $message == null) {
		$res = [
			'success' => false,
			'errors' => ['Данные некорретны']
		];
	} else {
		$post_data = array(
			'post_type' => 'messages',
			'post_title' => $subject,
			'post_content' => $message,
			'post_status' => 'publish',
			'meta_input' => [
				'name' => $name,
				'email' => $email
			]
		);

		$new_post_id = wp_insert_post(wp_slash($post_data));

		if (!$new_post_id) {
			$res = [
				'success' => false,
				'errors' => ['Ошибка при внесении в БД']
			];
		} else {
			$res = [
				'success' => true,
				'errors' => []
			];
		}
	}

	echo json_encode($res);
	wp_die();
}

add_action('wp_footer', function () {
	// Send backend vars to frontend
	$vars = [
		'templateUrl' => get_template_directory_uri(),
		'ajaxUrl' => admin_url('admin-ajax.php')
	];

	echo '<script>window.wp = ' . json_encode($vars) . '</script>';
});

add_action('init', function () {
	register_post_type('messages', [
		'labels' => array(
			'name'               => 'Сообщения',
			'singular_name'      => 'Сообщение',
			'add_new'            => 'Добавить новое',
			'add_new_item'       => 'Добавление сообщения',
			'edit_item'          => 'Редактирование сообщения',
			'new_item'           => 'Новое сообщение',
			'view_item'          => 'Смотреть сообщение',
			'search_items'       => 'Искать сообщение',
			'not_found'          => 'Не найдено',
			'not_found_in_trash' => 'Не найдено в корзине',
			'parent_item_colon'  => '',
			'menu_name'          => 'Сообщения',
		),
		'description'         => '',
		'public'              => false,
		'show_ui'             => false,
		'publicly_queryable'  => true,
		'exclude_from_search' => true,
		'show_in_nav_menus'   => false,
		'menu_position'       => 25,
		'menu_icon'           => 'dashicons-email-alt2',
		'hierarchical'        => false,
		'supports'            => ['title', 'editor', 'custom_fields']
	]);
});

add_action('admin_menu', function () {
	add_menu_page('Сообщения', 'Сообщения', 'edit_pages', 'messages-list', function () {
		$layout = [];

		$messages = get_posts([
			'orderby' => 'date',
			'order' => 'DESC',
			'post_type' => 'messages'
		]);

		foreach ($messages as $message) {
			$meta = get_post_custom($message->ID);
			ob_start();
			include __DIR__ . '/message.php';
			$layout[] = ob_get_clean();
		}

		$layout = implode('', $layout);
		ob_start();
		include __DIR__ . '/messages-list.php';

		echo ob_get_clean();
	}, 'dashicons-format-status', "26");
});
