<?php

namespace App\Command;

use App\Service\ParamService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SystemParamSetCommand extends Command
{
    protected static $defaultName = 'system:param:set';

    private $paramService;

    public function __construct(?string $name = null,ParamService $paramService)
    {
        $this->paramService=$paramService;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('key', InputArgument::OPTIONAL, 'Key of param')
            ->addArgument('val', InputArgument::OPTIONAL, 'Value of param')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $key = $input->getArgument('key');
        $val = $input->getArgument('val');

        if ($key) {
            $io->note(sprintf('Add param: %s', $key));
            $this->paramService->set($key,$val);
        }

        $io->success(sprintf('Add param: %s successful', $key));
    }
}
