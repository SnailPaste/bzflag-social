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

namespace App\Service;

class Forum
{
    public function getTitle(): string
    {
        return 'Site Title';
    }

    public function getTitleFirst(): bool
    {
        return true;
    }

    public function getTheme(): string
    {
        return 'default';
    }

    public function getLocale(): string
    {
        return 'en-us';
    }

    public function getTimeZone(): string
    {
        return 'America/Chicago';
    }
}
