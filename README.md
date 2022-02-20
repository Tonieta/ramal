# ramal
Se você quer criar um ramal pela web é aqui! Arriégua!

- Criar os arquivos;
 /etc/asterisk/pjsip_custom.conf
 /etc/asterisk/pjsip_custom_auth.conf
 /etc/asterisk/pjsip_custom_aor.conf
 
- Incluir em /etc/asterisk/pjsip.conf
#include pjsip_custom.conf
#include pjsip_custom_auth.conf
#include pjsip_custom_aor.conf
