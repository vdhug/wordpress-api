<?php

function api_usuario_get($request) {
  // Recuperando o usuário atual que está logado, ou seja, o usuário que está enviando o token
  $user = wp_get_current_user();  
  $user_id = $user->ID;

  // Checa se existe um usuário logado
  if($user_id > 0) {
    // Recuperando os meta dados desse usuário (meta dados são os dados além de username, email e password)
    $user_meta = get_user_meta($user_id);

    $response = array(
      "id" => $user->user_login,
      "nome" => $user->display_name,
      "email" => $user->user_email,
      "cep" => $user_meta['cep'][0],
      "numero" => $user_meta['numero'][0],
      "rua" => $user_meta['rua'][0],
      "bairro" => $user_meta['bairro'][0],
      "cidade" => $user_meta['cidade'][0],
      "estado" => $user_meta['estado'][0],
    );
  } else {
    $response = new WP_Error('permissao', 'Usuário não possui permissão', array('status' => 401));
  }

  return rest_ensure_response($response);
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