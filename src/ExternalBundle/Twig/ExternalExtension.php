<?php

namespace ExternalBundle\Twig;

use ExternalBundle\Domain\Import\Common\Status;
use ExternalBundle\Entity\Enum\SynchronizationStatus;
use Symfony\Component\Translation\TranslatorInterface;

class ExternalExtension extends \Twig_Extension
{
    protected $translator;

    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('syncStatus', [$this, 'syncStatus'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('synchronizationStatus', [$this, 'synchronizationStatus'], ['is_safe' => ['html']]),
        );
    }

    public function syncStatus($status, $label = true, $short = true)
    {
        $color = '';

        $key = $short ? 'short.'.$status : $status;

        $text = $this->translator->trans($key, [], 'Sync');

        $pattern = '%s';

        if ($label) {
            switch ($status) {
                case Status::ERROR:
                    $color = 'danger';
                    break;
                case Status::PENDING:
                    $color = 'warning';
                    break;
                case Status::SYNCED:
                default:
                    $color = 'success';
                    break;
            }

            $pattern  = sprintf('<span class="label label-%s">%%s</value>', $color);
        }

        return sprintf($pattern, $text);
    }

    public function synchronizationStatus($status, $label = true, $short = true)
    {
        $color = '';

        $key = $short ? 'short.'.$status : $status;

        $text = $this->translator->trans($key, [], 'SynchronizationStatus');

        $pattern = '%s';

        if ($label) {
            switch ($status) {
                case SynchronizationStatus::ERROR:
                    $color = 'danger';
                    break;
                case SynchronizationStatus::PENDING:
                    $color = 'warning';
                    break;
                case SynchronizationStatus::PROCESSING:
                    $color = 'info';
                    break;
                case SynchronizationStatus::SUCCESSED:
                default:
                    $color = 'success';
                    break;
            }

            $pattern  = sprintf('<span class="label label-%s">%%s</value>', $color);
        }

        return sprintf($pattern, $text);
    }
}
