<?php


namespace Transfermate\Web\Core;


use Transfermate\Web\Core\Interfaces\ControllerInterface;
use Transfermate\Web\Core\Interfaces\ModelInterface;
use Transfermate\Web\Utils\Utils;

class View
{
    /**
     * @var ControllerInterface
     */
    private ControllerInterface $controller;

    /**
     * @var ModelInterface
     */
    private ModelInterface $model;

    /**
     * View constructor.
     *
     * @param ControllerInterface $controller
     * @param ModelInterface $model
     */
    public function __construct(ControllerInterface $controller, ModelInterface $model)
    {
        $this->controller = $controller;
        $this->model = $model;
    }

    /**
     * Load output view.
     *
     * @param string $view
     * @return string
     */
    public function output(string $view): string
    {
        $viewPath = '';

        try {
            $classShortName = Utils::getClassShortName($this->controller);
            $viewPath = sprintf('../src/Views/%s/%s.php', $classShortName, $view);
            if (!file_exists($viewPath)) {
                $viewPath = '../src/Views/error/index.php';
            }
        } catch (\Exception $ex) {
            // TODO Implement Exception Handler.
        }

        return require_once($viewPath);
    }
}