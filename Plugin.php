<?php

namespace SealCuidarMelhor;
use \MapasCulturais\App;
use \MapasCulturais\i;
use MapasCulturais\Entities\SealMeta;

class Plugin extends \SealModelTab\SealModelTemplatePlugin {

    function __construct($config = []) {
        ini_set('display_errors', true);
        $config += [
           'logo-site' => 'img/logo-saude.png'
        ];
        parent::__construct($config);
  
     }
  
    function getModelData(){
        return [
            'label'=> 'Certificado Cuidar Melhor',
            'name' => 'SealCuidarMelhor',
            'css' => 'sealcuidarmelhor/styles.css',
            'js' => 'sealcuidarmelhor/seal-certified.js',
            'background' => 'sealcuidarmelhor/meu-certificado--bg.jpg',
            'preview' => 'sealcuidarmelhor/certificadocuidarmelhor--preview.png'
        ];
    }

   function _init () {
        parent::_init();
        ini_set('display_errors', true);
        $app = App::i();
        $data = $this->getModelData();
       
        $app->hook('template(seal.sealrelation.print-certificate):after', function($relation) use($app, $data){
            //Adicionando arquivos de estilo
            if($app->isEnabled('seals') && 
                $relation->seal->seal_model &&
                !$app->user->is('guest') &&
                (   $app->user->is('superAdmin') || 
                    $app->user->is('admin') || 
                    $app->user->profile->id == $relation->owner->id
                )
            ) {
                
                $this->part('sealcuidarmelhor/seal-model--printCertificate', ['relation' => $relation]);
            }
        });

        $app->hook('template(seal.<<edit>>.tab-about-service):after', function() use($app, $data){
            ini_set('display_errors', true);

            $entity = $app->repo('SealRelation')->findBy(['seal' => $this->data['entity']->id, 'status' => 1]);

            $opportunits = [];
            foreach ($entity as $key => $entities) {
                //VERIFICAÇÃO PARA PREENCHER O ARRAY
               if(is_object($entities) && $entities instanceof \MapasCulturais\Entities\OpportunitySealRelation && $entities->status == 1) {
                    //PREENCHENDO O ARRAY COM O ID E NOME DA OPORTUNIDADE
                    $opportunits[$entities->owner->id] = $entities->owner->name;
               }
            }
            $this->part('sealcuidarmelhor/options-opportunity', ['opportunits' => $opportunits]);
        });

        $app->hook('GET(seal.<<edit>>):before', function() use($app, $data){
            $app->view->enqueueStyle('app', $data['name'], 'css/' . $data['css']);           
        });        

        //TODOS OS CAMPOS DA OPORTUNIDADE
        $app->hook('GET(opportunity.allField)', function() use($app){

            if(isset($this->data['id']) && $this->data['id'] > 0) {
                $opportunity = $app->repo('Opportunity')->find($this->data['id']);
                $name = [];
    
                if(isset($opportunity->registrationFieldConfigurations)) {
                    foreach ($opportunity->registrationFieldConfigurations as $key => $value) {
                        $name[] = ['id' => $value->id, 'title' => $value->title];
                    }
                }
                if(is_array($name) && count($name) > 0) {
                    $this->json($name,200);
                }else{
                    $this->errorJson(i::__('Ocorreu um erro inexperado'), 400);
                }
            }
           
        });

        //SALVANDO CONFIGURAÇÃO DE OPORTUNIDADE E CAMPOS
        $app->hook('POST(seal.saveCuidarMelhor)', function() use($app){

            $seal = $app->repo('Seal')->find($this->data['id']);
            $sealMetaField = new SealMeta;
            $sealMetaField->key = 'field';
            $sealMetaField->value = $this->data['field'];
            $sealMetaField->owner = $seal;

            $sealMetaOp = new SealMeta;
            $sealMetaOp->key = 'opportunity';
            $sealMetaOp->value = $this->data['opportunity'];
            $sealMetaOp->owner = $seal;

            $app->em->persist($sealMetaField);
            $app->em->persist($sealMetaOp);
		    $app->em->flush();
            $this->json(['title' => 'Sucesso', 'message' => 'Confirmado', 'type' => 'success', 'status' => 200], 200);
            
        });
        
        //HOOK PARA PREENCHER O SEGUNDO SELECT (CAMPOS)
        $app->hook('GET(seal.fieldSelect)', function() use($app){
            $seal = $app->repo('Seal')->find($this->data['id']);
            $sealMeta = $app->repo('SealMeta')->findBy([
                'owner' => $seal
            ]);
            $idField = 0;
            foreach ($sealMeta as $key => $fieldSeal) {

                if($fieldSeal->key == 'field') {
                    $idField = $fieldSeal->value;                    
                }                
            }
            
            $meta = $app->repo('RegistrationFieldConfiguration')->find($idField);
            if(isset($meta)) {
                $this->json($meta->title);
            }else{
                $this->errorJson('Selecione um campo');
            }
           
        });

        //HOOK PARA CONSULTAR AO CARREGAR A PÁGINA DO SELO SE JÁ TEM ALGUMA CONFIGURAÇÃO DE OPORTUNIDADE E CAMPOS
        //CASO TENHA VALOR ENVIA PARA O .JS CARREGAR OS SELECTS
        $app->hook('GET(opportunity.sealOpportunity)', function() use($app){
            $sealMeta = $app->repo('SealMeta')->findBy([
                'owner' => $this->data['id']
            ]);

            $fieldSeal = [];
            foreach ($sealMeta as $key => $value) {
                $fieldSeal[$key] = ['field' => $value->key , 'value' => $value->value];
            }

            if(isset($fieldSeal)) {
                $this->json($fieldSeal);
            }else{
                $this->errorJson('Um erro inexperado.');
            }
        });
        
   }

    public function register() {
        // register metadata, taxonomies
        ini_set('display_errors', true);
        $app = App::i();
        $app->registerController('selo', 'SealCuidarMelhor\Controllers\Selo');
        $metadef = [
            "label" => i::__("ID da oportunidade à qual o selo se refere", "seal-cuidar-melhor"),
            "type" => "int",
            "default" => 0
        ];     
        if( $this->registerSealMetadata("opportunity", $metadef) !== NULL ) {
            $this->registerSealMetadata("opportunity", $metadef);
        }
        
        $metadef = [
            "label" => i::__("ID do campo que se refere na oportunidade", "seal-cuidar-melhor"),
            "type" => "int",
            "default" => 0
        ]; 
        
        if( $this->registerSealMetadata("field", $metadef) !== NULL ) {
            $this->registerSealMetadata("field", $metadef);
        }
    
    }
}