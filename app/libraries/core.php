<?php 
   class Core {
    // URL format --> /controller/method/params
    protected $currentController = 'home';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct(){ //constructor
        //print_r($this->getURL());
        $url =$this->getURL();

        if($url ===null){
            $url =[];
        }
        // Check if the constructor exists
        if(!empty($url) && file_exists('../app/controllers/'.ucwords($url[0]).'.php')){
            // if the controller exsists load it
            $this ->currentController =ucwords($url[0]);

            // unset the controller in the url
            unset($url[0]);
        }

            // call the controller
            require_once '../app/controllers/'.$this->currentController.'.php';

            // instentiate the controller
            $this->currentController = new $this->currentController; // initially currentController holds a string value but now it holds a object of this class


            // check whether the method exits in the controller
            if(isset($url[1])){
            // exists set it to currentMethod
                if(method_exists($this->currentController,$url[1])){
                    $this->currentMethod = $url[1];

                    unset($url[1]);
                }
            }
            

            // get parameter list
            $this->params= $url ? array_values($url) : [];

            // call method and pass the parameter list
            call_user_func_array([$this->currentController,$this->currentMethod],$this->params);
        
    }

    public function getURL(){ // fucntion to get the url 
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'],'/');
            $url = filter_var($url,FILTER_SANITIZE_URL);
            $url = explode('/',$url);

            return $url;

        }
        return null; // if url is not set
    }
   } 


?>