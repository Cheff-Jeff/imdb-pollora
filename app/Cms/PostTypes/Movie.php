<?php

declare(strict_types=1);

namespace App\Cms\PostTypes;

use Pollora\Attributes\PostType;
use Pollora\Attributes\PostType\HasArchive;
use Pollora\Attributes\PostType\MenuIcon;
use Pollora\Attributes\PostType\PublicPostType;
use Pollora\Attributes\PostType\PubliclyQueryable;
use Pollora\Attributes\PostType\Supports;

/**
 * Movie post type.
 *
 * This class defines the Movie custom post type using PHP attributes
 * for WordPress registration.
 */
#[PostType]
#[PublicPostType]
#[PubliclyQueryable]
#[HasArchive]
#[Supports(['title', 'editor', 'thumbnail'])]
#[MenuIcon('dashicons-admin-post')]
class Movie
{
}
