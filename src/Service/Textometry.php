<?php

namespace App\Service;

use Normalizer;
use ZipArchive;
use App\Entity\Resource;
use Symfony\Component\HttpFoundation\Response;

class Textometry
{

    private $words;
    private $txt;
    private $sort;
    private $final;
    private $folderIn;
    private $folderOut;
    private $pattern;

    //************************************************************************************************************************************* */


    public function setFolderIn($folderIn)
    {
        $this->folderIn = $folderIn;
    }

    //************************************************************************************************************************************* */

    public function setFolderOut($folderOut)
    {
        $this->folderOut = $folderOut;
    }


    //************************************************************************************************************************************* */


    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    //************************************************************************************************************************************* */

    public function setFInal($final)
    {
        $this->final = $final;
    }

    public function setWords($words)
    {
        $this->words = $words;
    }

    public function setTxt($txt)
    {

        $this->txt = $txt;
    }

    public function setClean($clean)
    {

        $this->clean = $clean;
    }

    public function setSort($sort)
    {

        $this->sort = $sort;
    }


    //************************************************************************************************************************************* */
    //************************************************************************************************************************************* */

    public function getWords()
    {

        return ($this->words);
    }

    public function getTxt()
    {

        return ($this->txt);
    }

    public function getFinal()
    {

        return ($this->final);
    }


    //************************************************************************************************************************************* */

    public function getWordCount($word)
    {
        $count = 0;


        $this->clean();


        if (preg_match_all('~\p{L}+~', $this->txt, $matches) > 0) {

            foreach ($matches[0] as $w) {
                if ($w == $word)
                    $count++;
            }
        }

        return $count;
    }


    //************************************************************************************************************************************* */

    public function getWordsCount()
    {

        $this->clean();

        if (preg_match_all('~\p{L}+~', $this->txt, $matches) > 0) {
            foreach ($matches[0] as $w) {
                $this->words[$w] = isset($this->words[$w]) === false ? 1 : $this->words[$w] + 1;
            }

            if ($this->sort == 'defaultdown')
                ksort($this->words);

            if ($this->sort == 'defaultup')
                krsort($this->words);

            if ($this->sort == 'down')
                asort($this->words);

            if ($this->sort == 'up')
                arsort($this->words);
        }
    }
    //************************************************************************************************************************************* */

    public function clean()
    {
        /** Mise en minuscules (cha??ne utf-8 !) */
        $this->txt = mb_strtolower($this->txt, 'utf-8');
        /** Nettoyage des caract??res */
        mb_regex_encoding('utf-8');
        $this->txt = trim(preg_replace('/ +/', ' ', mb_ereg_replace('[^0-9\p{L}]+', ' ', $this->txt)));
        /** strtr() sait g??rer le multibyte */
        $this->txt = strtr($this->txt, array(
            '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', 'a' => 'a', 'a' => 'a', 'a' => 'a', '??' => 'c', 'c' => 'c', 'c' => 'c', 'c' => 'c', 'c' => 'c', 'd' => 'd', 'd' => 'd', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'e', 'e' => 'e', 'e' => 'e', 'e' => 'e', 'e' => 'e', 'e' => 'e', 'g' => 'g', 'g' => 'g', 'g' => 'g', 'h' => 'h', 'h' => 'h', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i', 'i' => 'i', 'i' => 'i', 'i' => 'i', 'i' => 'i', 'i' => 'i', '?' => 'i', 'j' => 'j', 'k' => 'k', '?' => 'k', 'l' => 'l', 'l' => 'l', 'l' => 'l', '?' => 'l', 'l' => 'l', '??' => 'n', 'n' => 'n', 'n' => 'n', 'n' => 'n', '?' => 'n', '?' => 'n', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', 'o' => 'o', 'o' => 'o', 'o' => 'o', '??' => 'o', '??' => 'o', 'r' => 'r', 'r' => 'r', 's' => 's', 's' => 's', 's' => 's', '??' => 's', '?' => 's', 't' => 't', 't' => 't', 't' => 't', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'u', 'u' => 'u', 'u' => 'u', 'u' => 'u', 'u' => 'u', 'u' => 'u', 'u' => 'u', 'w' => 'w', '??' => 'y', '??' => 'y', 'y' => 'y', 'z' => 'z', 'z' => 'z', '??' => 'z'
        ));
    }
    //************************************************************************************************************************************* */

    public function removeAccents()
    {
        //Normalisation de la chaine utf8 en mode caract??re + accents
        $this->txt = Normalizer::normalize($this->txt, Normalizer::FORM_D);
        //Suppression des accents
        return preg_replace('~\p{Mn}~u', '', $this->txt);
    }

    //************************************************************************************************************************************* */

    public function deleteXMLTags()
    {

        $folder = opendir($this->folderIn);
        $replacement = ' ';


        while ($file = readdir($folder)) {

            if ($file != '.' && $file != '..' && !is_dir($this->folderIn . $file)) {

                $outputFile = file_get_contents($this->folderIn . $file);

                $output = preg_replace($this->pattern, $replacement, $outputFile);
                $filesres = pathinfo($this->folderOut . $file, PATHINFO_FILENAME);
                file_put_contents($this->folderOut . $filesres . '.xml', $output);
            }
        }

        closedir($folder);
    }
    //*********************************************************************************************** */

    public function uploadFiles(): Response
    {
        $zip = new ZipArchive();
        $zipname = $this->folderOut . 'uploaded.zip';
        $zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('wuxiancheng.cn.txt', 'yes');

        $folder = opendir($this->folderOut);

        while ($file = readdir($folder))
            if ($file)

                $zip->addFile($this->folderOut . $file);

        dump($zipname);
        $response = new Response(file_get_contents($zipname));
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $zipname . '"');
        $response->headers->set('Content-length', filesize($zipname));

        return $response;
    }
}
