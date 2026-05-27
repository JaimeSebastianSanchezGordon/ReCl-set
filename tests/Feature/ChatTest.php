<?php

namespace Tests\Feature;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Garment;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_chat_routes(): void
    {
        $this->get('/mensajes')->assertRedirect(route('login'));
        $this->get('/mensajes/iniciar/1')->assertRedirect(route('login'));
        $this->get('/mensajes/1')->assertRedirect(route('login'));
        $this->post('/mensajes/1', ['body' => 'Hola'])->assertRedirect(route('login'));
    }

    public function test_user_cannot_chat_with_themselves_on_own_garment(): void
    {
        $user = User::factory()->create();
        $garment = Garment::create([
            'user_id' => $user->id,
            'name' => 'Mi propia camisa',
            'description' => 'Mi camisa',
            'price' => '10.00',
            'category' => 'tops',
            'size' => 'm',
            'color' => 'white',
            'status' => 'available',
        ]);

        $response = $this->actingAs($user)->get("/mensajes/iniciar/{$garment->id}");
        $response->assertRedirect(route('garments.show', $garment));
        $this->assertEquals(0, Conversation::count());
    }

    public function test_user_can_initiate_chat_with_another_user_garment(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $garment = Garment::create([
            'user_id' => $seller->id,
            'name' => 'Camisa de Luis',
            'description' => 'Hermosa camisa',
            'price' => '15.00',
            'category' => 'tops',
            'size' => 'm',
            'color' => 'white',
            'status' => 'available',
        ]);

        // Iniciar conversación
        $response = $this->actingAs($buyer)->get("/mensajes/iniciar/{$garment->id}");

        $conversation = Conversation::first();
        $this->assertNotNull($conversation);
        $this->assertEquals($garment->id, $conversation->garment_id);
        $this->assertEquals($buyer->id, $conversation->creator_user_id);
        $this->assertEquals($seller->id, $conversation->recipient_user_id);

        $response->assertRedirect(route('chat.show', $conversation));
    }

    public function test_unauthorized_user_cannot_view_conversation(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $intruder = User::factory()->create();

        $garment = Garment::create([
            'user_id' => $seller->id,
            'name' => 'Camisa de Luis',
            'description' => 'Hermosa camisa',
            'price' => '15.00',
            'category' => 'tops',
            'size' => 'm',
            'color' => 'white',
            'status' => 'available',
        ]);

        $conversation = Conversation::create([
            'garment_id' => $garment->id,
            'creator_user_id' => $buyer->id,
            'recipient_user_id' => $seller->id,
            'last_message_at' => now(),
        ]);

        // Comprador y vendedor deben poder ver
        $this->actingAs($buyer)->get("/mensajes/{$conversation->id}")->assertOk();
        $this->actingAs($seller)->get("/mensajes/{$conversation->id}")->assertOk();

        // Intruso no debe poder ver (403)
        $this->actingAs($intruder)->get("/mensajes/{$conversation->id}")->assertStatus(403);
    }

    public function test_user_can_send_message_in_conversation_and_broadcasts(): void
    {
        Event::fake();

        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $garment = Garment::create([
            'user_id' => $seller->id,
            'name' => 'Camisa de Luis',
            'description' => 'Hermosa camisa',
            'price' => '15.00',
            'category' => 'tops',
            'size' => 'm',
            'color' => 'white',
            'status' => 'available',
        ]);

        $conversation = Conversation::create([
            'garment_id' => $garment->id,
            'creator_user_id' => $buyer->id,
            'recipient_user_id' => $seller->id,
            'last_message_at' => now()->subDay(),
        ]);

        // Enviar mensaje
        $response = $this->actingAs($buyer)->post("/mensajes/{$conversation->id}", [
            'body' => 'Hola Luis, ¿sigue disponible la camisa?',
        ]);

        $response->assertRedirect(route('chat.show', $conversation));

        // Verificar mensaje guardado en BD
        $message = Message::first();
        $this->assertNotNull($message);
        $this->assertEquals('Hola Luis, ¿sigue disponible la camisa?', $message->body);
        $this->assertEquals($buyer->id, $message->user_id);
        $this->assertEquals($conversation->id, $message->conversation_id);

        // Verificar last_message_at actualizado
        $this->assertTrue($conversation->fresh()->last_message_at->gt(now()->subMinute()));

        // Verificar que se haya transmitido el evento de Websocket
        Event::assertDispatched(MessageSent::class, function (MessageSent $event) use ($message) {
            return $event->message->id === $message->id;
        });
    }

    public function test_user_can_send_message_via_ajax(): void
    {
        Event::fake();

        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $garment = Garment::create([
            'user_id' => $seller->id,
            'name' => 'Camisa de Luis',
            'price' => '15.00',
            'category' => 'tops',
            'size' => 'm',
            'color' => 'white',
            'status' => 'available',
        ]);

        $conversation = Conversation::create([
            'garment_id' => $garment->id,
            'creator_user_id' => $buyer->id,
            'recipient_user_id' => $seller->id,
            'last_message_at' => now(),
        ]);

        // Enviar mensaje vía AJAX
        $response = $this->actingAs($buyer)->postJson("/mensajes/{$conversation->id}", [
            'body' => 'Me gusta mucho tu prenda',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'message' => [
                    'id',
                    'body',
                    'user_id',
                    'formatted_time',
                    'user_name',
                ]
            ]);

        Event::assertDispatched(MessageSent::class);
    }
}
