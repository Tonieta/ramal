<?php

class PjsipMysql{
  private $mysqli = null;
  private $envDir = '/../.env';
  // private $host = '192.168.15.100';
  // private $usuario = 'asterisk';
  // private $senha = 'camicaze';
  // private $base = 'asterisk';
  private $return = array();


  public function __construct() {
   
  }

  private function dotEnv(){
     
    $linhas = file(__DIR__ . $this->envDir, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($linhas as $linha) {

        if (strpos(trim($linha), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $linha, 2);

        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
   
  }
  private function connect(){
    $this->dotEnv();

    $mysqli = new mysqli(getenv('DB_HOST'),getenv('DB_USUARIO'),getenv('DB_SENHA'),getenv('DB_NOME'));
    
    if ($mysqli->connect_errno) {      
      echo "Falha ao conectar com o MySQL: " . $this->mysqli -> connect_error;
      exit(1);
    }

    return $mysqli;
  }
  
  public function getPjsip(){
    $this->mysqli = $this->connect();

    $query = "SELECT
                  ramal_numero,
                  ramal_nome,
                  ramal_contexto,
                  ramal_codecs,
                  ramal_senha,
                  autenticacao_tipo,
                  transport_contexto
                  FROM ramal
                  LEFT JOIN ramal_autenticacao ON ramal_autenticacao_tipo = ramal_autenticacao.id
                LEFT JOIN ramal_transport ON ramal_transport_tipo = ramal_transport.id";
   
    $pjsips = $this->mysqli->query($query);
        
    if($pjsips->num_rows > 0){
      while ($pjsip = $pjsips->fetch_assoc()) {        
        $this->return[$pjsip['ramal_numero']]['ramal_numero'] = $pjsip['ramal_numero'];
        $this->return[$pjsip['ramal_numero']]['ramal_nome'] = $pjsip['ramal_nome'];
        $this->return[$pjsip['ramal_numero']]['ramal_contexto'] = $pjsip['ramal_contexto'];
        $this->return[$pjsip['ramal_numero']]['ramal_codecs'] = $pjsip['ramal_codecs'];
        $this->return[$pjsip['ramal_numero']]['ramal_senha'] = $pjsip['ramal_senha'];
        $this->return[$pjsip['ramal_numero']]['autenticacao_tipo'] = $pjsip['autenticacao_tipo'];
        $this->return[$pjsip['ramal_numero']]['transport_contexto'] = $pjsip['transport_contexto'];                
      }
      return $this->return;
    }
    return false;
  }
  
  public function __destruct(){
    $this->mysqli->close();
  }
}


?>