<?php

namespace Tests\amoCRM\Entities;

use amoCRM\Filter\Interfaces\SearchFilter;
use amoCRM\Interfaces\Requester;
use amoCRM\Repository\BaseEntityRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class BaseEntityRequesterTest
 * @package Tests\amoCRM\Entities
 * @covers \amoCRM\Repository\BaseEntityRepository
 */
final class BaseEntityRepositoryTest extends TestCase
{
    public function testCanBeCreatedFromValidNamesAndPaths()
    {
        $args = [
            $this->createMock(Requester::class),
            ['many' => 'elements'],
            ['list' => 'elements/list', 'set' => 'elements/set'],
        ];

        $stub = $this->getMockBuilder(BaseEntityRepository::class)
            ->setConstructorArgs($args)
            ->enableOriginalConstructor()
            ->getMock();


        $this->assertInstanceOf(
            BaseEntityRepository::class,
            $stub
        );
    }

    /**
     * @expectedException \amoCRM\Exception\InvalidArgumentException
     */
    public function testCannotBeCreatedFromInvalidNames()
    {
        $args = [
            $this->createMock(Requester::class),
            ['many' => null],
            ['list' => 'elements/list', 'set' => 'elements/set'],
        ];

        $this->getMockBuilder(BaseEntityRepository::class)
            ->setConstructorArgs($args)
            ->enableOriginalConstructor()
            ->getMock();
    }

    /**
     * @expectedException \amoCRM\Exception\InvalidArgumentException
     */
    public function testCannotBeCreatedFromInvalidPaths()
    {
        $args = [
            $this->createMock(Requester::class),
            ['many' => 'elements'],
            ['set' => null],
        ];

        $this->getMockBuilder(BaseEntityRepository::class)
            ->setConstructorArgs($args)
            ->enableOriginalConstructor()
            ->getMock();
    }

    /**
     * @expectedException \amoCRM\Exception\InvalidArgumentException
     */
    public function testBuildFailedForAdd()
    {
        $requester = $this->createMock(Requester::class);

        // Wrong nesting level
        $elements = ['name' => 'Test'];

        $args = [
            $requester,
            ['many' => 'elements'],
            ['list' => 'elements/list', 'set' => 'elements/set'],
        ];

        $stub = $this->getMockBuilder(BaseEntityRepository::class)
            ->setConstructorArgs($args)
            ->enableOriginalConstructor()
            // Disable mock of any methods
            ->setMethods()
            ->getMock();

        /** @var BaseEntityRepository $stub */
        $stub->add($elements);
    }

    /**
     * @expectedException \amoCRM\Exception\InvalidArgumentException
     */
    public function testBuildFailedForUpdate()
    {
        $requester = $this->createMock(Requester::class);

        // Wrong nesting level
        $elements = ['id' => 12, 'name' => 'Test'];

        $args = [
            $requester,
            ['many' => 'elements'],
            ['list' => 'elements/list', 'set' => 'elements/set'],
        ];

        $stub = $this->getMockBuilder(BaseEntityRepository::class)
            ->setConstructorArgs($args)
            ->enableOriginalConstructor()
            // Disable mock of any methods
            ->setMethods()
            ->getMock();

        /** @var BaseEntityRepository $stub */
        $stub->update($elements);
    }

    /**
     * @expectedException \amoCRM\Exception\InvalidArgumentException
     */
    public function testBuildFailedForUpdateWithoutId()
    {
        $requester = $this->createMock(Requester::class);

        // Wrong nesting level
        $elements = [['name' => 'Test']];

        $args = [
            $requester,
            ['many' => 'elements'],
            ['list' => 'elements/list', 'set' => 'elements/set'],
        ];

        $stub = $this->getMockBuilder(BaseEntityRepository::class)
            ->setConstructorArgs($args)
            ->enableOriginalConstructor()
            // Disable mock of any methods
            ->setMethods()
            ->getMock();

        /** @var BaseEntityRepository $stub */
        $stub->update($elements);
    }

