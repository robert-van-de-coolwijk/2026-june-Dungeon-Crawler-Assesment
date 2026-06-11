<?php

namespace App\Core;

/**
 * Wraps messages with semantic intention
 *
 * This class separates inner working logic needed to understand messages,
 * by letting classes that have that responsibility indicate what a message signifies.
 *
 * The goal is to let a renderer that actually shows the message decide how to print them,.
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

    public string $contentType = MsgWrapContentType::Unset;

    public string $sentiment = MsgWrapSentiment::Normal;

    /**
     * @param string $msg What do you want to "print"
     * @param string $contentType What does "line" contain (example h1, h2, p, table)
     * @param string $sentiment How "important" is this "line" (normal, important, vital, error)
     */
    public function __construct(string $msg, string $contentType= MsgWrapContentType::Unset, string $sentiment = MsgWrapSentiment::Normal){
        $this->msg = $msg;
        $this->contentType = $contentType;
        $this->sentiment = $sentiment;
    }
}