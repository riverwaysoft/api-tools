<?php

declare(strict_types=1);

namespace Riverwaysoft\ApiTools\Tests\InputValueResolver;

use Riverwaysoft\ApiTools\InputValueResolver\Query;
use Riverwaysoft\ApiTools\InputValueResolver\QueryValueResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use PHPUnit\Framework\TestCase;

enum UserStatus: string {
    case Active = 'Active';
    case Inactive = 'Inactive';
}

class UserQuery {
    public function __construct(
        public int $age,
        public UserStatus $status,
        public bool $isActive,
    )
    {

    }
}


class QueryValueResolverTest extends TestCase
{
    private function createResolver(): QueryValueResolver
    {
        $encoder = new JsonEncoder();
        $normalizers = [new BackedEnumNormalizer(), new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, [$encoder]);

        return new QueryValueResolver($serializer);
    }

    public function testResolveReturnsEmptyArrayForNonQueryArgument(): void
    {
        $resolver = $this->createResolver();
        $request = new Request();
        $argument = new ArgumentMetadata('user', null, false, false, null, false, []);

        $result = $resolver->resolve($request, $argument);

        $this->assertSame([], $result);
    }

    public function testResolveReturnsDenormalizedValue(): void
    {
        $resolver = $this->createResolver();
        $queryArray = ['age' => '20', 'status' => UserStatus::Active->value, 'isActive' => false];
        $request = new Request($queryArray);
        $argument = new ArgumentMetadata('user', UserQuery::class, false, false, null, false, [new Query()]);

        $result = $resolver->resolve($request, $argument)[0];

        $this->assertInstanceOf(UserQuery::class, $result);

        $this->assertIsInt($result->age);
        $this->assertIsBool($result->isActive);

        $this->assertEquals($result->age, 20);
        $this->assertEquals($result->isActive, false);
    }

    public function testResolveReturnsDenormalizedRawValue(): void
    {
        $resolver = $this->createResolver();
        $request = new Request(['age' => 20, 'status' => 'Active', 'isActive' => false]);
        $argument = new ArgumentMetadata('user', UserQuery::class, false, false, null, false, [new Query()]);

        $result = $resolver->resolve($request, $argument)[0];

        $this->assertInstanceOf(UserQuery::class, $result);
    }

}
