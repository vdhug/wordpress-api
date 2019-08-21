<?php

$template_diretorio = get_template_directory();

require_once($template_diretorio . "/custom-post-type/produto.php");
require_once($template_diretorio . "/custom-post-type/transacao.php");

require_once($template_diretorio . "/endpoints/usuario_post.php");
require_once($template_diretorio . "/endpoints/usuario_get.php");
require_once($template_diretorio . "/endpoints/usuario_put.php");

require_once($template_diretorio . "/endpoints/produto_post.php");
require_once($template_diretorio . "/endpoints/produto_get.php");
require_once($template_diretorio . "/endpoints/produto_delete.php");


require_once($template_diretorio . "/endpoints/transacao_post.php");

// Passando para a requisição o número total de instancias que a busca possui
add_action('rest_pre_serve_request', function() {
  header('Access-Control-Expose-Headers: X-Total-Count');
});

// Função que irá retornar o produto por uma slug
function get_produto_id_by_slug($slug) {
  $query = new WP_Query(array(
    'name' => $slug,
    'post_type' => 'produto',
    'numberposts' => 1,
    'fields' => 'ids'
  ));
  $posts = $query->get_posts();
  return array_shift($posts);
}

// Função que define quando o token vai expirar, no caso, 1 dia depois
function expire_token() {
  return time() + (60 * 60 * 24);
}
add_action('jwt_auth_expire', 'expire_token');

?>