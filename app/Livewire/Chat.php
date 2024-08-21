<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Livewire\Component;
use App\Events\MessageSent;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class Chat extends Component
{
    use WithFileUploads;
    public $message = '';
    public $file;
    public $messages = [];
    public $recipientId;
    public $users = [];
    public $unreadCounts = [];

    public function mount($recipientId = null)
    {
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $this->users = User::where('id', '!=', auth()->id())
            ->withCount(['messagesToMe' => function ($query) {
                $query->where('to', auth()->id())->where('is_seen', false);
            }])
            ->with(['lastMessageToMe' => function ($query) {
                $query->where('to', auth()->id())->latest();
            }])
            ->get()
            ->sortByDesc(function ($user) {
                return optional($user->lastMessageToMe)->created_at;
            });

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

        $this->markMessagesAsSeen();
    }

    public function sendMessage()
    {
        if (empty($this->message) && empty($this->file)) return false;

        $customFileName = null;

        $directory = storage_path('app/public/chat-uploads');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        if ($this->file) {
            $fileExtension = $this->file->getClientOriginalExtension();
            $customFileName = rand(11111, 99999) . time() . '.' . $fileExtension;
            $this->file->storeAs('chat-uploads', $customFileName, 'public');
        }

        $newMessage = Message::create([
            'from' => auth()->id(),
            'to' => $this->recipientId,
            'message' => $this->message,
            'file' => $customFileName,
        ]);

        broadcast(new MessageSent($newMessage, User::find($this->recipientId)));

        $this->messages[] = array_merge($newMessage->toArray(), ['user' => auth()->user()->toArray()]);

        $this->message = '';
        $this->reset(['file']);
        $this->dispatch('reFocus');
        $this->dispatch('scrollBottom');
    }

    #[On('newMessageReceived')]
    public function notifyNewMessage($data)
    {
        if ($data['message']['from'] === $this->recipientId) {
            $this->messages[] = array_merge($data['message'], ['user' => $data['user']]);
            $this->markMessagesAsSeen();
            $this->dispatch('scrollBottom');
        } else {
            $this->loadUsers();
        }
    }

    public function switchRecipient($recipientId)
    {
        $this->recipientId = $recipientId;
        $this->loadMessages();
        $this->markMessagesAsSeen();
        $this->loadUsers();
        $this->dispatch('reFocus');
        $this->dispatch('scrollBottom');
    }

    protected function markMessagesAsSeen()
    {
        Message::where('to', auth()->id())->where('from', $this->recipientId)->update(['is_seen' => true]);
    }

    public function render()
    {
        return view('livewire.chat', [
            'users' => $this->users,
            'unreadCounts' => $this->unreadCounts,
        ]);
    }
}
