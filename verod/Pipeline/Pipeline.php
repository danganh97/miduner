<?php

namespace Midun\Pipeline;

use Closure;
use Midun\Container;
use Midun\Contracts\Pipeline\Pipeline as MainPipeline;

class Pipeline implements MainPipeline
{
    /**
     * The container implementation.
     *
     * @var \Midun\Container
     */
    protected $container;

    /**
     * The object being passed through the pipeline.
     *
     * @var mixed
     */
    protected $passable;

    /**
     * The array of class pipes.
     *
     * @var array
     */
    protected $pipes = [];

    /**
     * The method to call on each pipe.
     *
     * @var string
     */
    protected $method = 'handle';

    /**
     * Create a new class instance.
     *
     * @param  \Midun\Container|null  $container
     * @return void
     */
    public function __construct(Container $container = null)
    {
        $this->container = $container ?: Container::getInstance();
    }
    
    /**
     * Set the object being sent through the pipeline.
     *
     * @param  mixed  $passable
     * @return $this
     */
    public function send($passable)
    {
        $this->passable = $passable;

        return $this;
    }

    /**
     * Set the array of pipes.
     *
     * @param  array|mixed  $pipes
     * @return $this
     */
    public function through($pipes)
    {
        $this->pipes = is_array($pipes) ? $pipes : func_get_args();

        return $this;
    }

    /**
     * Set the method to call on the pipes.
     *
     * @param  string  $method
     * @return $this
     */
    public function via($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Run the pipeline with a final handleRouting callback.
     *
     * @param  \Closure  $handleRouting
     * @return mixed
     */
    public function then(Closure $handleRouting)
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes), $this->carry(), $this->prepareHandleRouting($handleRouting)
        );
        return $pipeline($this->passable);
    }

    /**
     * Get the final piece of the Closure onion.
     *
     * @param  \Closure  $handleRouting
     * @return \Closure
     */
    protected function preparehandleRouting(Closure $handleRouting)
    {
        return function () use ($handleRouting) {
            return $handleRouting();
        };
    }

    /**
     * Get a Closure that represents a slice of the application onion.
     *
     * @return \Closure
     */
    protected function carry()
    {
        return function ($stack, $pipe) {
            return function ($passable) use ($stack, $pipe) {

                $pipe = Container::getInstance()->make($pipe);
                
                return $pipe->{$this->method}($passable, $stack);
            };
        };
    }
}