    public function testBuildValidFormatForAdd()
    {
        $requester = $this->createMock(Requester::class);

        $path = Requester::API_PATH . 'elements/set';
        $elements = [
            [
                'name' => 'Test',
            ],
        ];
        $post_result = ['elements' => ['add' => $elements]];
        $post_data = [
            'request' => [
                'elements' => [
                    'add' => $elements,
                ],
            ],
        ];

        $requester
            ->expects($this->once())
            ->method('post')->with(
                $this->equalTo($path),
                $this->equalTo($post_data)
            )
            ->willReturn($post_result);

        $args = [
            $requester,
            ['many' => 'elements'],
            ['list' => 'elements/list', 'set' => 'elements/set'],
        ];

        $stub = $this->getMockBuilder(BaseEntityRepository::class)
            ->setConstructorArgs($args)
            ->enableOriginalConstructor()
            // Disable mock of any methods
            ->setMethods()
            ->getMock();

        /** @var BaseEntityRepository $stub */
        $this->assertEquals($post_result['elements']['add'], $stub->add($elements));
    }

    public function testBuildValidFormatForUpdate()
    {
        $requester = $this->createMock(Requester::class);

        $path = Requester::API_PATH . 'elements/set';
        $elements = [
            [
                'id' => 12,
                'name' => 'Test',
            ],
        ];
        $post_result = ['elements' => ['update' => $elements]];
        $post_data = [
            'request' => [
                'elements' => [
                    'update' => $elements,
                ],
            ],
        ];

        $requester
            ->expects($this->once())
            ->method('post')->with(
                $this->equalTo($path),
                $this->equalTo($post_data)
            )
            ->willReturn($post_result);

        $args = [
            $requester,
            ['many' => 'elements'],
            ['list' => 'elements/list', 'set' => 'elements/set'],
        ];

        $stub = $this->getMockBuilder(BaseEntityRepository::class)
            ->setConstructorArgs($args)
            ->enableOriginalConstructor()
            // Disable mock of any methods
            ->setMethods()
            ->getMock();

        /** @var BaseEntityRepository $stub */
        $this->assertEquals($post_result['elements']['update'], $stub->update($elements));
    }

    public function testSearch()
    {
        $requester = $this->createMock(Requester::class);

        $path = Requester::API_PATH . 'elements/list';

        $elements = [
            [
                'id' => 123,
                'name' => 'Test',
            ],
        ];
        $get_result = ['elements' => $elements];

        $requester->expects($this->once())
            ->method('get')->with(
                $this->equalTo($path),
                $this->equalTo([])
            )
            ->willReturn($get_result);

        $args = [
            $requester,
            ['many' => 'elements'],
            ['list' => 'elements/list', 'set' => 'elements/set'],
        ];

        $stub = $this->getMockBuilder(BaseEntityRepository::class)
            ->setConstructorArgs($args)
            ->enableOriginalConstructor()
            // Disable mock of any methods
            ->setMethods()
            ->getMock();

        /** @var BaseEntityRepository $stub */
        $this->assertEquals($elements, $stub->search());
    }

    public function testSearchWithFilterAndNav()
    {
        $requester = $this->createMock(Requester::class);

        $path = Requester::API_PATH . 'elements/list';

        $elements = [
            [
                'id' => 123,
                'name' => 'Test',
            ],
        ];
        $get_result = ['elements' => $elements];

        $filter = $this->createMock(SearchFilter::class);
        $filter_array = [
            'foo' => 'bar',
        ];
        $filter->expects($this->once())
            ->method('toArray')
            ->willReturn($filter_array);

        $nav = [
            'limit' => 20,
            'offset' => 40,
        ];

        $query = [
                'limit_rows' => $nav['limit'],
                'limit_offset' => $nav['offset'],
            ] + $filter_array;

        $requester->expects($this->once())
            ->method('get')->with(
                $this->equalTo($path),
                $this->equalTo($query)
            )
            ->willReturn($get_result);

        $args = [
            $requester,
            ['many' => 'elements'],
            ['list' => 'elements/list', 'set' => 'elements/set'],
        ];

        $stub = $this->getMockBuilder(BaseEntityRepository::class)
            ->setConstructorArgs($args)
            ->enableOriginalConstructor()
            // Disable mock of any methods
            ->setMethods()
            ->getMock();

        $this->assertEquals($elements, $stub->search($filter, $nav));
    }

