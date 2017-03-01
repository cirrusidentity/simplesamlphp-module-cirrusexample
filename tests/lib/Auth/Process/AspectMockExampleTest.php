<?php

use AspectMock\Test as test;

class AspectMockExampleTest extends \PHPUnit_Framework_TestCase
{


    /**
     * Test the behavior if nothing is mocked/stubbed. Default behavior is to throw an exception.
     */
    public function testDefaultBehavior()
    {
        $this->expectException(\SimpleSAML_Error_Exception::class);
        $this->expectExceptionMessage("Process filter exploded");

        $config = [];
        $request = [];
        $filter = new sspmod_cirrusexample_Auth_Process_AspectMockExample($config, null);
        $filter->process($request);

    }

    /**
     * Test to confirm AspectMock is configured correctly. The phpunit bootstrap.php has the configuration for AspectMock
     * and that is where you can tell it which classes to do its AOP magic on.
     */
    public function testAspectMockConfigured()
    {
        // Ensure mocks are configured for SSP classes
        $httpDouble = test::double('SimpleSAML\Utils\HTTP', [
            'getAcceptLanguage' => ['some-lang']
        ]);

        $this->assertEquals(['some-lang'], SimpleSAML\Utils\HTTP::getAcceptLanguage());
        // You can also validate the that a method was called.
        $httpDouble->verifyInvokedOnce('getAcceptLanguage');

        // Ensure mocks can be configred for our classes
        $linkDouble = test::double('sspmod_cirrusexample_ExampleService', [
            'updateAction' => null,
        ]);
        (new sspmod_cirrusexample_ExampleService())->updateAction('argument');
        // Verify it was invoked with the expected argument
        $linkDouble->verifyInvokedOnce('updateAction', ['argument']);

    }
}