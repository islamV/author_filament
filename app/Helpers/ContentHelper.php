<?php

namespace App\Helpers;

class ContentHelper
{
    /**
     * Split large content into chunks that fit within database limits
     * 
     * @param string $content The content to split (can be HTML from RichEditor)
     * @param int $maxSize Maximum size in bytes (default: 1MB = 1048576 bytes for longText)
     * @return array Array of content chunks
     */
    public static function splitContent(string $content, int $maxSize = 1048576): array
    {
        $contentSize = strlen($content);
        
        // If content is smaller than max size, return as single chunk
        if ($contentSize <= $maxSize) {
            return [$content];
        }
        
        $chunks = [];
        $currentChunk = '';
        $currentSize = 0;
        
        // For HTML content, try to split by closing tags to maintain HTML structure
        // First, try to split by </p> tags (common in RichEditor)
        $htmlParts = preg_split('/(<\/p>)/i', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        // If no </p> tags found, try splitting by </div> tags
        if (count($htmlParts) === 1) {
            $htmlParts = preg_split('/(<\/div>)/i', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
        }
        
        // If still no HTML structure, split by paragraphs (plain text)
        if (count($htmlParts) === 1) {
            $htmlParts = preg_split('/(\r?\n\s*\r?\n)/', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
        }
        
        foreach ($htmlParts as $part) {
            $partSize = strlen($part);
            
            // If single part is larger than max size, split it by characters
            if ($partSize > $maxSize) {
                // Save current chunk if exists
                if (!empty($currentChunk)) {
                    $chunks[] = $currentChunk;
                    $currentChunk = '';
                    $currentSize = 0;
                }
                
                // Split large part by characters (last resort)
                $charChunks = str_split($part, $maxSize);
                foreach ($charChunks as $charChunk) {
                    $chunks[] = $charChunk;
                }
            } else {
                // Check if adding this part would exceed max size
                if ($currentSize + $partSize > $maxSize && !empty($currentChunk)) {
                    $chunks[] = $currentChunk;
                    $currentChunk = $part;
                    $currentSize = $partSize;
                } else {
                    $currentChunk .= $part;
                    $currentSize += $partSize;
                }
            }
        }
        
        // Add remaining chunk
        if (!empty($currentChunk)) {
            $chunks[] = $currentChunk;
        }
        
        return $chunks;
    }
}

