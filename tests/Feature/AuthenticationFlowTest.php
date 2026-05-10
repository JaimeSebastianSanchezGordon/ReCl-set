<?php

namespace Tests\Feature;

use App\Models\Garment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_two_users_can_register_login_publish_and_see_the_expected_garments(): void
    {
        $password = 'password123';

        $this->post('/registrarse', [
            'name' => 'Ana Closet',
            'email' => 'ana@example.com',
            'password' => $password,
            'password_confirmation' => $password,
        ])->assertRedirect(route('garments.my'));

        $ana = User::where('email', 'ana@example.com')->firstOrFail();
        $this->assertAuthenticatedAs($ana);

        $this->post('/logout')->assertRedirect(route('garments.explore'));
        $this->assertGuest();

        $this->post('/registrarse', [
            'name' => 'Luis Moda',
            'email' => 'luis@example.com',
            'password' => $password,
            'password_confirmation' => $password,
        ])->assertRedirect(route('garments.my'));

        $luis = User::where('email', 'luis@example.com')->firstOrFail();
        $this->assertAuthenticatedAs($luis);

        $this->post('/logout')->assertRedirect(route('garments.explore'));
        $this->assertGuest();

        $this->post('/login', [
            'email' => $ana->email,
            'password' => $password,
        ])->assertRedirect(route('garments.my'));
        $this->assertAuthenticatedAs($ana);

        $this->post('/prendas', $this->garmentPayload('Chaqueta verde Ana'))
            ->assertRedirect();

        $anaGarment = Garment::where('name', 'Chaqueta verde Ana')->firstOrFail();
        $this->assertTrue($anaGarment->isOwnedBy($ana->id));

        $this->post('/logout')->assertRedirect(route('garments.explore'));

        $this->post('/login', [
            'email' => $luis->email,
            'password' => $password,
        ])->assertRedirect(route('garments.my'));
        $this->assertAuthenticatedAs($luis);

        $this->post('/prendas', $this->garmentPayload('Camisa blanca Luis'))
            ->assertRedirect();

        $luisGarment = Garment::where('name', 'Camisa blanca Luis')->firstOrFail();
        $this->assertTrue($luisGarment->isOwnedBy($luis->id));

        $this->get('/explorar')
            ->assertOk()
            ->assertSee('Chaqueta verde Ana')
            ->assertDontSee('Camisa blanca Luis');

        $this->get('/mis-prendas')
            ->assertOk()
            ->assertSee('Camisa blanca Luis')
            ->assertDontSee('Chaqueta verde Ana');

        $this->post('/logout')->assertRedirect(route('garments.explore'));

        $this->post('/login', [
            'email' => $ana->email,
            'password' => $password,
        ])->assertRedirect(route('garments.my'));
        $this->assertAuthenticatedAs($ana);

        $this->get('/explorar')
            ->assertOk()
            ->assertSee('Camisa blanca Luis')
            ->assertDontSee('Chaqueta verde Ana');

        $this->get('/mis-prendas')
            ->assertOk()
            ->assertSee('Chaqueta verde Ana')
            ->assertDontSee('Camisa blanca Luis');
    }

    public function test_private_garment_routes_require_authentication(): void
    {
        $this->get('/mis-prendas')->assertRedirect(route('login'));
        $this->get('/prendas/crear')->assertRedirect(route('login'));
        $this->post('/prendas', $this->garmentPayload('Prenda sin usuario'))->assertRedirect(route('login'));
    }

    /**
     * @return array<string, string>
     */
    private function garmentPayload(string $name): array
    {
        return [
            'name' => $name,
            'description' => 'Prenda de prueba publicada desde feature test.',
            'price' => '25.50',
            'category' => 'tops',
            'size' => 'm',
            'color' => 'white',
        ];
    }
}
