<?php
namespace SealCuidarMelhor;
use \MapasCulturais\App;

class Plugin extends \SealModelTab\SealModelTemplatePlugin {

    function __construct($config = []) {
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

        $app = App::i();
        $data = $this->getModelData();

        $app->hook('template(seal.sealrelation.print-certificate):after', function($relation) use($app, $data){
            //Adicionando arquivos de estilo
           dump($app);
           dump($data);
        });
   }

    public function register() {
        // register metadata, taxonomies
        $app = App::i();
        $app->registerController('selo', 'SealCuidarMelhor\Controllers\Selo');
    }
}