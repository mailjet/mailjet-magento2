<?php

namespace Mailjet\Mailjet\Api;

interface JobRepositoryInterface
{
    /**
     * Save job.
     *
     * @param  \Mailjet\Mailjet\Api\Data\JobInterface $job
     * @return \Mailjet\Mailjet\Api\Data\JobInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Mailjet\Mailjet\Api\Data\JobInterface $job);

    /**
     * Retrieve job.
     *
     * @param  int $jobId
     * @return \Mailjet\Mailjet\Api\Data\JobInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($jobId);

    /**
     * Retrieve jobs matching the specified criteria.
     *
     * @param  \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete job.
     *
     * @param  \Mailjet\Mailjet\Api\Data\JobInterface $job
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Mailjet\Mailjet\Api\Data\JobInterface $job);

    /**
     * Delete job by ID.
     *
     * @param  int $jobId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($jobId);

    /**
     * Generate Jobs
     *
     * @param  \Mailjet\Mailjet\Api\Data\ConfigInterface[]|array|null $configs
     * @return Void
     */
    public function generateJobs($configs = null);

    /**
     * Execute Jobs
     *
     * @param  \Mailjet\Mailjet\Api\Data\JobInterface[] $job
     * @return Void
     */
    public function executeJob($job);

    /**
     * Execute All Jobs
     *
     * @return Void
     */
    public function executeAllJobs();
}
