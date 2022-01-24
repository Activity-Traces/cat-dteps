<?php

namespace App\Service;

use App\Repository\DictionaryRepository;
use Stichoza\GoogleTranslate\GoogleTranslate;


class translatewords
{

    private $word;
    private $translation;
    private $translations;
    private $synonym;
    private $message;


    private $languagein;
    private $languageout;



    function __construct($word, $languagein, $languageout)
    {
        $this->word = $word;
        $this->translation = "";
        $this->translations = "";
        $this->synonym = "";
        $this->message = "";


        $this->languagein = $languagein;
        $this->languageout = $languageout;
    }

    //*************************************************************************************************************************** */
    public function translate(DictionaryRepository $rep)
    {

        // chercher en premier dans le dictionnaire 
        $record = $rep->findBy(['word' => $this->word]);

        if (!empty($record[0])) {

            $this->translation = $record[0]->getTranslate();
            $this->translations = $record[0]->getTranslated();
            $this->synonym = $record[0]->getElquals();
            $this->message = ' ( Mot trouvé dans le dictionnaire local )';
        }
        // rechercher dans google Translate
        else {
            $this->message = ' (Mot trouvé dans Google Translate )';

            $tr = new GoogleTranslate();

            $tr->setSource($this->languagein);
            $tr->setTarget($this->languageout);

            $this->translation =  $tr->translate($this->word);

            $temp = $tr->getResponse($this->word);

            if (isset($temp[1][0][1])) {
                //  $TempResults = $temp[1][0][1];
                //  $TempEquals = $temp[1][0][2][0][1];

                foreach ($temp[1][0][1] as $val)
                    $this->translations = $this->translations . $val . ',';


                foreach ($temp[1][0][2][0][1] as $val)
                    $this->synonym = $this->synonym . $val . ',';
            }
            unset($tr);
        }
    }



    /**
     * Get the value of translation
     */
    public function getTranslation()
    {
        return $this->translation;
    }


    /**
     * Get the value of translations
     */
    public function getTranslations()
    {
        return $this->translations;
    }



    /**
     * Get the value of synonym
     */
    public function getSynonym()
    {
        return $this->synonym;
    }



    /**
     * Get the value of message
     */
    public function getMessage()
    {
        return $this->message;
    }


    /**
     * Set the value of languagein
     *
     * @return  self
     */
}
