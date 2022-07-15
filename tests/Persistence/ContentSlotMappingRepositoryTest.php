<?php

declare( strict_types = 1 );

namespace ProfessionalWiki\WikibaseRDF\Tests\Persistence;

use PHPUnit\Framework\TestCase;
use ProfessionalWiki\WikibaseRDF\Application\Mapping;
use ProfessionalWiki\WikibaseRDF\Application\MappingList;
use ProfessionalWiki\WikibaseRDF\Persistence\ContentSlotMappingRepository;
use ProfessionalWiki\WikibaseRDF\Persistence\InMemoryEntityContentRepository;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @covers \ProfessionalWiki\WikibaseRDF\Persistence\ContentSlotMappingRepository
 * @covers \ProfessionalWiki\WikibaseRDF\Persistence\InMemoryEntityContentRepository
 */
class ContentSlotMappingRepositoryTest extends TestCase {

	public function testGetMappingsForNonExistingEntity(): void {
		$this->assertEquals(
			new MappingList(),
			$this->newRepo()->getMappings( new ItemId( 'Q404' ) )
		);
	}

	private function newRepo(): ContentSlotMappingRepository {
		return new ContentSlotMappingRepository(
			contentRepository: new InMemoryEntityContentRepository()
		);
	}

	public function testPersistenceRoundTrip(): void {
		$repo = $this->newRepo();

		$repo->setMappings(
			new ItemId( 'Q1' ),
			new MappingList( [
				new Mapping( 'q1-predicate-1', 'q1-object-1' ),
			] )
		);

		$repo->setMappings(
			new ItemId( 'Q2' ),
			new MappingList( [
				new Mapping( 'q2-predicate-1', 'q2-object-1' ),
				new Mapping( 'q2-predicate-2', 'q2-object-2' ),
			] )
		);

		$repo->setMappings(
			new ItemId( 'Q3' ),
			new MappingList( [
				new Mapping( 'q3-predicate-1', 'q3-object-1' ),
			] )
		);

		$this->assertEquals(
			new MappingList( [
				new Mapping( 'q2-predicate-1', 'q2-object-1' ),
				new Mapping( 'q2-predicate-2', 'q2-object-2' ),
			] ),
			$repo->getMappings( new ItemId( 'Q2' ) )
		);
	}

}
