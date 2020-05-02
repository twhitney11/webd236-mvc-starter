<?php
function get_createView($dir, $view) {
  $view = sanitize($view);
  
  $template =<<<'END'
%% views/header.html %%

<div class="row">
  <div class="col-lg-12">
    <h1>{{$title}}</h1>
    <p>Replace this with your view contents</p>
  </div>
</div>
          
%% views/footer.html %% 
END;
  
  file_put_contents("views/{$view}", $template);
  `refresh`; // force glitch to find the new file
  exit();
}

function get_createController($controller) {
  $controller = sanitize($controller);
  $template = =<<<'END'
  <?php
    require_once "include/util.php";
  
  END;
  file_put_contents("controllers/{$controller}.php", $template);
  `refresh`; // force glitch to find the new file
  exit();
}

function get_createFunction($controller, $function) {
  $controller = sanitize($controller);
  $function = sanitize($function);
  $contents = file_get_contents("controllers/{$controller}.php");
  $view = $controller . substr($function, strpos($function, "_") + 1);
  $template =<<<END
function {$function}() {
  // put your code for {$function} here
  renderTemplate(
    "views/{$view}.php",
    array(
      'title' => '{$view}',
    )
  );
}

function 
END;
  $template = preg_replace("/<\?php.*function/U", $template, $contents, 1);
  file_put_contents("controllers/{$controller}.php", $template);
  `refresh`; // force glitch to find the new file
  exit();
}

function sanitize($str) {
  // sanitize controller and function names
  $str = preg_replace("/([^\w\d_\.])/", '', $str);
  $str = preg_replace("/([\.]{2,})/", ".", $str);
  return $str;
}