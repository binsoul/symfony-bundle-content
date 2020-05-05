<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element;

/**
 * Holds parameters.
 */
class Context
{
    /**
     * @var mixed[]
     */
    private $parameters = [];

    /**
     * Constructs an instance of this class.
     *
     * @param mixed[] $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->add($parameters);
    }

    /**
     * Clears all parameters.
     */
    public function clear(): void
    {
        $this->parameters = [];
    }

    /**
     * Adds a list of parameters.
     *
     * @param mixed[] $parameters
     */
    public function add(array $parameters): void
    {
        foreach ($parameters as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Returns all parameters.
     *
     * @return mixed[]
     */
    public function all(): array
    {
        return $this->parameters;
    }

    /**
     * Returns a parameter or the default value if the parameter doesn't exist.
     *
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function get(string $name, $default = null)
    {
        if (! array_key_exists($name, $this->parameters)) {
            return $default;
        }

        return $this->parameters[$name];
    }

    /**
     * Sets a parameter.
     *
     * @param string $name  The parameter name
     * @param mixed  $value The parameter value
     */
    public function set(string $name, $value): void
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Determines if the parameter exists.
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->parameters);
    }

    /**
     * Removes a parameter.
     */
    public function remove(string $name): void
    {
        unset($this->parameters[$name]);
    }
}
