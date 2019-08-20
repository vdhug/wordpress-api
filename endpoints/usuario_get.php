<?php

function api_usuario_get($request) {
  // Recuperando o usuário atual que está logado, ou seja, o usuário que está enviando o token
  $user = wp_get_current_user();

  return rest_ensure_response($user);
}

function registrar_api_usuario_get() {
  register_rest_route('api', '/usuario', array(
    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'api_usuario_get',
    ),
  ));
}

add_action('rest_api_init', 'registrar_api_usuario_get');


?>