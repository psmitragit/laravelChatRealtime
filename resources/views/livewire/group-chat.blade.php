<div>

    {{-- chat container --}}
    <div class="chat-container">
        <div class="chat-content">
            <div class="messages">
                @foreach ($messages as $msg)
                    <div class="message {{ $msg->user_id == auth()->id() ? 'sent' : 'received' }}">
                        <div class="message-info">
                            <strong>{{ $msg->user->name }}</strong>
                        </div>
                        <div class="message-text">
                            @if (!empty($msg->file))
                                <div>
                                    <a href="{{ asset('storage/chat-uploads/' . $msg->file) }}" target="_blank">See
                                        attachment</a>
                                </div>
                            @endif
                            <p>{{ $msg->message }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <form wire:submit.prevent='sendMessage' class="chatForm">
                <div class="message-input">
                    <input id="typeMessageId" type="text" wire:model="message" placeholder="Type a message...">
                    <div>
                        <input type="file" wire:model='file'>
                        @error('file')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit">Send</button>
                </div>
            </form>
        </div>
    </div>
    {{-- chat container --}}



    {{-- perticipents --}}
    <div>
        <h4>Participants</h4>
        <ul>
            @php
                $participants = array_unique($participants);
            @endphp
            @foreach ($participants as $participant)
                @php
                    $userRow = App\Models\User::find($participant);
                @endphp
                <li>{{ $userRow->name }}</li>
            @endforeach
        </ul>
    </div>
    {{-- perticipents --}}



    {{-- recording --}}
    <div>
        <button id="startRecording">Start Recording</button>
        <button id="stopRecording" disabled>Stop Recording</button>

        <script>
            let mediaRecorder;
            let recordedChunks = [];
            let stream;

            async function startRecording() {
                try {
                    stream = await navigator.mediaDevices.getDisplayMedia({
                        video: true,
                        audio: true
                    });

                    mediaRecorder = new MediaRecorder(stream);

                    mediaRecorder.ondataavailable = (event) => {
                        if (event.data.size > 0) {
                            recordedChunks.push(event.data);
                        }
                    };

                    mediaRecorder.onstop = () => {
                        const blob = new Blob(recordedChunks, {
                            type: 'video/webm'
                        });
                        const url = URL.createObjectURL(blob);

                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `recording-${new Date().toISOString().split('T')[0]}.webm`;
                        a.click();
                        const formData = new FormData();
                        formData.append('video', blob, 'recording.webm');
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'));

                        fetch('/upload-video', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => console.log(data))
                            .catch(error => console.error('Error:', error));
                    };

                    mediaRecorder.start();
                    document.getElementById('stopRecording').disabled = false;
                } catch (error) {
                    console.error('Error starting recording.', error);
                }
            }

            function stopRecording() {
                if (mediaRecorder) {
                    mediaRecorder.stop();
                    stream.getTracks().forEach(track => track.stop());
                    document.getElementById('stopRecording').disabled = true;
                }
            }

            document.getElementById('startRecording').addEventListener('click', startRecording);
            document.getElementById('stopRecording').addEventListener('click', stopRecording);
        </script>
    </div>
    {{-- recording --}}

</div>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('reFocus', function() {
            document.getElementById('typeMessageId').focus();
        })

        Livewire.on('scrollBottom', function() {
            setTimeout(() => {
                const messages = document.querySelectorAll('.message');
                if (messages.length > 0) {
                    const lastMessage = messages[messages.length - 1];
                    lastMessage.scrollIntoView({
                        behavior: 'smooth',
                        block: 'end'
                    });
                }
            }, 100);
        })
    });
</script>
