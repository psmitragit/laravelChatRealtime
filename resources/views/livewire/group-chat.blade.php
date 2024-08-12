<div>
    <div class="chat-container">
        <div class="chat-content">
            <div class="messages">
                @foreach ($messages as $msg)
                    <div class="message {{ $msg->user_id == auth()->id() ? 'sent' : 'received' }}">
                        <div class="message-info">
                            <strong>{{ $msg->user->name }}</strong>
                        </div>
                        <div class="message-text">
                            <p>{{ $msg->message }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="message-input">
                <input type="text" wire:model.defer="message" placeholder="Type a message..."
                    aria-label="Type a message">
                <button wire:click="sendMessage">Send</button>
            </div>
        </div>
    </div>
</div>
