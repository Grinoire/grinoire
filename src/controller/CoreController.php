<?php
declare(strict_types=1);

namespace grinoire\src\controller;

use Exception;


/**
 * This is the base for all controller, handle SuperGlobal
 *
 * Contains all data for actual controller, path, name, action, default view...
 */
class CoreController
{

    /**
     * @var  global[]
     */
    protected $get;

    /**
     * @var  global[]
     */
    protected $post;

    /**
     * @var  global[]
     */
    protected $session;

    /**
     * Directory name for controller
     * @var  string
     */
    private $classNameDirectory;

    /**
     * Path of called controller
     * @var string
     */
    private $controllerPath;

    /**
     * Name of the called controller
     * @var string
     */
    private $controllerName;

    /**
     * Nam of called action
     * @var string
     */
    private $actionName;

    /**
     * Name of the view to display
     * @var string
     */
    private $view;

    /**
     * Properties passed to display in view
     * @var string
     */
    private $properties = [];

    /**
     * View template
     * @var  string
     */
    private $layout = '';

    /**
     * @var string
     */
    private $newLayout = '';
    /**
     * --------------------------------------------------
     *     MAGIC METHOD
     * ------------------------------------------------------
     */



    /**
     * SelectionController Construct
     * @param  array  $get   Global $_GET
     * @param  array  $post  Global $_POST
     */
    public function __construct($get, $post)
    {
        $this->setGet($get);
        $this->setPost($post);
        $this->session = &$_SESSION;

        $folder = explode('\\', get_class($this));
        $classNameDirectory = $folder[count($folder) - 2];

        $this->classNameDirectory = $classNameDirectory;
    }


    /**
     * --------------------------------------------------
     * METHODS
     * --------------------------------------------------
     */

    /**
     * Initiates the controller
     * @param  string   $file       Name of the called file
     * @param  string   $action     Name of the called action
     * @return void
     */
    protected function init($file, $action) :void
    {
        $this->setcontrollerPath($file);
        $this->controllerName = basename($this->getcontrollerPath());
        $this->setActionName($action);
        $this->setLayout();
        $this->setView();
    }



    /**
     * Display view by default , or other passed in parameters
     * @param  bool         $layout         TRUE = Display HEADER & FOOTER by default
     * @param  string       $nameRender     Load a specific view
     * @param  array        $data           Data passed in view for display
     */
    public function render(bool $layout = false, $nameRender = "", $data = []) : void
    {
        if (!$data) {
            extract($this->getProperties());
        } else {
            extract($data);
        }

        if (!$nameRender) {
            $nameRender = $this->getView();
        }

        if ($layout && file_exists(DIR_VIEW . $this->getNewLayout() . $this->getLayout() . 'header.php')) {
            require(DIR_VIEW . $this->getNewLayout() . $this->getLayout() . 'header.php');
        }

        if (file_exists(stream_resolve_include_path($this->classNameDirectory . '/view/' . $nameRender . '.php'))) {
            require($this->classNameDirectory . '/view/' . $nameRender . '.php');
        } elseif (file_exists(DIR_VIEW . $this->getLayout(). $nameRender. '.php')) {
            require(DIR_VIEW . $this->getLayout(). $nameRender . '.php');
        } else {
            throw new Exception('Vue : (' . $nameRender . ') non trouvé');
        }

        if ($layout && file_exists(DIR_VIEW . $this->getNewLayout() . $this->getLayout() . 'footer.php')) {
            require(DIR_VIEW . $this->getNewLayout() . $this->getLayout() . 'footer.php');
        }
    }


    /**
     * Check if all fields needed exists and is not empty
     * @param   array   $required   Fields needed
     * @param   array   $fields     Super-global get or post
     * @return  bool                If one is empty or not isset return false
     */
    public function isRequiredPassed(array $required, array $fields ) :bool
    {
        foreach( $required as $item ) :
            if( !( isset( $fields[$item] ) && !empty( $fields[$item] ) ) )
                return FALSE;
        endforeach;

        return TRUE;
    }


    /**
     * --------------------------------------------------
     *     SETTERS
     * ------------------------------------------------------
     */

    /**
     * setPath The setter of the "controllerPath" property
     * @param  string   $file   The name of the called file
     * @return void
     */
    protected function setcontrollerPath($file): void
    {
        $this->controllerPath = dirname($file);
    }

    /**
     * The setter of the "actionName" property
     * @param  string   $action     The name of the called action
     * @return void
     */
    protected function setActionName($action): void
    {
        $this->actionName = substr($action, 0, strlen('Action')*(-1));
    }

