<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 11.01.2019
 * Time: 17:12
 */

namespace App\Entity;


class MailJob
{
    private $id;

    private $email;

    private $subject;

    private $senderEmail;

    private $accountData;

    private $bodyData;

    private $template;

    private $createdAt;

    private $updatedAt;

    private $mailJobStatus;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getSenderEmail(): ?string
    {
        return $this->senderEmail;
    }

    public function setSenderEmail(string $senderEmail): self
    {
        $this->senderEmail = $senderEmail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountData()
    {
        return $this->accountData;
    }

    /**
     * @param mixed $accountData
     */
    public function setAccountData($accountData): void
    {
        $this->accountData = $accountData;
    }

    public function getBodyData(): ?string
    {
        return $this->bodyData;
    }

    public function setBodyData(string $bodyData): self
    {
        $this->bodyData = $bodyData;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getMailJobStatus(): ?MailJobStatus
    {
        return $this->mailJobStatus;
    }

    public function setMailJobStatus(?MailJobStatus $mailJobStatus): self
    {
        $this->mailJobStatus = $mailJobStatus;

        return $this;
    }
}