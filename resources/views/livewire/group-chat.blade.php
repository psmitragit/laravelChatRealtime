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
            <form wire:submit.prevent='sendMessage' id="scrollToDiv">
                <div class="message-input">
                    <input id="typeMessageId" type="text" wire:model="message" placeholder="Type a message..."
                    >
                    <button type="submit">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
