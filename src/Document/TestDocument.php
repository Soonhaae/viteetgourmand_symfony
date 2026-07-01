<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'test')]
class TestDocument
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field(type: 'string')]
    private string $nom;

    #[ODM\Field(type: 'string')]
    private string $message;

    public function __construct(string $nom, string $message)
    {
        $this->nom = $nom;
        $this->message = $message;
    }

    public function getId(): ?string { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getMessage(): string { return $this->message; }
}
