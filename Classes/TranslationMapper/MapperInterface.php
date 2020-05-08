<?php
namespace Mireo\XlfEditor\TranslationMapper;

interface MapperInterface{

    /**
     * @param string $key
     * @return string
     */
    public function mapTo(string $key) : string;
}
