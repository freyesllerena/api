<?php

namespace ApiBundle\Command;

use ApiBundle\Entity\DicDictionnaire;
use ApiBundle\Listener\DynamicDatabaseConnectionListener;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LoadTranslationsCommand
 * @package ApiBundle\Command
 * @codeCoverageIgnore
 */
class LoadTranslationsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('api:load_translations_command')
            ->setDescription('Chargement des traductions depuis un fichier JSON')
            ->addOption('instance', null, InputOption::VALUE_REQUIRED, 'Numéro d\'instance');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Traitement - Début');
        $container = $this->getContainer();
        $file = $container->get('kernel')->getRootDir() . '/../src/ApiBundle/Translation/dictionary.json';

        if (!file_exists($file)) {
            $output->writeln(
                'Le fichier \'dictionary.json\' n\'existe pas !' . "\n" .
                'Chemin complet : \'' . $file . '\'' . "\n" . 'Abandon !'
            );
        } else {
            $content = file_get_contents($file);
            if (!$translations = json_decode($content, true)) {
                $output->writeln('Erreur de décodage. Veuillez vérifier que le JSON est correctement formé !');
            } else {
                // Connexion à la BDD
                $doctrine = $container->get('doctrine');
                /** @var \Doctrine\DBAL\Connection $connection */
                $connection = $doctrine->getConnection();
                $dynamicConnection = new DynamicDatabaseConnectionListener($container, $connection);
                $dynamicConnection->ikpCaller($input->getOption('instance'));
                // EntityManager
                $entityManager = $doctrine->getManager();

                if (count($translations)) {
                    $cpt = 0;
                    $nbTrad = 0;

                    foreach ($translations as $code => $translation) {
                        $nbTrad++;
                        // Découpage
                        list($lang, $device, $code) = explode('.', $code, 3);
                        // Contrôle de DES ou MOB
                        if (!in_array($device, DicDictionnaire::$availableDevices, true)) {
                            continue;
                        }
                        // Lecture
                        if (!$dictionary = $entityManager->getRepository('ApiBundle:DicDictionnaire')
                                              ->findOneBy(['dicCode' => $code, 'dicSupport' => $device])) {
                            $dictionary = new DicDictionnaire();
                            $dictionary->setDicCode($code);
                            $dictionary->setDicSupport($device);
                        }
                        $dictionary->setTranslatableLocale($lang);
                        $dictionary->setDicValeur($translation);
                        $entityManager->persist($dictionary);
                        $entityManager->flush();

                        if (++$cpt >= 10) {
                            $entityManager->clear();
                            $cpt = 0;
                        }
                    }

                    $output->writeln('Nombre de traductions intégrées : ' . $nbTrad);
                }
            }
        }

        $output->writeln('Traitement - Fin');
    }
}