    /**
     * @expectedException \amoCRM\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid navigation field "offset" value: "-1"
     */
    public function testSearchThrowInvalidArgumentExceptionLessZero()
    {
        $nav = ['offset' => -1];
        $this->buildStubForSearchException()->search(null, $nav);
    }

    /**
     * @return BaseEntityRepository
     *
     */
    private function buildStubForSearchException()
    {
        $requester = $this->createMock(Requester::class);

        $args = [
            $requester,
            ['many' => 'elements'],
            ['list' => 'elements/list', 'set' => 'elements/set'],
        ];

        $stub = $this->getMockBuilder(BaseEntityRepository::class)
            ->setConstructorArgs($args)
            ->enableOriginalConstructor()
            // Disable mock of any methods
            ->setMethods()
            ->getMock();

        /** @var BaseEntityRepository $stub */
        return $stub;
    }

    /**
     * @expectedException \amoCRM\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid navigation field "limit" value: "0"
     */
    public function testSearchThrowInvalidArgumentExceptionLimitEqualZero()
    {
        $nav = ['limit' => 0];
        $this->buildStubForSearchException()->search(null, $nav);
    }

    /**
     * @expectedException \amoCRM\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid navigation field "limit" value: "501"
     */
    public function testSearchThrowInvalidArgumentExceptionLimitGreater500()
    {
        $nav = ['limit' => 501];
        $this->buildStubForSearchException()->search(null, $nav);
    }

    public function testAddErrorResult()
    {
        $requester = $this->createMock(Requester::class);

        $path = Requester::API_PATH . 'elements/set';
        $elements = [
            [
                'name' => 'Test',
            ],
        ];
        $post_result = ['error' => 'test error', 'error_code' => 102];
        $post_data = [
            'request' => [
                'elements' => [
                    'add' => $elements,
                ],
            ],
        ];
        $requester
            ->expects($this->once())
            ->method('post')->with(
                $this->equalTo($path),
                $this->equalTo($post_data)
            )
            ->willReturn($post_result);

        $args = [
            $requester,
            ['many' => 'elements'],
            ['list' => 'elements/list', 'set' => 'elements/set'],
        ];

        $stub = $this->getMockBuilder(BaseEntityRepository::class)
            ->setConstructorArgs($args)
            ->enableOriginalConstructor()
            // Disable mock of any methods
            ->setMethods()
            ->getMock();

        /** @var BaseEntityRepository $stub */
        $this->assertEquals($post_result, $stub->add($elements));
    }

    public function testUpdateErrorResult()
    {
        $requester = $this->createMock(Requester::class);

        $path = Requester::API_PATH . 'elements/set';
        $elements = [
            [
                'id' => 12,
                'name' => 'Test',
            ],
        ];
        $post_result = ['error' => 'test error', 'error_code' => 102];
        $post_data = [
            'request' => [
                'elements' => [
                    'update' => $elements,
                ],
            ],
        ];
        $requester
            ->expects($this->once())
            ->method('post')->with(
                $this->equalTo($path),
                $this->equalTo($post_data)
            )
            ->willReturn($post_result);

        $args = [
            $requester,
            ['many' => 'elements'],
            ['list' => 'elements/list', 'set' => 'elements/set'],
        ];

        $stub = $this->getMockBuilder(BaseEntityRepository::class)
            ->setConstructorArgs($args)
            ->enableOriginalConstructor()
            // Disable mock of any methods
            ->setMethods()
            ->getMock();

        /** @var BaseEntityRepository $stub */
        $this->assertEquals($post_result, $stub->update($elements));
    }
}
