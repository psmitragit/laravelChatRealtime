<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\GroupMessage;
use App\Events\GroupChat as EventGroupChat;

class GroupChat extends Component
{
    public $roomId;
    public $message = '';
    public $messages;
    public function mount()
    {
        $this->loadMessages();
    }
    public function loadMessages()
    {
        $this->messages = GroupMessage::where('room_id', $this->roomId)
            ->orderBy('created_at', 'asc')
            ->get();
        $this->dispatch('scrollBottom');
    }
    public function sendMessage()
    {
        $newMessage = GroupMessage::create([
            'user_id' => auth()->id(),
            'room_id' => $this->roomId,
            'message' => $this->message,
        ]);

        broadcast(new EventGroupChat($newMessage, $this->roomId));

        $this->message = '';
        $this->loadMessages();
    }
    #[On('newMessageReceived')]
    public function notifyNewMessage($data)
    {
        $this->loadMessages();
    }
    public function render()
    {
        return view('livewire.group-chat');
    }
}
