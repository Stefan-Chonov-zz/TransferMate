<?php


namespace Transfermate\Web\Controllers;


use Transfermate\Web\Core\Interfaces\ControllerInterface;
use Transfermate\Web\Core\Interfaces\ModelInterface;
use Transfermate\Web\Core\View;

class Home implements ControllerInterface
{
    /**
     * @var ModelInterface
     */
    private ModelInterface $model;

    /**
     * Home constructor.
     *
     * @param ModelInterface $model
     */
    public function __construct(ModelInterface $model)
    {
        $this->model = $model;
    }

    /**
     * Index page.
     *
     * @return void
     */
    public function index(): void
    {
        $view = new View($this, $this->model);
        echo $view->output(__FUNCTION__);
    }
}