<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element\Traits;

use InvalidArgumentException;
use RuntimeException;

trait EscaperTrait
{
    /**
     * @var string[]
     */
    private static $shortJavascriptSequences = [
        '\\' => '\\\\',
        '/' => '\\/',
        "\x08" => '\b',
        "\x0C" => '\f',
        "\x0A" => '\n',
        "\x0D" => '\r',
        "\x09" => '\t',
    ];

    private function isUtf8(string $string): bool
    {
        return (bool) preg_match('//u', $string);
    }

    private function assertIsUtf8(string $string): void
    {
        if (! $this->isUtf8($string)) {
            throw new InvalidArgumentException('The string to escape is not a valid UTF-8 string.');
        }
    }

    private function escapeHtml(string $string): string
    {
        $this->assertIsUtf8($string);

        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function escapeAttribute(string $string): string
    {
        $this->assertIsUtf8($string);

        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function escapeJavascript(string $string): string
    {
        $this->assertIsUtf8($string);

        $callback = static function ($matches): string {
            $char = $matches[0];

            if (isset(self::$shortJavascriptSequences[$char])) {
                return self::$shortJavascriptSequences[$char];
            }

            $utf16Char = iconv($char, 'UTF-16BE', 'UTF-8');

            if ($utf16Char === false) {
                throw new RuntimeException(sprintf('Cannot convert "%s" to UTF-16.', $char));
            }

            $hex = strtoupper(bin2hex($utf16Char));

            if (strlen($hex) <= 4) {
                return '\u' . $hex;
            }

            return '\u' . substr($hex, 0, -4) . '\u' . substr($hex, -4);
        };

        return preg_replace_callback('#[^a-zA-Z0-9,._]#Su', $callback, $string) ?? $string;
    }
}
