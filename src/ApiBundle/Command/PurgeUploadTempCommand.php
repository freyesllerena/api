<?php

namespace ApiBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class PurgeUploadTempCommand
 * @package ApiBundle\Command
 */
class PurgeUploadTempCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('api:purge_upload_temp_command')
            ->setDescription('Effacement de fichiers de plus de 24 heures');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $basePath = __DIR__ . '/../../bin';
        $output->writeln('Purge - Début');
        $date = new \DateTime('now');
        $dateFormat = $date->format('d-m-Y h:i:s');
        $container = $this->getContainer();
        $dir = $container->getParameter('base_path_upload');
        $cmd = sprintf($basePath."/purge-upload-temp.sh %s", escapeshellarg($dir));
        try {
            exec($cmd);
        } catch (Exception $e) {
            throwException($e);
        }
        $output->writeln('Purge - Fin : '.$dateFormat);
    }
}