<?php

namespace App\Controller\Api;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/currency')]
class CurrencyController extends AbstractController
{
    public function __construct(
        private CurrencyRepository $currencyRepository,
        private ValidatorInterface $validator,
    ) {}

    #[ParamConverter('date', options: ['format' => '!Y-m-d'])]
    #[Route('/{date}/{currency?}', name: 'api_currency_show', methods: 'GET')]
    public function show(\DateTime $date, ?string $currency, Request $request): JsonResponse
    {
        $values = ['date' => $date];

        if ($currency) {
            $values['currency'] = $currency;
        }
        
        return $this->json($this->currencyRepository->findByMultiple($values));
    }

    // hack to permit trailing slash on route
    #[Route('{_</?>}', name: 'api_currency_create', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        $content = json_decode($request->getcontent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('Invalid JSON content.');
        }

        $currency = (new Currency())
            ->setCurrency($content['currency'] ?? null)
            ->setDate($content['date'] ?? new \DateTime())
            ->setAmount($content['amount'] ?? null);
        
        $errors = $this->validator->validate($currency);

        if (count($errors) > 0) {
            return $this->json(['errors' => $this->getSingleFieldErrors($errors)], 400);
        }

        $this->currencyRepository->save($currency, true);

        return $this->json($currency);
    }

    private function getSingleFieldErrors(\Traversable $errors): array
    {
        $result = [];
        foreach ($errors as $error) {
            $result[$error->getPropertyPath()] ??= $error->getMessage();
        }

        return $result;
    }
}
