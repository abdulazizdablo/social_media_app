<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class MessagingController implements MessageComponentInterface
{
    /**
     * The WebSocket connection.
     *
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * The list of users who are connected.
     *
     * @var array
     */
    protected $users = [];

    /**
     * The list of messages.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Constructor.
     *
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;

        $this->connection->on('open', [$this, 'onOpen']);
        $this->connection->on('message', [$this, 'onMessage']);
        $this->connection->on('close', [$this, 'onClose']);
    }

    /**
     * Called when the WebSocket connection opens.
     *
     * @param ConnectionInterface $connection
     */
    public function onOpen(ConnectionInterface $connection)
    {
        $this->users[] = $connection;
    }

    /**
     * Called when the WebSocket connection receives a message.
     *
     * @param ConnectionInterface $connection
     * @param string $message
     */
    public function onMessage(ConnectionInterface $connection, $message)
    {
        $data = json_decode($message, true);

        if (isset($data['text'])) {
            $this->sendMessage($data['text'], $connection);
        }
    }

    /**
     * Called when the WebSocket connection closes.
     *
     * @param ConnectionInterface $connection
     */
    public function onClose(ConnectionInterface $connection)
    {
        foreach ($this->users as $key => $user) {
            if ($user === $connection) {
                unset($this->users[$key]);
            }
        }
    }

    /**
     * Sends a message to all connected users.
     *
     * @param string $text
     * @param ConnectionInterface $connection
     */
    public function sendMessage($text, ConnectionInterface $connection = null)
    {
        $this->messages[] = [
            'text' => $text,
            'created_at' => time(),
        ];

        foreach ($this->users as $user) {
            if ($connection !== $user) {
                $user->send(json_encode($this->messages[count($this->messages) - 1]));
            }
        }
    }
}