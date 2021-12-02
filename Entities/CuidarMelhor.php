<?php
namespace SealCuidarMelhor\Entities;

use MapasCulturais\App;

class CuidarMelhor extends \MapasCulturais\Entity{

    static public function message($seal, $idOp) {
      
        $app = App::i();
        
        if(!is_null($idOp)) {
            $opportunity = $app->repo('Opportunity')->find($idOp);
            //BUSCA A INSCRIÇÃO
            $registration = $app->repo('Registration')->findBy([
                                'opportunity' => $opportunity,
                                'owner' => $seal->owner_relation
                            ]);
            //CONCATENANDO PARA PEGAR O NOME DO CAMPO NA TABELA REGISTRATION_META
            $field = "field_{$seal->seal->field}";

            $regMeta = $app->repo('RegistrationMeta')->findBy([
                'owner' => $registration,
                'key' => $field
            ]);
            
        }else{
            $regMeta = null;
        }
        //MENSAGEM QUE ESTÁ NO BANCO NA TABELA SEAL\    \       
        $message = $seal->seal->certificateText;
        if(!is_null($regMeta) && count($regMeta) > 0) {
            if(!empty($message)){
                $message = str_replace("\t","&nbsp;&nbsp;&nbsp;&nbsp",$message);
                $message = str_replace("[substituir]", $regMeta[0]->value , $message);
                $message = preg_replace('/\v+|\\\r\\\n/','<br/>',$message);
            }
        }
        
        return  $message;
    }

}