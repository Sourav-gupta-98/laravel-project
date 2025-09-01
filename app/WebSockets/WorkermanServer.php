<?php

namespace App\WebSockets;

use App\Models\Customer;
use App\Models\Orders;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Workerman\Worker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class WorkermanServer
{
    // Store connections by userId
    protected $adminUserConnections = [];
    protected $customerUserConnections = [];


    public function start()
    {
        global $argv;

        // Fake correct CLI args for Workerman
        $argv[0] = 'workerman:serve'; // pretend filename
        $argv[1] = 'start';           // force start mode

        // Create WebSocket server
        $ws = new Worker("websocket://0.0.0.0:2346");

        // Handle new connection
        $ws->onConnect = function ($connection) {
        };

        // Handle messages
        $ws->onMessage = function ($connection, $data) use ($ws) {
            $payload = json_decode($data, true);
            // Authenticate user (simplified, can be JWT or Laravel session)
            if ($payload['type'] === 'auth' && $payload['user_type'] == 'ADMIN') {
                $userId = $payload['user_id'];

                User::where('id', $userId)->update(['logged_in_status' => 'ACTIVE', 'logged_in_time' => date('Y-m-d H:i:s')]);

                // Save connection in user's room
                $connection->userId = $userId;
                $connection->userType = 'ADMIN';
                $this->adminUserConnections[$userId][$connection->id] = $connection;

                // Notify presence
                foreach ($this->adminUserConnections as $otherUserId => $connections) {
                    foreach ($connections as $conn) {
                        $conn->send(json_encode([
                            'type' => 'presence',
                            'user_type' => 'ADMIN',
                            'status' => 'Online',
                            'user_id' => $userId,
                            'time' => date('Y-m-d H:i:s')
                        ]));
                    }
                }
                foreach ($this->customerUserConnections as $otherUserId => $connections) {
                    foreach ($connections as $conn) {
                        $conn->send(json_encode([
                            'type' => 'presence',
                            'user_type' => 'ADMIN',
                            'status' => 'Online',
                            'user_id' => $userId,
                            'time' => date('Y-m-d H:i:s')
                        ]));
                    }
                }
                return;
            }

            // authenticating customer login
            if ($payload['type'] === 'auth' && $payload['user_type'] == 'CUSTOMER') {
                $userId = $payload['user_id'];
                Customer::where('id', $userId)->update(['logged_in_status' => 'ACTIVE', 'logged_in_time' => date('Y-m-d H:i:s')]);
                // Save connection in user's room
                $connection->userId = $userId;
                $connection->userType = 'CUSTOMER';
                $this->customerUserConnections[$userId][$connection->id] = $connection;

                // Notify presence
                foreach ($this->adminUserConnections as $otherUserId => $connections) {
                    foreach ($connections as $conn) {
                        $conn->send(json_encode([
                            'type' => 'presence',
                            'user_type' => 'CUSTOMER',
                            'status' => 'Online',
                            'user_id' => $userId,
                            'time' => date('Y-m-d H:i:s')
                        ]));
                    }
                }
                foreach ($this->customerUserConnections as $otherUserId => $connections) {
                    foreach ($connections as $conn) {
                        $conn->send(json_encode([
                            'type' => 'presence',
                            'user_type' => 'CUSTOMER',
                            'status' => 'Online',
                            'user_id' => $userId,
                            'time' => date('Y-m-d H:i:s')
                        ]));
                    }
                }
                return;
            }

            if ($payload['type'] === 'STATUS_UPDATE') {
                $userId = $payload['customer_id'];
                Orders::where('id', $payload['order_id'])->update(['status' => $payload['status']]);

                // Notify presence
                foreach ($this->customerUserConnections[$userId] as $conn) {
                    $conn->send(json_encode([
                        'type' => 'STATUS_UPDATE',
                        'status' => $payload['status'],
                        'order_id' => $payload['order_id']
                    ]));

                }
                return;
            }

            // Handle sending message to a user room
            if ($payload['type'] === 'message') {
                $toUser = $payload['to_user'];
                $message = $payload['status'];

                if (!empty($this->userConnections[$toUser])) {
                    foreach ($this->userConnections[$toUser] as $conn) {
                        $conn->send(json_encode([
                            'type' => 'message',
                            'from_user' => $connection->userId,
                            'message' => $message
                        ]));
                    }
                }
            }
        };

        // Handle disconnect
        $ws->onClose = function ($connection) {
            if (isset($connection->userId) && $connection->userType == 'ADMIN') {
                $userId = $connection->userId;
                User::where('id', $userId)->update(['logged_in_status' => 'INACTIVE', 'logged_in_time' => date('Y-m-d H:i:s')]);
                unset($this->adminUserConnections[$userId][$connection->id]);

                // Notify if no devices remain
                if (empty($this->adminUserConnections[$userId])) {
                    // Notify presence
                    foreach ($this->adminUserConnections as $otherUserId => $connections) {
                        foreach ($connections as $conn) {
                            $conn->send(json_encode([
                                'type' => 'presence',
                                'user_type' => 'ADMIN',
                                'status' => 'Offline',
                                'user_id' => $userId,
                                'time' => date('Y-m-d H:i:s')
                            ]));
                        }
                    }
                    foreach ($this->customerUserConnections as $otherUserId => $connections) {
                        foreach ($connections as $conn) {
                            $conn->send(json_encode([
                                'type' => 'presence',
                                'user_type' => 'ADMIN',
                                'status' => 'Offline',
                                'user_id' => $userId,
                                'time' => date('Y-m-d H:i:s')
                            ]));
                        }
                    }
                }
            }
            if (isset($connection->userId) && $connection->userType == 'CUSTOMER') {
                $userId = $connection->userId;
                Customer::where('id', $userId)->update(['logged_in_status' => 'INACTIVE', 'logged_in_time' => date('Y-m-d H:i:s')]);
                unset($this->customerUserConnections[$userId][$connection->id]);

                // Notify if no devices remain
                if (empty($this->customerUserConnections[$userId])) {

                    // Notify presence
                    foreach ($this->adminUserConnections as $otherUserId => $connections) {
                        foreach ($connections as $conn) {
                            $conn->send(json_encode([
                                'type' => 'presence',
                                'user_type' => 'CUSTOMER',
                                'status' => 'Offline',
                                'user_id' => $userId,
                                'time' => date('Y-m-d H:i:s')
                            ]));
                        }
                    }
                    foreach ($this->customerUserConnections as $otherUserId => $connections) {
                        foreach ($connections as $conn) {
                            $conn->send(json_encode([
                                'type' => 'presence',
                                'user_type' => 'CUSTOMER',
                                'status' => 'Offline',
                                'user_id' => $userId,
                                'time' => date('Y-m-d H:i:s')
                            ]));
                        }
                    }

                }
            }
        };

        Worker::runAll();
    }
}
