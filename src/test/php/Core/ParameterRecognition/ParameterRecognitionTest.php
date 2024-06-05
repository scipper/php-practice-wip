<?php declare(strict_types = 1);

namespace Mys\Core\ParameterRecognition;

use Mys\Core\ClassNotFoundException;
use PHPUnit\Framework\TestCase;

class ParameterRecognitionTest extends TestCase
{
    /**
     * @var ParameterRecognition
     */
    private ParameterRecognition $parameterRecognition;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->parameterRecognition = new ParameterRecognition();
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws FunctionNotFoundException
     * @throws MissingPayloadException
     */
    public function test_returns_void_when_function_does_not_have_any_parameter()
    {
        $parameter = $this->parameterRecognition->recognise(DummyParameterComponent::class, "voidFunction");

        $this->assertSame([], $parameter);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws FunctionNotFoundException
     * @throws MissingPayloadException
     */
    public function test_returns_string_when_function_has_string_parameter()
    {
        $parameter = $this->parameterRecognition->recognise(DummyParameterComponent::class, "stringFunction", "payload");

        $this->assertSame(["payload"], $parameter);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws FunctionNotFoundException
     * @throws MissingPayloadException
     */
    public function test_returns_two_strings_when_function_has_two_string_parameters()
    {
        $parameter = $this->parameterRecognition->recognise(DummyParameterComponent::class, "twoStringFunction", "payloadA", "payloadB");

        $this->assertSame(["payloadA", "payloadB"], $parameter);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws FunctionNotFoundException
     * @throws MissingPayloadException
     */
    public function test_returns_int_when_function_has_int_parameter()
    {
        $parameter = $this->parameterRecognition->recognise(DummyParameterComponent::class, "intFunction", "1");

        $this->assertSame([1], $parameter);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws FunctionNotFoundException
     * @throws MissingPayloadException
     */
    public function test_returns_mixed_parameters()
    {
        $parameter = $this->parameterRecognition->recognise(DummyParameterComponent::class, "mixedFunction", "1", "test");

        $this->assertSame([1, "test"], $parameter);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws FunctionNotFoundException
     * @throws MissingPayloadException
     */
    public function test_returns_instance_of_class()
    {
        $parameter = $this->parameterRecognition->recognise(DummyParameterComponent::class, "classFunction", "{\"type\": \"test\"}");

        $this->assertInstanceOf(ClassParameter::class, $parameter[0]);
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws FunctionNotFoundException
     * @throws MissingPayloadException
     */
    public function test_json_payload_is_mapped_correctly_to_class()
    {
        $parameter = $this->parameterRecognition->recognise(DummyParameterComponent::class, "classFunction", "{\"type\": \"test\"}");

        $this->assertSame("test", $parameter[0]->getType());
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws FunctionNotFoundException
     * @throws MissingPayloadException
     */
    public function test_throws_when_class_is_not_found()
    {
        $this->expectException(ClassNotFoundException::class);

        $this->parameterRecognition->recognise("MissingClass", "classFunction", "{\"type\": \"test\"}");
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws FunctionNotFoundException
     * @throws MissingPayloadException
     */
    public function test_throws_when_function_is_not_found()
    {
        $this->expectException(FunctionNotFoundException::class);

        $this->parameterRecognition->recognise(DummyParameterComponent::class, "missingFunction", "{\"type\": \"test\"}");
    }

    /**
     * @return void
     * @throws ClassNotFoundException
     * @throws FunctionNotFoundException
     * @throws MissingPayloadException
     */
    public function test_throws_when_no_payload_is_provided_but_needed()
    {
        $this->expectException(MissingPayloadException::class);

        $this->parameterRecognition->recognise(DummyParameterComponent::class, "stringFunction");
    }
}
