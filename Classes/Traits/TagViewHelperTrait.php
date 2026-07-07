<?php

declare(strict_types=1);

namespace ITX\Jobapplications\Traits;

/*
 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

/**
 * Trait TagViewHelperTrait
 *
 * Trait implemented by ViewHelpers which require access
 * to functions dealing with tag generation.
 *
 */
trait TagViewHelperTrait
{
    public function registerArguments(): void
    {
        $this->registerArgument(
            'forceClosingTag',
            'boolean',
            'If TRUE, forces the created tag to use a closing tag. If FALSE, allows self-closing tags.',
            false,
            false
        );
        $this->registerArgument(
            'hideIfEmpty',
            'boolean',
            'Hide the tag completely if there is no tag content',
            false,
            false
        );
    }

    /**
     * Renders the provided tag with the given name and any
     * (additional) attributes not already provided as arguments.
     *
     * @param array<string, mixed> $attributes
     * @param string[] $nonEmptyAttributes
     */
    protected function renderTag(
        string $tagName,
        mixed $content = null,
        array $attributes = [],
        array $nonEmptyAttributes = ['id', 'class']
    ): string {
        $trimmedContent = trim((string) $content);
        $forceClosingTag = (bool) $this->arguments['forceClosingTag'];

        if (empty($trimmedContent) && (bool) $this->arguments['hideIfEmpty']) {
            return '';
        }

        if ($tagName === 'none' || empty($tagName)) {
            // skip building a tag if special keyword "none" is used, or tag name is empty
            return $trimmedContent;
        }

        $this->tag->setTagName($tagName);
        $this->tag->addAttributes($attributes);
        $this->tag->forceClosingTag($forceClosingTag);

        if ($content !== null) {
            $this->tag->setContent($trimmedContent);
        }

        // process some attributes differently - if empty, remove the property:
        foreach ($nonEmptyAttributes as $propertyName) {
            $value = $this->arguments[$propertyName] ?? null;
            if (empty($value)) {
                $this->tag->removeAttribute($propertyName);
            } else {
                $this->tag->addAttribute($propertyName, $value);
            }
        }

        return $this->tag->render();
    }

    /**
     * Renders the provided tag and optionally appends or prepends
     * it to the main tag's content depending on 'mode' which can
     * be one of 'none', 'append' or 'prepend'
     *
     * @param array<string, mixed> $attributes
     */
    protected function renderChildTag(
        string $tagName,
        array $attributes = [],
        bool $forceClosingTag = false,
        string $mode = 'none'
    ): string {
        $tagBuilder = clone $this->tag;
        $tagBuilder->reset();
        $tagBuilder->setTagName($tagName);
        $tagBuilder->addAttributes($attributes);
        $tagBuilder->forceClosingTag($forceClosingTag);
        $childTag = $tagBuilder->render();

        if ($mode === 'append' || $mode === 'prepend') {
            $content = $this->tag->getContent();
            $content = $mode === 'append'
                ? $content . $childTag
                : $childTag . $content;
            $this->tag->setContent($content);
        }

        return $childTag;
    }
}
