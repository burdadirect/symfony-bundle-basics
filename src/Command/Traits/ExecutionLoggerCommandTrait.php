<?php

namespace HBM\BasicsBundle\Command\Traits;

use Doctrine\ORM\QueryBuilder;
use HBM\BasicsBundle\Command\AbstractExtendableCommand;
use HBM\BasicsBundle\Command\Enum\PostExecutionResult;
use HBM\BasicsBundle\Command\Enum\PreExecutionResult;
use HBM\BasicsBundle\Entity\AbstractEntity;
use HBM\BasicsBundle\Entity\Interfaces\CommandExecutionInterface;
use HBM\BasicsBundle\Entity\Repository\AbstractServiceEntityRepo;
use HBM\BasicsBundle\Util\CommandExecutionLimit;
use HBM\BasicsBundle\Util\DateIntervalFormatter;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method OutputInterface getExtendedOutput()
 * @method string          getName()
 */
trait ExecutionLoggerCommandTrait
{
    protected ?bool $commandExecutionLogged = null;
    protected ?bool $commandExecutionChecked = null;

    protected string $commandExecutionId;
    protected string $commandExecutionAlias = 'ce';

    protected string $commandExecutionMessageLimitReached = '<warning>WARNING:</warning> Command execution limit reached: <options=bold>%2$d/%3$d</>';
    protected string $commandExecutionMessageLimitReachedInterval = ' <muted>(Interval: %4$s)</muted>';
    protected string $commandExecutionMessageExecuted = "\n" . '<options=bold>%1$s</> command execution(s) marked as executed.';

    protected int $commandExecutionVerbosityLimitReached = OutputInterface::VERBOSITY_NORMAL;
    protected int $commandExecutionVerbosityExecuted = OutputInterface::VERBOSITY_VERBOSE;

    /* ABSTRACT FUNCTIONS */

    abstract protected function getCommandExecution(): AbstractEntity&CommandExecutionInterface;
    abstract protected function getCommandExecutionRepository(): ?AbstractServiceEntityRepo;

    /* STEERING FUNCTIONS */

    protected function isExecutionLogged(): bool
    {
        return true;
    }

    /**
     * @return int|list<CommandExecutionLimit>
     */
    protected function getExecutionLimits(): array|int
    {
        return [];
    }

    /* IMPLEMENTATIONS */

    protected function formatExecutionLimitDateInterval(\DateInterval $dateInterval): string
    {
        return DateIntervalFormatter::formatIso8601($dateInterval);
    }

    /**
     * @param list<CommandExecutionLimit> $limits
     */
    protected function checkAndLogExecution(array $contexts = [], array|int $limits = [], ?bool $check = null, ?bool $log = null): ?CommandExecutionInterface
    {
        $check ??= $this->commandExecutionChecked ?? $this->isExecutionLogged();
        $log ??= $this->commandExecutionLogged ?? $this->isExecutionLogged();

        if (is_int($limits)) {
            $limits = [new CommandExecutionLimit($limits)];
        }

        if ($check !== true) {
            $limits = [];
        }

        foreach ($limits as $limitIndex => $limit) {
            $numOfExecutions = $this->countExecutions($contexts, $limit->getInterval(), $limit->getExitCode());

            if ($numOfExecutions >= $limit->getMax()) {
                $messageFormat = $this->commandExecutionMessageLimitReached;
                $messageParams = [$limitIndex + 1, $numOfExecutions, $limit->getMax()];

                if ($limit->getInterval()) {
                    $messageFormat .= $this->commandExecutionMessageLimitReachedInterval;
                    $messageParams[] = $this->formatExecutionLimitDateInterval($limit->getInterval());
                }
                $this->getExtendedOutput()->writeln(sprintf($messageFormat, ...$messageParams), $this->commandExecutionVerbosityLimitReached);

                return null;
            }
        }

        return $this->logExecution($contexts, $log);
    }

    protected function updateExecutions(int $exitCode, \DateTime $exited): QueryBuilder
    {
        $qb = $this->getCommandExecutionRepository()->createQueryBuilder($this->commandExecutionAlias);
        $qb->update();

        // Update exit code and exit timestamp.
        $qb->set($this->commandExecutionAlias . '.exitCode', ':exitCode');
        $qb->setParameter('exitCode', $exitCode);
        $qb->set($this->commandExecutionAlias . '.exited', ':exited');
        $qb->setParameter('exited', $exited);

        return $qb;
    }

