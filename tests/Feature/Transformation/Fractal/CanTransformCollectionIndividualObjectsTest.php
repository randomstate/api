<?php


namespace RandomState\Tests\Api\Feature\Transformation\Fractal;


use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use RandomState\Api\Transformation\Adapters\Fractal\CollectionAdapter;
use RandomState\Api\Transformation\Fractal\Resolver;
use RandomState\Api\Transformation\Fractal\Switchboard;
use RandomState\Api\Transformation\Manager;
use RandomState\Tests\Api\TestCase;

class CanTransformCollectionIndividualObjectsTest extends TestCase {

	/**
	 * @test
	 */
	public function can_transform_single_entities_of_different_type_in_collection()
	{
	    $manager = new Manager();
	    $manager->register(
	    	new CollectionAdapter(
	    		new \League\Fractal\Manager(),
			    new Switchboard($resolver = new Resolver())
		    )
	    );

	    $resolver->bind(RaffleTicket::class, RaffleTicketTransformer::class);
	    $resolver->bind(Prize::class, PrizeTransformer::class);

	    $output = $manager->transform(
	    	[new RaffleTicket(), new Prize(), new RaffleTicket(), new RaffleTicket()]
	    );

	    $this->assertEquals(
	    	[
	    		'data' => [
				    ['raffle_number' => 1932],
				    ['value' => 100],
				    ['raffle_number' => 1932],
				    ['raffle_number' => 1932],
			    ]
		    ],
		    $output
	    );
	}
	
	/**
	 * @test
	 */
	public function can_transform_with_includes()
	{
		$manager = new Manager();
		$manager->register(
			new CollectionAdapter(
				$fractalManager = new \League\Fractal\Manager(),
				new Switchboard($resolver = new Resolver())
			)
		);

		$fractalManager->parseIncludes(['winning_ticket']);

		$resolver->bind(RaffleTicket::class, RaffleTicketTransformer::class);
		$resolver->bind(Prize::class, PrizeTransformer::class);

		$output = $manager->transform(
			[new RaffleTicket(), new Prize(), new RaffleTicket(), new RaffleTicket()]
		);

		$this->assertEquals(
			[
				'data' => [
					['raffle_number' => 1932],
					[
						'value' => 100,
						'winning_ticket' => [
							'data' => [
								'raffle_number' => 1932
							]
						]
					],
					['raffle_number' => 1932],
					['raffle_number' => 1932],
				]
			],
			$output
		);
	}
}

class RaffleTicket {

	public $number;

	public function __construct()
	{
		$this->number = 1932;
	}
}

class Prize {

	public $value;

	public function __construct()
	{
		$this->value = 100;
	}
}

class RaffleTicketTransformer extends TransformerAbstract {

	public function transform(RaffleTicket $ticket)
	{
		return [
			'raffle_number' => $ticket->number,
		];
	}
}

class PrizeTransformer extends TransformerAbstract {

	protected $availableIncludes = [
		'winning_ticket'
	];

	public function includeWinningTicket(Prize $prize)
	{
		return new Item(new RaffleTicket(), new RaffleTicketTransformer());
	}

	public function transform(Prize $prize)
	{
		return [
			'value' => $prize->value,
		];
	}

}