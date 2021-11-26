<?php

namespace SealCuidarMelhor;
use \MapasCulturais\App;
use MapasCulturais\Entities\Opportunity;

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
            
            $listProject = [];
            //dump($this->data['entity']->id);
            $entity = $app->repo('SealRelation')->findBy(['seal' => $this->data['entity']->id, 'status' => 1]);

            $opportunits = [];
            foreach ($entity as $key => $entities) {
                //VERIFICAÇÃO PARA PREENCHER O ARRAY
               if(is_object($entities) && $entities instanceof \MapasCulturais\Entities\OpportunitySealRelation && $entities->status == 1) {
                    //PREENCHENDO O ARRAY COM O ID E NOME DA OPORTUNIDADE
                    $opportunits[$entities->owner->id] = $entities->owner->name;
               }
            }
            //dump($opportunits);

            //die;
            $this->part('sealcuidarmelhor/options-opportunity', ['opportunits' => $opportunits]);
        });

        $app->hook('GET(opportunity.allField)', function() use($app){
            dump($this->data);
        });
   }

    public function register() {
        // register metadata, taxonomies
        $app = App::i();
        $app->registerController('selo', 'SealCuidarMelhor\Controllers\Selo');
    }
}