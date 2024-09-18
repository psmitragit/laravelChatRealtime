<?php

namespace App\Livewire;

use GuzzleHttp\Client;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\GroupMessage;
use Livewire\WithFileUploads;
use App\Events\GroupChat as EventGroupChat;
use App\Http\Controllers\RecordingController;

class GroupChat extends Component
{
    use WithFileUploads;
    public $roomId;
    public $message = '';
    public $file;
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

        $data = [
            'user_id' => auth()->id(),
            'room_id' => $this->roomId,
            'message' => $this->message,
            'file' => $customFileName,
        ];

        GroupMessage::create($data);

        broadcast(new EventGroupChat($this->roomId));

        $this->message = '';
        $this->reset(['file']);
        $this->loadMessages();
    }
    #[On('newMessageReceived')]
    public function notifyNewMessage()
    {
        $this->loadMessages();
    }



    public $participants = [];
    #[On('updateUserList')]
    public function handel_updateUserList($userIds): void
    {
        $participants = [];
        $allParticipants = array_merge($participants, $userIds);
        $this->participants = $allParticipants;
    }
    #[On('userJoined')]
    public function handel_userJoined($joinedUserId): void
    {
        array_push($this->participants, $joinedUserId);
    }
    #[On('userLeft')]
    public function handel_userLeft($leftUserId): void
    {
        if (($key = array_search($leftUserId, $this->participants)) !== false) {
            unset($this->participants[$key]);
        }
    }

    // public function getResourceId()
    // {
    //     // $recordingController = new RecordingController();
    //     // $res = $recordingController->acquire();




    //     $appId = env('AGORA_APP_ID');
    //     $apiUrl = env('AGORA_REST_API') . '/' . $appId;
    //     $url = "{$apiUrl}/cloud_recording/acquire";
    //     $client = new Client();

    //     $customerId = env('AGORA_CUSTOMER_ID');
    //     $customerSecret = env('AGORA_CUSTOMER_SECRET');

    //     $credentials = $customerId . ":" . $customerSecret;
    //     $base64Credentials = base64_encode($credentials);

    //     $param = [
    //         'cname' => 'httpClient463224',
    //         'uid' => '527841',
    //         'clientRequest' => [
    //             'resourceExpiredHour' => 24,
    //             'scene' => 1,
    //         ]
    //     ];

    //     $arr_header = "Authorization: Basic " . $base64Credentials;
    //     $curl = curl_init();
    //     curl_setopt_array(
    //         $curl,
    //         array(
    //             CURLOPT_URL => $url,
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_ENCODING => '',
    //             CURLOPT_MAXREDIRS => 10,
    //             CURLOPT_TIMEOUT => 0,
    //             CURLOPT_FOLLOWLOCATION => true,
    //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //             CURLOPT_CUSTOMREQUEST => 'POST',
    //             CURLOPT_HTTPHEADER => array($arr_header,    'Content-Type: application/json'),
    //             CURLOPT_POSTFIELDS => json_encode($param)
    //         )
    //     );

    //     $response = curl_exec($curl);
    //     if ($response === false) {
    //         echo "Error in cURL : " . curl_error($curl);
    //     }
    //     curl_close($curl);

    //     dd($response);

    //     // // try {
    //     //     $response = $client->post($url, [
    //     //         'headers' => [
    //     //             'Authorization' => "Basic $credentials",
    //     //             'Content-Type' => 'application/json;charset=utf-8'
    //     //         ],
    //     //         'json' => [
    //     //             'cname'=> 'httpClient463224',
    //     //             'uid'=> '527841',
    //     //             'clientRequest' => [
    //     //                 'resourceExpiredHour' => 24,
    //     //                 'scene' => 1
    //     //             ]
    //     //         ]
    //     //     ]);

    //     //     dd($response);

    //     //     $result = json_decode($response->getBody(), true);
    //     //     return response()->json([
    //     //         'resourceId' => $result['resourceId']
    //     //     ]);
    //     // } catch (\Exception $e) {
    //     //     return response()->json([
    //     //         'error' => $e->getMessage()
    //     //     ], 500);
    //     // }

    // }

    public function render()
    {
        return view('livewire.group-chat');
    }
}
