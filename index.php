<?

//Slim
require 'libs/Slim/Slim.php';

// Paris and Idiorm
require 'libs/Paris/idiorm.php';
require 'libs/Paris/paris.php';

// Models
require 'models/Project.php';

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

    if(isset($payload->repository) && isset($payload->ref)) {
		$payload_branch = explode("/", $payload->ref);
		$payload_branch = $payload_branch[2];
		
    	//Check to see if repo is in the db
    	$project = Model::factory('Project')
    				->where_equal('name', $payload->repository->name)
    				->where_equal('branch', $payload_branch)
    				->find_one();

		$file = 'requests/'.$payload->after.'.txt';
		$content = $project->path.'|'.$project->branch;

		file_put_contents($file, $content, LOCK_EX);
    	
	} else {
		$app->halt(400);
	}
});

$app->run();