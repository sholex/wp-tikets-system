<?php

//namespace notifications;


class notifications {



	public function __construct() {

		/**
		 * Отправка уведомлений при публикации поста
		 */
		add_action('publish', [$this, 'new_faq_email']);
		add_action('new_to_publish', [$this, 'new_faq_email']);
		add_action('draft_to_publish', [$this, 'new_faq_email']);
		add_action('pending_to_publish', [$this, 'new_faq_email']);




		//add_action( 'transition_post_status', 'roomble_send_notification', 30, 3 );


		//add_action('save_post_faq', [$this, 'send_message_2']);
	}



	public function set_answer_author()
	{
		global $post;
		wp_update_post(array(
			'ID'    => $post->ID,
			'post_author'   => get_current_user_id()
		));
	}

	public function roomble_send_notification( $new_status, $old_status, $post ) {
		if(
			'publish' === $new_status &&
			'publish' !== $old_status &&
			TICKETS_POST_TYPE === $post->post_type
		) {
			$this->mail_to_author($post->id, $post, 'new');
		}
	}



	public function mail_to_author($postid, $post, $status){
		//$post_data = get_post( $postid );
		$user_id = 1;//TODO: get user_id

		$post_data = $post;
		$userid = $post_data->post_author;
		$author_mail = get_the_author_meta('user_email', (int) $userid);
		$mail_custom = get_post_meta($postid, 'ap_author_email');

		if (!$name = get_the_author_meta('first_name', (int) $userid)) $name = get_the_author_meta('nickname', (int) $user_id);
		//else

		$post_title =  $post_data->post_title;

		$to = array($mail_custom[0]);
		//$subject = 'Ваш вопрос опубликован';
		$message = 'Здравствуйте, '.$name.'!'.PHP_EOL;
		if ($status == 'new'){
			$subject = ''.$name.', Ваш вопрос опубликован.';

			$message .= 'Ваш вопрос <b>'.$post_title.'</b> опубликован.'.PHP_EOL;
			$message .= 'Посмотреть его можно по ссылке: <a href="'.get_permalink($postid).'">'.get_permalink($postid).'</a>';


		}
		elseif ($status == 'answer'){
			$subject = ''.$name.', получен ответ на Ваш вопрос';

			$message .= 'Наш эксперт ответил на ваш вопрос <b>'.$post_title.'</b>'.PHP_EOL;
			$message .= 'Ответ можно посмотреть по ссылке: <a href="'.get_permalink($postid).'">'.get_permalink($postid).'</a>';
			$message .= get_field('ответ_эксперта', $postid);

		}


		$headers[] = 'From: БезПуза.ру <wordpress@bezpuza.ru>';
		$headers[] = 'content-type: text/html';
		wp_mail( $to, $subject, $message, $headers );

	}



	public function send_message_2() {
		global $wpdb;

		$user_id = 1;//TODO:

		//Если это полноценная запись
		if ( $post_id = $_REQUEST["post_ID"] ) {

			$answer = get_post_meta($post_id, 'doc_answer');
			$status = get_post_meta( $post_id, 'emailed_answer' );

			//	pre($status);

			//Если написан ответ и ранее письмо не отправлялось
			if ($answer != '' AND $answer != null AND $status[0] != 'yes'){

				$post_author = $_REQUEST["post_author"];
				$author_mail = get_the_author_meta('user_email', (int) $post_author);
				//	$mail_custom = get_post_meta($post_id, 'ap_author_email');

				if (!$name = get_the_author_meta('display_name', (int) $post_author)) $name = get_the_author_meta('nickname', (int) $user_id);

				$post_title =  get_the_title($post_id);

				//формируем письмо при ответе.
				$headers[] = 'From: ЛечениеДетей.ру <wordpress@lecheniedetej.ru>';
				$headers[] = 'content-type: text/html';

				$subject = ''.$name.', получен ответ на Ваш вопрос';
				$message = 'Здравствуйте, '.$name.'!'.PHP_EOL;
				$message .= 'Наш эксперт ответил на ваш вопрос <b>'.$post_title.'</b>'.PHP_EOL;
				$message .= 'Ответ можно посмотреть по ссылке: <a href="'.get_permalink($post_id).'">'.get_permalink($post_id).'</a>';
				$message .= get_field('ответ_эксперта', $post_id);

				//Пытаемся отправить
				$to = $author_mail;
				$sendstatus = wp_mail( $to, $subject, $message, $headers );
				//	var_dump($sendstatus);
				//	exit;
				if ($sendstatus == true){
					update_post_meta($post_id, 'emailed_answer', 'yes'); //если письмо отправлено ставим соответствующий маркер в произвольное поле
				}


			}
		}

	}


	public function new_faq_email(){
		global $post;

		$this->set_answer_author();

		if ($post->post_type == TICKETS_POST_TYPE){

			$post_id = $post->ID;

			$admin_email = get_option('admin_email');

			$admin_email = 'sholex@mail.ru';//временно

			//	$post_author = $_REQUEST["post_author"];//id автора
			//$author_mail = get_the_author_meta('user_email', (int) $post_author);//мейл автора
			$author_mail = get_post_meta( $post_id, 'ap_author_email' );
			$name =  get_post_meta( $post_id, 'ap_author_name' );

			$author_mail = get_post_meta( $post_id, 'email' )[0];
			$name =  get_post_meta( $post_id, 'vashe-imja' )[0];


			//if (!$name = get_the_author_meta('display_name', (int) $post_author)) $name = get_the_author_meta('nickname', (int) $user_id);//имя автора
			$post_title =  $post->title;//заголовок вопроса
			$post_content =  $post->post_content;//контент вопроса

			//Заголовки для письма
			$headers[] = 'From: Lecheniedetej.ru <no-reply@lecheniedetej.ru>';
			$headers[] = 'content-type: text/html';

			//Уведомляем администратора сайта
//			$subject = 'Новый вопрос на сайте';
//			$message = 'Поздравляем, новый вопрос для эксперта!'.PHP_EOL;
//			$message .= 'Заголовок вопроса <b>'.$post_title.'</b>.'.PHP_EOL;
//			$message .= 'Содержание вопроса <b>'.$post_content.'</b>.'.PHP_EOL;
//			$message .= 'Посмотреть его можно по ссылке: <a href="'.get_permalink($post_id).'">'.get_permalink($post_id).'</a>';
//			wp_mail( $admin_email, $subject, $message, $headers );


			//Уведомляем пользователя о публикации
			$subject = ''.$name[0].', Ваш вопрос опубликован.';
			$message = 'Здравствуйте, '.$name.'!'.PHP_EOL;
			$message .= 'Ваш вопрос <b>'.$post_title.'</b> опубликован.'.PHP_EOL;
			$message .= 'Посмотреть его можно по ссылке: <a href="'.get_permalink($post_id).'">'.get_permalink($post_id).'</a>';

//			write_log($author_mail);
//			write_log($subject);
//			write_log($message);
//			write_log($headers);
			wp_mail( $author_mail, $subject, $message, $headers );
			//exit;
		}
}}