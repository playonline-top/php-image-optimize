# php-image-optimize

Easily create thumbnails from external image assets.

This script is intended mainly for game sites who embed games from GameMonetize.com but could be used for other game distribution partners, or entirely different sites.

## What this script does

By default it will downscale the image provided from the URL in the `?url` query parameter to 256x192 and will convert the image to WebP with 60% quality.

The default configuration only allows image URLs from GM's thumbnail domain (`img.gamemonetize.com`).

## Additional consideration: Cloudflare proxy caching

Since by default dynamic scripts are not cached by Cloudflare, you should add a caching rule to the location of your optimization script to force caching. For information on how to do this, read the Cloudflare documentation.

## License

The code is licensed under the Mozilla Public License, Version 2.0. See the file `LICENSE` for more information.
