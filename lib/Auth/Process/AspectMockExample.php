<?php

/**
 * An AuthProcess filter used to illustrate testing static and other hard to test things with AspectMock
 */
class sspmod_cirrusexample_Auth_Process_AspectMockExample extends SimpleSAML_Auth_ProcessingFilter
{
    /**
     * @var array
     */
    protected $config;

    public function __construct(&$config, $reserved)
    {
        parent::__construct($config, $reserved);
        $this->config = $config;
    }

    /**
     * Process a request.
     *
     * When a filter returns from this function, it is assumed to have completed its task.
     *
     * @param array &$request The request we are currently processing.
     * @throws SimpleSAML_Error_Exception thrown if service determines to explode
     */
    public function process(&$request)
    {
        $service = new sspmod_cirrusexample_ExampleService($this->config);

        if (!isset($request['Attributes']['action'])) {
            throw new SimpleSAML_Error_Exception("No 'action' Attribute");
        }
        $action = $request['Attributes']['action'][0];
        switch ($action) {
            case "redirect":
                SimpleSAML\Utils\HTTP::redirectTrustedURL($this->config['redirectUrl'], ['param1' => 'value1']);
                assert('FALSE');
                break;
            case "update":
                $service->updateAction($request['Attributes']['someAttribute']);
                break;
            default:
                throw new SimpleSAML_Error_Exception("Unrecognized $action Attribute");
                break;

        }
    }
}