<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Symfony\Bundle\DebugBundle\DebugBundle::class => ['local' => true],
    Symfony\Bundle\WebServerBundle\WebServerBundle::class => ['local' => true],
    Rollbar\Symfony\RollbarBundle\RollbarBundle::class => ['all' => true],
];
