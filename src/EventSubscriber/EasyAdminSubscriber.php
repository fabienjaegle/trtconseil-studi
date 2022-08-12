<?php

  namespace App\EventSubscriber;

  use App\Entity\User;
  use Doctrine\ORM\EntityManagerInterface;
  use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
  use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
  use Symfony\Component\EventDispatcher\EventSubscriberInterface;
  use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

  class EasyAdminSubscriber implements EventSubscriberInterface
  {
    private $entityManager;
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['addUser'],
            BeforeEntityUpdatedEvent::class => ['updateUser'], //surtout utile lors d'un reset de mot passe plutôt qu'un réel update, car l'update va de nouveau encrypter le mot de passe DEJA encrypté ...
        ];
    }

    public function updateUser(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }
        $this->setPassword($entity);
    }

    public function addUser(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }
        $this->setPassword($entity);
    }

    /**
     * @param User $user
     */
    public function setPassword(User $user): void
    {
        $pass = $user->getPassword();

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $pass
            )
        );
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
  }