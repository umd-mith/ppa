<?php

/**
 * Factory to build RESTServer object for testing.
 */
class MockServicesRESTServerFactory extends ServicesRESTServerFactory {
  public function __construct($data = array()) {
    parent::__construct($data);
    drupal_static_reset();
  }

  protected function getContext($endpoint_path) {
    $context = new MockServicesContext($endpoint_path);
    $context->setData($this->data['context_data']);
    return $context;
  }

  protected function getResources() {
    if (isset($this->data['resources'])) {
      return $this->data['resources'];
    }
    return array();
  }

  protected function getFormatters() {
    if (isset($this->data['formatters'])) {
      return $this->data['formatters'];
    }
    return array();
  }

  protected function getParsers() {
    if (isset($this->data['parsers'])) {
      return $this->data['parsers'];
    }
    return array();
  }
}

/**
 * Mock ServicesContext object.
 */
class MockServicesContext extends ServicesContext {
  public function setData($data) {
    $this->data = array_merge($this->data, $data);
  }
}