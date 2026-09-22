<?php
declare(strict_types=1);

namespace Fyre\Color\Colors;

use Fyre\Color\Color;
use Fyre\Color\ColorConverter;
use Fyre\Color\Traits\RgbTrait;
use Override;

/**
 * Srgb
 */
class Srgb extends Color
{
    use RgbTrait;

    protected const COLOR_SPACE = 'srgb';

    /**
     * Calculate the relative luminance value.
     *
     * @return float The relative luminance value.
     */
    #[Override]
    public function luma(): float
    {
        return ColorConverter::srgbToLuma($this->red, $this->green, $this->blue);
    }

    /**
     * Convert the Color to Hsl.
     *
     * @return Hsl The Hsl Color.
     */
    #[Override]
    public function toHsl(): Hsl
    {
        [$h, $s, $l] = ColorConverter::srgbToHsl($this->red, $this->green, $this->blue);

        return new Hsl($h, $s * 100, $l * 100, $this->alpha);
    }

    /**
     * Convert the Color to Hwb.
     *
     * @return Hwb The Hwb Color.
     */
    #[Override]
    public function toHwb(): Hwb
    {
        [$h, $w, $b] = ColorConverter::srgbToHwb($this->red, $this->green, $this->blue);

        return new Hwb($h, $w * 100, $b * 100, $this->alpha);
    }

    /**
     * Convert the Color to Rgb.
     *
     * @return Rgb The Rgb Color.
     */
    #[Override]
    public function toRgb(): Rgb
    {
        [$r, $g, $b] = ColorConverter::srgbToRgb($this->red, $this->green, $this->blue);

        return new Rgb($r, $g, $b, $this->alpha);
    }

    /**
     * Convert the Color to Srgb.
     *
     * @return Srgb The Srgb Color.
     */
    #[Override]
    public function toSrgb(): Srgb
    {
        return $this;
    }

    /**
     * Convert the Color to SrgbLinear.
     *
     * @return SrgbLinear The SrgbLinear Color.
     */
    #[Override]
    public function toSrgbLinear(): SrgbLinear
    {
        [$r, $g, $b] = ColorConverter::srgbToSrgbLinear($this->red, $this->green, $this->blue);

        return new SrgbLinear($r, $g, $b, $this->alpha);
    }
}
