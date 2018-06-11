<?php
declare(strict_types=1);


namespace App\Infrastructure\User\Repository;


use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\AggregateFactory;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Ramsey\Uuid\UuidInterface;

final class UserStore extends EventSourcingRepository implements UserRepositoryInterface
{
    public function __construct(EventStore $eventStore, EventBus $eventBus, $eventStreamDecorators = [])
    {
        parent::__construct(
            $eventStore,
            $eventBus,
            User::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }

    public function get(UuidInterface $uuid): User
    {
        /** @var User $user */
        $user = $this->load((string) $uuid);

        return $user;
    }

    public function store(User $user): void
    {
        $this->save($user);
    }
}