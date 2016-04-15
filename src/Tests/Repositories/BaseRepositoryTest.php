<?php

namespace Bludata\Tests\Repositories;

use Bludata\Tests\BaseTest;

abstract class BaseRepositoryTest extends BaseTest
{
	abstract public function getRepository();

	abstract public function getMockObject(array $except = []);

	public function getFlushedMockObject(array $except = [])
	{
		$entity = $this->getMockObject($except);

		$this->getRepository()->save($entity)->flush($entity);

		return $entity;
	}

	public function getMockArray(array $except = [])
	{
		return $this->getMockObject($except)->toArray();
	}

	public function getFlushedMockArray(array $except = [])
	{
		return $this->getFlushedMockObject($except)->toArray();
	}

	public function testFindAll()
	{
		$entity = $this->getFlushedMockObject();

		$findAll = $this->getRepository()->findAll()->getResult();

		$this->assertGreaterThan(0, count($findAll));
		$this->assertInstanceOf($this->getRepository()->getEntityName(), $findAll[0]);
	}

	public function testFindBy()
	{
		$entity = $this->getFlushedMockObject();

		$repository = $this->getRepository();

		$findBy = $repository->findBy([$repository->getPrimaryKeyEntity() => $entity->getId()])->getResult();

		$this->assertGreaterThan(0, count($findBy));
		$this->assertInstanceOf($this->getRepository()->getEntityName(), $findBy[0]);
	}

	public function testFindOneBy()
	{
		$entity = $this->getFlushedMockObject();

		$repository = $this->getRepository();

		$findOneBy = $repository->findOneBy([$repository->getPrimaryKeyEntity() => $entity->getId()]);

		$this->assertInstanceOf($this->getRepository()->getEntityName(), $findOneBy);
		$this->assertEquals($entity->getId(), $findOneBy->getId());
	}

	public function testFind()
	{
		$entity = $this->getFlushedMockObject();

		$find = $this->getRepository()->find($entity->getId());

		$this->assertInstanceOf($this->getRepository()->getEntityName(), $find);
		$this->assertEquals($entity->getId(), $find->getId());
	}

	/**
     * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
	public function testRemove()
	{
		$entity = $this->getFlushedMockObject();

		$this->getRepository()
			 ->remove($entity)->flush()
			 ->find($entity->getId());
	}
}
