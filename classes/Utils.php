<?php
class Utils {
    /**
     * 
     * @param type $host
     * @param type $dbname
     * @param type $user
     * @param type $password
     * @return \PDO|boolean
     */
    public static function connectionBDD($host,$dbname,$user,$password){
        if(isset($host) && !empty($host) && isset($dbname) && !empty($dbname) && isset($user) && !empty($user)){  
            try{
               $pdo = new PDO('mysql:host='.$host.';dbname='.$dbname.'',$user,$password);
               $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,  PDO::FETCH_ASSOC);
               $pdo->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_WARNING);
               $pdo->query('SET NAMES UTF8');

            }
            catch(exeption $e){
               echo $e->getMessage();
               return False;
            }
            return $pdo;
           
        }
        else{
            return False;
        }
    }
   /**
    * 
    * @param type $db
    * @return type
    */
    public static function showTables($db){
        $sql = $db->query('SHOW FULL TABLES ');
        while($data = $sql->fetch(PDO::FETCH_ASSOC))
                $tables[] = $data;
        return $tables ;
        
    }
    public static function showDescribeOfTables($db,$table){
        $sql = $db->query('DESCRIBE '.$table);
        while($data = $sql->fetch(PDO::FETCH_ASSOC))
                $describes[] = $data;
        return $describes ;
        
    }
    
    /**
     * 
     * @param type $nom
     */
    public static function creatFolder($nom){
      
        mkdir($nom, 0700);
        
    }
    /**
     * 
     * @param type $chemin
     * @param type $nom
     * @return type
     */
    public static function creatFilePhp($chemin,$nom){
      
        $file = fopen($chemin.'/'.$nom.'.php', 'w+'); 
        return $file;
    }
   /**
    * 
    * @param type $chemin
    * @param type $fileName
    * @param type $describes
    */
    public static function writeAPhpClass($chemin,$fileName,$describes){
        $file = fopen($chemin.'/'.$fileName.'.php','w');
        $sautLigne = "\n";
        $classAttributs = '';
        $getters = '';
        $setters ='';
        foreach($describes as $describe){
            $classAttributs .= '    private $'.$describe['Field'].';'.$sautLigne;
            $construct = '    public function __construct($'.lcfirst($fileName).' =array()) {'.$sautLigne
                        .'        parent::__construct($'.lcfirst($fileName).');'.$sautLigne
                        .'    }';
            
            $getters .=  $sautLigne.'    function get'.ucfirst($describe['Field']).'() {'.$sautLigne
                        .'        return $this->'.$describe['Field'].';'.$sautLigne  
                        .'    }'.$sautLigne;
            
            $setters .=  $sautLigne.'    function set'.ucfirst($describe['Field']).'($'.lcfirst($describe['Field']).') {'.$sautLigne
                        .'        return $this->'.$describe['Field'].' = $'.lcfirst($describe['Field']).' ;'.$sautLigne  
                        .'    }'.$sautLigne;
        }
        $texte =  '<?php'.$sautLigne
                .'class '.ucfirst($fileName).' extends Object {'.$sautLigne.$sautLigne
                .$classAttributs.$sautLigne
                .$construct.$sautLigne
                .$getters.$sautLigne
                .$setters.$sautLigne
                .'}'.$sautLigne;
                
        fwrite ($file, $texte);
        fclose($file);
        
    }
    public static function writeAPhpManager($chemin,$fileName,$describes,$className){
        $file = fopen($chemin.'/'.$fileName.'.php','w');
        $sautLigne = "\n";
        $getByID ='';
        $addparams = "";
        $addparamsValues = "";
        $addparams2 = "";
        $addparamsUpdate2 = "";
        $addparamsUpdate = "";
        $idtest="";
        $dernireValeur = end($describes);
        $compteur = 0;
        foreach($describes as $key => $condition){
            if($compteur == 0){
                $addparamsUpdate2 .= '            $pdo->bindValue(\':'.$condition['Field'].'\',$'.$className.'->get'.ucfirst($condition['Field']).'());'.$sautLigne;
                $idtest .= '$pdo->bindValue(\':'.$condition['Field'].'\',$'.$className.'->get'.ucfirst($condition['Field']).'());'.$sautLigne;
            }
            if($compteur != 0){
            if($dernireValeur != $condition ){
                $addparams .= ''.$condition['Field'].',';
                $addparamsUpdate .= $condition['Field'].'=:'.$condition['Field'].',';
                $addparamsValues .= ':'.$condition['Field'].',';
            }else{
                $addparams .= ''.$condition['Field'].' ';
                 $addparamsValues .= ':'.$condition['Field'].'';
                $addparamsUpdate .= $condition['Field'].'=:'.$condition['Field'].'';
            }
            $addparams2 .= '            $pdo->bindValue(\':'.$condition['Field'].'\',$'.$className.'->get'.ucfirst($condition['Field']).'());'.$sautLigne;
            $addparamsUpdate2 .= '            $pdo->bindValue(\':'.$condition['Field'].'\',$'.$className.'->get'.ucfirst($condition['Field']).'());'.$sautLigne;
            
            
            };
           
            
            $compteur++;
            };
        foreach($describes as $describe){
            $describeName[] = $describe['Field'];
            $id = strtolower(substr($describes[0]['Field'], 0, 2));
            $conditionID = ($id === 'id') ? $describes[0]['Field'].'=:'.$describes[0]['Field'] : '';
            $getAll = '    public static function getAll'.ucfirst($className).'(){'.$sautLigne
                     .'        $pdo = Database::getInstance()->query(\'SELECT * FROM '.$className.'\');'.$sautLigne
                     .'        while($datas = $pdo->fetch(PDO::FETCH_ASSOC)){'.$sautLigne
                     .'             $'.$className.'[] = new '.ucfirst($className).'($datas);'.$sautLigne
                     .'        }'.$sautLigne
                     .'        return (isset($'.$className.')) ? $'.$className.' : null ;'.$sautLigne
                     .'    }'.$sautLigne;
            if($id === 'id')
                $getByID = '    public static function get'.ucfirst($className).'ById($id){'.$sautLigne
                         .'        $pdo = Database::getInstance()->prepare(\'SELECT * FROM '.$className.' WHERE '.$describes[0]['Field'].'=:'.$describes[0]['Field'].'\');'.$sautLigne
                         .'        $pdo->bindValue(\':'.$describes[0]['Field'].'\',$id);'.$sautLigne
                         .'        $pdo->execute();'.$sautLigne
                         .'        $data = $pdo->fetch(PDO::FETCH_ASSOC);'.$sautLigne
                         .'        $'.$className.' = new '.ucfirst($className).'($data);'.$sautLigne
                         .'        return ($data != false ) ? $'.$className.' : false;'.$sautLigne
                         .'    }'.$sautLigne;

                $add = '    public static function add'.ucfirst($className).'('.ucfirst($className).' $'.$className.'){'.$sautLigne
                      .'        try { '.$sautLigne
                      .'            $pdo = Database::getInstance()->prepare(\'INSERT INTO  '.$className.' ('.$addparams.') VALUES ('.$addparamsValues.')\');'.$sautLigne
                      .''.$addparams2
                      .'            $pdo->execute();'.$sautLigne
                      .'            $'.$className.'->set'.  ucfirst($describes[0]['Field']).'(Database::getInstance()->lastInsertId());'.$sautLigne
                      .'        }'.$sautLigne
                      .'        catch (PDOException $e) {'.$sautLigne
                      .'            echo $e->getMessage();'.$sautLigne
                      .'        }'.$sautLigne
                      .'    }'.$sautLigne;
                
                $update = '    public static function update'.ucfirst($className).'('.ucfirst($className).' $'.$className.'){'.$sautLigne
                      .'        try { '.$sautLigne
                      .'            $pdo = Database::getInstance()->prepare(\'UPDATE  '.$className.' SET '.$addparamsUpdate.' WHERE '.$conditionID.' \');'.$sautLigne
                      .''.$addparamsUpdate2
                      .'            $pdo->execute();'.$sautLigne
                      .'        }'.$sautLigne
                      .'        catch (PDOException $e) {'.$sautLigne
                      .'            echo $e->getMessage();'.$sautLigne
                      .'        }'.$sautLigne
                      .'    }'.$sautLigne;
                $delete = '    public static function delete'.ucfirst($className).'('.ucfirst($className).' $'.$className.'){'.$sautLigne
                        .'      $pdo = Database::getInstance()->prepare(\'DELETE FROM '.$className.' WHERE '.$conditionID.' \');'.$sautLigne
                        .'      '.$idtest.''.$sautLigne
                        .'      $pdo->execute();'.$sautLigne
                        .'    }'.$sautLigne;
        }
        $texte =  '<?php'.$sautLigne
                .'class '.ucfirst($fileName).' {'.$sautLigne.$sautLigne
                .$getAll.$sautLigne
                .$getByID.$sautLigne
                .$add.$sautLigne
                .$update.$sautLigne
                .$delete.$sautLigne
                .'}'.$sautLigne;
                
        fwrite ($file, $texte);
        fclose($file);
    }
    public static function writeAPhpConf($chemin,$dbuser,$dbname,$dbpassword,$dbhost){
        $file = fopen($chemin.'/conf/conf.php','w');
        $sautLigne = "\n";
        $ipconfig = 'ftp.infos';
        $config = 'session_start();'.$sautLigne.$sautLigne
                .'include_once \'autoload.php\';'.$sautLigne
                .'include_once \'assets/libs/Twig/Autoloader.php\';'.$sautLigne.$sautLigne
                .'Twig_Autoloader::register();'.$sautLigne.$sautLigne
                .'$twig = new Twig_Environment(new Twig_Loader_Filesystem(\'templates\'), array('.$sautLigne
                .'    \'cache\' => false, // \'/path/to/compilation_cache\','.$sautLigne
                .'    \'debug\' => false'.$sautLigne
                .'));'.$sautLigne.$sautLigne
                .'$twig->addExtension(new Twig_Extension_Debug());'.$sautLigne
                .'$ip = $_SERVER[\'SERVER_ADDR\'];'.$sautLigne.$sautLigne
                .'if( $ip == \'127.0.0.1\' ){'.$sautLigne.$sautLigne
                .'    define(\'DB_HOST\', \''.$dbhost.'\');'.$sautLigne
                .'    define(\'DB_USER\', \''.$dbuser.'\');'.$sautLigne
                .'    define(\'DB_PASSWORD\', \''.$dbpassword.'\');'.$sautLigne
                .'    define(\'DB_NAME\', \''.$dbname.'\');'.$sautLigne
                .'    define(\'DB_PATH\', \''.$chemin.'\');'.$sautLigne.$sautLigne
                .'}'.$sautLigne
                .'else {'.$sautLigne.$sautLigne
                .'    define(\'DB_HOST\', \''.$ipconfig.'\');'.$sautLigne
                .'    define(\'DB_USER\', \''.$ipconfig.'\');'.$sautLigne
                .'    define(\'DB_PASSWORD\', \''.$ipconfig.'\');'.$sautLigne
                .'    define(\'DB_NAME\', \''.$ipconfig.'\');'.$sautLigne
                .'    define(\'DB_PATH\', \''.$ipconfig.'\');'.$sautLigne.$sautLigne
                .'}'.$sautLigne;
        $texte =  '<?php'.$sautLigne
                .''.$config.$sautLigne;
                
        fwrite ($file, $texte);
        fclose($file);
        
      }
    public static function writeAPhpConfPOO($chemin,$dbuser,$dbname,$dbpassword,$dbhost){
        $file = fopen($chemin.'/conf/conf.php','w');
        $sautLigne = "\n";
        $ipconfig = 'ftp.infos';
        $config = 'session_start();'.$sautLigne.$sautLigne
                .'include_once \'autoload.php\';'.$sautLigne
                .'$ip = $_SERVER[\'SERVER_ADDR\'];'.$sautLigne.$sautLigne
                .'if( $ip == \'127.0.0.1\' ){'.$sautLigne.$sautLigne
                .'    define(\'DB_HOST\', \''.$dbhost.'\');'.$sautLigne
                .'    define(\'DB_USER\', \''.$dbuser.'\');'.$sautLigne
                .'    define(\'DB_PASSWORD\', \''.$dbpassword.'\');'.$sautLigne
                .'    define(\'DB_NAME\', \''.$dbname.'\');'.$sautLigne
                .'}'.$sautLigne
                .'else {'.$sautLigne.$sautLigne
                .'    define(\'DB_HOST\', \''.$ipconfig.'\');'.$sautLigne
                .'    define(\'DB_USER\', \''.$ipconfig.'\');'.$sautLigne
                .'    define(\'DB_PASSWORD\', \''.$ipconfig.'\');'.$sautLigne
                .'    define(\'DB_NAME\', \''.$ipconfig.'\');'.$sautLigne
                .'}'.$sautLigne;
        $texte =  '<?php'.$sautLigne
                .''.$config.$sautLigne;
                
        fwrite ($file, $texte);
        fclose($file);
        
    }
    /**
     * 
     * @param type $src
     * @param type $dst
     */
    public static function recurse_copy($src,$dst) { 
     $dir = opendir($src); 
     @mkdir($dst); 
     while(false !== ( $file = readdir($dir)) ) { 
         if (( $file != '.' ) && ( $file != '..' )) { 
             if ( is_dir($src . '/' . $file) ) { 
                 Utils::recurse_copy($src . '/' . $file,$dst . '/' . $file); 
             } 
             else { 
                 copy($src . '/' . $file,$dst . '/' . $file); 
             } 
         } 
     } 
     closedir($dir); 
    }
    
    public static function createClass($name){
        
    }
    
}