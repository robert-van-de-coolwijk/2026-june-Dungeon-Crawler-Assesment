<?php

namespace App\Core\MsgWrap;

/**
 * Message Wrap(per)
 * Wraps messages while maintaining semantic intention.
 *
 * This class separates inner working logic needed to understand messages,
 * by letting classes that have that responsibility indicate what a message signifies.
 *
 * The goal is to let a renderer that actually shows the message decide how to print them,
 * without know where the data comes from, what it means, it only knows what it signifies.
 *
 * All logic is kept short and to the point.
 * This class should be so simple and easy to use it should not have to explain itself
 * that is why all logic is written so briefly and in shorthand.
 *
 * This class does NOT protect any of the data BY DESIGN.
 * It is here to HELP the developer and can be used for good and for evil at your own leisure.
 *
 * Following the KISS principle.
 */
class MsgWrap
{
    public string $msg = "";

    public string $contentType;

    public string $sentiment;

    /**
     * @param string|MsgWrap $msg What do you want to "print"
     * @param string $contentType What does "line" contain (example h1, h2, p, table)
     * @param string $sentiment How "important" is this "line" (normal, important, vital, error)
     */
    public function __construct(string|MsgWrap $msg, string $contentType = ContType::Unset, string $sentiment = Sentiment::Normal){
        if(is_string($msg)){
            $this->msg = $msg;
            $this->contentType = $contentType;
            $this->sentiment = $sentiment;
        } else {
            $this->ingest($msg);
        }
    }

    private function ingest(MsgWrap $msg)
    {
        $this->msg = $msg->msg;
        $this->contentType = $msg->contentType;
        $this->sentiment = $msg->sentiment;
    }


    public function __toString() : string {
        return $this->msg;
    }



}
