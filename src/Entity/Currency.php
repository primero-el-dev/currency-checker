<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(
    fields: ['currency', 'date'], 
    message: 'Given currency already has amount from provided date', 
    errorPath: 'currency'
)]
#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    #[Ignore]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[Assert\NotBlank(message: 'Currency is required')]
    #[Assert\Length(max: 5, maxMessage: 'Currency may be {{ limit }} characters at most')]
    #[Assert\Regex(pattern: '/^[A-Z]+$/', message: 'Currency may contain only uppercase latin letters')]
    #[ORM\Column(length: 5)]
    private ?string $currency = null;

    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    #[Assert\NotBlank(message: 'Date is required')]
    #[Assert\Type('datetime', message: 'Date is invalid')]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[Assert\NotBlank(message: 'Amount is required')]
    #[Assert\Type('float', message: 'Amount must be floating point number')]
    #[Assert\Positive(message: 'Amount must be positive value')]
    #[ORM\Column]
    private ?float $amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
