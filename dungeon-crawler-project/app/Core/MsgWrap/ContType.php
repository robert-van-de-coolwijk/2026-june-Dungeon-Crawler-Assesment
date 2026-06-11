<?php

namespace App\Core\MsgWrap;

/**
 * Message Wrap Content Type
 */
class ContType
{
    /**
     * Heading level 1
     */
    public const H1 = 'h1';

    /**
     * Heading level 2
     */
    public const H2 = 'h2';

    /**
     * Heading level 3
     */
    public const H3 = 'h3';

    /**
     * Paragraph
     */
    public const P = 'p';

    /**
     * Table
     */
    public const Table = 'table';

    /**
     * The content type is NOT set, do not use!
     */
    const Unset = 'unset';
}