<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <style>
        /* General styling for chat container */
        .chat-container {
            display: flex;
            height: 100vh;
            max-width: 100%;
            border: 1px solid #ddd;
        }

        /* Sidebar styling */
        .chat-sidebar {
            width: 25%;
            border-right: 1px solid #ddd;
            background-color: #f5f5f5;
            overflow-y: auto;
        }

        /* List styles within sidebar */
        .chat-sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .chat-sidebar li {
            padding: 15px;
            cursor: pointer;
            border-bottom: 1px solid #ddd;
            position: relative;
        }

        .chat-sidebar li.active {
            background-color: #e0e0e0;
        }

        .chat-sidebar .badge {
            background-color: #ff5722;
            color: #fff;
            border-radius: 12px;
            padding: 2px 8px;
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 12px;
        }

        /* Chat content styling */
        .chat-content {
            width: 75%;
            display: flex;
            flex-direction: column;
            background-color: #fff;
            height: 100%; /* Ensure it takes the full height of its container */
            overflow-y: auto;
        }

        /* Messages container */
        .messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            /* Ensure content fits properly and allows scrolling */
            display: flex;
            flex-direction: column;
        }

        /* Individual messages */
        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        /* Sent messages */
        .message.sent {
            background-color: #e1ffc7;
            align-self: flex-end;
        }

        /* Received messages */
        .message.received {
            background-color: #f1f0f0;
            align-self: flex-start;
        }

        /* Message input styling */
        .message-input {
            display: flex;
            border-top: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
        }

        .message-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
        }

        .message-input button {
            padding: 10px 15px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }

        .message-input button:hover {
            background-color: #0056b3;
        }
    </style>
    <div class="container">
        <script>
            window.userId = @json(auth()->id());
        </script>
        @livewire('chat')
    </div>
</body>

</html>
