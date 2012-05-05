<?

//Slim
require 'libs/Slim/Slim.php';

// Paris and Idiorm
require 'libs/Paris/idiorm.php';
require 'libs/Paris/paris.php';

// Models
require 'models/Article.php';

//Init DB
ORM::configure('mysql:host=localhost;dbname=slimjim');
ORM::configure('username', 'root');
ORM::configure('password', '');

//App with custom settings
$app = new Slim(array(
    'log.enable' => true,
    'log.path' => './logs',
    'log.level' => 4
));

//GET route
$app->get('/', function () {
    echo "Hello you've reached SlimJim!";
});

//POST route
$app->post('/deploy', function () use ($app) {
	
	//TODO: Check for github's IP
	$ip = $app->request()->getIp();
	
	//Get the repo or site URL

	//

	//Get the current branch
	/*$stringfromfile = file(ROOT . '/.git/HEAD', FILE_USE_INCLUDE_PATH);
	$stringfromfile = $stringfromfile[0]; 
	$explodedstring = explode("/", $stringfromfile);
	$current_branch = $explodedstring[2];*/

	$payload = $app->request()->params('payload');

    if(empty($payload)) {
    	$app->halt(403);
    }
    
    $payload = json_decode($payload); 

    if(isset($payload->ref) && $payload->ref === 'refs/heads/develop') {

    	//Check to see if repo is in the model
    	//if($payload->repository)

    	$commands = array(
	        'whoami',
	        /*'git fetch origin'
	        'git reset --hard',
	        'git rebase origin/develop develop',*/
	        'git status',
	    );

	    $output = '';
	    foreach($commands AS $command) {
	        $tmp = shell_exec($command);
	        $output .= "{$command}\n";
	        $output .= htmlentities(trim($tmp)) . "\n";
	    }
	    
	    echo $output;
    	
	} else {
		$app->halt(400);
	}
});

$app->run();
