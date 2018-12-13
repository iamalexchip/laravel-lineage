<?php

namespace Zerochip\Lineage;

class Picker
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * Target object
     *
     * @var object
     */
    private $target;

    /**
     * @var array
     */
    private $errorMessages = [];
    
    /**
     * Target object
     *
     * @var object
     */
    private $lineage;

    /**
     * Initialize instance
     *
     * @return void
     */
    public function __construct($attributes, $target)
    {
        $this->attributes = $attributes;
        $this->target = $target;
        $this->lineage = collect();
    }

    /**
     * Gets $this->attributes from $this->target object
     *
     * @return array
     */
    public function get()
    {
        $this->fetch($this->attributes, $this->target);
        
        return $this->lineage->unique();
    }

    /**
     * Checks if a object can be iterated
     *
     * @param mixed
     *
     * @return boolean
     */
    private function is_collection($instance)
    {
        return is_a($instance, 'Illuminate\Database\Eloquent\Collection');
    }

    /**
     * Fetch requested keys from an object
     *
     * @param array $attributes
     *
     * @param object $instance
     *
     * @return array
     */
    private function fetch($attributes, $instance)
    {
        $skeleton = null;

        if (is_null($instance)) {
            return null;
        }

        if ($this->is_collection($instance)) {
            if (!$instance->count()) {
                return [];
            }

            foreach ($instance as $item) {
                $skeleton[] = $this->getAttributes($attributes, $item);
            }
        } else {
            $skeleton = $this->getAttributes($attributes, $instance);
        }

        return $skeleton;
    }

    /**
     * Gets given attributes from a model instance
     *
     * @param array $attributes
     * @param object $instance
     *
     * @return array
     */
    private function getAttributes($attributes, $instance)
    {
        $skeleton = [];

        // if lineage add to array
        if (array_key_exists('lineage', $attributes)) {
            
            $lineage = array_keys($attributes)[0];

            if ($this->is_collection($instance->$lineage)) {

                $this->lineage = $this->lineage->merge($instance->$lineage);
            
            } else {

                $this->lineage->push($instance->$lineage);
            }
        }

        foreach ($attributes as $key => $value) {
            
            if (is_array($value)) {
            
                $skeleton[$key] = $this->fetch($value, $instance->$key);
            }
        }

        return $skeleton;
    }
}
