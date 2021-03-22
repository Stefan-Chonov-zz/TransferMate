<?php


namespace Transfermate\Web\Core\Interfaces;


interface ControllerInterface
{
    /**
     * ControllerInterface constructor.
     *
     * @param ModelInterface $model
     */
    public function __construct(ModelInterface $model);

    /**
     * Index action.
     */
    public function index(): void;
}