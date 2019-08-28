<?php

namespace Tests\Feature\Countries;

use Tests\TestCase;
use App\Models\User;
use App\Models\Country;
use App\Http\Resources\Countries\CountryResource;

class CountryIndexTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->country = factory(Country::class)->create();
    }
    
    /** @test */
    public function it_fails_if_unauthenticated()
    {
        $response = $this->getJson(route('countries.index'));

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_returns_a_country_resource_collection()
    {
        $response = $this->getJsonAs($this->user, route('countries.index'));

        $response->assertOk();

        $response->assertResource(CountryResource::collection(Country::get()));
    }
}
