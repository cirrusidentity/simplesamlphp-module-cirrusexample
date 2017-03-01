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
        $this->expectExceptionMessage("Process filter exploded.");

        $config = [];
        $request = [];
        $filter = new sspmod_cirrusexample_Auth_Process_AspectMockExample($config, null);
        $filter->process($request);
    }
}