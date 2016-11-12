<?php

namespace r0mdau;

class r0mdauDb
{
    private $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function collection($name)
    {
        return new r0mdauCollection($this->directory, $name);
    }
}
