<?php
declare(strict_types=1);

namespace grinoire\src\controller\gameController;

use grinoire\src\controller\CoreController;

class GameController extends CoreController
{

    public function __construct(array $get, array $post)
    {
        parent::__construct($get, $post);
    }

    public function gameAction(){
        $this->init(__FILE__, __FUNCTION__);

        $this->render(true);
    }

}