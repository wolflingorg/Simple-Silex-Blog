<?php

namespace Blog\Entity\Traits;

trait ArrayableTrait
{
    public function toArray(): array
    {
        $result = [];
        foreach (get_object_vars($this) as $key => $value) {
            $new_key = strtolower(preg_replace('/([a-z\d])([A-Z])/', '$1_$2', $key));
            $result[$new_key] = $value;
        }

        return $result;
    }
}
