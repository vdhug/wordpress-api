<?php

function api_usuario_post($request) {
  // Recupera os valores do objeto que foi passado na requisição
  $email = sanitize_email($request['email']);
  $senha = sanitize_text_field($request['senha']);
  $nome = sanitize_text_field($request['nome']);
  $rua = sanitize_text_field($request['rua']);
  $cep = sanitize_text_field($request['cep']);
  $numero = sanitize_text_field($request['numero']);
  $bairro = sanitize_text_field($request['bairro']);
  $cidade = sanitize_text_field($request['cidade']);
  $estado = sanitize_text_field($request['estado']);

  $user_exists = username_exists($email);
  $email_exists = email_exists($email);

  // Checa se ja existe um usuário com esse email e se a senha que o usuario passou não está vazia
  if(!$user_exists && !$email_exists && $email && $senha) {
    // Insere o usuário
    $user_id = wp_create_user($email, $senha, $email);

    // Atualiza algumas informações do usuario que acabou de se cadastrar
    $response = array(
      'ID' => $user_id,
      'display_name' => $nome,
      'first_name' => $nome,
      'role' => 'subscriber',
    );
    wp_update_user($response);

    // Inserindo os novos dados do usuário. Update irá criar o campo se o usuário não tiver esse campo (usuario, campo, valor)
    update_user_meta($user_id, 'cep', $cep);
    update_user_meta($user_id, 'rua', $rua);
    update_user_meta($user_id, 'numero', $numero);
    update_user_meta($user_id, 'bairro', $bairro);
    update_user_meta($user_id, 'cidade', $cidade);
    update_user_meta($user_id, 'estado', $estado);
  } else {
    $response = new WP_Error('email', 'Email já cadastrado.', array('status' => 403));
  }
  return rest_ensure_response($response);
}

function registrar_api_usuario_post() {
  register_rest_route('api', '/usuario', array(
    array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'api_usuario_post',
    ),
  ));
}

add_action('rest_api_init', 'registrar_api_usuario_post');


?>