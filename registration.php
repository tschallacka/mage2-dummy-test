<?php
use Magento\Framework\Component\ComponentRegistrar;
use Test\Dummy\Configuration\Config;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    Config::MODULE_NAME,
    __DIR__
);
