<?php

declare(strict_types=1);
/*
 * BZFlag Social - Simple forum and messaging website for BZFlag
 * Copyright (C) 2022  Snail Paste, LLC
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Twig;

use s9e\TextFormatter\Bundles\Forum as TextFormatter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TextFormatterExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('tfxml_to_html', [$this, 'tfxml_to_html'], ['is_safe' => ['html']]),
        ];
    }

    public function tfxml_to_html(string $xml): string
    {
        return TextFormatter::render($xml);
    }
}
