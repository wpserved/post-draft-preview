<?php
require PDP_ROOT_PATH . '/vendor/autoload.php';

if (!function_exists('pdpDoc') && function_exists('PostDraftPreview\\pdpDoc')) {
  /**
   * Initialize pdpDoc() function.
   *
   * @return object
   */
  function pdpDoc(): object
  {
    return PostDraftPreview\pdpDoc();
  }
}

if (!function_exists('pdp') && function_exists('PostDraftPreview\\pdp')) {
  /**
   * Initialize pdp() function.
   *
   * @param string $moduleName
   * @return object
   */
  function pdp(string $moduleName = ''): object
  {
    return PostDraftPreview\pdp($moduleName);
  }
}

if (!function_exists('createClass') && function_exists('PostDraftPreview\\createClass')) {
  /**
   * Initialize createClass() function.
   *
   * @param string $class
   * @param array $params
   * @return object
   */
  function createClass(string $class, array $params = array()): object
  {
    $object = new $class(...$params);
    pdpDoc()->addDocHooks($object);
    return $object;
  }
}

if (!function_exists('errorLog')) {
  /**
   * Log error to debug file and print to screen when debug mode enabled.
   *
   * @param \Throwable $error
   * @return void
   */
  function errorLog(\Throwable $error): void
  {
    error_log($error);
    if (defined('WP_DEBUG') && true === WP_DEBUG && defined('WP_DEBUG_DISPLAY') && true === WP_DEBUG_DISPLAY) {
      dump($error);
    }
  }
}

pdpDoc();
pdp();
