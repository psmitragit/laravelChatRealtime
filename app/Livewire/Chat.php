<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Livewire\Component;
use App\Events\MessageSent;
use Livewire\Attributes\On;

class Chat extends Component
{
    public $message;
    public $messages = [];
    public $recipientId;
    public $users = [];
    public $unreadCounts = [];

    public function mount($recipientId = null)
    {
        $this->recipientId = $recipientId ?? auth()->id();
        $this->loadUsers();
        $this->loadMessages();
    }

    public function loadUsers()
    {
        $this->users = User::where('id', '!=', auth()->id())->get();

        $this->unreadCounts = $this->users->mapWithKeys(function ($user) {
            return [$user->id => Message::where('to', auth()->id())->where('from', $user->id)->where('is_seen', false)->count()];
        })->toArray();
    }

    public function loadMessages()
    {
        $this->messages = Message::where(function ($query) {
            $query->where('from', auth()->id())->where('to', $this->recipientId);
        })->orWhere(function ($query) {
            $query->where('from', $this->recipientId)->where('to', auth()->id());
        })->latest()->get()->reverse()->values()->toArray();

        // Mark messages as seen
        $this->markMessagesAsSeen();
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string|max:255',
        ]);

        $newMessage = Message::create([
            'from' => auth()->id(),
            'to' => $this->recipientId,
            'message' => $this->message,
            'is_seen' => false,
        ]);

        broadcast(new MessageSent($newMessage, auth()->user()))->toOthers();

        $this->messages[] = array_merge($newMessage->toArray(), ['user' => auth()->user()->toArray()]);

        $this->message = '';
    }

    #[On('newMessageReceived')]
    public function notifyNewMessage($data)
    {
        if ($data['message']['to'] == auth()->id()) {
            $this->messages[] = array_merge($data['message'], ['user' => $data['user']]);
        }
        // Update unread message count or notify the user if the message is meant for the current recipient
        if ($data['message']['from'] === $this->recipientId) {
            $this->markMessagesAsSeen();
        }
    }

    protected function markMessagesAsSeen()
    {
        Message::where('to', auth()->id())->where('from', $this->recipientId)->update(['is_seen' => true]);
    }

    public function switchRecipient($recipientId)
    {
        $this->recipientId = $recipientId;
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.chat', [
            'users' => $this->users,
            'unreadCounts' => $this->unreadCounts,
        ]);
    }
}
