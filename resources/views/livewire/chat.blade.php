<div class="chat-container">
    <div class="chat-sidebar">
        <ul>
            @foreach ($users as $user)
                <li wire:click="switchRecipient({{ $user->id }})"
                    class="{{ $user->id == $recipientId ? 'active' : '' }}">
                    {{ $user->name }}
                    @if (isset($unreadCounts[$user->id]) && $unreadCounts[$user->id] > 0)
                        <span class="badge">{{ $unreadCounts[$user->id] }}</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <div class="chat-content">
        <div class="messages">
            @foreach ($messages as $msg)
                <div class="message {{ $msg['from'] == auth()->id() ? 'sent' : 'received' }}">
                    <p>{{ $msg['message'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="message-input">
            <input type="text" wire:model.defer="message" placeholder="Type a message...">
            <button wire:click="sendMessage">Send</button>
        </div>
    </div>
</div>
