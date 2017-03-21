<?php

/**
 * Application entry point
 * Instantiation Application Class and Run it
 */

// Base config
require('../Library/Config/base.php');

// Get app name
$appName = Library\GetApp::appName();

// Start the application
$appClass = 'Applications\\'.$appName.'\\'.$appName.'Application';
$App = new $appClass($appName);

// Cleanup unused var
unset($appClass, $appName);

// Start the Application
$App->run();

