<?php
/**
 * CLASS UTILS
 *
 * Regroupe un certain nombre de fonctions utilitaires
 * Vérification numéro SIRET, adresse e-mail, code postal FR, position tableau
 * Ajout dans tableau sérialisé
 * Affichage popin jquery fancybox
 * Cryptage / décryptage
 * Nettoyage chaîne pour url
 * 
 */
class Utils {

    /**
     * 
     * @param type $data
     */
    public static function json($data) {
//	header('Content-type: application/json');
        echo json_encode($data);
        exit();
    }

    /**
     * Redirection vers l'url URL
     *
     * @param string $url URL de destination
     * @param string $message Message d'information
     * @param string $level Niveau de priorité du message. success/info/warning/danger
     */
    public static function redirect($url, $message, $level = 'info') {
        $_SESSION['toast_message'] = $message;
        $_SESSION['toast_level'] = $level;
        header('location: ' . $url);
        exit();
    }

    /**
     * Validation numéro SIRET
     *
     * @param  int    $val  Le numéro SIRET à vérifier
     * @return bool
     */
    private function checkLuhn($val) {
        $len = strlen($val);
        $total = 0;
        for ($i = 1; $i <= $len; $i++) {
            $chiffre = substr($val, -$i, 1);
            if ($i % 2 == 0) {
                $total += 2 * $chiffre;
                if ((2 * $chiffre) >= 10)
                    $total -= 9;
            }else {
                $total += $chiffre;
            }
        }
        if ($total % 10 == 0)
            return true;
        else
            return false;
    }

    public function validSiret($num) {
        if (!is_numeric($num)) {
            throw new Exception('Votre numéro SIRET n\'est pas valide');
        }
        if (!self::checkLuhn($num)) {
            throw new Exception('Votre numéro SIRET n\'est pas valide');
        }
        return true;
    }

    /**
     * Validation adresse email
     *
     * @param  string    $mail  Le mail à vérifier
     * @return bool
     */
    public static function validMail($mail) {
        if (empty($mail)) {
            return false;
        }
        $syntax = '#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
        if (!preg_match($syntax, $mail)) {
            return false;
        }

        if (filter_var($mail, FILTER_VALIDATE_EMAIL) == false) {
            return false;
        }
        return true;
    }

    /**
     * Validation code postal FRANCAIS
     *
     * @param  int    $cp  Le code postal à vérifier
     * @return bool
     */
    public function validCPFR($cp) {
        if (preg_match('#^[0-9]{5}$#', $cp)) {
            return true;
        } else {
            return false;
        }
    }

