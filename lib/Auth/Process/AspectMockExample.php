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

        $action = $service->determineAction();
        switch ($action) {
            case "explode":
                throw new SimpleSAML_Error_Exception('Process filter exploded');
                break;
            case "redirect":
                SimpleSAML\Utils\HTTP::redirectTrustedURL('https://someurl', ['param1' => 'value1']);
                assert('FALSE');
                break;
            case "method":
                $service->updateAction($request['Attributes']['someAttribute']);
                break;
        }
    }
}