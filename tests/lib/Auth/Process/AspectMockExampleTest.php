<?php

use AspectMock\Test as test;

class AspectMockExampleTest extends \PHPUnit_Framework_TestCase
{


    /**
     * Test the behavior if nothing is mocked/stubbed. Default behavior is to throw an exception if there
     * are no attributes.
     */
    public function testDefaultBehavior()
    {
        $this->expectException(\SimpleSAML_Error_Exception::class);
        $this->expectExceptionMessage("No 'action' Attribute");

        $config = [];
        $request = [];
        $filter = new sspmod_cirrusexample_Auth_Process_AspectMockExample($config, null);
        $filter->process($request);

    }

    protected function tearDown()
    {
        test::clean(); // remove all registered test doubles
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

        // Ensure mocks can be configured for our service class
        $linkDouble = test::double('sspmod_cirrusexample_ExampleService', [
            'updateAction' => null,
        ]);
        (new sspmod_cirrusexample_ExampleService())->updateAction('argument');
        // Verify it was invoked with the expected argument
        $linkDouble->verifyInvokedOnce('updateAction', ['argument']);

    }

    /**
     * An example test to show how to stub static methods
     */
    public function testRedirect()
    {
        // Override redirect behavior
        test::double('SimpleSAML\Utils\HTTP', [
            'redirectTrustedURL' => function () {
                // Throwing an exception seems to prevent AspectMock from recording the invocation. So we do an argument capture here.
                // We throw an exception to cause the auth processor to fail and stop processing.
                throw new sspmod_cirrusexample_ArgumentCaptureException('redirect called', func_get_args());
            }
        ]);
        $config = ['redirectUrl' => 'https://example.com'];
        $request = [
            'Attributes' =>
                [
                    'action' => ['redirect']
                ],
        ];

        $filter = new sspmod_cirrusexample_Auth_Process_AspectMockExample($config, null);
        try {
            $filter->process($request);
            $this->assertFalse(true, "Error should have been thrown by test double");
        } catch (sspmod_cirrusexample_ArgumentCaptureException $e) {
            // We catch the exception ourselves instead of using $this->expectException since we want to
            // verify the argumens in the exception.
            $this->assertEquals('redirect called', $e->getMessage());
            $this->assertEquals(
                'https://example.com',
                $e->getArguments()[0],
                "First argument should be the redirect url"
            );
            $this->assertEquals('value1', $e->getArguments()[1]['param1']);
        }
    }

    /**
     * An example test to show how to stub the method on an instance class.
     */
    public function testUpdateVerification()
    {
        // Mock the update service call. Even though 'updateAction' is an instance method
        // we can still mock the call without needing access to the actual ExampleService instance.
        $serviceMock = test::double(sspmod_cirrusexample_ExampleService::class, [
            'updateAction' => null,
        ]);
        $config = [];
        $request = [
            'Attributes' =>
                [
                    'action' => ['update'],
                    'someAttribute' => 'theValue'
                ],
        ];

        $filter = new sspmod_cirrusexample_Auth_Process_AspectMockExample($config, null);
        $filter->process($request);
        $serviceMock->verifyInvokedOnce('updateAction', ['theValue']);

        // If you need to perform a more advanced validation of the arguments you can access those as well
        $argsFromCall = $serviceMock->getCallsForMethod('updateAction')[0];
        $this->assertEquals('theValue', $argsFromCall[0]);

    }
}