    /**
     * setLayout The setter of the "layout" property
     * @param  string   $layout   The URI of the layout
     * @return void
     */
    protected function setLayout(string $layout = ''): void
    {
        $this->layout = $layout;
    }

    /**
     * setView The setter of the "view" property
     * @param  string|null   $view   The URI of the view
     * @return void
     */
    protected function setView(?string $view = null): void
    {
        if (!is_null($view)) {
            $this->view = $view;
        } else {
            $this->view = $this->getActionName();
        }
    }

    /**
     * setProperty The setter of the "properties" property
     * @param  string   $property   The name of the property
     * @param  mixed    $value      The value for the property
     * @return void
     */
    public function setProperty($property, $value): void
    {
        $this->properties[$property] = $value;
    }

    /**
     * @param mixed $get
     */
    public function setGet($get): void
    {
        $this->get = $get;
    }

    /**
     * @param mixed $post
     */
    public function setPost($post): void
    {
        $this->post = $post;
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setSession(string $key, $value) : void
    {
        $this->session['grinoire'][$key] = $value;
    }

    /**
     * @param string $newLayout
     */
    public function setNewLayout(string $newLayout): void
    {
        $this->newLayout = $newLayout;
    }



    /**
     * --------------------------------------------------
     * GETTERS
     * --------------------------------------------------
     */

    /**
     * The getter of the "controllerPath" property
     * @return string   The path of the controller
     */
    protected function getcontrollerPath()
    {
        return $this->controllerPath;
    }


    /**
     * The getter of the "controllerName" property
     * @return string   The name of the controller
     */
    protected function getControllerName()
    {
        return $this->controllerName;
    }


    /**
     * The getter of the "actionName" property
     * @return string   The name of the called actionName
     */
    protected function getActionName()
    {
        return $this->actionName;
    }

    /**
     * The getter of the "layout" property
     * @return string   The name of the used layout
     */
    protected function getLayout()
    {
        return $this->layout ? $this->layout.DS :'';
    }

    /**
     * The getter of the "view" property
     * @return string   The name of the printed view
     */
    protected function getView()
    {
        return $this->view;
    }

    /**
     * The getter of the "properties" property
     * @param  string|null  $key    The name of the property
     * @return string               The value contained in the requested key of the properties array, or the properties array if the key is not set, or an empty array if the requested key doesn't exist in the properties array
     */
    protected function getProperties($key = null)
    {
        return (isset($key) ? (isset($this->properties[$key]) ? $this->properties[$key] : array()) : $this->properties);
    }

    /**
     * Retourne la super-global get si la key n'est pas defini
     *
     * Sinon la valeur de la key si defini
     *
     * @param   string|null   $key    Clé a retourné
     * @return  mixed
     */
    public function getGet(string $key = null)
    {
        if (isset($key)) {
            return $this->get[$key];
        } else {
            return $this->get;
        }
    }

    /**
     * Retourne la super-global POST associé a la clé en paramètres
     * Retourne toutes les super-global POST de l'application sans clé
     *
     * @param    string|null $key     Nom de la super-global POST recherché
     * @return   mixed                Array | string | null
     */
    public function getPost(string $key = null)
    {
        if (isset($key)) {
            return $this->post[$key];
        } else {
            return $this->post;
        }
    }

    /**
     * Retourne la session associé a la clé en paramètres
     * Retourne toutes les sessions de l'application sans clé
     *
     * @param    string|null  $key    Nom de la session recherché
     * @return   mixed                Array | string | null
     */
    public function getSession( ?string $key = null)
    {
        if (isset($key)) {
            return $this->session['grinoire'][$key];
        } else {
            return $this->session['grinoire'];
        }
    }

    /**
     * @return string
     */
    public function getNewLayout(): string
    {
        return $this->newLayout;
    }



    /**
     * --------------------------------------------------
     *     DEBUG WiTH SCRIPT
     * ------------------------------------------------------
     */

     /**
      * Affiche la valeur en parametre dans la console du navigateur
      * 
      * @param   mixed  $value   string ou array
      * @return  void
      */
    public function debugScript($value) {
        if (gettype($value) == 'string') {
            echo '<script>console.log("' . $value . '")</script>';
        } elseif (gettype($value) == 'array') {
            foreach ($value as $key => $val) {
                if (gettype($val) == 'array') {
                    foreach ($val as $key => $v) {
                        echo '<script>console.log("' . $v . '")</script>';
                    }
                } elseif (gettype($val) == 'string') {
                    echo '<script>console.log("' . $val . '")</script>';
                }
            }
        }
    }

}
