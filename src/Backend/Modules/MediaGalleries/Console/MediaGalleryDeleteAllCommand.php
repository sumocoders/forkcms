<?php

namespace Backend\Modules\MediaGalleries\Console;

use Backend\Modules\MediaGalleries\Domain\MediaGallery\Command\DeleteMediaGallery;
use Backend\Modules\MediaGalleries\Domain\MediaGallery\MediaGalleryRepository;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Delete media galleries Console Command
 * Example: "bin/console media_galleries:delete:galleries", will delete all galleries
 * Example: "bin/console media_galleries:delete:galleries --delete-media-items", will delete all galleries and all MediaItem entities
 */
class MediaGalleryDeleteAllCommand extends Command
{
    /**
     * The MediaGroupMediaItem connections are always deleted,
     * but should we delete the source MediaItem items as well?
     *
     * @var bool
     */
    protected $deleteMediaItems = false;

    public function __construct(
        private readonly MediaGalleryRepository $mediaGalleryRepository,
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('media_galleries:delete:galleries')
            ->setDescription('Delete media galleries.')
            ->addOption(
                'delete-media-items',
                null,
                InputOption::VALUE_NONE,
                'If set, all connected MediaItems will be deleted as well from the library.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('<info>-Started deleting media galleries.</info>');

        $this->checkOptions($input);
        $this->deleteMediaGalleries();

        $output->writeln('<info>-Finished deleting media galleries.</info>');
    }

    private function checkOptions(InputInterface $input): void
    {
        if ($input->getOption('delete-media-items')) {
            $this->deleteMediaItems = true;
        }
    }

    private function deleteMediaGalleries(): void
    {
        /** @var array $mediaGalleries */
        $mediaGalleries = $this->mediaGalleryRepository->findAll();

        if (empty($mediaGalleries)) {
            return;
        }

        // Loop all media galleries
        foreach ($mediaGalleries as $mediaGallery) {
            $this->commandBus->handle(
                new DeleteMediaGallery($mediaGallery),
            );
        }
    }
}