    protected function queryExecutions(array $contexts = [], ?\DateInterval $interval = null, int|bool $exitCode = true): QueryBuilder
    {
        $qb = $this->getCommandExecutionRepository()->createQueryBuilder($this->commandExecutionAlias);
        $this->getCommandExecutionRepository()->searchValue($qb, $this->commandExecutionAlias, 'commandId', $this->getName());

        foreach ($contexts as $context) {
            $contextToUse = $context;

            if (is_object($context) && method_exists($context, 'getIdFQCN')) {
                $contextToUse = $context->getIdFQCN();
                $contextToUse = json_encode($contextToUse);
                $contextToUse = str_replace('\\', '\\\\', $contextToUse);
            }
            $this->getCommandExecutionRepository()->searchLike($qb, $this->commandExecutionAlias, 'contexts', $contextToUse);
        }

        if ($interval !== null) {
            $this->getCommandExecutionRepository()->searchDateFrom($qb, $this->commandExecutionAlias, 'created', new \DateTime()->sub($interval));
        }

        if (is_bool($exitCode)) {
            $this->getCommandExecutionRepository()->searchNull($qb, $this->commandExecutionAlias, 'exitCode', !$exitCode);
        } elseif (is_int($exitCode)) {
            $this->getCommandExecutionRepository()->searchValue($qb, $this->commandExecutionAlias, 'exitCode', $exitCode);
        }

        return $qb;
    }

    protected function countExecutions(array $contexts = [], ?\DateInterval $interval = null, int|bool $exitCode = true): int
    {
        $qb = $this->queryExecutions($contexts, $interval, $exitCode);
        $qb->select($qb->expr()->count($this->commandExecutionAlias));

        return $qb->getQuery()->getSingleScalarResult();
    }

    protected function getExecutions(array $contexts = [], ?\DateInterval $interval = null, int|bool $exitCode = true): array
    {
        return $this->queryExecutions($contexts, $interval, $exitCode)->getQuery()->getResult();
    }

    protected function logExecution(array $contexts = [], bool $persistAndFlush = true): CommandExecutionInterface
    {
        $execution = $this->getCommandExecution();
        $execution->setContexts($contexts);
        $execution->setExecutionId($this->commandExecutionId);

        if ($persistAndFlush) {
            $this->getCommandExecutionRepository()->save($execution);
        }

        return $execution;
    }

    protected function commitExecution(CommandExecutionInterface $execution, int $exitCode = Command::SUCCESS): mixed
    {
        $execution->setExitCode($exitCode);
        $execution->setExited(new \DateTime());

        $qb = $this->updateExecutions($execution->getExitCode(), $execution->getExited());

        $qb->andWhere($qb->expr()->eq($this->commandExecutionAlias . '.id', ':id'));
        $qb->setParameter('id', $execution->getId());

        return $qb->getQuery()->execute();
    }

    /* PRE- AND POST-EXECUTIONS */

    public function beginExecutionLogger(AbstractExtendableCommand $command, ?int &$exitCode): PreExecutionResult
    {
        $this->commandExecutionId = Uuid::uuid4()->toString();

        if (!$this->isExecutionLogged()) {
            return PreExecutionResult::SKIPPED;
        }

        if (!$this->checkAndLogExecution(limits: $this->getExecutionLimits())) {
            $exitCode = Command::INVALID;
            return PreExecutionResult::ABORTED;
        }

        return PreExecutionResult::SUCCEEDED;
    }

    public function commitExecutionLogger(AbstractExtendableCommand $command, ?int &$exitCode): PostExecutionResult
    {
        if (!$this->isExecutionLogged()) {
            return PostExecutionResult::SKIPPED;
        }

        $qb = $this->updateExecutions($exitCode, new \DateTime());

        // No proper exit code or exit timestamp.
        $noExitCodeOrExitTimestamp = $qb->expr()->orX();
        $noExitCodeOrExitTimestamp->add($qb->expr()->isNull($this->commandExecutionAlias . '.exitCode'));
        $noExitCodeOrExitTimestamp->add($qb->expr()->isNull($this->commandExecutionAlias . '.exited'));

        // All of this command execution, that not already have been commited.
        $qb->andWhere($noExitCodeOrExitTimestamp);
        $qb->andWhere($qb->expr()->eq($this->commandExecutionAlias . '.executionId', ':executionId'));
        $qb->setParameter('executionId', $this->commandExecutionId);

        $updated = $qb->getQuery()->execute();

        $this->getExtendedOutput()->writeln(sprintf($this->commandExecutionMessageExecuted, $updated), $this->commandExecutionVerbosityExecuted);

        return PostExecutionResult::SUCCEEDED;
    }
}
