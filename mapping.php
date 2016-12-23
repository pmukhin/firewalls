<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

AnnotationRegistry::registerFile(__DIR__ . '/src/Annotations/Permission.php');
AnnotationRegistry::registerFile(__DIR__ . '/src/Annotations/Assert.php');
AnnotationRegistry::registerFile(__DIR__ . '/src/Annotations/Accessible.php');
