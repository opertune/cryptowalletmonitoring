<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WalletRepository::class)
 */
class Wallet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="wallet")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apiKey;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $secretKey;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $dataJson = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $passPhrase;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): ?User
    {
        return $this->account;
    }

    public function setAccount(?User $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getSecretKey(): ?string
    {
        return $this->secretKey;
    }

    public function setSecretKey(?string $secretKey): self
    {
        $this->secretKey = $secretKey;

        return $this;
    }

    public function getDataJson(): ?array
    {
        return $this->dataJson;
    }

    public function setDataJson(?array $dataJson): self
    {
        $this->dataJson = $dataJson;

        return $this;
    }

    public function getPassPhrase(): ?string
    {
        return $this->passPhrase;
    }

    public function setPassPhrase(?string $passPhrase): self
    {
        $this->passPhrase = $passPhrase;

        return $this;
    }
}
