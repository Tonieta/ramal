

<?php
include_once(__DIR__ . '/../models/Pjsip.php');

class Pjsip extends PjsipMysql{
    private $pjsipExtensionCustomDir = '/etc/asterisk/pjsip_custom.conf';
    private $pjsipAuthDir = '/etc/asterisk/pjsip_custom_auth.conf';
    private $pjsipAorDir = '/etc/asterisk/pjsip_custom_aor.conf';
    private $pjsipHeadInfo = null;
    private $pjsipExntension = null;
    private $pjsipAuth = null;
    private $pjsipAor = null;

    public function __construct()
    {
        $this->pjsipHeadInfo .= ";Tudo o que for criado aqui, será pela interface. \n";
        $this->pjsipHeadInfo .= ";Se você adicionar algo diretamente nesse arquivo, quando \n";
        $this->pjsipHeadInfo .= ";clicar em aplicar la na pagina web, você irá perder sua alteração \n";
        $this->pjsipHeadInfo .= ";e eu irei mangar do cê!!! Nãnn! \n";
        $this->pjsipHeadInfo .= ";Por: Tonieta Programadora <tonieta.programadora@gmail.com> \n\n";        

    }

    private function gravarPjsipExtensionCustom($pjsipExtension){        
        $configuracoes  = $this->pjsipHeadInfo;
        $configuracoes .= $pjsipExtension;

        $fp = fopen($this->pjsipExtensionCustomDir,'w');      

        if(fwrite($fp, $configuracoes) == false){
            $return = array('status' => 'error', 'mensage' => 'Não foi possivel escrever no arquivo ' . $this->pjsipExtensionCustomDir);
            fclose($fp);
        }  

        $return = array('status' => 'success', 'mensage' => 'Extensio gravada com sucesso no arquivo ' . $this->pjsipExtensionCustomDir);
        fclose($fp);     
        return $return;             
        
    }
    private function gravarPjsipAuthCustom($pjsipAuh){
        $configuracoes  = $this->pjsipHeadInfo;
        $configuracoes .= $pjsipAuh;

        $fp = fopen($this->pjsipAuthDir,'w');  

        if(fwrite($fp, $configuracoes) == false){
            $return = array('status' => 'error', 'mensage' => 'Não foi possivel escrever no arquivo ' . $this->pjsipAuthDir);
            fclose($fp);
        }  

        $return = array('status' => 'success', 'mensage' => 'Extensio gravada com sucesso no arquivo ' . $this->pjsipAuthDir);
        fclose($fp);     

        return $return;  
                 
        
    }
    private function gravarPjsipAorCustom($pjsioAor){    
        $configuracoes  = $this->pjsipHeadInfo;
        $configuracoes .= $pjsioAor;

        $fp = fopen($this->pjsipAorDir,'w');                                 
        
        if(fwrite($fp, $configuracoes) == false){
            $return = array('status' => 'error', 'mensage' => 'Não foi possivel escrever no arquivo ' . $this->pjsipAorDir);
            fclose($fp);
        }  

        $return = array('status' => 'success', 'mensage' => 'Extensio gravada com sucesso no arquivo ' . $this->pjsipAorDir);
        fclose($fp);     

        return $return;  
    }

    public function criarPjsipExtension(){
        $arrayPjsip = parent::getPjsip();

        foreach($arrayPjsip as $extension => $pjsip){
            
            $this->pjsipExntension .= "[$extension]\n";
            $this->pjsipExntension .= "trust_id_outbound=yes\n";
            $this->pjsipExntension .= "callerid=$pjsip[ramal_nome]\n";
            $this->pjsipExntension .= "type=endpoint\n";
            $this->pjsipExntension .= "context=$pjsip[ramal_contexto]\n";
            $this->pjsipExntension .= "disallow=all\n";
            $this->pjsipExntension .= "allow=$pjsip[ramal_codecs]\n";
            $this->pjsipExntension .= "transport=$pjsip[transport_contexto]\n";
            $this->pjsipExntension .= "auth=auth$extension\n";
            $this->pjsipExntension .= "aors=$extension\n";
            $this->pjsipExntension .= "direct_media=yes\n";
            $this->pjsipExntension .= "rtp_symmetric=yes\n";
            $this->pjsipExntension .= "force_rport=yes\n";
            $this->pjsipExntension .= "\n";
            
            $this->pjsipAuth .= "[auth$extension]\n";
            $this->pjsipAuth .= "type=auth\n";
            $this->pjsipAuth .= "auth_type=userpass\n";
            $this->pjsipAuth .= "password=$pjsip[ramal_senha]\n";
            $this->pjsipAuth .= "username=$extension\n";
            $this->pjsipAuth .= "\n";

            $this->pjsipAor .= "[$extension]\n";
            $this->pjsipAor .= "type=aor\n";
            $this->pjsipAor .= "max_contacts=2\n";
            $this->pjsipAor .= "\n";

        }

        $this->gravarPjsipExtensionCustom($this->pjsipExntension);
        $this->gravarPjsipAuthCustom($this->pjsipAuth);
        $this->gravarPjsipAorCustom($this->pjsipAor);

    }

    public function __destruct()
    {
        
    }

}