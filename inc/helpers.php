<?php
namespace PostDraftPreview;

use PostDraftPreview\Init;
use PostDraftPreview\Core\DocHooks;

if (!function_exists('PostDraftPreview\\pdpDoc')) {
  function pdpDoc()
  {
    return DocHooks::get();
  }
}

if (!function_exists('PostDraftPreview\\pdp')) {
  function pdp(string $moduleName = '')
  {
    $modules = Init::get();
    if ('' === $moduleName) {
      return $modules;
    }
    if (!array_key_exists($moduleName, $modules->public)) {
      throw new \Exception(sprintf(__('Module %1$s doesn\'t exists!', 'pdp'), $moduleName));
    }
    return $modules->public[$moduleName];
  }
}

if (!function_exists('PostDraftPreview\\createClass')) {
  /**
   * Create instance of Class
   *
   * @see https://gist.github.com/nikic/6390366
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
