<?php
/**
 * Created by PhpStorm.
 * User: teo
 * Date: 23/01/19
 * Time: 13:26
 */

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImageTypeExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [FileType::class];
    }
}