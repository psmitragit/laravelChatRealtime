<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Group chat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <style>
        /* General styling for chat container */
        .chat-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            max-width: 100%;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        /* Chat content styling */
        .chat-content {
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
            flex: 1;
            padding: 10px;
            overflow-y: auto;
        }

        /* Message container styling */
        .messages {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Message styling */
        .message {
            display: flex;
            flex-direction: column;
            max-width: 70%;
            padding: 10px;
            border-radius: 10px;
            font-size: 14px;
            line-height: 1.5;
        }

        .message.sent {
            align-self: flex-end;
            background-color: #e1ffc7;
            text-align: right;
            border: 1px solid #c1e1b2;
        }

        .message.received {
            align-self: flex-start;
            background-color: #f1f0f0;
            text-align: left;
            border: 1px solid #ddd;
        }

        /* Message info (sender's name) */
        .message-info {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 12px;
            color: #333;
        }

        /* Message text */
        .message-text {
            background: #fff;
            padding: 8px 10px;
            border-radius: 5px;
        }

        /* Message input styling */
        .message-input {
            display: flex;
            border-top: 1px solid #ddd;
            padding: 10px;
            background-color: #ffffff;
            align-items: center;
        }

        .message-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
            font-size: 14px;
            outline: none;
        }

        .message-input input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(38, 143, 255, 0.3);
        }

        .message-input button {
            padding: 10px 15px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .message-input button:hover {
            background-color: #0056b3;
        }
    </style>
    <div class="container">
        <script>
            window.roomId = "{{ $roomId }}";
        </script>
        @livewire('GroupChat', ['roomId' => $roomId])
    </div>
</body>

</html>