    public static function encrypt($val, $key = 'siteenligne', $iv = 'hmco3773') {
        $key_size = mcrypt_module_get_algo_key_size(MCRYPT_BLOWFISH);
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_NOFB);
        $key = substr($key, 0, $key_size);
        $crypte = mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $val, MCRYPT_MODE_NOFB, $iv);
        $crypte = base64_encode($crypte);
        $crypte = urlencode($crypte);
        return $crypte;
    }

    public static function decrypt($val, $key = 'siteenligne', $iv = 'hmco3773') {
        $key_size = mcrypt_module_get_algo_key_size(MCRYPT_BLOWFISH);
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_NOFB);
        $key = substr($key, 0, $key_size);
        $crypte = urldecode($val);
        $crypte = base64_decode($crypte);
        $crypte = mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $crypte, MCRYPT_MODE_NOFB, $iv);
        return $crypte;
    }

    public static function genPass($chrs = "") {
        if ($chrs == "")
            $chrs = 8;
        $list = "0123456789abscdefghijkmnpqrstuvwxyz";
        mt_srand((double) microtime() * 1000000);
        $newstring = "";
        while (strlen($newstring) < $chrs) {
            $newstring .= $list[mt_rand(0, strlen($list) - 1)];
        }
        return $newstring;
    }

    public static function test() {
        return 'ok';
    }

    /**
     * Affiche une popin à l'aide de fancybox
     *
     * @param  string   $libelle  Titre de la popin
     * @param  string   $message  Contenu textuel de la popin
     * @return void   
     */
    public function showPopIn($libelle, $message, $redirect = null) {
        ?>
        <div id = "show-popin" class="popin center" style="width: 750px; height: 250px; padding-top: 25px;">
            <div class="quickR fs23 to-upper center"><?php echo $libelle; ?></div>	<br /><br />

            <span class="fs14"><?php echo $message; ?></span>
        </div>	

        <script type="text/javascript">
            $(document).ready(function () {
                $.fancybox({
                    href: '#show-popin',
                    afterClose: function () {
        <?php if ($redirect) { ?>setTimeout(function () {
                                window.location = 'index.html';
                            }, 1000);<?php } ?>
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * @param $date
     * @return string
     */
    public static function pickerToDateTime($date) {
        $array = explode('/', $date);
        return $array[2] . '/' . $array[1] . '/' . $array[0] . ' 00:00:00';
    }

    /**
     * Affiche un nombre
     *
     * @param  string   $nb le nombre 
     * @param  string   $round  decimal
     * @return float   
     */
    public function displayNumber($nb, $round = 2) {
        return number_format($nb, $round, ',', ' ');
    }

    /**
     * applique une taxe à un montant
     *
     * @param  string   $nb le nombre 
     * @param  string   $round  decimal
     * @return float   
     */
    public function applyTax($price, $tax) {
        return round($price * $tax / 100 + $price, 2);
    }

    /**
     * remplace les caractères accentués dans une chaine
     *
     * @param  string   $str la chaîne 
     * @return string   
     */
    public static function replaceAccents($str) {
        $unwanted_array = array('Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
            'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y');
        $string = strtr($str, $unwanted_array);
        return $string;
    }

    /**
     * Supprime les caractères spéciaux d'une chaine
     *
     * @param  string   $str la chaîne 
     * @return string   
     */
    private static function toAscii($str, $replace = array(), $delimiter = '-') {

        if (!empty($replace)) {
            $str = str_replace((array) $replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

    /**
     * Affiche un mois en lettre
     *
     * @param  string   $id  Numéro du mois
     * @param  string   $lang  choix de la langue
     * @return string
     */
    public static function getMonth($id = null, $lang = "fr") {
        $_TMP['fr']["01"] = "janvier";
        $_TMP['fr']["02"] = "février";
        $_TMP['fr']["03"] = "mars";
        $_TMP['fr']["04"] = "avril";
        $_TMP['fr']["05"] = "mai";
        $_TMP['fr']["06"] = "juin";
        $_TMP['fr']["07"] = "juillet";
        $_TMP['fr']["08"] = "août";
        $_TMP['fr']["09"] = "septembre";
        $_TMP['fr']["10"] = "octobre";
        $_TMP['fr']["11"] = "novembre";
        $_TMP['fr']["12"] = "décembre";

        $_TMP['en']["01"] = "january";
        $_TMP['en']["02"] = "february";
        $_TMP['en']["03"] = "march";
        $_TMP['en']["04"] = "april";
        $_TMP['en']["05"] = "may";
        $_TMP['en']["06"] = "june";
        $_TMP['en']["07"] = "july";
        $_TMP['en']["08"] = "august";
        $_TMP['en']["09"] = "september";
        $_TMP['en']["10"] = "october";
        $_TMP['en']["11"] = "november";
        $_TMP['en']["12"] = "december";

        if (isset($id) && strlen($id) > 0 && $id >= 1 && $id <= 12) {
            return $_TMP[$lang][$id];
        } else {
            return $_TMP[$lang];
        }
    }

    public static function datetimeToStr($date) {
        $date = str_replace(' 00:00:00', '', $date);
        $array = explode('-', $date);
        return $array[2] . ' ' . self::getMonth($array[1]) . ' ' . $array[0];
    }

    /**
     * Nettoie une url 
     *
     * @param  string   $url le lien 
     * @return string   
     */
    public static function cleanUrl($url) {
        $accentClean = self::replaceAccents($url);
        $espaceClean = str_replace(" ", "-", $accentClean);
        $espaceClean = str_replace("'", "-", $accentClean);
        $urlClean = strtolower($espaceClean);
        return $urlClean;
    }

    public static function formatUrlAgency($url) {
        $accentClean = self::replaceAccents($url);
        $miniscule = strtolower($accentClean);
        $espaceClean = str_replace(" ", "", $miniscule);
        $urlClean = str_replace("/", "-", $espaceClean);
        return $urlClean;
    }

    /**
     * Upload un fichier PDF sur le serveur
     * @param string $dir le chemin de l'upload
     * @param string $file $_FILES
     * @param string $filename le nom du fichier
     * @return string
     */
    public static function uploadPdf($dir, $file, $filename) {
        $_UPLOAD['MAXSIZE'] = "2000000";
        $_UPLOAD['MIMETYPEFILE'] = array('application/pdf');
        $_UPLOAD['TYPEFILE'] = array('pdf');

        $result = array();
        $extension = strtolower(substr($file['name'], -3));

        if ($extension != 'pdf') {
            $result['retour'] = "erreur";
            $result['message'] = "Le fichier n'a pas l'extension attendue.";
        } elseif ($file['size'] > $_UPLOAD['MAXSIZE']) {
            $result['retour'] = "erreur";
            $result['message'] = "Le fichier est trop volumineux (max 2Mo).";
        } else {
            if (move_uploaded_file($file['tmp_name'], $dir . $filename)) {
                $result['retour'] = "ok";
            } else {
                $result['retour'] = "erreur";
                $result['message'] = "Une erreur est survenue.";
            }
        }
        return $result;
    }

    /**
     * Upload une image JPG ou PNG sur le serveur
     * @param string $dir le chemin de l'upload
     * @param string $file $_FILES
     * @param string $filename le nom du fichier
     * @return string
     */
    /*
      elseif ($file['size'] > $_UPLOAD['MAXSIZE']) {
      $result['retour'] = "erreur";
      $result['message'] = "L'image est trop volumineuse (max 2Mo).";
      }
     */

    public static function uploadImage($dir, $file, $filename) {

//        $_UPLOAD['MAXSIZE'] = "2000000";
        $_UPLOAD['MIMETYPEIMAGE'] = array('image/jpeg', 'image/png','image/gif');
        $_UPLOAD['TYPEIMAGE'] = array('jpg', 'jpeg', 'png', 'PNG', 'JPG', 'JPEG','gif','GIF');

        $result = array();
        $extension = strtolower(substr($file['name'], -3));

        if (!in_array($file['type'], $_UPLOAD['MIMETYPEIMAGE']) || !in_array($extension, $_UPLOAD['TYPEIMAGE'])) {
            $result['retour'] = "erreur";
            $result['message'] = "L'image n'a pas l'extension attendue.";
        } else {
            if (move_uploaded_file($file['tmp_name'], $dir . $filename)) {
                $result['retour'] = "ok";
            } else {
                $result['retour'] = "erreur";
                $result['message'] = "Une erreur est survenue.";
            }
        }
        return $result;
    }

    /**
     * supprime un fichier du serveur
     *
     * @param  string   $file
     *
     * @return void
     */
    public static function removeFile($url, $file) {

        if (file_exists($url . $file)) {
            unlink($url . $file);
        }
    }

    public static function objToArray($d) {
        if (is_object($d)) {
            $d = get_object_vars($d);
        }

        return is_array($d) ? array_map(__METHOD__, $d) : $d;
    }

    public static function csvToArray($file) {
        $rows = array();
        $headers = array();
        if (file_exists($file) && is_readable($file)) {
            $handle = fopen($file, 'r');
            while (!feof($handle)) {
                $row = fgetcsv($handle, 10240, ',', '"');
                if (empty($headers))
                    $headers = $row;
                else if (is_array($row)) {
                    array_splice($row, count($headers));
                    $rows[] = array_combine($headers, $row);
                }
            }
            fclose($handle);
        } else {
            throw new Exception($file . ' doesn`t exist or is not readable.');
        }
        return $rows;
    }

    public static function linebreaks($str) {
        return str_replace("\r", "\n", str_replace("\r\n", "\n", $str));
    }

    public static function sendEmail($template, $infos) {
        // Chargement du template
        $mail_message = trim(file_get_contents('views/' . $template));

        foreach ($infos AS $info_key => $info_value)
            $mail_message = str_replace('*' . $info_key . '*', $info_value, $mail_message);

        // Default sender/return address
        if (!isset($infos['from']) OR empty($infos['from']))
            $infos['from'] = '"Siteenligne" <info@siteenligne.fr>';

        // Do a little spring cleaning
        $to = isset($infos['to']) ? trim(preg_replace('#[\n\r]+#s', '', $infos['to'])) : 'info@siteenligne.fr';
        $from = trim(preg_replace('#[\n\r:]+#s', '', $infos['from']));

        $headers = 'From: ' . $from . "\r\n" . 'Return-Path: ' . $from . "\r\n" . 'Date: ' . date('r') . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-transfer-encoding: 8bit' . "\r\n" . 'Content-type: text/plain; charset=utf-8' . "\r\n" . 'X-Mailer: Sitenligne Mailer';

        // Make sure all linebreaks are CRLF in message (and strip out any NULL bytes)
        $message = str_replace(array("\n", "\0"), array("\r\n", ''), self::linebreaks($mail_message));

        if (isset($infos['bcc'])) {
            foreach ($infos['bcc'] AS $bcc)
                $headers .= "\r\n" . 'Bcc: ' . $bcc;
        }

        // Change the linebreaks used in the headers according to OS
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'MAC')
            $headers = str_replace("\r\n", "\r", $headers);
        else if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN')
            $headers = str_replace("\r\n", "\n", $headers);

        mail($to, $infos['sujet'], $message, $headers);
    }

    public static function createCookie($email, $password) {
        setcookie(CUSTOMER_COOKIE_NAME, serialize(array($email, MD5($password . CUSTOMER_COOKIE_SEED))));
    }

    public static function removeCookie() {
        setcookie(CUSTOMER_COOKIE_NAME, '', -1);
    }

}