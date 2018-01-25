<?php
namespace App\App42;

use App\App42\JSONObject;
use App\App42\session;
use App\App42\App42ResponseBuilder;
use App\App42\Queue;

/**
 *
 * QueueResponseBuilder class converts the JSON response retrieved from the
 * server to the value object i.e Queue
 *
 */
class QueueResponseBuilder extends App42ResponseBuilder {

    /**
     * Converts the response in JSON format to the value object i.e Queue
     *
     * @param json
     *            - response in JSON format
     *
     * @return Queue object filled with json data
     *
     */
    function buildResponse($json) {

        $queuesJSONObj = $this->getServiceJSONObject("queues", $json);
        $queueJSONObj = $queuesJSONObj->__get("queue");
        $queue = new Queue();
        $queue->setStrResponse($json);
        $queue->setResponseSuccess($this->isRespponseSuccess($json));
        $this->buildObjectFromJSONTree($queue, $queueJSONObj);

        if (!$queueJSONObj->has("messages"))
            return $queue;
        if (!$queueJSONObj->__get("messages")->has("message"))
            return $queue;

        if ($queueJSONObj->__get("messages")->__get("message") instanceof JSONObject) {
            // Single Entry

            $messageObj = new QueueMessage($queue);
            $this->buildObjectFromJSONTree($messageObj, $queueJSONObj->__get("messages")->__get("message"));
        } else {
            // Multiple Entry
            $messagesJSONArray = $queueJSONObj->getJSONObject("messages")->getJSONArray("message");
            for ($i = 0; $i < count($messagesJSONArray); $i++) {
                $messageJSONObj = $messagesJSONArray[$i];
                $messageObj = new QueueMessage($queue);
                $messageJSONObj = new JSONObject($messageJSONObj);
                $this->buildObjectFromJSONTree($messageObj, $messageJSONObj);
            }
        }
        return $queue;
    }

}
?>