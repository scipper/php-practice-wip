<?php declare(strict_types = 1);

namespace Mys\Core\ParameterRecognition;

use PHPUnit\Framework\TestCase;

class ParameterRecognitionTest extends TestCase
{
    /**
     * @var ParameterRecognition
     */
    private ParameterRecognition $parameterRecognition;

    public function setUp(): void
    {
        $this->parameterRecognition = new ParameterRecognition();
    }

    public function test_returns_void_when_function_does_not_have_any_parameter()
    {
        $parameter = $this->parameterRecognition->recognise(DummyParameterComponent::class, "voidFunction");

        $this->assertSame([], $parameter);
    }

    public function test_returns_string_when_function_has_string_parameter()
    {
        $parameter = $this->parameterRecognition->recognise(DummyParameterComponent::class, "stringFunction", "payload");

        $this->assertSame(["payload"], $parameter);
    }

    public function test_returns_two_strings_when_function_has_two_string_parameters()
    {
        $parameter = $this->parameterRecognition->recognise(DummyParameterComponent::class, "twoStringFunction", "payloadA", "payloadB");

        $this->assertSame(["payloadA", "payloadB"], $parameter);
    }

    public function test_returns_int_when_function_has_int_parameter()
    {
        $parameter = $this->parameterRecognition->recognise(DummyParameterComponent::class, "intFunction", "1");

        $this->assertSame([1], $parameter);
    }

    public function test_returns_mixed_parameters()
    {
        $parameter = $this->parameterRecognition->recognise(DummyParameterComponent::class, "mixedFunction", "1", "test");

        $this->assertSame([1, "test"], $parameter);
    }

    public function test_returns_instance_of_class()
    {
        $parameter = $this->parameterRecognition->recognise(DummyParameterComponent::class, "classFunction", "{\"type\": \"test\"}");

        $this->assertInstanceOf(ClassParameter::class, $parameter[0]);
    }

    public function test_json_payload_is_mapped_correctly_to_class()
    {
        $parameter = $this->parameterRecognition->recognise(DummyParameterComponent::class, "classFunction", "{\"type\": \"test\"}");

        $this->assertSame("test", $parameter[0]->getType());
    }
}
