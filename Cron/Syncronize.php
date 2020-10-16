<?php

namespace Mailjet\Mailjet\Cron;

class Syncronize
{
    /**
     * @var \Mailjet\Mailjet\Api\JobRepositoryInterface
     */
    private $jobRepository;

    /**
     * Syncronize constructor.
     * @param \Mailjet\Mailjet\Api\JobRepositoryInterface $jobRepository
     */
    public function __construct(
        \Mailjet\Mailjet\Api\JobRepositoryInterface $jobRepository
    ) {
        $this->jobRepository    = $jobRepository;
    }

    public function execute()
    {
        $this->jobRepository->generateJobs();
        $this->jobRepository->executeAllJobs();
    }
}
