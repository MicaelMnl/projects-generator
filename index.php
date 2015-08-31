<?php
// blabla
require_once 'conf/conf.php';
if(isset($_POST['submit'])){
    if(isset($_POST)){
        $error = new Errors();
        if(empty($_POST['USER']))
            $error->add ('Veuilliez sasir l\'utilisateur mysql.', 'USER');
        
        if(empty($_POST['HOST']))
            $error->add ('Veuilliez sasir le host.', 'HOST');
     
        if(empty($_POST['DBNAME']))
            $error->add ('Veuilliez sassir le nom de la base de donnés. ', 'DBNAME');
        
        if(empty($_POST['PATH']))
            $error->add ('Veuilliez sassir le chemi des fichers ', 'PATH');
        if(!$db = Utils::connectionBDD($_POST['HOST'], $_POST['DBNAME'], $_POST['USER'], $_POST['PASSWORD']))
                $error->add ('Impossible de ce connecter a la base de données ', 'DBERROR');
        
        if($error->isEmpty()){
            $categorie = (isset($_POST['CATEGORIE']) && !empty($_POST['CATEGORIE'])) ? $_POST['CATEGORIE'] : '0';
            switch($categorie){
                case '1': 
                    $chemin = $_POST['PATH'];
                    // create folder principale
                    //Utils::creatFolder($chemin);
                    // Racine
                    define("CHEMIN" ,$chemin);
                    //copiage structure des dossier 
                    Utils::recurse_copy('structures/Twig/', CHEMIN);
                    //search data base 
                    $db = Utils::connectionBDD($_POST['HOST'], $_POST['DBNAME'], $_POST['USER'], $_POST['PASSWORD']);
                    // récuperation des tables de la base des donnes 
                    $tables = Utils::showTables($db);

                    foreach($tables as $table){
                        $bddName = 'Tables_in_'.$_POST['DBNAME'];
                        $describes = Utils::showDescribeOfTables($db,$table[$bddName]);
                        //creat class
                        Utils::creatFilePhp ($chemin.'/'.'classes/',  ucfirst($table[$bddName]));
                        Utils::writeAPhpClass($chemin.'/'.'classes/',ucfirst($table[$bddName]),$describes);
                        Utils::creatFilePhp ($chemin.'/'.'managers/',  ucfirst($table[$bddName]).'Manager');
                        Utils::writeAPhpManager($chemin.'/'.'managers/', ucfirst($table[$bddName]).'Manager',$describes,$table[$bddName]);
                        Utils::creatFilePhp ($chemin.'/'.'conf/',  'conf');
                        Utils::writeAPhpConf($chemin, $_POST['USER'], $_POST['DBNAME'], $_POST['PASSWORD'],$_POST['HOST']);
                    }
                    break;
                    
                case '2' :
                    $chemin = $_POST['PATH'];
                    // create folder principale
                    //Utils::creatFolder($chemin);
                    // Racine
                    define("CHEMIN" ,$chemin);
                    //copiage structure des dossier 
                    Utils::recurse_copy('structures/POO/', CHEMIN);
                    //search data base 
                    $db = Utils::connectionBDD($_POST['HOST'], $_POST['DBNAME'], $_POST['USER'], $_POST['PASSWORD']);
                    // récuperation des tables de la base des donnes 
                    $tables = Utils::showTables($db);

                    foreach($tables as $table){
                        $bddName = 'Tables_in_'.$_POST['DBNAME'];
                        $describes = Utils::showDescribeOfTables($db,$table[$bddName]);
                        //creat class
                        Utils::creatFilePhp ($chemin.'/'.'classes/',  ucfirst($table[$bddName]));
                        Utils::writeAPhpClass($chemin.'/'.'classes/',ucfirst($table[$bddName]),$describes);
                        Utils::creatFilePhp ($chemin.'/'.'managers/',  ucfirst($table[$bddName]).'Manager');
                        Utils::writeAPhpManager($chemin.'/'.'managers/', ucfirst($table[$bddName]).'Manager',$describes,$table[$bddName]);
                        Utils::creatFilePhp ($chemin.'/'.'conf/',  'conf');
                        Utils::writeAPhpConfPOO($chemin, $_POST['USER'], $_POST['DBNAME'], $_POST['PASSWORD'],$_POST['HOST']);
                    }
                break;
            
                case '3' :
                        $chemin = $_POST['PATH'];
                        // create folder principale
                        //Utils::creatFolder($chemin);
                        // Racine
                        define("CHEMIN" ,$chemin);
                        //copiage structure des dossier 
                        Utils::recurse_copy('structures/libs/', CHEMIN);
                        //search data base 
                        $db = Utils::connectionBDD($_POST['HOST'], $_POST['DBNAME'], $_POST['USER'], $_POST['PASSWORD']);
                        // récuperation des tables de la base des donnes 
                        $tables = Utils::showTables($db);

                        foreach($tables as $table){
                            $bddName = 'Tables_in_'.$_POST['DBNAME'];
                            $describes = Utils::showDescribeOfTables($db,$table[$bddName]);
                            //creat class
                            Utils::creatFilePhp ($chemin.'/'.'classes/',  ucfirst($table[$bddName]));
                            Utils::writeAPhpClass($chemin.'/'.'classes/',ucfirst($table[$bddName]),$describes);
                            Utils::creatFilePhp ($chemin.'/'.'managers/',  ucfirst($table[$bddName]).'Manager');
                            Utils::writeAPhpManager($chemin.'/'.'managers/', ucfirst($table[$bddName]).'Manager',$describes,$table[$bddName]);
                        }
                break;
            
                case '0' :
                    exit();
                break;
            }
                
            

        }
        
    }
  
    
}
echo $twig->render('index.twig',array(
    'POST' => (isset($_POST)) ? $_POST : null,
    'errors' => (isset($error)) ? $error : null
  
));


